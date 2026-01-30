<?php

namespace Drupal\md_newsletter\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'NewsletterBlock' block.
 *
 * @Block(
 *  id = "md_newsletter_block",
 *  admin_label = @Translation("md Newsletter block"),
 * )
 */
class NewsletterBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\md_newsletter\Form\NewsletterForm');
    return array(
        '#theme' => 'newsletter',
        '#form' =>  (object)array('form' => $form),
		'#cache' => [
          'max-age' => 0,
      ],
		'#attached' => array(
                'library' => array(
                    'md_newsletter/md-newsletter-hp'
                )
            )
    );
  }

}
