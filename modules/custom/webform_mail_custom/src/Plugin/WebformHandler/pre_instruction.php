<?php

namespace Drupal\webform_mail_custom\Plugin\WebformHandler;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Form\FormStateInterface;

use Drupal\node\Entity\Node;

use Drupal\webform\WebformInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\webformSubmissionInterface;
use Drupal\webform\Entity\WebformSubmission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Routing\CurrentRouteMatch;


/**
 * Creates a new node from Webform pre instruction Submissions.
 *
 * @WebformHandler(
 *   id = "Create a pre instruction",
 *   label = @Translation("Create a node from pre instruction"),
 *   category = @Translation("Entity Creation"),
 *   description = @Translation("Creates a new node from Webform pre instruction Submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */

class pre_instruction extends WebformHandlerBase {
     
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
   
  // Create node object from webform-submission.
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
  // Create product object from webform-submission.
  
  // Function to be fired while submitting the Webform.
  protected function executeAction(WebformSubmissionInterface $webform_submission) {
	 $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $submission_array = $webform_submission->getData();
 $id_array = $webform_submission->getOwnerId();
  $account = \Drupal\user\Entity\User::load($id_array);
  $name = $account->getUsername();
  $archi_mail = $account->getEmail();
    // Before you actually save the node, make sure you get what you
    // want. So at first comment the line further down with 
    // $node->save(), run the debug code, check the output and then
    // uncomment the $node->save() and delete the debug foreach loop.
    // You can use whatever debug method you prefer, this is just a
    // simple one-time example.
   //  $values = $webform_submission->getData();

	$recurtement = \Drupal::routeMatch()->getRawParameter('node');
	$prefecture = $submission_array['prefecture'];
	if (!empty($submission_array['commune'])) {$commune = $submission_array['commune'];}
	if (!empty($submission_array['commune_2'])) {$commune = $submission_array['commune_2'];}
	$nature_du_projet_envisage = $submission_array['nature_du_projet_envisage'];
	$situation_du_projet_ = $submission_array['situation_du_projet_'];
	$nom_prenom_du_proprietaire_ = $submission_array['nom_prenom_du_proprietaire_'];
	$references_foncieres = $submission_array['references_foncieres'];
// -------------------------- -------------------------------	
	$accord_de_proprietaire_fid = $submission_array['accord_de_proprietaire'];
    $plans_architectureaux_coupes_fid = $submission_array['plans_architectureaux_coupes_'];
	$contrat_d_ingenieur_geometre_topographe_fid = $submission_array['note_descriptive_du_projet'];
    $plan_du_projet_de_morcellement_fid = $submission_array['plan_du_projet_de_morcellement'];
	$Justificatif_propriete_fid = $submission_array['justificatif_de_propriete_certificat'];
	$plan_cadastral_ou_plan_topographique_fid = $submission_array['plan_cadastral_ou_plan_topographique'];
	$plans_architectureaux_facades_fid = $submission_array['plans_architectureaux_facades_'];
	$plan_de_conception_urbanistique_du_lotissement_fid = $submission_array['plan_de_conception_urbanistique_du_lotissement'];
	
	
	
	
	
	$uuid_note =  time();
// Create file PDF CIN.
// Create file PDF accord_de_proprietaire. // c  ok 
    if (!empty($accord_de_proprietaire_fid)) {
      $file = \Drupal\file\Entity\File::load($accord_de_proprietaire_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_accord = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF $node_plans_architectureaux_facades // c  ok 
	    if (!empty($plans_architectureaux_coupes_fid)) {
      $file = \Drupal\file\Entity\File::load($plans_architectureaux_coupes_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_plans_architectureaux_coupes = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF liste_des_coordonnees_lambert.
    if (!empty($contrat_d_ingenieur_geometre_topographe_fid)) {
      $file = \Drupal\file\Entity\File::load($contrat_d_ingenieur_geometre_topographe_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_contrat_d_ingenieur_geometre_topographe_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF liste_des_coordonnees_lambert.
    if (!empty($plan_du_projet_de_morcellement_fid)) {
      $file = \Drupal\file\Entity\File::load($plan_du_projet_de_morcellement_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_plan_du_projet_de_morcellement_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    } 
// Create file PDF justificatif_de_propriete_certificat. // c  ok 
    if (!empty($Justificatif_propriete_fid)) {
      $file = \Drupal\file\Entity\File::load($Justificatif_propriete_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $Justificatif_propriete_pdf_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
// Create file PDF plan_cadastral_ou_plan_topographique. // c  ok 
    if (!empty($plan_cadastral_ou_plan_topographique_fid)) {
      $file = \Drupal\file\Entity\File::load($plan_cadastral_ou_plan_topographique_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $plan_cadastral_pdf_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }    

	// Create file PDF plans_architectureaux_coupes .
	    if (!empty($plans_architectureaux_facades_fid)) {
      $file = \Drupal\file\Entity\File::load($plans_architectureaux_facades_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_plans_architectureaux_facades = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }
 // Create file PDF liste_des_coordonnees_lambert.
    if (!empty($plan_de_conception_urbanistique_du_lotissement_fid)) {
      $file = \Drupal\file\Entity\File::load($plan_de_conception_urbanistique_du_lotissement_fid);
      $path = $file->getFileUri();
      $data = file_get_contents($path);
      $node_pdf_de_conception_urbanistique_du_lotissement_file = file_save_data($data, 'public://' . $file->getFilename(), FILE_EXISTS_REPLACE);
    }


	



    // This is the node creating/saving part.
	 $node = Node::create([
 	  'type' =>  'pre_instruction',
	  'uid' => 1,
	  'title' => 'Demande de la part : ' . $name . ' '   ,
	  'field_prefecture' => $prefecture,
      'field_commune' => $commune,
      'field_nature_du_projet_envisage' => $nature_du_projet_envisage,
      'field__situation_du_projet' => $situation_du_projet_,
      'field_nom_prenom_du_proprietaire' => $nom_prenom_du_proprietaire_,
      'field_references_foncieres' => $references_foncieres,
      'field_email_' => $archi_mail,
      
	  
	  
	  
	  ///////////////////////////////
	  // c  ok 
	  'field_justificatif_de_propriete' => [
        'target_id' => (!empty($Justificatif_propriete_pdf_file) ? $Justificatif_propriete_pdf_file->id() : NULL),
        'alt' => 'Attestation de propriété (Certificat de propriété, Acte adulaire, etc.)',
        'title' => 'Attestation de propriété (Certificat de propriété, Acte adulaire, etc.)'
      ],
      // c ok 
      'field_plan_cadastral_ou_plan_top' => [
        'target_id' => (!empty($plan_cadastral_pdf_file) ? $plan_cadastral_pdf_file->id() : NULL),
        'alt' => 'Plan cadastral avec calcul de contenances ou Levé topographique',
        'title' => 'Plan cadastral avec calcul de contenances ou Levé topographique'
      ],
      // c ok 
      'field_accord_de_proprietaire' => [
        'target_id' => (!empty($node_pdf_accord) ? $node_pdf_accord->id() : NULL),
        'alt' => 'Plans architectureaux (Plan de masse)',
        'title' => 'Plans architectureaux (Plan de masse)'
      ],
      // c  ok 
	  'field_plans_architectureaux_coup' => [
        'target_id' => (!empty($node_plans_architectureaux_coupes) ? $node_plans_architectureaux_coupes->id() : NULL),
        'alt' => 'Plans architectureaux (Coupes)',
        'title' => 'Plans architectureaux (Coupes)'
      ],
	  // c ok 
	  'field_plans_architectureaux_faca' => [
        'target_id' => (!empty($node_plans_architectureaux_facades) ? $node_plans_architectureaux_facades->id() : NULL),
        'alt' => 'Plans architectureaux (Façades)',
        'title' => 'Plans architectureaux (Façades)'
      ],
      
      
      
      // c fait 
      'field_field_plan_de_conception_u' => [
        'target_id' => (!empty($node_pdf_de_conception_urbanistique_du_lotissement_file) ? $node_pdf_de_conception_urbanistique_du_lotissement_file->id() : NULL),
        'alt' => 'Plan de conception urbanistique du projet',
        'title' => 'Plan de conception urbanistique du projet'
      ],
      
      // c fait 
      'field_plan_du_projet_de_morcelle' => [
        'target_id' => (!empty($node_pdf_plan_du_projet_de_morcellement_file) ? $node_pdf_plan_du_projet_de_morcellement_file->id() : NULL),
        'alt' => 'Plan du projet de morcellement',
        'title' => 'Plan du projet de morcellement'
      ],
      
      'field_contrat_d_ingenieur_geomet' => [
        'target_id' => (!empty($node_pdf_contrat_d_ingenieur_geometre_topographe_file) ? $node_pdf_contrat_d_ingenieur_geometre_topographe_file->id() : NULL),
        'alt' => 'Contrat d’ingénieur géomètre topographe',
        'title' => 'Contrat d’ingénieur géomètre topographe'
      ],
	 
	  
	  
	  
	  'field_cheking' => 1,
	  'field_code_s' => $uuid_note,
                		]);
					  $node->save();


	
  }
	
}