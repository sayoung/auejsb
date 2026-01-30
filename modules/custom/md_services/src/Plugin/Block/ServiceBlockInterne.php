<?php

namespace Drupal\md_services\Plugin\Block;

use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'ServiceBlockInterne' block.
 *
 * @Block(
 *  id = "md_services_block_interne",
 *  admin_label = @Translation("Service Block interne"),
 * )
 */
class ServiceBlockInterne extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {


$config = \Drupal::config("servicespage.setting");
    //print_r($sliders);exit;
$query = \Drupal::database()->select('md_services', 'm');
      $query->fields('m', ['id','service_title','service_link_title_1','service_link_title_2','service_link_title_3','service_link_1','service_link_2','service_link_3','service_icon_service','bg_service' ,'bg_service_interne','service_order']);
      //$results = $query->execute()->fetchAssoc();
	  $results = $query->execute()->fetchAll();
    return array(
      '#theme' => 'theme_md_services_interne',

	  '#sliders' => $results,

		'#cache' => [
          'max-age' => 0,
      ],
        '#attached' => array(
                'library' => array(
                    'md_services/md-services-interne'
                )
            )
    );

   }
}
