<?php
/**
 * @file
 * Contains \Drupal\md_publication\Controller\PubsController.
 */

namespace Drupal\md_publication\Controller;

use Drupal\Core\Controller\ControllerBase;

class PubsController extends ControllerBase
{
    public function getPubs()
    
    {
        

		$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'publication');
        $query->condition('status', 1);
        $query->condition('field_tag_publi.value', 'publication');
        $query->sort('created', 'desc');
		$query->condition('langcode', $lang_code);
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_publication_page',
          '#nodes' => $nodes,
		  '#cur_lang' => $lang_code,
	      '#attached' => array(
                    'library' => array(
                     'md_publication/md-pub-page'
                            )
                  )
    );
    }
    public function getMagazine()
    {
		$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'publication');
        $query->condition('status', 1);
        $query->condition('field_tag_publi.value', 'magazine');
        $query->sort('created', 'desc');
		$query->condition('langcode', $lang_code);
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_magazine_page',
          '#nodes' => $nodes,
		  '#cur_lang' => $lang_code,
	      '#attached' => array(
                    'library' => array(
                     'md_publication/md-pub-page'
                            )
                  )
    );
    }
    public function getRessources()
    {
		$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'publication');
        $query->condition('field_tag_publi.value', 'ressources');
        $query->condition('status', 1);
        $query->sort('created', 'desc');
		$query->condition('langcode', $lang_code);
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_ressources_page',
          '#nodes' => $nodes,
		  '#cur_lang' => $lang_code,
	      '#attached' => array(
                    'library' => array(
                     'md_publication/md-pub-page'
                            )
                  )
    );
    }
    public function getIndicateurs()
    {
		$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'publication');
        $query->condition('field_tag_publi.value', 'ressources');
        $query->condition('status', 1);
        $query->sort('created', 'desc');
		$query->condition('langcode', $lang_code);
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_indicateurs_page',
          '#nodes' => $nodes,
		  '#cur_lang' => $lang_code,
	      '#attached' => array(
                    'library' => array(
                     'md_publication/md-pub-page'
                            )
                  )
    );
    }
    public function getOldresource()
    {
		$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'publication');
        $query->condition('field_tag_publi.value', 'oldresource');
        $query->condition('status', 1);
        $query->sort('created', 'desc');
		$query->condition('langcode', $lang_code);
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_ressources_page',
          '#nodes' => $nodes,
		  '#cur_lang' => $lang_code,
	      '#attached' => array(
                    'library' => array(
                     'md_publication/md-pub-page'
                            )
                  )
    );
    }
    public function getRapport()
    {
       
    
		$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'publication');
        $query->condition('field_tag_publi.value', 'rapport');
        $query->condition('status', 1);
        $query->sort('created', 'desc');
		$query->condition('langcode', $lang_code);
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_rapport_page',
          '#nodes' => $nodes,
		  '#cur_lang' => $lang_code,
	      '#attached' => array(
                    'library' => array(
                     'md_publication/md-pub-page'
                            )
                  )
    );
    }
    public function getBrochures()
    {
		$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'publication');
        $query->condition('field_tag_publi.value', 'brochures');
        $query->condition('status', 1);
        $query->sort('created', 'desc');
		$query->condition('langcode', $lang_code);
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_brochures_page',
          '#nodes' => $nodes,
		  '#cur_lang' => $lang_code,
	      '#attached' => array(
                    'library' => array(
                     'md_publication/md-pub-page'
                            )
                  )
    );
    }
        public function getChart()
    {
		$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'publication');
        $query->condition('field_tag_publi.value', 'chartes');
        $query->condition('status', 1);
        $query->sort('created', 'desc');
		$query->condition('langcode', $lang_code);
        $nids = $query->execute();
        $nodes = entity_load_multiple('node', $nids);

        return array(
          '#theme' => 'theme_md_chart_page',
          '#nodes' => $nodes,
		  '#cur_lang' => $lang_code,
	      '#attached' => array(
                    'library' => array(
                     'md_publication/md-pub-page'
                            )
                  )
    );
    }
    
}
