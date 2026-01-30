<?php

namespace Drupal\md_maps\Form;

use Drupal\md_maps\Helper\Helper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
/**
 * Flatchr settings form.
 */
class BlocConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'maps_conf_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      "md_maps.setting",
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

	$config = $this->config("md_maps.setting");
    $form['md_maps']['title_block'] = array(
      '#title' => $this->t('Titre de la Bloc :'),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      '#default_value' => $config->get('title_block'),
    );	
    for($i=1; $i<=3; $i++){
    $form['md_maps']['title_'. $i] = array(
      '#title' => $this->t('Titre de la Bloc : '. $i),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      '#default_value' => $config->get('title_'. $i),
    );
	$form['md_maps']['info_'. $i] = array(
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      '#default_value' => $config->get('info_'. $i)['value'],
	 
    );
		$form['md_maps']['link_'. $i] = array(
      '#type' => 'textfield',
	  '#title' => $this->t('lien : '. $i),
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      '#default_value' => $config->get('link_'. $i),
    );
	
	}
	
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
for($i=1; $i<=6; $i++){
	   $this->configFactory->getEditable("md_maps.setting")
      ->set('title_'. $i, $form_state->getValue('title_'. $i))
	  ->set('link_'. $i, $form_state->getValue('link_'. $i))
	  ->set('info_'. $i, $form_state->getValue('info_'. $i))
	  ->set('title_block', $form_state->getValue('title_block'))
	  ->save();
}
    parent::submitForm($form, $form_state);
  }


}
