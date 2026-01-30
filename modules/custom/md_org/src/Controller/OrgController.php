<?php
/**
 * @file
 * Contains \Drupal\md_org\Controller\OrgController.
 */

namespace Drupal\md_org\Controller;

use Drupal\Core\Controller\ControllerBase;

class OrgController extends ControllerBase
{
    public function getOrg()
    {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'team');
        $query->condition('status', 1);
        $query->sort('field_poristion.value', 'asc');
        $nids = $query->execute();
        $org = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_orgs_page',
          '#nodes' => $org,
          '#cache' => [
                'max-age' => 0,
                 ],
	         '#attached' => array(
           'library' => array(
            'md_org/md-org'
              )
          )
    );
    }
}
