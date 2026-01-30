<?php

namespace Drupal\notemail\Plugin\WebformHandler;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Form\FormStateInterface;

use Drupal\node\Entity\Node;
use Drupal\webform\WebformInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\Entity\WebformSubmission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\taxonomy\Entity\Term;
use Drupal\Component\Serialization\Json;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\webform\Element\WebformHtmlEditor;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use Drupal\webform\WebformTokenManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;




/**
 * Creates a new product from Webform prestation Submissions.
 *
 * @WebformHandler(
 *   id = "Create note de renseignements d'urbanisme",
 *   label = @Translation("note de renseignements d'urbanisme"),
 *   category = @Translation("Entity Creation"),
 *   description = @Translation("Creates a note de renseignements  from Webform E-note Submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */

class EnoteHandler extends WebformHandlerBase {
  
     /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'states' => [WebformSubmissionInterface::STATE_COMPLETED],
      'notes' => '',
      'sticky' => NULL,
      'locked' => NULL,
      'data' => '',
      'message' => '',
      'message_type' => 'status',
      'debug' => FALSE,
    ];
  }
     /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $results_disabled = $this->getWebform()->getSetting('results_disabled');

    $form['trigger'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Trigger'),
    ];
    $form['trigger']['states'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Execute'),
      '#options' => [
        WebformSubmissionInterface::STATE_DRAFT => $this->t('...when <b>draft</b> is saved.'),
        WebformSubmissionInterface::STATE_CONVERTED => $this->t('...when anonymous submission is <b>converted</b> to authenticated.'),
        WebformSubmissionInterface::STATE_COMPLETED => $this->t('...when submission is <b>completed</b>.'),
        WebformSubmissionInterface::STATE_UPDATED => $this->t('...when submission is <b>updated</b>.'),
      ],
      '#required' => TRUE,
      '#access' => $results_disabled ? FALSE : TRUE,
      '#default_value' => $results_disabled ? [WebformSubmissionInterface::STATE_COMPLETED] : $this->configuration['states'],
    ];

    $form['actions'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Actions'),
    ];
    $form['actions']['sticky'] = [
      '#type' => 'select',
      '#title' => $this->t('Change status'),
      '#empty_option' => $this->t('- None -'),
      '#options' => [
        '1' => $this->t('Flag/Star'),
        '0' => $this->t('Unflag/Unstar'),
      ],
      '#default_value' => ($this->configuration['sticky'] === NULL) ? '' : ($this->configuration['sticky'] ? '1' : '0'),
    ];
    $form['actions']['locked'] = [
      '#type' => 'select',
      '#title' => $this->t('Change lock'),
      '#description' => $this->t('Webform submissions can only be unlocked programatically.'),
      '#empty_option' => $this->t('- None -'),
      '#options' => [
        '' => '',
        '1' => $this->t('Lock'),
        '0' => $this->t('Unlock'),
      ],
      '#default_value' => ($this->configuration['locked'] === NULL) ? '' : ($this->configuration['locked'] ? '1' : '0'),
    ];
    $form['actions']['notes'] = [
      '#type' => 'webform_codemirror',
      '#mode' => 'text',
      '#title' => $this->t('Append the below text to notes (Plain text)'),
      '#default_value' => $this->configuration['notes'],
    ];
    $form['actions']['message'] = [
      '#type' => 'webform_html_editor',
      '#title' => $this->t('Display message'),
      '#default_value' => $this->configuration['message'],
    ];
    $form['actions']['message_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Display message type'),
      '#options' => [
        'status' => t('Status'),
        'error' => t('Error'),
        'warning' => t('Warning'),
        'info' => t('Info'),
      ],
      '#default_value' => $this->configuration['message_type'],
    ];
    $form['actions']['data'] = [
      '#type' => 'webform_codemirror',
      '#mode' => 'yaml',
      '#title' => $this->t('Update the below submission data. (YAML)'),
      '#default_value' => $this->configuration['data'],
    ];

    $elements_rows = [];
    $elements = $this->getWebform()->getElementsInitializedFlattenedAndHasValue();
    foreach ($elements as $element_key => $element) {
      $elements_rows[] = [
        $element_key,
        (isset($element['#title']) ? $element['#title'] : ''),
      ];
    }
    $form['actions']['elements'] = [
      '#type' => 'details',
      '#title' => $this->t('Available element keys'),
      'element_keys' => [
        '#type' => 'table',
        '#header' => [$this->t('Element key'), $this->t('Element title')],
        '#rows' => $elements_rows,
      ],
    ];
    $form['actions']['token_tree_link'] = $this->tokenManager->buildTreeLink();

    // Development.
    $form['development'] = [
      '#type' => 'details',
      '#title' => $this->t('Development settings'),
    ];
    $form['development']['debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable debugging'),
      '#description' => $this->t('If checked, trigger actions will be displayed onscreen to all users.'),
      '#return_value' => TRUE,
      '#default_value' => $this->configuration['debug'],
    ];

    $this->tokenManager->elementValidate($form);

    return $this->setSettingsParentsRecursively($form);
  }

     /**
   * {@inheritdoc}
   */
   
     public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    $state = $webform_submission->getWebform()->getSetting('results_disabled') ? WebformSubmissionInterface::STATE_COMPLETED : $webform_submission->getState();
    if (in_array($state, $this->configuration['states'])) {
      $this->executeAction($webform_submission);
    }
  }
  
  protected function executeAction(WebformSubmissionInterface $webform_submission) {
     $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $submission_array = $webform_submission->getData();
 

    
	    $profession_demandeur = $submission_array['profession_demandeur'];
	$nom_ = $submission_array['nom_'];
	$prenom = $submission_array['prenom'];
	$cin = $submission_array['cin'];
	$mre = $submission_array['marocains_residents_a_l_etranger'];
	$adresse = $submission_array['adresse'];
	$telephone_ = $submission_array['telephone_mobile'];
	$email = $submission_array['email'];
	$en_qualite = $submission_array['en_qualite'];
	$references_foncieres = $submission_array['references_foncieres'];
	$statut_foncier_ = $submission_array['statut_foncier_'];
	$prefecture = $submission_array['prefecture'];
	if (!empty($submission_array['commune'])) {$commune = $submission_array['commune'];}
	if (!empty($submission_array['commune_2'])) {$commune = $submission_array['commune_2'];}
	if (!empty($submission_array['commune_3'])) {$commune = $submission_array['commune_3'];}
	// if (!empty($submission_array['commune_4'])) {$commune = $submission_array['commune_4'];}
	$nature_du_projet_envisage = $submission_array['nature_du_projet_envisage'];
	//$autres_projet = $submission_array['autres_projet'];
	$cin_fid = $submission_array['carte_d_identite_nationale_scannee_pdf'];
	$Justificatif_propriete_fid = $submission_array['justificatif_de_propriete_certificat'];
	$plan_cadastral_ou_plan_topographique_fid = $submission_array['plan_cadastral_ou_plan_topographique'];
	$liste_des_coordonnees_lambert_fid = $submission_array['liste_des_coordonnees_lambert'];
	if (!empty($submission_array['pieces_complementaires'])) {$pieces_complementaires = $submission_array['pieces_complementaires'];}else {$pieces_complementaires = "";}
	$accord_de_proprietaire_fid = $submission_array['accord_de_proprietaire'];
	$uuid_note =  time();
	
// Create file PDF CIN.
    if (!empty($cin_fid)) {
      $file = \Drupal\file\Entity\File::load($cin_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_cin = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF justificatif_de_propriete_certificat.
    if (!empty($Justificatif_propriete_fid)) {
      $file = \Drupal\file\Entity\File::load($Justificatif_propriete_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $Justificatif_propriete_pdf_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF plan_cadastral_ou_plan_topographique.
    if (!empty($plan_cadastral_ou_plan_topographique_fid)) {
      $file = \Drupal\file\Entity\File::load($plan_cadastral_ou_plan_topographique_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $plan_cadastral_pdf_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF liste_des_coordonnees_lambert.
    if (!empty($liste_des_coordonnees_lambert_fid)) {
      $file = \Drupal\file\Entity\File::load($liste_des_coordonnees_lambert_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_liste_des_coordonneespdf_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF accord_de_proprietaire.
    if (!empty($accord_de_proprietaire_fid)) {
      $file = \Drupal\file\Entity\File::load($accord_de_proprietaire_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_accord = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }


    $current_user = \Drupal::currentUser();
    $user = \Drupal\user\Entity\User::load($current_user->id());
    
    
          $price = 200;		
		
       	$product = Product::create([
              'uid' => $current_user->id(),
              'type' => 'e_note',
			  'stores' => '2',
              'title' => 'E-note reference  : ' . $references_foncieres  ,
			  'status' => 1,
            ]);
			$product->set('field_cin', $cin);
			$product->set('field_mail', $email);
			$product->set('field_titre_foncier', $references_foncieres);
			$product->set('field_code_de_suivi', $uuid_note);
			
			
			
			            $variation = ProductVariation::create([
              'type' => 'instruction',
              'sku' => $uuid_note,
			  'price' => new \Drupal\commerce_price\Price($price, 'MAD'),
			  'title' => $references_foncieres  ,
              'status' => 1
            ]);
            
            $variation->save();
            $product->addVariation($variation);
            $product->save();	
			$product_id = $product->product_id->value;
	
	 $node = Node::create([
 	  'type' =>  'note',
	  'uid' => $current_user->id(),
	  'title' => 'Demande de la part : ' . $nom_ . ' ' . $prenom ,
	  'field_email' => $email,
      'field_profession_demandeur' => $profession_demandeur,
	  'field_nom' => $nom_,
      'field_prenom' => $prenom,
	  'field_cin' => $cin,
	  'field_marocains_residents_a_l_et' => $mre,
      'field_adresse' => $adresse,
	  'field_phone_number' => $telephone_,
	  'field_en_qualite' => $en_qualite,
	  'field_references_foncieres' => $references_foncieres,
      'field_statut_foncier' => $statut_foncier_,
	  'field_prefecture' => $prefecture,
      'field_commune' => $commune,
	  'field_nature_du_projet_envisage' => $nature_du_projet_envisage,
	  //'field_autres_projet' => $autres_projet,
	  'field_carte_d_identite_nationale' => [
        'target_id' => (!empty($node_pdf_cin) ? $node_pdf_cin->id() : NULL),
        'alt' => 'Carte d identité nationale scannée (PDF)(*)',
        'title' => 'Carte d identité nationale scannée'
      ],
	  'field_justificatif_de_propriete' => [
        'target_id' => (!empty($Justificatif_propriete_pdf_file) ? $Justificatif_propriete_pdf_file->id() : NULL),
        'alt' => 'Certificat de propriété, Acte adulaire(*)',
        'title' => 'Certificat de propriété, Acte adulaire'
      ],
	  'field_plan_cadastral_ou_plan_top' => [
        'target_id' => (!empty($plan_cadastral_pdf_file) ? $plan_cadastral_pdf_file->id() : NULL),
        'alt' => 'Plan cadastral ou plan topographique (PDF)(*)',
        'title' => 'Plan cadastral ou plan topographique'
      ],
	  'field_liste_des_coordonnees_lamb' => [
        'target_id' => (!empty($node_pdf_liste_des_coordonneespdf_file) ? $node_pdf_liste_des_coordonneespdf_file->id() : NULL),
        'alt' => 'Liste des coordonnées Lambert fournie par les services de l’ANCFCCPDF)(*)',
        'title' => 'Liste des coordonnées Lambert fournie par les services de l’ANCFCC'
      ],
	  'field_accord_de_proprietaire' => [
        'target_id' => (!empty($node_pdf_accord) ? $node_pdf_accord->id() : NULL),
        'alt' => 'Accord de propriétaire(PDF)(*)',
        'title' => 'Accord de propriétaire'
      ],
      'field_pieces_complementaires'  => $pieces_complementaires,
	  'field_cheking' => 0,
	  'field_uuid' => $uuid_note,
	  'field_product_id'=>$product_id,
	  'field_webform_' => $webform_submission->id(),
                		]);
					  $node->save();
					  
					 // $form_state->setRedirect('/fr/product/$product_id'. $nid_last_create );
					  (new RedirectResponse('/fr/product/'. $product_id ))->send();
           	   
  }
  
    // Resave the webform submission without trigger any hooks or handlers.
   // $webform_submission->resave();

    // Display debugging information about the current action.
  //  $this->displayDebug($webform_submission);
  

  }