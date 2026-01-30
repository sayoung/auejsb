<?php
/**
 * @file
 * Contains \Drupal\md_services\Controller\ServiceController.
 */

namespace Drupal\md_services\Controller;

use Drupal\Core\Controller\ControllerBase;

class EventController extends ControllerBase
{
    public function getServices()
    {
		//print_r($cid);exit;
     $this->id = $cid;
	 


//select records from table
    $query = \Drupal::database()->select('md_services', 'm');
      $query->fields('m', ['id','service_title','description','service_link_title_1','service_link_title_2','service_link_title_3','service_link_1','service_link_2','service_link_3','bg_service','service_icon_service','service_order','bg_service_interne']);
      $query->condition('id',$this->id);
      $results = $query->execute()->fetchAssoc();
        
        $nodes = entity_load_multiple('node', $results);

        return array(
          '#theme' => 'theme_md_services_interne',
          '#nodes' => $nodes,
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
