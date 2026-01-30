<?php

namespace Drupal\d8mail\Form;

use Drupal\d8mail\Instruction\Instruction;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
/**
 * Flatchr settings form.
 */
class InsConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'instruction_conf_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      Instruction::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(Instruction::SETTINGS);

    $form['mail'] = array(
      '#type' => 'fieldset',
      '#title' => $this
        ->t('Mail'),
    );
    $form['mail']['subject'] = array(
      '#title' => $this->t('Subject'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Subject')),
      '#default_value' => $config->get('subject'),

    );
    $form['mail']['message_mail'] = array(
      '#title' => $this->t('Confirmation de la demande pré-instruction(mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de pré-instruction',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Confirmation de la demande pré-instruction')),
      '#default_value' => $config->get('message_mail')['value'],
    );
    $form['mail']['sendMailFinal'] = array(
      '#title' => $this->t('la demande pré-instruction a été traitée (mail)'),
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#description' => '[code] est une variable sera remplacer par le code de pré-instruction [observation]',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('')),
      '#default_value' => $config->get('sendMailFinal')['value'],
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

       $this->configFactory->getEditable(Instruction::SETTINGS)
      ->set('subject', $form_state->getValue('subject'))
      ->set('message_mail', $form_state->getValue('message_mail'))
      ->set('sendMailFinal', $form_state->getValue('sendMailFinal'))
      ->set('footer1', $form_state->getValue('footer1'))
      ->set('footer2', $form_state->getValue('footer2'))
	  ->set('emailcci', $form_state->getValue('emailcci'))
      ->save();

    parent::submitForm($form, $form_state);
  }


}
