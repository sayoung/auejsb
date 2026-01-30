<?php

namespace Drupal\private_access\EventSubscriber;

use Drupal\search_api\Event\QueryPreExecuteEvent;
use Drupal\search_api\Event\SearchApiEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Excludes private_article from search results.
 */
class SearchApiExcludeSubscriber implements EventSubscriberInterface {

  /**
   * Alters the Search API query to exclude private content.
   *
   * @param \Drupal\search_api\Event\QueryPreExecuteEvent $event
   *   The Search API query event.
   */
  public function queryAlter(QueryPreExecuteEvent $event) {
    $query = $event->getQuery();

    // Ajout de condition pour exclure le type 'private_article'.
    $query->addCondition('type', 'note', '<>');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    if (!class_exists('\Drupal\search_api\Event\SearchApiEvents', TRUE)) {
      return [];
    }

    return [
      SearchApiEvents::QUERY_PRE_EXECUTE => 'queryAlter',
    ];
  }

}
