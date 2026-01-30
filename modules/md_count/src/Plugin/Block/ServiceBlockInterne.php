<?php

namespace Drupal\md_count\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Drupal\md_services\Form;

/**
 * Provides a 'ServiceBlockInterne' block.
 *
 * @Block(
 *  id = "md_count_block_intern",
 *  admin_label = @Translation("count Block interne"),
 * )
 */
class ServiceBlockInterne extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {




//select records from table
    $query = \Drupal::database()->select('md_count', 'm');
      $query->fields('m', ['id','service','value']);
      //$results = $query->execute()->fetchAssoc();
	  $results = $query->execute()->fetchAll();
	  
 //  	print_r($results); 
// print_r("toppppp");die;     
     //   $nodes = entity_load_multiple('node', $results);

    return array(
      '#theme' => 'theme_md_count_interne',
	  '#count' => $results,
		'#cache' => [
          'max-age' => 0,
      ],
        '#attached' => array(
                'library' => array(
                    'md_count/md-count-hp'
                )
            )
    );

   }
}
