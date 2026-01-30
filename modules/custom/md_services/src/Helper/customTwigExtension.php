<?php

namespace Drupal\md_services\Twig;

use Drupal\block\Entity\Block;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * extend Drupal's Twig_Extension class
 */
class FunctionsTwig extends \Twig_Extension {

  /**
   * {@inheritdoc}
   * Let Drupal know the name of your extension
   * must be unique name, string
   */
  public function getName() {
    return 'fonctions_services_twig';
  }


  /**
   * {@inheritdoc}
   * Return your custom twig function to Drupal
   */
  public function getFunctions() {
        return array(
          new \Twig_SimpleFunction('get_url_by_fid',
                array($this, 'get_url_by_fid'),
                array('is_safe' => array('html')
                ))
                );
  }


  /**
   * {@inheritdoc}
   * Return your custom twig filter to Drupal
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('replace_tokens', [$this, 'replace_tokens']),
    ];
  }


  /**
   * Returns $_GET query parameter
   *
   * @param string $fid
   *   name of the query parameter
   *
   * @return string
   *   value of the query parameter fid
   */
 function get_url_by_fid($fid)
{
$file = \Drupal\file\Entity\File::load($fid);
$path = $file->getFileUri();
$fid_url = $path;
return $fid_url;
}

  /**
   * Replaces available values to entered tokens
   * Also accept HTML text
   *
   * @param string $text
   *   replaceable tokens with/without entered HTML text
   *
   * @return string
   *   replaced token values with/without entered HTML text
   */
  public function replace_tokens($text) {
    return \Drupal::token()->replace($text);
  }

}