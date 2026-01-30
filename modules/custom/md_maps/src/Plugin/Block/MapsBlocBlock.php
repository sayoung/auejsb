<?php

namespace Drupal\md_maps\Plugin\Block;

use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'MapsBlocBlock' block.
 *
 * @Block(
 *  id = "md_maps_block",
 *  admin_label = @Translation("maps block Block"),
 * )
 */
class MapsBlocBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

 
$config = \Drupal::config("md_maps.setting");
    //print_r($sliders);exit;
  
    return array(
      '#theme' => 'theme_md_maps',
	   
	  '#md_maps' => (object)array(
	                  "link_1" => $config->get('link_1'),
					  "link_2" => $config->get('link_2'),
					  "link_3" => $config->get('link_3'),
                      "title_1" => $config->get('title_1'),
					  "title_2" => $config->get('title_2'),
					  "title_3" => $config->get('title_3'),
					  "info_1" => $config->get('info_1'),
					  "info_2" => $config->get('info_2'),
					  "info_3" => $config->get('info_3'),
					  "title_block" => $config->get('title_block'),
					  "title_services_scnd_1" => $config->get('title_services_scnd_1'),
					  "title_services_scnd_2" => $config->get('title_services_scnd_2'),
					  "title_services_scnd_3" => $config->get('title_services_scnd_3'),
                      "title_services_scnd_4" => $config->get('title_services_scnd_4'),
                      "link_services_scnd_1" => $config->get('link_services_scnd_1'),
					  "link_services_scnd_2" => $config->get('link_services_scnd_2'),
					  "link_services_scnd_3" => $config->get('link_services_scnd_3'),
                      "link_services_scnd_4" => $config->get('link_services_scnd_4'),
                    ),
		'#cache' => [
          'max-age' => 0,
      ],
	  '#attached' => array(
                'library' => array(
                    'md_maps/md-maps-hp'
                )
            )
	);

   }
}
