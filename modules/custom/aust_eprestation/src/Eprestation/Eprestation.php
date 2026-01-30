<?php
namespace Drupal\aust_eprestation\Eprestation;
use Drupal\aust_eprestation\Helper\Helper;
use Drupal\Component\Serialization\Json;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;

Class Eprestation{

  public static function synchroniser(){
      
    $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );
    $json_commisions= file_get_contents(Helper::API_COMMISSION, false, stream_context_create($arrContextOptions));
    $json_communes = file_get_contents(Helper::API_COMMUNE, false, stream_context_create($arrContextOptions));
    $json_prefectures = file_get_contents(Helper::API_PREFECTURE, false, stream_context_create($arrContextOptions));
    $commisions = Json::decode($json_commisions);
    $communes = Json::decode($json_communes);
    $prefectures = Json::decode($json_prefectures);
    if($commisions){$i=0;
      foreach ($commisions as $prestation) {$i++;
        $prestation_id = Helper::checkEPrestation($prestation['IDaust_commission']);
		$id_arr = array_values($prestation_id);
		$id_array = array_shift($id_arr);
		//$nid_array = \Drupal::entityQuery('node')->condition('nid', $nid)->execute();
		$id = $id_array;
        if(!$prestation_id){
            $product = Product::create([
              'uid' => 1,
              'type' => 'e_prestation',
			  'stores' => '3',
              'title' => $prestation['NUM_DOSS'] . " - " .$prestation['PETITIONNAIRE'] . " - " . $prestation['consistance'],
            ]);
            $product->set('field_num_doss', $prestation['NUM_DOSS']);
            if(isset($prestation['COMMISSION'])){
              $commission = $prestation['COMMISSION'];
              if($commission){
                  $commission_id = Helper::getTidByName($commission, Helper::TYPE_VID);
                  if(!$commission_id){
                      $commission_id = Helper::addTerm($commission, Helper::TYPE_VID);
                  }
              }
            }
            
           
            if(isset($prestation['AVIS_PREF'])){
              $prefecture = $prefectures[$prestation['code_prefecture']];
              if($prefecture){
                  $prefecture_id = Helper::getTidByName($prefecture, Helper::PREFECTURE_VID);
                  if(!$prefecture_id){
                      $prefecture_id = Helper::addTerm($prefecture, Helper::PREFECTURE_VID);
                  }
              }
            }
            $product->set('field_commission', ['target_id' => $commission_id, 'target_type' => 'taxonomy_term']);
            $product->set('field_architecte', $prestation['ARCHITECTE']);
            $product->set('field_consistance', $prestation['consistance']);
            $product->set('field_idaust_commission', $prestation['IDaust_commission']);
            if(isset($communes[$prestation['CODE_COM']])){
              $commune = $communes[$prestation['CODE_COM']];
              if($commune){
                  $commune_id = Helper::getTidByName($commune, Helper::PREFECTURE_VID);
                  if(!$commune_id){
                      $commune_id = Helper::addTerm($commune, Helper::PREFECTURE_VID, $prefecture_id);
                  }
              }
            }
            $product->set('field_avis_pref', ['target_id' => $prefecture_id, 'target_type' => 'taxonomy_term']);
            $product->set('field_code_com', ['target_id' => $commune_id, 'target_type' => 'taxonomy_term']);
            $product->set('field_date_com', $prestation['date_com']);
            $product->set('field_avis_aust', $prestation['AVIS_AUst']);
			$product->set('field_avis_pdf', $prestation['AVIS_PDF']);
            $variation = ProductVariation::create([
              'type' => 'e_prestation',
              'sku' => "ref-" . $prestation['IDaust_commission'],
			  'price' => '',
              'status' => 1
            ]);
            $variation->save();
            $product->addVariation($variation);
            $product->save();
            drupal_set_message("E Prestation (" . $product->getTitle() . ") has bein created!\n");
        }
		else{
            $change = false;
            $product = Product::load($id);
            if($product->get('field_num_doss')->getValue() != $prestation['NUM_DOSS']){
              $product->set('field_num_doss', $prestation['NUM_DOSS']);
              $change = true;
            }
            if(isset($prestation['COMMISSION'])){
              $commission = $prestation['COMMISSION'];
              if($commission){
                  $commission_id = Helper::getTidByName($commission, Helper::TYPE_VID);
                  if(!$commission_id){
                      $commission_id = Helper::addTerm($commission, Helper::TYPE_VID);
                  }
              }
            }
            if($product->get('field_commission')->target_id != $commission_id){
              $product->set('field_commission', ['target_id' => $commission_id, 'target_type' => 'taxonomy_term']);
              $change = true;
            }
            if($product->get('field_architecte')->getValue() != $prestation['ARCHITECTE']){
              $product->set('field_architecte', $prestation['ARCHITECTE']);
              $change = true;
            }
            if($product->get('field_consistance')->getValue() != $prestation['consistance']){
              $product->set('field_consistance', $prestation['consistance']);
              $change = true;
            }

          /*  if(isset($communes[$prestation['CODE_COM']])){
              $commune = $communes[$prestation['CODE_COM']];
              if($commune){
                  $commune_id = Helper::getTidByName($commune, Helper::COMMUNE_VID);
                  if(!$commune_id){
                      $commune_id = Helper::addTerm($commune, Helper::COMMUNE_VID);
                  }
              }
            }
            if($product->get('field_code_com')->target_id != $commune_id){
              $product->set('field_code_com', ['target_id' => $commune_id, 'target_type' => 'taxonomy_term']);
              $change = true;
            }*/
            if($product->get('field_date_com')->getValue() != $prestation['date_com']){
              $product->set('field_date_com', $prestation['date_com']);
              $change = true;
            }

            if(isset($prestation['AVIS_PREF'])){
              $prefecture = $prefectures[$prestation['code_prefecture']];
              if($prefecture){
                  $prefecture_id = Helper::getTidByName($prefecture, Helper::PREFECTURE_VID);
                  if(!$prefecture_id){
                      $prefecture_id = Helper::addTerm($prefecture, Helper::PREFECTURE_VID);
                  }
              }
            }
            if($product->get('field_avis_pref')->target_id != $prefecture_id){
              $product->set('field_avis_pref', ['target_id' => $prefecture_id, 'target_type' => 'taxonomy_term']);
              $change = true;
            }

            if($product->get('field_avis_aust')->getValue() != $prestation['AVIS_AUst']){
              $product->set('field_avis_aust', $prestation['AVIS_AUst']);
              $change = true;
            }
			 if($product->get('field_avis_pdf')->getValue() != $prestation['AVIS_PDF']){
              $product->set('field_avis_pdf', $prestation['AVIS_PDF']);
              $change = true;
            }

            if($change && $product->get('title')->getValue()){
              $product->save();
              drupal_set_message("E Prestation (" . $product->getTitle() . ") has bein updated!\n");
            }

        }
      }
    }
  }

}
