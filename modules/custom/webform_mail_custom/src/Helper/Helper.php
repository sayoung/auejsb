<?php

namespace Drupal\webform_mail_custom\Helper;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;


Class Helper{

  const SETTINGS = 'webform_mail.settings';
  
    /**
  * la liste des fonction event
  *
  */
  
  public static function list_event_france(){
    $config = \Drupal::config('webform_mail.settings');
    $event_france = $config->get('list_mail_event_france');
	 return $event_france;
  }
    public static function list_event_usa(){
    $config = \Drupal::config('webform_mail.settings');
    $event_usa = $config->get('list_mail_event_usa');
	 return $event_usa;
  }
    public static function list_event_unitedkingdom(){
    $config = \Drupal::config('webform_mail.settings');
    $event_unitedkingdom = $config->get('list_mail_event_unitedkingdom');
	 return $event_unitedkingdom;
  }
    public static function list_event_belgique(){
    $config = \Drupal::config('webform_mail.settings');
    $event_belgique = $config->get('list_mail_event_belgique');
	 return $event_belgique;
  }
    public static function list_event_nederland(){
    $config = \Drupal::config('webform_mail.settings');
    $event_nederland = $config->get('list_mail_event_nederland');
	 return $event_nederland;
  }
    public static function list_event_espana(){
    $config = \Drupal::config('webform_mail.settings');
    $event_espana = $config->get('list_mail_event_espana');
	 return $event_espana;
  }
    public static function list_event_canada(){
    $config = \Drupal::config('webform_mail.settings');
    $event_canada = $config->get('list_mail_event_canada');
	 return $event_canada;
  }
    public static function list_event_maroc(){
    $config = \Drupal::config('webform_mail.settings');
    $event_maroc = $config->get('list_mail_event_maroc');
	 return $event_maroc;
  }
    public static function list_event_italia(){
    $config = \Drupal::config('webform_mail.settings');
    $event_italia = $config->get('list_mail_event_italia');
	 return $event_italia;
  }
    public static function list_event_polska(){
    $config = \Drupal::config('webform_mail.settings');
    $event_polska = $config->get('list_mail_event_polska');
	 return $event_polska;
  }
  
    /**
  * la liste des fonction contact
  *
  */
  
   public static function list_contact_france(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_france = $config->get('list_mail_contact_france');
	 return $contact_france;
  }
    public static function list_contact_usa(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_usa = $config->get('list_mail_contact_usa');
	 return $contact_usa;
  }
    public static function list_contact_unitedkingdom(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_unitedkingdom = $config->get('list_mail_contact_unitedkingdom');
	 return $contact_unitedkingdom;
  }
    public static function list_contact_belgique(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_belgique = $config->get('list_mail_contact_belgique');
	 return $contact_belgique;
  }
    public static function list_contact_nederland(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_nederland = $config->get('list_mail_contact_nederland');
	 return $contact_nederland;
  }
    public static function list_contact_espana(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_espana = $config->get('list_mail_contact_espana');
	 return $contact_espana;
  }
    public static function list_contact_canada(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_canada = $config->get('list_mail_contact_canada');
	 return $contact_canada;
  }
    public static function list_contact_maroc(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_maroc = $config->get('list_mail_contact_maroc');
	 return $contact_maroc;
  }
    public static function list_contact_italia(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_italia = $config->get('list_mail_contact_italia');
	 return $contact_italia;
  }
    public static function list_contact_polska(){
    $config = \Drupal::config('webform_mail.settings');
    $contact_polska = $config->get('list_mail_contact_polska');
	 return $contact_polska;
  }
  
  /**
  * la liste des fonction condidature 
  *
  */
  
   public static function list_candidature_france(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_france = $config->get('list_mail_candidature_france');
	 return $candidature_france;
  }
    public static function list_candidature_usa(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_usa = $config->get('list_mail_candidature_usa');
	 return $candidature_usa;
  }
    public static function list_candidature_unitedkingdom(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_unitedkingdom = $config->get('list_mail_candidature_unitedkingdom');
	 return $candidature_unitedkingdom;
  }
    public static function list_candidature_belgique(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_belgique = $config->get('list_mail_candidature_belgique');
	 return $candidature_belgique;
  }
    public static function list_candidature_nederland(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_nederland = $config->get('list_mail_candidature_nederland');
	 return $candidature_nederland;
  }
    public static function list_candidature_espana(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_espana = $config->get('list_mail_candidature_espana');
	 return $candidature_espana;
  }
    public static function list_candidature_canada(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_canada = $config->get('list_mail_candidature_canada');
	 return $candidature_canada;
  }
    public static function list_candidature_maroc(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_maroc = $config->get('list_mail_candidature_maroc');
	 return $candidature_maroc;
  }
    public static function list_candidature_italia(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_italia = $config->get('list_mail_candidature_italia');
	 return $candidature_italia;
  }
    public static function list_candidature_polska(){
    $config = \Drupal::config('webform_mail.settings');
    $candidature_polska = $config->get('list_mail_candidature_polska');
	 return $candidature_polska;
  }
 

}
