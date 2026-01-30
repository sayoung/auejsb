<?php

namespace Drupal\md_block\Form;

use Drupal\md_block\Helper\Helper;
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
    return 'block_conf_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      "md_block.setting",
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

	$config = $this->config("md_block.setting");
    for($i=1; $i<=4; $i++){
    $form['md_block']['title_'. $i] = array(
      '#title' => $this->t('Titre de la Bloc : '. $i),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      '#default_value' => $config->get('title_'. $i),
    );
	$form['md_block']['icon_'. $i] = array(
      '#title' => $this->t('icon : '. $i),
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Remplire le champs')),
      '#default_value' => $config->get('icon_'. $i),
    );
	$form['md_block']['link_'. $i] = array(
      '#title' => $this->t('lien de la Bloc :  '. $i),
      '#type' => 'textfield',
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
for($i=1; $i<=4; $i++){
	   $this->configFactory->getEditable("md_block.setting")
      ->set('title_'. $i, $form_state->getValue('title_'. $i))
	  ->set('icon_'. $i, $form_state->getValue('icon_'. $i))
	  ->set('link_'. $i, $form_state->getValue('link_'. $i))
	  ->save();
}
    parent::submitForm($form, $form_state);
  }


}
