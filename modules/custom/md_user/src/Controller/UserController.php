<?php
/**
 * @file
 * Contains \Drupal\md_user\Controller\UserController.
 */

namespace Drupal\md_user\Controller;

use Drupal\Core\Controller\ControllerBase;

class UserController extends ControllerBase
{
    public function getUser()
    {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'team');
        $query->condition('status', 1);
        $query->sort('field_categorie_auer');
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_user_page',
          '#nodes' => $nodes,
          '#cache' => [
                'max-age' => 0,
                 ],
	         '#attached' => array(
           'library' => array(
            'md_user/md-user-hp'
              )
          )
    );
    }
}
