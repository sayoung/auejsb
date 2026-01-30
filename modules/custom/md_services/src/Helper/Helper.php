<?php

namespace Drupal\md_services\Helper;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;


Class Helper{

  const SETTINGS = 'md_services.settings';
  
    /**
  * la liste des fonction event
  *
  */
    public static function title_1(){
    $config = \Drupal::config('md_services.settings');
    $title_1 = $config->get('title_1');
	 return $title_1;
  }

    public static function link_1(){
    $config = \Drupal::config('md_services.settings');
    $link_1 = $config->get('link_1');
	 return $link_1;
  }
  
}
