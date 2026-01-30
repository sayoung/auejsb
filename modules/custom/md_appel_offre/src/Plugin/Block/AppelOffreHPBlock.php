<?php

namespace Drupal\md_appel_offre\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity;
/**
 * Provides a 'AppelOffreHPBlock' block.
 *
 * @Block(
 *  id = "md_appel_offre_hp_block",
 *  admin_label = @Translation("Appel offre HP Block"),
 * )
 */
class AppelOffreHPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
	$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'publication');
    $query->condition('status', 1);
	$query->condition('langcode', $lang_code);
    $query->sort('created', 'desc');
    $query->range(0, 3);
    $nids = $query->execute();
    $nodes = entity_load_multiple('node', $nids);

   // print_r($nodes);exit;

    return array(
      '#theme' => 'theme_md_appel_offre_hp',
      '#nodes' => $nodes,
      '#cur_lang' => $lang_code,
	  '#cache' => [
          'max-age' => 0,
      ],
	  '#attached' => array(
                'library' => array(
                    'md_appel_offre/appel-offre-hp'
                )
            )
    );
  }

}
