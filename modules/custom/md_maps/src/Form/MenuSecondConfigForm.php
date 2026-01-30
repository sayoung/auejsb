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
class MenuSecondConfigForm extends ConfigFormBase {

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
    for($i=1; $i<=4; $i++){
    $form['title_services_scnd_'. $i] = array(
      '#title' => $this->t('Titre du services : '. $i),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      '#default_value' => $config->get('title_services_scnd_'. $i),
    );
		$form['link_services_scnd_'. $i] = array(
      '#type' => 'textfield',
	  '#title' => $this->t('lien du services : '. $i),
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      '#default_value' => $config->get('link_services_scnd_'. $i),
    );
	
	}
	
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
for($i=1; $i<=4; $i++){
	   $this->configFactory->getEditable("md_maps.setting")
      ->set('title_'. $i, $form_state->getValue('title_services_scnd_'. $i))
	  ->set('link_'. $i, $form_state->getValue('link_services_scnd_'. $i))
	  ->save();
}
    parent::submitForm($form, $form_state);
  }


}
