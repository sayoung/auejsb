<?php

namespace Drupal\md_services\TwigExtension;

use Twig_Extension;
use Twig_SimpleFilter;
use Drupal\block\Entity\Block;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class TwigWordCountExtension extends \Twig_Extension  {
  /**
   * This is the same name we used on the services.yml file
   */
  public function getName() {
    return 'twig_word_count_extension.twig_extension';
  }
public function getFunctions() {
        return array(
		   new \Twig_SimpleFunction('get_url_by_fid',
                array($this, 'get_url_by_fid'),
                array('is_safe' => array('html')
                ))
        );
    }
  // Basic definition of the filter. You can have multiple filters of course.
  public function getFilters() {
    return [
      new Twig_SimpleFilter('word_count', [$this, 'wordCountFilter']),
    ];
  }
  // The actual implementation of the filter.
  public function wordCountFilter($context) {
    if(is_string($context)) {
      $context = str_word_count($context);
    }
    return $context;
  }
   public function get_url_by_fid($fid)
{
$file = \Drupal\file\Entity\File::load($fid);
$path = $file->getFileUri();
$fid_url = $path;
return $fid_url;
}

}
