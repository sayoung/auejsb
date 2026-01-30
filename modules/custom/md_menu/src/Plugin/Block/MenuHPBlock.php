<?php

namespace Drupal\md_menu\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'MenuHPBlock' block.
 *
 * @Block(
 *  id = "md_menu_hp_block",
 *  admin_label = @Translation("Menu HP Block"),
 * )
 */
class MenuHPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'md_agenda');
    $query->condition('status', 1);
	$query->condition('langcode', $lang_code);
    $query->sort('created', 'desc');
    $query->range(0, 3);
    $nids = $query->execute();
    $nodes = entity_load_multiple('node', $nids);

   // print_r($nodes);exit;

    return array(
      '#theme' => 'theme_md_menu_hp',
	  '#attached' => array(
                'library' => array(
                    'md_menu/md-menu-hp'
                )
            )
    );
  }

}
