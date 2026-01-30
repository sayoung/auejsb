<?php

namespace Drupal\aust_newsletter\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'NewsletterBlock' block.
 *
 * @Block(
 *  id = "aust_newsletter_block",
 *  admin_label = @Translation("AUST Newsletter block"),
 * )
 */
class NewsletterBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\aust_newsletter\Form\NewsletterForm');
    return array(
        '#theme' => 'newsletter',
        '#form' =>  (object)array('form' => $form)
    );
  }

}
