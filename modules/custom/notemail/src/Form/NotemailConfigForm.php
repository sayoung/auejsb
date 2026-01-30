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
class NotemailConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'notemail_conf_settings_form';
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

    $form['mail'] = array(
      '#type' => 'fieldset',
      '#title' => $this
        ->t('Mail'),
    );
    $form['sms'] = array(
      '#type' => 'fieldset',
      '#title' => $this
        ->t('SMS'),
    );
    $form['mail']['subject'] = array(
      '#title' => $this->t('Subject'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Subject')),
      '#default_value' => $config->get('subject'),

    );
    $form['mail']['message_mail'] = array(
      '#title' => $this->t('Confirmation de la demande E-note(mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de E-note',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Confirmation de la demande E-note')),
      '#default_value' => $config->get('message_mail')['value'],
    );
    $form['mail']['en_echange_mail'] = array(
      '#title' => $this->t('En echange E(mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de E-note </br> [nom] : nom de demandeur </br> [prenom] : nom de demandeur </br> [titre_f] : Références foncières </br> [observations] : Observations ',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Confirmation de la demande E-note')),
      '#default_value' => $config->get('en_echange_mail')['value'],
    );
    $form['mail']['sendMailFinal'] = array(
      '#title' => $this->t('la demande E-note a été traitée (mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de E-note',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('')),
      '#default_value' => $config->get('sendMailFinal')['value'],
    );
    $form['mail']['sendMailAnnulerpaiement'] = array(
      '#title' => $this->t('la demande de la Note de renseignements d\'urbanisme électronique a été archivée (mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de E-note',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail Comptable')),
      '#default_value' => $config->get('sendMailAnnulerpaiement')['value'],
    );
    $form['mail']['footer1'] = array(
      '#title' => $this->t('Footer 1'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Footer 1')),
      '#default_value' => $config->get('footer1'),

    );
    $form['mail']['footer2'] = array(
      '#title' => $this->t('Footer 2'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Footer 2')),
      '#default_value' => $config->get('footer2'),

    );
    $form['sms']['message_phone'] = array(
      '#title' => $this->t('Confirmation de la demande E-note (SMS)'),
      '#type' => 'textarea',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de E-note',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message SMS')),
      '#default_value' => $config->get('message_phone'),
    );
	$form['sms']['message_phone_final'] = array(
      '#title' => $this->t('E-note a été traitée(sms)'),
      '#type' => 'textarea',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de E-note',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message SMS')),
      '#default_value' => $config->get('message_phone_final'),
    );
	$form['sms']['sendSMSAnnulerpaiement'] = array(
      '#title' => $this->t('E-note a été annuler (sms)'),
      '#type' => 'textarea',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de E-note',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message SMS')),
      '#default_value' => $config->get('sendSMSAnnulerpaiement'),
    );
	$form['mail']['emailcci'] = array(
      '#title' => $this->t('Mail en cci'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('mail en cci')),
      '#default_value' => $config->get('emailcci'),

    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

       $this->configFactory->getEditable(Helper::SETTINGS)
      ->set('subject', $form_state->getValue('subject'))
      ->set('message_mail', $form_state->getValue('message_mail'))
      ->set('en_echange_mail', $form_state->getValue('en_echange_mail'))
      ->set('sendMailFinal', $form_state->getValue('sendMailFinal'))
      ->set('sendMailAnnulerpaiement', $form_state->getValue('sendMailAnnulerpaiement'))
      ->set('footer1', $form_state->getValue('footer1'))
      ->set('footer2', $form_state->getValue('footer2'))
      ->set('message_phone', $form_state->getValue('message_phone'))
	  ->set('message_phone_final', $form_state->getValue('message_phone_final'))
	  ->set('sendSMSAnnulerpaiement', $form_state->getValue('sendSMSAnnulerpaiement'))
	  ->set('emailcci', $form_state->getValue('emailcci'))
      ->save();

    parent::submitForm($form, $form_state);
  }


}
