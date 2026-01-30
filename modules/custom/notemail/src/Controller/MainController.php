<?php
/**
 * @file
 * Contains \Drupal\notemail\Controller\MainController.
 */

namespace Drupal\notemail\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Site\Settings;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\user\Entity\User;
use Drupal\Core\CronInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Drupal\Core\Entity\RevisionLogInterface;

class MainController extends ControllerBase {
    /**
     * récupérer la liste des offres
     * @param $limit
     * @return \Drupal\Core\Entity\EntityInterface[]|static[]
     */
  public function getMailsByRoleAdmin() {
            
    $ids1 = \Drupal::entityQuery('user')
    ->condition('status', 1)
    ->condition('roles', 'administrator')
    ->execute();
    $users = User::loadMultiple($ids1);
    
    	return $users;
    //kint($users->get('mail')->value);
 }
    public function getMailsByRoleTechVer() {
        
        $query = \Drupal::entityQuery('user')
        ->condition('status', 1)
        ->condition('roles', 'technicien')
        ->execute();
        //$group = $query->orConditionGroup()
        //  ->condition('roles', 'administrator')
         // ->condition('roles', 'technicien');
        //$tids = $query->condition($group)->execute();
        $users = User::loadMultiple($query);
        
        	return $users;
        //kint($users->get('mail')->value);
  }
  

  
  
  
    }

   