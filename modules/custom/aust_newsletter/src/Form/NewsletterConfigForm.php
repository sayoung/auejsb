<?php

namespace Drupal\aust_newsletter\Form;

use Drupal\aust_newsletter\Helper\Helper;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
 
/**
 * Flatchr settings form.
 */
class NewsletterConfigForm extends ConfigFormBase {
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'newsletter_conf_settings_form';
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
    
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'actualites')
      ->sort('created' , 'DESC')
      ->range(0, 50);
      $nids = $query->execute();
    $nodes = node_load_multiple($nids);
    
    $nodes_array = array();
    foreach ($nodes as $node){
      $nodes_array[$node->id()] = $node->getTitle();
    }
    
    $form['subject'] = array(
      '#type' => 'textfield',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Subject')),
      '#default_value' => $config->get('subject'),
 
    );
    $form['intro'] = array(
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Header')),
      '#default_value' => $config->get('intro')['value'],
    );
    $form['news'] = array(
      '#title' => t('Actualités'),
      '#type' => 'checkboxes',
      '#description' => $this->t('Séléctionnez les actualités à envoyer dans la newslettrer'),
      '#options' => $nodes_array,
      '#default_value' => $config->get('news'),
    );
    $form['footer'] = array(
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => $this->t('Footer')),
      '#default_value' => $config->get('footer')['value'],
    );
    
    $form['open_modal'] = [
      '#type' => 'link',
      '#title' => $this->t('Send'),
      '#url' => Url::fromRoute('modal_form_send.open_modal_form'),
      '#attributes' => [
        'class' => [
          'use-ajax',
          'button',
        ],
      ],
    ];
    
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      
       $this->configFactory->getEditable(Helper::SETTINGS)
      ->set('subject', $form_state->getValue('subject'))
      ->set('intro', $form_state->getValue('intro'))
      ->set('news', $form_state->getValue('news'))
      ->set('footer', $form_state->getValue('footer'))
      ->save();

    parent::submitForm($form, $form_state);
  }
  

}
