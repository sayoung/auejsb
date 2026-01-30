<?php

namespace Drupal\md_count\Form;

use Drupal\md_count\Helper\Helper;
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
    return 'count_conf_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['service'] = array(
        '#title' => $this->t('service : '),
        '#type' => 'textfield',
        '#prefix' => '<div class="layout-column layout-column--half"><div class="panel">',
        '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      );
    $form['value'] = array(
      '#title' => $this->t('value: '),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
    );
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


	$values = array(
		'service' => $form_state->getValue('service'),
		'value' => $form_state->getValue('value'),
	);

	  $insert = db_insert('md_count')
	  -> fields(array(
			'service' => $values['service'],
			'value' => $values['value'],
		))
		->execute();
		drupal_set_message(t('Settings have been saved'));

    
  }


}
