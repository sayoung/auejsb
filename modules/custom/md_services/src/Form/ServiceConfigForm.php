<?php

namespace Drupal\md_services\Form;

use Drupal\md_services\Helper\Helper;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\file\Entity\File;

/**
 * Flatchr settings form.
 */
class ServiceConfigForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'service_conf_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['title_service'] = array(
        '#title' => $this->t('E-service : '),
        '#type' => 'textfield',
        '#prefix' => '<div class="layout-column layout-column--half"><div class="panel">',
        '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      );
      $form['description'] = array(
        '#title' => $this->t('description : '),
        '#type' => 'textfield',
        '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      );
    $form['title_1'] = array(
      '#title' => $this->t('Titre de la E-service : '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
    );
	$form['link_1'] = array(
      '#title' => $this->t('lien de la E-service :  '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
    );
	$form['title_2'] = array(
      '#title' => $this->t('Titre de la E-service(2eme bottom)'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
    );
	$form['link_2'] = array(
      '#title' => $this->t('lien de la E-service (2eme bottom)  :  '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
    );
	$form['title_3'] = array(
      '#title' => $this->t('Titre de la E-service(2eme bottom)'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
    );
	$form['link_3'] = array(
      '#title' => $this->t('lien de la E-service (2eme bottom)  :  '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
    );
    $form['order'] = array(
      '#title' => $this->t('Order de la E-service:'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
    );
	$form['icon_service'] = array(
      '#title' => $this->t('icon de la E-service :  '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
    );
	$form['bg_service'] = [
  '#type' => 'managed_file',
  '#title' => $this->t('bg_service'),
  '#upload_location' => 'public://myfile',

];
 $form['submit'] = array(
            '#type' => 'submit',
			'#suffix' => '</div></div>',
            '#value' => t('insert')
        );

    return $form;
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
//$file = file_load($form_state->getValue('bg_service'));
//$file->status = FILE_STATUS_PERMANENT;
$fid = $form_state->getValue('bg_service');
$form_file = $form_state->getValue('bg_service')[0];
  //  $file = \Drupal\file\Entity\File::load($fid);
	$file_object = File::load($form_file);
	// way 1

$file_object->setPermanent();
$file_object->save();
// way 2
$file_uri = $file_object->getFileUri();
$file_url = Url::fromUri(file_create_url($file_uri))->toString();
    ///$filename = $file->name; //or maybe $file->filename
//	$file->setPermanent();
//file_save($file);
$form_file_interne = $form_state->getValue('bg_service_interne')[0];
	$file_object_interne = File::load($form_file_interne);
$file_object_interne->setPermanent();
$file_object_interne->save();
$file_uri_interne = $file_object_interne->getFileUri();
$file_url_interne = Url::fromUri(file_create_url($file_uri_interne))->toString();

	//print_r($form_state->getValue('bg_service'));  

	$values = array(
		'title' => $form_state->getValue('title_service'),
		'description' => $form_state->getValue('description'),
		'link_1' => $form_state->getValue('link_1'),
		'link_2' => $form_state->getValue('link_2'),
		'link_3' => $form_state->getValue('link_3'),
		'title_1' => $form_state->getValue('title_1'),
		'title_2' => $form_state->getValue('title_2'),
		'title_3' => $form_state->getValue('title_3'),
		'order' => $form_state->getValue('order'),
		'bg_service' => $form_file,
		'bg_service_interne' => $form_file_interne,
		'icon_service' => $form_state->getValue('icon_service'),
		
	);

	  $insert = db_insert('md_services')
	  -> fields(array(
			'service_title' => $values['title'],
			'description' => $values['description'],
			'service_link_title_1' => $values['title_1'],
			'service_link_title_2' => $values['title_2'],
			'service_link_title_3' => $values['title_3'],
			'service_link_1' => $values['link_1'],
			'service_link_2' => $values['link_2'],
			'service_link_3' => $values['link_3'],
			'service_order' => $values['order'],
			'bg_service' => $values['bg_service'],
			'bg_service_interne' => $values['bg_service_interne'],
			'service_icon_service' => $values['icon_service'],
			
		))
		->execute();
		drupal_set_message(t('Settings have been saved'));

    
  }


}
