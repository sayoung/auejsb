<?php

namespace Drupal\md_services\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Drupal\md_services\Form;

/**
 * Provides a 'ServiceBlock' block.
 *
 * @Block(
 *  id = "md_services_block",
 *  admin_label = @Translation("Service Block"),
 * )
 */
class ServiceBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {




//select records from table
    $query = \Drupal::database()->select('md_services', 'm');
      $query->fields('m', ['id','service_title','description','service_link_title_1','service_link_title_2','service_link_title_3','service_link_1','service_link_2','service_link_3','service_icon_service','bg_service','service_order' ,'bg_service_interne']);
      //$results = $query->execute()->fetchAssoc();
     // $query->sort('service_order', 'desc');
      $query->orderBy('service_order', 'asc');
	  $results = $query->execute()->fetchAll();
	  
 //  	print_r($results); 
// print_r("toppppp");die;     
     //   $nodes = entity_load_multiple('node', $results);

    return array(
      '#theme' => 'theme_md_services',
	  '#sliders' => $results,
		'#cache' => [
          'max-age' => 0,
      ],
        '#attached' => array(
                'library' => array(
                    'md_services/md-services-hp'
                )
            )
    );

   }
}
