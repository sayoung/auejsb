<?php

namespace Drupal\md_services\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class NewsletterController.
 *
 * @package Drupal\md_services\Controller
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
      '#markup' => $this->t('This page contain all inforamtion about E-service ')
    ];
  }

}
