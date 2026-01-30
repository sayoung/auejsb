<?php

namespace Drupal\md_new_prestation\Plugin\WebformHandler;

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
use Drupal\md_new_prestation\Helper\Helper;
use Drupal\Component\Serialization\Json;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;



/**
 * Creates a new product from Webform prestation Submissions.
 *
 * @WebformHandler(
 *   id = "Create a product prestation",
 *   label = @Translation("Create a product from prestation"),
 *   category = @Translation("Entity Creation"),
 *   description = @Translation("Creates a new product from Webform prestation Submissions."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_REQUIRED,
 * )
 */


class EprestationNew extends WebformHandlerBase {
	
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
  
  /**
   * Function to be fired while submitting the Webform.
   */
protected function executeAction(WebformSubmissionInterface $webform_submission) {
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $submission_array = $webform_submission->getData();

    // Extract submission data
    $field_ndeg_de_dossier = $submission_array['ndeg_de_dossier'] ?? NULL;
    $field_situation_du_projet = $submission_array['situation_du_projet'] ?? NULL;
    $field_collectivite_territoriale_ = $submission_array['prefecture'] ?? NULL;
    
    $commune = !empty($submission_array['commune'])
    ? $submission_array['commune']
    : (!empty($submission_array['commune_2'])
        ? $submission_array['commune_2']
        : null);

    $references_foncieres = $submission_array['references_foncieres'] ?? NULL;
    $field_nom_du_petitionnaire = $submission_array['nom_du_petitionnaire'] ?? NULL;
    $field_adresse_maitres = $submission_array['adresse_maitres_d_ouvrage_'] ?? NULL;
    $cin = $submission_array['cin'] ?? NULL;
    $field_architecte_ou_igt = $submission_array['architecte_ou_igt'] ?? NULL;
    
    $mail = $submission_array['e_mail_'] ?? NULL;
    $telephone = $submission_array['telephone_'] ?? NULL;
    $field_date_commission = $submission_array['date'] ?? NULL;
    
    $field_type_du_projet = $submission_array['nature_du_pro2jet'] ?? NULL;
    $field_type = $submission_array['type'] ?? NULL;
    $superficie_totale = $submission_array['superficie_totale'] ?? NULL;
    $field_nature_du_projet = $submission_array['nature_du_projet'] ?? NULL;
    $consistance_du_projet = $submission_array['consistance_du_projet'] ?? NULL;
    
    $surface_de_terrain = $submission_array['surface_de_terrain'] ?? NULL;
    $metrage_du_projet = $submission_array['metrage_du_projet'] ?? NULL;
    $surface_planchers_couverts_supplementaires_apres_modification = $submission_array['surface_planchers_couverts_supplementaires_apres_modification'] ?? NULL;
    $surfaces_cessibles_apres_modification = $submission_array['surfaces_cessibles_apres_modification'] ?? NULL;
    $montant_d_investissement_mdhs_ = $submission_array['montant_d_investissement_mdhs_'] ?? NULL;
    $montant_a_verser_en_lettres_dh_ = $submission_array['montant_a_verser_en_lettres_dh_'] ?? NULL;
    
    $sid_2 = $webform_submission->id();
    $current_year = date('Y');

    // ===== FIXED AUTO-INCREMENT =====
    /*
    $query = \Drupal::entityTypeManager()
      ->getStorage('commerce_product')
      ->getQuery()
      ->accessCheck(FALSE)
      ->condition('type', 'instruction')
      ->condition('field_autorisation_nbr', '%/' . $current_year, 'LIKE');
    
    $ids = $query->execute();
    $last_number = 0;
    
    if (!empty($ids)) {
      $products = Product::loadMultiple($ids);
      
      foreach ($products as $product) {
        $value = $product->get('field_autorisation_nbr')->value;
        
        if (preg_match('/^(\d+)\/' . $current_year . '$/', $value, $matches)) {
          $number = (int) $matches[1];
          if ($number > $last_number) {
            $last_number = $number;
          }
        }
      }
    }
    
    $new_autorisation_nbr = ($last_number + 1) . '/' . $current_year; */
    // ===== END FIXED AUTO-INCREMENT =====

    // Handle file upload
    $facture = NULL;
    $facture_fid = $submission_array['facture'] ?? NULL;
    if (!empty($facture_fid)) {
      $file = \Drupal\file\Entity\File::load($facture_fid);
      if ($file) {
        $path = $file->getFileUri();
        $data = file_get_contents($path);
        $facture = file_save_data($data, 'public://' . $file->getFilename(), \Drupal\Core\File\FileSystemInterface::EXISTS_REPLACE);
      }
    }
    
    $current_user = \Drupal::currentUser();
    
    // Create product
    $product = Product::create([
      'uid' => $current_user->id(),
      'type' => 'instruction',
      'stores' => '4',
      'title' => 'Dossier N° : ' . $field_ndeg_de_dossier,
    ]);
    
    $product->set('field_sid', $sid_2);
    $product->set('field_autorisation_nbr', $new_autorisation_nbr);
    
    $product->set('field_ndeg_de_dossier', $field_ndeg_de_dossier);
    $product->set('field_situation_du_projet', $field_situation_du_projet);
    $product->set('field_collectivite_territoriale_', $field_collectivite_territoriale_);
    $product->set('field_commune', $commune);
    $product->set('field_references_foncieres', $references_foncieres);
    $product->set('field_nom_du_petitionnaire', $field_nom_du_petitionnaire);
    $product->set('field_adresse_maitres', $field_adresse_maitres);
    $product->set('field_cin', $cin);
    $product->set('field_architecte_ou_igt', $field_architecte_ou_igt);
    
    $product->set('field_mail', $mail);
    $product->set('field_telephone', $telephone);
    $product->set('field_date_commission', $field_date_commission);
    
    $product->set('field_type_du_projet', $field_type_du_projet);
    $product->set('field_type_2', $field_type);
    $product->set('field_nature_du_projet_2', $field_nature_du_projet);
    $product->set('field_consistance_du_projet', $consistance_du_projet);
    
    $product->set('field_surface_de_terrain', $surface_de_terrain);
    $product->set('field_metrage_du_projet', $metrage_du_projet);
    $product->set('field_supp_apres_modi', $surface_planchers_couverts_supplementaires_apres_modification);
    $product->set('field_surf_cessible_modi', $surfaces_cessibles_apres_modification);
    $product->set('field_mnt_investissement_mdhs', $montant_d_investissement_mdhs_);
    $product->set('field_superficie_totale', $superficie_totale);
    
    $product->set('field_prix_en_lettre', $montant_a_verser_en_lettres_dh_);
    
    $product->set('field_facture', [
      [
        'target_id' => (!empty($facture) ? $facture->id() : NULL),
        'alt' => 'facture modification (*)',
        'title' => 'facture modification',
      ],
    ]);
    
    // Calculate price
    $price = $this->calculateTotal($metrage_du_projet, $field_nature_du_projet, $field_type, $surface_planchers_couverts_supplementaires_apres_modification);
    
    if ($price['total'] === NULL) {
      \Drupal::messenger()->addError($price['message']);
      throw new \Exception($price['message']);
    }
    
    // Create SKU and variation
    $sku = $field_ndeg_de_dossier . "-" . $sid_2;
    
    $variation = ProductVariation::create([
      'type' => 'instruction',
      'sku' => $sku,
      'price' => new \Drupal\commerce_price\Price($price['total'], 'MAD'),
      'status' => 1
    ]);
    
    $variation->save();
    $product->addVariation($variation);
    $product->save();
    
    \Drupal::logger('md_new_prestation')->notice('Generated autorisation number @num for product @title', [
      '@num' => $new_autorisation_nbr,
      '@title' => $product->getTitle()
    ]);
  }
  /**
   * Calculate total price based on project parameters.
   */
  private function calculateTotal($metrage_du_projet, $field_nature_du_projet, $field_type, $surface_planchers_couverts_supplementaires_apres_modification) {
    $total = 0;
    
    // Determine which metric to use
    $metric = $metrage_du_projet;
    if ($field_type === "Modification") {
      $metric = $surface_planchers_couverts_supplementaires_apres_modification;
    }
    
    if ($field_nature_du_projet === "Morcellement (hors périmètre urbain)") {
      if ($metric < 10000) {
        return [
          'total' => NULL,
          'message' => "Veuillez vérifier SVP les superficies déclarées"
        ];
      } elseif ($metric == 10000) {
        $total = 2500 * 1.2;
      } elseif ($metric > 10000 && $metric < 20000) {
        $total = 4500 * 1.2;
      } elseif ($metric == 20000) {
        $total = 5000 * 1.2;
      } elseif ($metric > 20000 && $metric < 30000) {
        $total = 7000 * 1.2;
      } elseif ($metric == 30000) {
        $total = 7000 * 1.2;
      } elseif ($metric > 30000 && $metric < 40000) {
        $total = 8500 * 1.2;
      } elseif ($metric == 40000) {
        $total = 9000 * 1.2;
      } elseif ($metric > 40000 && $metric < 50000) {
        $total = 10500 * 1.2;
      } elseif ($metric == 50000) {
        $total = 10500 * 1.2;
      } elseif ($metric > 50000) {
        $additional_hectares = floor(($metric - 50000) / 10000);
        $additional_cost = 10500 + ($additional_hectares * 1500);
        
        if (($metric - 50000) % 10000 > 0) {
          $additional_cost += 1200;
        }
        
        $total = $additional_cost * 1.2;
      }
    } else {
      $total = $metric * 3.6;
    }
    
    return [
      'total' => round($total, 2),
      'message' => NULL
    ];
  }
}