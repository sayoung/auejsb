<?php
/**
 * @file
 * Contains \Drupal\md_agenda\Controller\EventController.
 */

namespace Drupal\md_agenda\Controller;

use Drupal\Core\Controller\ControllerBase;

class EventController extends ControllerBase
{
    public function getEvents()
    {   $lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'md_agenda');
        $query->condition('status', 1);
		$query->condition('langcode', $lang_code);
        $query->sort('field_date', 'desc');
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_agenda_page',
          '#nodes' => $nodes,
		  '#cur_lang' => $lang_code,
		  '#attached' => array(
                'library' => array(
                    'md_agenda/md-agenda-interne'
                )
            )
        );
    }
}
