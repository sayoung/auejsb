<?php
/**
 * @file
 * Contains \Drupal\aust_eprestation\Controller\MainController.
 */

namespace Drupal\aust_eprestation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Site\Settings;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\user\Entity\User;


class MainController extends ControllerBase
{


    /**
     * récupérer la liste des offres
     * @param $limit
     * @return \Drupal\Core\Entity\EntityInterface[]|static[]
     */
    public function getMailsByRole()
    {
        
$ids1 = \Drupal::entityQuery('user')
->condition('status', 1)
->execute();
$users = User::loadMultiple($ids1);

	return $users;
//kint($users->get('mail')->value);
}
    }

   