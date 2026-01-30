<?php
/**
 * @file
 * Contains \Drupal\md_actualite\Controller\ActualiteController.
 */


namespace Drupal\md_actualite\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Pager\PagerManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ActualiteController extends ControllerBase
{
    public function getActualites()
    {
        $query = \Drupal::entityQuery('node')
            ->condition('type', 'actualites')
            ->condition('status', 1)
            ->sort('created', 'DESC')
            ->pager(10); // Limits to 5 items per page

        // Execute paginated query
        $nids = $query->execute();

        // Load only the paginated nodes
        $nodes = Node::loadMultiple($nids);

        return [
            '#theme' => 'theme_md_actualites_page',
            '#nodes' => $nodes,
            '#pager' => [
                '#type' => 'pager',
            ],
            '#attached' => [
                'library' => [
                    'md_actualite/md-actualites-hp'
                ]
            ],
        ];
    }
}

