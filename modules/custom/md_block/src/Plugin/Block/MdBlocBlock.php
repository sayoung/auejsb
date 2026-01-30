<?php

namespace Drupal\md_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'MdBlocBlock' block.
 *
 * @Block(
 *  id = "md_block_block",
 *  admin_label = @Translation("md block Block"),
 * )
 */
class MdBlocBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

 
$config = \Drupal::config("md_block.setting");
    //print_r($sliders);exit;
  
    return array(
      '#theme' => 'theme_md_block',
	   
	  '#md_block' => (object)array(
	                  "link_1" => $config->get('link_1'),
					  "link_2" => $config->get('link_2'),
					  "link_3" => $config->get('link_3'),
					  "link_4" => $config->get('link_4'),
					  "icon_1" => $config->get('icon_1'),
					  "icon_2" => $config->get('icon_2'),
                      "title_1" => $config->get('title_1'),
					  "title_2" => $config->get('title_2'),
					  "title_3" => $config->get('title_3'),
					  "title_4" => $config->get('title_4'),
					  "icon_3" => $config->get('icon_3'),
					  "icon_4" => $config->get('icon_4'),
                    ),
	  '#cache' => [
          'max-age' => 0,
      ],
	  '#attached' => array(
                'library' => array(
                    'md_block/md-block-hp'
                )
            )
	);

   }
}
