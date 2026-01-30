<?php

namespace Drupal\aust_eprestation\Form;

use Drupal\aust_eprestation\Helper\Helper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
/**
 * Flatchr settings form.
 */
class EprestationConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'eprestation_conf_settings_form';
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
      '#title' => $this->t('Message De confirmation demande de Calcul (E-mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail')),
      '#default_value' => $config->get('message_mail')['value'],
    );
	$form['mail']['message_mail_final'] = array(
      '#title' => $this->t('Message Final pour proceder au paiement (E-mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail')),
      '#default_value' => $config->get('message_mail_final')['value'],
    );
    $form['mail']['message_mail_tech'] = array(
      '#title' => $this->t('Message E-mail Technicien'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail Technicien')),
      '#default_value' => $config->get('message_mail_tech')['value'],
    );
    $form['mail']['message_mail_compta'] = array(
      '#title' => $this->t('Message E-mail Comptable'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail Comptable')),
      '#default_value' => $config->get('message_mail_compta')['value'],
    );
	$form['mail']['message_mail_Final_client'] = array(
      '#title' => $this->t('Message E-mail Client aprés paiement'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message E-mail apres paiement')),
      '#default_value' => $config->get('message_mail_Final_client')['value'],
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
	$form['mail']['emailcomptable'] = array(
      '#title' => $this->t('Adresse e-mail de comptable'),
      '#type' => 'email',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('E-mail')),
      '#default_value' => $config->get('emailcomptable'),

    );
	$form['mail']['emailtechnicien'] = array(
      '#title' => $this->t('Adresse e-mail de Technicien'),
      '#type' => 'email',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('E-mail')),
      '#default_value' => $config->get('emailtechnicien'),

    );
    $form['sms']['message_phone'] = array(
      '#title' => $this->t('Message De confirmation demande de Calcul (sms)'),
      '#type' => 'textarea',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message SMS')),
      '#default_value' => $config->get('message_phone'),
    );
	$form['sms']['message_phone_final'] = array(
      '#title' => $this->t('Message SMS Final'),
      '#type' => 'textarea',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de e-prestation',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Message SMS')),
      '#default_value' => $config->get('message_phone_final'),
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
	  ->set('message_mail_final', $form_state->getValue('message_mail_final'))
      ->set('message_mail_tech', $form_state->getValue('message_mail_tech'))
      ->set('message_mail_compta', $form_state->getValue('message_mail_compta'))
      ->set('footer1', $form_state->getValue('footer1'))
      ->set('footer2', $form_state->getValue('footer2'))
      ->set('message_phone', $form_state->getValue('message_phone'))
	  ->set('message_phone_final', $form_state->getValue('message_phone_final'))
	  ->set('emailtechnicien', $form_state->getValue('emailtechnicien'))
	  ->set('emailcomptable', $form_state->getValue('emailcomptable'))
	  ->set('message_mail_Final_client', $form_state->getValue('message_mail_Final_client'))
      ->set('emailcci', $form_state->getValue('emailcci'))
	  ->save();

    parent::submitForm($form, $form_state);
  }


}
