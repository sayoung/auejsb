<?php
/**
 * @file
 * Contains \Drupal\md_count\Controller\ServiceController.
 */

namespace Drupal\md_count\Controller;

use Drupal\Core\Controller\ControllerBase;

class EventController extends ControllerBase
{
    public function getServices()
    {
		//print_r($cid);exit;
     $this->id = $cid;
	 


//select records from table
    $query = \Drupal::database()->select('md_count', 'm');
      $query->fields('m', ['id','service','value']);
      $query->condition('id',$this->id);
      $results = $query->execute()->fetchAssoc();
        
        $nodes = entity_load_multiple('node', $results);

        return array(
          '#theme' => 'theme_md_count_interne',
          '#nodes' => $nodes,
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
