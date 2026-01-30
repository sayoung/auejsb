<?php

namespace Drupal\md_slider\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'SliderBlock' block.
 *
 * @Block(
 *  id = "md_slider_block",
 *  admin_label = @Translation("Slider Block"),
 * )
 */
class SliderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
	$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'slider');
    $query->condition('status', 1);
    $query->condition('langcode', $lang_code);
    $query->sort('created', 'desc');
    $nids = $query->execute();
    $sliders = entity_load_multiple('node', $nids);

    //print_r($sliders);exit;

    return array(
      '#theme' => 'theme_md_slider',
      '#sliders' => $sliders,
      '#cur_lang' => $lang_code,
	  '#cache' => [
          'max-age' => 0,
      ],
	  	  '#attached' => array(
                'library' => array(
                    'md_slider/md-slider-hp'
                )
            )
    );
  }

}
