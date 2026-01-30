<?php

namespace Drupal\md_count\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\md_count\Controller
 */
class DisplayTableController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'md_count_description',
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
      'service' => t('service'),
	  'value' => t('value'),
    );

//select records from table
    $query = \Drupal::database()->select('md_count', 'm');
      $query->fields('m', ['id','service','value']);
      $query->orderBy('id', 'desc');
      $results = $query->execute()->fetchAll();
        $rows=array();
    foreach($results as $data){
        $delete = Url::fromUserInput('/au_backend/count/form/delete/'.$data->id);
        $edit   = Url::fromUserInput('/au_backend/count/form/update/'.$data->id);

      //print the data from table
             $rows[] = array(
                'id' =>$data->id,
                'service' => $data->service,
				'value' => $data->value,

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
