<?php
/**
 * @file
 * Contains \Drupal\md_appel_offre\Controller\AppelOffreController.
 */

namespace Drupal\md_appel_offre\Controller;

use Drupal\Core\Controller\ControllerBase;

class AppelOffreController extends ControllerBase
{
    public function getOffres()
    {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'appel_d_offre');
        $query->condition('status', 1);
        $query->sort('created', 'desc');
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_appel_offre_page',
          '#nodes' => $nodes
        );
    }
}
