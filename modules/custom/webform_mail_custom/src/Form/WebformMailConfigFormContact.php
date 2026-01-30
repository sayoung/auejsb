<?php

namespace Drupal\webform_mail_custom\Form;
use Drupal\webform_mail_custom\Helper\Helper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
/**
 * webform mail custom settings form.
 */

class WebformMailConfigFormContact extends ConfigFormBase { 
const SETTINGS = 'webform_mail.settings';
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webform_mail_contact_config_settings_form';
  }
    /**
   * {@inheritdoc}
   *
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

	$form['text']['list_mail_contact_france'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact france'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact france')),
      '#default_value' => $config->get('list_mail_contact_france'),
    );
		$form['text']['list_mail_contact_usa'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact usa'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact usa')),
      '#default_value' => $config->get('list_mail_contact_usa'),
    );
		$form['text']['list_mail_contact_unitedkingdom'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact united kingdom'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact united kingdom')),
      '#default_value' => $config->get('list_mail_contact_unitedkingdom'),
    );
		$form['text']['list_mail_contact_belgique'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact belgique'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact belgique')),
      '#default_value' => $config->get('list_mail_contact_belgique'),
    );
		$form['text']['list_mail_contact_nederland'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact nederland'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact nederland')),
      '#default_value' => $config->get('list_mail_contact_nederland'),
    );
		$form['text']['list_mail_contact_espana'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact espana'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact espana')),
      '#default_value' => $config->get('list_mail_contact_espana'),
    );
		$form['text']['list_mail_contact_canada'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact canada'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact canada')),
      '#default_value' => $config->get('list_mail_contact_canada'),
    );
		$form['text']['list_mail_contact_maroc'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact maroc'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact maroc')),
      '#default_value' => $config->get('list_mail_contact_maroc'),
    );
		$form['text']['list_mail_contact_italia'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact italia'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact italia')),
      '#default_value' => $config->get('list_mail_contact_italia'),
    );
		$form['text']['list_mail_contact_polska'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire contact polska'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire contact polska')),
      '#default_value' => $config->get('list_mail_contact_polska'),
    );
	
	 return parent::buildForm($form, $form_state);
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {

       $this->configFactory->getEditable(Helper::SETTINGS)
      ->set('list_mail_contact_france', $form_state->getValue('list_mail_contact_france'))
	  ->set('list_mail_contact_usa', $form_state->getValue('list_mail_contact_usa'))
	  ->set('list_mail_contact_unitedkingdom', $form_state->getValue('list_mail_contact_unitedkingdom'))
	  ->set('list_mail_contact_belgique', $form_state->getValue('list_mail_contact_belgique'))
	  ->set('list_mail_contact_nederland', $form_state->getValue('list_mail_contact_nederland'))
	  ->set('list_mail_contact_espana', $form_state->getValue('list_mail_contact_espana'))
	  ->set('list_mail_contact_canada', $form_state->getValue('list_mail_contact_canada'))
	  ->set('list_mail_contact_maroc', $form_state->getValue('list_mail_contact_maroc'))
	  ->set('list_mail_contact_italia', $form_state->getValue('list_mail_contact_italia'))
	  ->set('list_mail_contact_polska', $form_state->getValue('list_mail_contact_polska'))
      ->save();

    parent::submitForm($form, $form_state);
  }
	

}