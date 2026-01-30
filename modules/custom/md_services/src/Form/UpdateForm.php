<?php

namespace Drupal\md_services\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Drupal\file\Entity\File;
use Drupal\md_services\Helper\Helper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class UpdateForm.
 *
 * @package Drupal\md_services\Form
 */
class UpdateForm extends ConfirmFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'Update_Form';
  }

  public $cid;

  public function getQuestion() {
    return t('Do you want to Update %cid?', array('%cid' => $this->cid));
  }

  public function getCancelUrl() {
    return new Url('md_services.display_table_controller_display');
}
public function getDescription() {
    return t('Only do this if you are sure!');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Update it!');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL) {

     $this->id = $cid;
	 


//select records from table
    $query = \Drupal::database()->select('md_services', 'm');
      $query->fields('m', ['id','service_title','description','service_link_title_1','service_link_title_2','service_link_title_3','service_link_1','service_link_2','service_link_3','service_icon_service','bg_service','service_order','bg_service_interne']);
      $query->condition('id',$this->id);
      $results = $query->execute()->fetchAssoc();
	  

//$fid = $existing_file ? $existing_file->id() : NULL;
//$fid = 220;
    //display data in site

		//echo 'gggg';print_r($fid);
		// echo '<pre>';print_r($results['service_title']);
        // echo '<pre>';print_r($results['id']);exit;
$form['title_service'] = array(
        '#title' => $this->t('E-service : '),
        '#type' => 'textfield',
        '#prefix' => '<div class="layout-column layout-column--half"><div class="panel">',
        '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
		'#default_value' => $results['service_title'],
      );
      $form['description'] = array(
        '#title' => $this->t('description : '),
        '#type' => 'textfield',
        '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
        '#default_value' => $results['description'],
      );
    $form['title_1'] = array(
      '#title' => $this->t('Titre de la E-service : '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
	  '#default_value' => $results['service_link_title_1'],
    );
	$form['link_1'] = array(
      '#title' => $this->t('lien de la E-service :  '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
	  '#default_value' => $results['service_link_1'],
    );
	$form['title_2'] = array(
      '#title' => $this->t('Titre de la E-service(2eme bottom)'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
	  '#default_value' => $results['service_link_title_2'],
    );
	$form['link_2'] = array(
      '#title' => $this->t('lien de la E-service (2eme bottom)  :  '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
	  '#default_value' => $results['service_link_2'],
    );
	$form['title_3'] = array(
      '#title' => $this->t('Titre de la E-service(2eme bottom)'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
	  '#default_value' => $results['service_link_title_3'],
    );
	$form['link_3'] = array(
      '#title' => $this->t('lien de la E-service (2eme bottom)  :  '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
	  '#default_value' => $results['service_link_3'],
    );
    $form['order'] = array(
      '#title' => $this->t('Order de la E-service:'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
	  '#default_value' => $results['service_order'],
    );
    $form['icon_service'] = array(
      '#title' => $this->t('icon de la E-service :  '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
	  '#default_value' => $results['service_icon_service'],
    );
   
  $form['bg_service'] = [
  '#type' => 'managed_file',
  '#title' => $this->t('bg_service'),
  '#upload_location' => 'public://myfile',
  '#default_value' => array($results['bg_service']),
];
$form['bg_service_interne'] = [
  '#type' => 'managed_file',
  '#title' => $this->t('bg_service_interne'),
  '#upload_location' => 'public://myfile',
  '#default_value' => array($results['bg_service_interne']),
];

 $form['submit'] = array(
            '#type' => 'submit',
			'#suffix' => '</div></div>',
            '#value' => t('update')
        );

        return $form;

  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

$form_file = $form_state->getValue('bg_service')[0];
	$file_object = File::load($form_file);
$file_object->setPermanent();
$file_object->save();
$file_uri = $file_object->getFileUri();
$file_url = Url::fromUri(file_create_url($file_uri))->toString();

$form_file_interne = $form_state->getValue('bg_service_interne')[0];
	$file_object_interne = File::load($form_file_interne);
$file_object_interne->setPermanent();
$file_object_interne->save();
$file_uri_interne = $file_object_interne->getFileUri();
$file_url_interne = Url::fromUri(file_create_url($file_uri_interne))->toString();
	
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
		'icon_service' => $form_state->getValue('icon_service'),
		'bg_service' => $form_file,
		'bg_service_interne' => $form_file_interne,
		
		
	);

	  $insert = db_update('md_services')
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
		->condition('id',$this->id)
		->execute();
		drupal_set_message(t('Settings have been update'));

    
  }



}
