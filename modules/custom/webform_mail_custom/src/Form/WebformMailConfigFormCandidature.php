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

class WebformMailConfigFormCandidature extends ConfigFormBase { 
const SETTINGS = 'webform_mail.settings';
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webform_mail_candidature_config_settings_form';
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

	$form['text']['list_mail_candidature_france'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature france'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature france')),
      '#default_value' => $config->get('list_mail_candidature_france'),
    );
		$form['text']['list_mail_candidature_usa'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature usa'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature usa')),
      '#default_value' => $config->get('list_mail_candidature_usa'),
    );
		$form['text']['list_mail_candidature_unitedkingdom'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature united kingdom'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature united kingdom')),
      '#default_value' => $config->get('list_mail_candidature_unitedkingdom'),
    );
		$form['text']['list_mail_candidature_belgique'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature belgique'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature belgique')),
      '#default_value' => $config->get('list_mail_candidature_belgique'),
    );
		$form['text']['list_mail_candidature_nederland'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature nederland'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature nederland')),
      '#default_value' => $config->get('list_mail_candidature_nederland'),
    );
		$form['text']['list_mail_candidature_espana'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature espana'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature espana')),
      '#default_value' => $config->get('list_mail_candidature_espana'),
    );
		$form['text']['list_mail_candidature_canada'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature canada'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature canada')),
      '#default_value' => $config->get('list_mail_candidature_canada'),
    );
		$form['text']['list_mail_candidature_maroc'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature maroc'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature maroc')),
      '#default_value' => $config->get('list_mail_candidature_maroc'),
    );
		$form['text']['list_mail_candidature_italia'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature italia'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature italia')),
      '#default_value' => $config->get('list_mail_candidature_italia'),
    );
		$form['text']['list_mail_candidature_polska'] = array(
      '#title' => $this->t('la liste des e-mail pour la formulaire candidature polska'),
      '#type' => 'textarea',
	  '#description' => 'separer entre les email par (,) pour ajouter d\'autre destinataires',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('la liste des e-mail pour la formulaire candidature polska')),
      '#default_value' => $config->get('list_mail_candidature_polska'),
    );
	
	 return parent::buildForm($form, $form_state);
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {

       $this->configFactory->getEditable(Helper::SETTINGS)
      ->set('list_mail_candidature_france', $form_state->getValue('list_mail_candidature_france'))
	  ->set('list_mail_candidature_usa', $form_state->getValue('list_mail_candidature_usa'))
	  ->set('list_mail_candidature_unitedkingdom', $form_state->getValue('list_mail_candidature_unitedkingdom'))
	  ->set('list_mail_candidature_belgique', $form_state->getValue('list_mail_candidature_belgique'))
	  ->set('list_mail_candidature_nederland', $form_state->getValue('list_mail_candidature_nederland'))
	  ->set('list_mail_candidature_espana', $form_state->getValue('list_mail_candidature_espana'))
	  ->set('list_mail_candidature_canada', $form_state->getValue('list_mail_candidature_canada'))
	  ->set('list_mail_candidature_maroc', $form_state->getValue('list_mail_candidature_maroc'))
	  ->set('list_mail_candidature_italia', $form_state->getValue('list_mail_candidature_italia'))
	  ->set('list_mail_candidature_polska', $form_state->getValue('list_mail_candidature_polska'))
      ->save();

    parent::submitForm($form, $form_state);
  }
	

}