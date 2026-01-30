<?php

namespace Drupal\aust_eprestation\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\aust_eprestation\Helper\Helper;
use Drupal\aust_eprestation\Eprestation\Eprestation;

/**
 * Eprestation settings form.
 */
class EprestationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'eprestation_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['eprestation.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    //Helper::deleteAll();
    Eprestation::synchroniser();
    parent::submitForm($form, $form_state);
  }

}
