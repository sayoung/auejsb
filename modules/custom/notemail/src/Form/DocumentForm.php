<?php

namespace Drupal\notemail\Form;

use Drupal\notemail\Helper\Helper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
/**
 * Flatchr settings form.
 */
class DocumentForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'document_conf_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      Helper::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(Helper::SETTINGS);


	$form['mail']['documentcci'] = array(
      '#title' => $this->t('la liste des mail pour recevoir la notificarion par email -- separer les mail par virgule --'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('les mails')),
      '#default_value' => $config->get('documentcci'),

    );
    $form['mail']['document_mail'] = array(
      '#title' => $this->t('Confirmation de la demande E-note(mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[client] est une variable sera remplacer par le Nom du client',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('mail to client')),
      '#default_value' => $config->get('document_mail')['value'],
    );
    $form['mail']['document_mail_bo'] = array(
      '#title' => $this->t('Confirmation de la demande E-note(mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[client] est une variable sera remplacer par le nom du client',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('mail to bo')),
      '#default_value' => $config->get('document_mail_bo')['value'],
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

       $this->configFactory->getEditable(Helper::SETTINGS)
	  ->set('documentcci', $form_state->getValue('documentcci'))
	  ->set('document_mail', $form_state->getValue('document_mail'))
	  ->set('document_mail_bo', $form_state->getValue('document_mail_bo'))
      ->save();

    parent::submitForm($form, $form_state);
  }


}
