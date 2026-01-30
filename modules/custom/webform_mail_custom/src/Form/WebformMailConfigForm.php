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

class WebformMailConfigForm extends ConfigFormBase { 
const SETTINGS = 'webform_mail.settings';
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webform_mail_config_settings_form';
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

	$form['text']['list_mail_event_france'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event france'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event france')),
      '#default_value' => $config->get('list_mail_event_france'),
    );
		$form['text']['list_mail_event_usa'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event usa'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event usa')),
      '#default_value' => $config->get('list_mail_event_usa'),
    );
		$form['text']['list_mail_event_unitedkingdom'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event united kingdom'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event united kingdom')),
      '#default_value' => $config->get('list_mail_event_unitedkingdom'),
    );
		$form['text']['list_mail_event_belgique'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event belgique'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event belgique')),
      '#default_value' => $config->get('list_mail_event_belgique'),
    );
		$form['text']['list_mail_event_nederland'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event nederland'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event nederland')),
      '#default_value' => $config->get('list_mail_event_nederland'),
    );
		$form['text']['list_mail_event_espana'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event espana'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event espana')),
      '#default_value' => $config->get('list_mail_event_espana'),
    );
		$form['text']['list_mail_event_canada'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event canada'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event canada')),
      '#default_value' => $config->get('list_mail_event_canada'),
    );
		$form['text']['list_mail_event_maroc'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event maroc'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event maroc')),
      '#default_value' => $config->get('list_mail_event_maroc'),
    );
		$form['text']['list_mail_event_italia'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event italia'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event italia')),
      '#default_value' => $config->get('list_mail_event_italia'),
    );
		$form['text']['list_mail_event_polska'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire event polska'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire event polska')),
      '#default_value' => $config->get('list_mail_event_polska'),
    );
	
	 return parent::buildForm($form, $form_state);
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {

       $this->configFactory->getEditable(Helper::SETTINGS)
      ->set('list_mail_event_france', $form_state->getValue('list_mail_event_france'))
	  ->set('list_mail_event_usa', $form_state->getValue('list_mail_event_usa'))
	  ->set('list_mail_event_unitedkingdom', $form_state->getValue('list_mail_event_unitedkingdom'))
	  ->set('list_mail_event_belgique', $form_state->getValue('list_mail_event_belgique'))
	  ->set('list_mail_event_nederland', $form_state->getValue('list_mail_event_nederland'))
	  ->set('list_mail_event_espana', $form_state->getValue('list_mail_event_espana'))
	  ->set('list_mail_event_canada', $form_state->getValue('list_mail_event_canada'))
	  ->set('list_mail_event_maroc', $form_state->getValue('list_mail_event_maroc'))
	  ->set('list_mail_event_italia', $form_state->getValue('list_mail_event_italia'))
	  ->set('list_mail_event_polska', $form_state->getValue('list_mail_event_polska'))
      ->save();

    parent::submitForm($form, $form_state);
  }
	

}