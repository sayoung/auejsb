<?php
namespace Drupal\aust_eprestation\Helper;


//require_once '/var/www/austweb/aust/vendor/autoload.php';
use Twilio\Rest\Client;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;

use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\Node;
use Drupal\commerce_product\Entity\Product;

Class Helper{

  const SETTINGS = 'eprestation.settings';

  const API_COMMISSION  = "http://api.tequality.ma/commission";
  const API_COMMUNE = "http://api.tequality.ma/commune";
  const API_PREFECTURE = "http://api.tequality.ma/prefecture";

  const TYPE_VID = "type_commission";
  const COMMUNE_VID = "communes";
  const PREFECTURE_VID = "prefecture";
  const API_KEY = "mlPkDBLlKOyk5tVZM311isBXUX0dFP0QNxUWzu1jbWxW02r";



  public static function getTidByName($name, $vid) {
    $properties = [];
    if (!empty($name)) {
      $properties['name'] = $name;
    }
    if (!empty($vid)) {
      $properties['vid'] = $vid;
    }
    $terms = \Drupal::entityManager()->getStorage('taxonomy_term')->loadByProperties($properties);
    $term = reset($terms);

    return !empty($term) ? $term->id() : 0;
  }

  public static function addTerm($name, $vid, $parent = 0){
    $param = [
        'vid' => $vid,
        'name' => $name,
        'parent' => array(['target_id' => $parent])
    ];
    $term = Term::create($param);
    $term->enforceIsNew();
    $term->save();
    return $term->id();
  }

  public static function checkEPrestation($ePrestationID){
    $query = \Drupal::entityQuery('commerce_product')
    ->condition('type', 'e_prestation')
    ->condition('field_idaust_commission', $ePrestationID);
    return $query->execute();
  }
    public static function checkEPrestationFront($ePrestationCm, $ePrestationNmDs, $ePrestationType){
    $query = \Drupal::entityQuery('commerce_product')
    ->condition('type', 'e_prestation')
    ->condition('field_code_com', $ePrestationCm)
	->condition('field_num_doss', $ePrestationNmDs)
	->condition('field_commission', $ePrestationType);
    return $query->execute();
  }

  public static function setCalculEPrestation($ePrestationCm, $ePrestationNmDs, $ePrestationType){
    $pids = self::checkEPrestationFront($ePrestationCm, $ePrestationNmDs, $ePrestationType);

    foreach ($pids as $pid) {
      $product = Product::load($pid);
      $product->set('field_demande_de_calcul' , 1);
      $product->save();
    }
  }

  public static function deleteAll(){
    $pids = \Drupal::entityQuery('commerce_product')
      ->condition('type', 'e_prestation')
	  ->range(0, 500)
      ->execute();


    foreach ($pids as $pid) {
      $product = Product::load($pid);
      $product->delete();
    }
    drupal_set_message("All E-Prestation has bein deleted!\n");
  }

  public static function sendSMS($number, $code){

    $config = \Drupal::config('eprestation.settings');
$sid    = "AC0bcd5d9cbbd011e2b507fdc28ae89d49";
$token  = "3722ec3d0d0cd772b5e8d8cb1c750f11";
$twilio = new Client($sid, $token);
    $result =  $twilio->messages
                ->create($number,
				//array("from" => "+12058528207", "body" => "helllo ")
                  array("from" => "+12058528207", "body" => str_replace("[code]", $code[0]['value'], $config->get('message_phone')))
                  );
                  return $result;
  }
  public static function sendSMSFinal($number, $code){
     $config = \Drupal::config('eprestation.settings');
$sid    = "AC0bcd5d9cbbd011e2b507fdc28ae89d49";
$token  = "3722ec3d0d0cd772b5e8d8cb1c750f11";
$twilio = new Client($sid, $token);
    $result =  $twilio->messages
                ->create($number,
				//array("from" => "+12058528207", "body" => "helllo ")
                 array("from" => "+12058528207", "body" => str_replace("[code]", $code[0]['value'], $config->get('message_phone_final')))
                  );
                  return $result;
  }
 


}
