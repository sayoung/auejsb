<?php

namespace Drupal\md_block_contact\Form;

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
      "md_block_contact.setting",
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

	$config = $this->config("md_block_contact.setting");
    $form['title_block_map'] = array(
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Subject')),
      '#default_value' => $config->get('title_block_map'),

    );
        $form['facebook'] = array(
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('facebook')),
      '#default_value' => $config->get('facebook'),

    );
    $form['twitter'] = array(
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('twitter')),
      '#default_value' => $config->get('twitter'),

    );
    $form['youtube'] = array(
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('youtube')),
      '#default_value' => $config->get('youtube'),

    );
    $form['insta'] = array(
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('insta')),
      '#default_value' => $config->get('insta'),

    );
$form['googleplus'] = array(
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('googleplus')),
      '#default_value' => $config->get('googleplus'),

    );


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

	   $this->configFactory->getEditable("md_block_contact.setting")
      ->set('title_block_map', $form_state->getValue('title_block_map'))
	   ->set('facebook', $form_state->getValue('facebook'))
	  ->set('twitter', $form_state->getValue('twitter'))
	  ->set('youtube', $form_state->getValue('youtube'))
	  ->set('insta', $form_state->getValue('insta'))
	  ->set('googleplus', $form_state->getValue('googleplus'))
	  ->save();
    parent::submitForm($form, $form_state);
  }


}
