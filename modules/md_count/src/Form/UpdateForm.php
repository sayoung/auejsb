<?php

namespace Drupal\md_count\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Drupal\file\Entity\File;
use Drupal\md_count\Helper\Helper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class UpdateForm.
 *
 * @package Drupal\md_count\Form
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
    return new Url('md_count.display_table_controller_display');
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
    $query = \Drupal::database()->select('md_count', 'm');
      $query->fields('m', ['id','service','value']);
      $query->condition('id',$this->id);
      $results = $query->execute()->fetchAssoc();
	  

//$fid = $existing_file ? $existing_file->id() : NULL;
//$fid = 220;
    //display data in site

		//echo 'gggg';print_r($fid);
		// echo '<pre>';print_r($results['service_title']);
        // echo '<pre>';print_r($results['id']);exit;
$form['service'] = array(
        '#title' => $this->t('E-service : '),
        '#type' => 'textfield',
        '#prefix' => '<div class="layout-column layout-column--half"><div class="panel">',
        '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
		'#default_value' => $results['service'],
      );
    $form['value'] = array(
      '#title' => $this->t('Titre de la E-service : '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
	  '#default_value' => $results['value'],
    );

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
	
	$values = array(
		'service' => $form_state->getValue('service'),
		'value' => $form_state->getValue('value'),
		
		
	);

	  $insert = db_update('md_count')
	  -> fields(array(
			'service' => $values['service'],
			'value' => $values['value']
		))
		->condition('id',$this->id)
		->execute();
		drupal_set_message(t('Settings have been update'));

    
  }
  //   $num_deleted = db_delete('md_count')
  // ->condition('id', 1)
  // ->execute();

  //     if($num_deleted == TRUE){
  //        drupal_set_message("deleted suceesfully");
  //      }
  //    else
  //     {

  //       drupal_set_message(" unsucessfully");
  //      }

  // }



}
