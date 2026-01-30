<?php

namespace Drupal\aust_newsletter\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class NewsletterController.
 *
 * @package Drupal\aust_newsletter\Controller
 */
class NewsletterController extends ControllerBase {

  /**
   * Display.
   *
   * @return string
   *   Return string.
   */
  public function display() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('This page contain all inforamtion about Marie aides ')
    ];
  }

}
