<?php
/**
 * @file
 * Contains \Drupal\md_videotheque\Controller\VideoController.
 */

namespace Drupal\md_videotheque\Controller;

use Drupal\Core\Controller\ControllerBase;

class VideoController extends ControllerBase
{
    public function getVideos()
    {
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'video');
        $query->condition('status', 1);
        $query->sort('created', 'desc');
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);
    
        return array(
          '#theme' => 'theme_md_video_page',
          '#nodes' => $nodes
        );
    }
}