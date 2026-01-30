<?php

namespace Drupal\md_agenda\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity;
/**
 * Provides a 'AgendaHPBlock' block.
 *
 * @Block(
 *  id = "md_agenda_hp_block",
 *  admin_label = @Translation("Agenda HP Block"),
 * )
 */
class AgendaHPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
	$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = \Drupal::entityQuery('node');
    $query->condition('type',['appel_d_offre','md_agenda','appel_a_candidatures'], 'IN');
    $query->condition('status', 1);
	$query->condition('langcode', $lang_code);
	$query->sort('field_date', 'desc');
    $nids = $query->execute();
    $nodes = entity_load_multiple('node', $nids);

   // print_r($nodes);exit;

    return array(
      '#theme' => 'theme_md_agenda_hp',
      '#nodes' => $nodes,
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
