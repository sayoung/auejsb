<?php

namespace Drupal\md_agenda\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity;
/**
 * Provides a 'OffreHPBlock' block.
 *
 * @Block(
 *  id = "md_offre_hp_block",
 *  admin_label = @Translation("offre HP Block"),
 * )
 */
class OffreHPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
	$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = \Drupal::entityQuery('node');
    $query->condition('type','appel_d_offre');
    $query->condition('status', 1);
	$query->condition('langcode', $lang_code);
    $query->sort('created', 'desc');
    $query->range(0, 5);
    $nids = $query->execute();
    $offres = entity_load_multiple('node', $nids);

   // print_r($nodes);exit;

    return array(
      '#theme' => 'theme_md_agenda_hp',
      '#offres' => $offres,
      '#cur_lang' => $lang_code,
	  '#cache' => [
          'max-age' => 0,
      ],
	  '#attached' => array(
                'library' => array(
                    'md_agenda/md-agenda-hp'
                )
            )
    );
  }

}
