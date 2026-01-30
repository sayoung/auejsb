<?php

namespace Drupal\md_publication\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'PublicationBlock' block.
 *
 * @Block(
 *  id = "md_publication_block",
 *  admin_label = @Translation("Publication Block"),
 * )
 */
class PublicationBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'publication');
    $query->condition('status', 1);
    $query->sort('created');
    $nids = $query->execute();
    $publication = entity_load_multiple('node', $nids);

    //print_r($sliders);exit;

    return array(
      '#theme' => 'theme_md_publication',
      '#publication' => $publication,
	  '#cache' => [
          'max-age' => 0,
      ],
	  '#attached' => array(
                'library' => array(
                    'md_publication/md-publication-hp'
                )
            )
    );
  }

}
