<?php

namespace Drupal\md_block_contact\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Site\Settings;
/**
 * Flatchr settings form.
 */
class MenuConfigForm extends ConfigFormBase {

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
	$form['cat_1_sub_menu1'] = array(
      '#type' => 'textfield',
	  '#title' => 'menu',
	  '#prefix' => '<div class="layout-column layout-column--half"><div class="panel">',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Subject')),
      '#default_value' => $config->get('cat_1_sub_menu1'),

    );
	for($i=2; $i<=7; $i++){
	 $form['cat_1_sub_menu'. $i] = array(
      '#type' => 'textfield',
	  '#title' => 'menu'. $i,
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Subject')),
      '#default_value' => $config->get('cat_1_sub_menu'. $i),

    );
		}
	$form['cat_1_sub_menu8'] = array(
      '#type' => 'textfield',
	  '#title' => 'menu',
	  '#suffix' => '</div></div>',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Subject')),
      '#default_value' => $config->get('cat_1_sub_menu8'),

    );
	$form['cat_2_link_1'] = array(
      '#type' => 'textfield',
	  '#title' => 'Lien',
	  '#prefix' => '<div class="layout-column layout-column--half"><div class="panel">',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('lien')),
      '#default_value' => $config->get('cat_2_link_1'),

    );
	for($i=2; $i<=7; $i++){
	 $form['cat_2_link_'. $i] = array(
      '#type' => 'textfield',
	  '#title' => 'Lien'. $i,
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('lien')),
      '#default_value' => $config->get('cat_2_link_'. $i),

    );
		}
		$form['cat_2_link_8'] = array(
      '#type' => 'textfield',
	  '#title' => 'Lien',
	  '#suffix' => '</div></div>',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('lien')),
      '#default_value' => $config->get('cat_2_link_8'),

    );
		
			


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

	   $this->configFactory->getEditable("md_block_contact.setting")
	  ->set('cat_1_sub_menu1', $form_state->getValue('cat_1_sub_menu1'))
	  ->set('cat_1_sub_menu2', $form_state->getValue('cat_1_sub_menu2'))
	  ->set('cat_1_sub_menu3', $form_state->getValue('cat_1_sub_menu3'))
	  ->set('cat_1_sub_menu4', $form_state->getValue('cat_1_sub_menu4'))
	  ->set('cat_1_sub_menu5', $form_state->getValue('cat_1_sub_menu5'))
	  ->set('cat_1_sub_menu6', $form_state->getValue('cat_1_sub_menu6'))
	  ->set('cat_1_sub_menu7', $form_state->getValue('cat_1_sub_menu7'))
	  ->set('cat_1_sub_menu8', $form_state->getValue('cat_1_sub_menu8'))
      ->set('cat_2_link_1', $form_state->getValue('cat_2_link_1'))
	  ->set('cat_2_link_2', $form_state->getValue('cat_2_link_2'))
	  ->set('cat_2_link_3', $form_state->getValue('cat_2_link_3'))
	  ->set('cat_2_link_4', $form_state->getValue('cat_2_link_4'))
	  ->set('cat_2_link_5', $form_state->getValue('cat_2_link_5'))
	  ->set('cat_2_link_6', $form_state->getValue('cat_2_link_6'))
	  ->set('cat_2_link_7', $form_state->getValue('cat_2_link_7'))
	  ->set('cat_2_link_8', $form_state->getValue('cat_2_link_8'))

	  ->save();
    parent::submitForm($form, $form_state);
  }


}
