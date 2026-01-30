<?php

namespace Drupal\md_user\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'UserHPBlock' block.
 *
 * @Block(
 *  id = "md_user_hp_block",
 *  admin_label = @Translation("user HP Block"),
 * )
 */
class UserHPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'actualites');
    $query->condition('status', 1);
    $query->condition('langcode', $lang_code);
    $query->sort('created', 'desc');
    $query->range(0, 10);
    $nids = $query->execute();
    $nodes = entity_load_multiple('node', $nids);

   // print_r($nodes);exit;

    return array(
      '#theme' => 'theme_md_user_hp',
      '#nodes' => $nodes,
      '#cur_lang' => $lang_code,
	  '#cache' => [
          'max-age' => 0,
      ],
	  '#attached' => array(
                'library' => array(
                    'md_user/md-user-hp'
                )
            )
    );
  }

}
