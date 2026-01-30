<?php

namespace Drupal\md_services\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\md_services\Controller
 */
class DisplayTableController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'md_services_description',
        '#description' => 'foo',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    /**return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: display with parameter(s): $name'),
    ];*/

    //create table header
    $header_table = array(
      'id' => t('ID'),
      'service_title' => t('service_title'),
      'description' => t('description'),
	  'service_link_title_1' => t('service_link_title_1'),
	  'service_link_title_2' => t('service_link_title_2'),
	  'service_link_title_3' => t('service_link_title_3'),
	  'service_link_1' => t('service_link_1'),
	  'service_link_2' => t('service_link_2'),
	  'service_link_3' => t('service_link_3'),
	  'service_order' => t('service_order'),
	  'bg_service' => t('bg_service'),
	  'bg_service_interne' => t('bg_service_interne'),
	  'service_icon_service' => t('service_icon_service'),
    );

//select records from table
    $query = \Drupal::database()->select('md_services', 'm');
      $query->fields('m', ['id','service_title','description','service_link_title_1','service_link_title_2','service_link_title_3','service_link_1','service_link_2','service_link_3','bg_service','service_icon_service','service_order','bg_service_interne']);
      $query->orderBy('id', 'desc');
      $results = $query->execute()->fetchAll();
        $rows=array();
    foreach($results as $data){
        $delete = Url::fromUserInput('/au_backend/md_services/form/delete/'.$data->id);
        $edit   = Url::fromUserInput('/au_backend/md_services/form/update/'.$data->id);

      //print the data from table
             $rows[] = array(
                'id' =>$data->id,
                'service_title' => $data->service_title,
                'description' => $data->description,
				'service_link_title_1' => $data->service_link_title_1,
				'service_link_title_2' => $data->service_link_title_2,
				'service_link_title_3' => $data->service_link_title_3,
				'service_link_1' => $data->service_link_1,
				'service_link_2' => $data->service_link_2,
				'service_link_3' => $data->service_link_3,
				'service_order' => $data->service_order,
				'bg_service' => $data->bg_service,
				'service_icon_service' => $data->service_icon_service,
				'bg_service_interne'  => $data->bg_service_interne,

                 \Drupal::l('Delete', $delete),
                 \Drupal::l('Edit', $edit),
            );

    }
    //display data in site
    $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No emails found'),
        ];
//        echo '<pre>';print_r($form['table']);exit;
        return $form;

  }

}
