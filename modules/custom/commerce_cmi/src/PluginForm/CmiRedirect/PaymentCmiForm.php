<?php

namespace Drupal\commerce_cmi\PluginForm\CmiRedirect;

use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\user\Entity\User;

use Drupal\webform\Entity;
use Drupal\webform\Entity\WebformSubmission;

use Drupal\webform_product\Plugin\WebformHandler;
use Drupal\commerce_product\Entity\ProductInterface;

use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_price\Price;

/**
 * Implements PaymentCmiForm class.
 *
 * - this class used for build to payment form.
 */
class PaymentCmiForm extends BasePaymentOffsiteForm {

    // const CMI_API_URL = 'https://testpayment.cmi.co.ma/fim/est3Dgate';

    public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildConfigurationForm($form, $form_state);
        $payment = $this->entity;
        $redirect_method = 'post';

        /** @var \Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayInterface $payment_gateway_plugin */
        $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();
        $orgClientId  = trim($payment_gateway_plugin->getConfiguration()['merchant_id']);
        $redirect_url  = trim($payment_gateway_plugin->getConfiguration()['actionslk']);
        $SLKSecretkey  = trim($payment_gateway_plugin->getConfiguration()['SLKSecretkey']);

        $current_user = \Drupal::currentUser();
        $user = \Drupal\user\Entity\User::load($current_user->id());
        $email = $user->get('mail')->value;

        //echo "<pre>";print_r($payment_gateway_plugin->getConfiguration());echo "</pre>";die;

        $data = [];


        $order        = $payment->getOrder();
        $total_price  = $order->getTotalPrice();
		$sx = $order->getOrderNumber();

        $symbolCur  = $total_price->getCurrencyCode();
		$lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
        $amount = $payment->getAmount()->getNumber();
        // $orgOkUrl =  Url::fromRoute('commerce_cmi.ok', [], ['absolute' => TRUE])->toString();
        // $orgFailUrl = Url::fromRoute('commerce_cmi.fail', [], ['absolute' => TRUE])->toString();
        // $shopurl = Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString();
        $orgOkUrl = $form['#return_url'];
        $orgFailUrl = $form['#cancel_url'];
        $shopurl = $form['#cancel_url'];
        $orgTransactionType = "PreAuth";
        $orgRnd =  microtime();
        $orgCallbackUrl = Url::fromRoute('commerce_cmi.callback', [], ['absolute' => TRUE])->toString();
        $order_id = \Drupal::routeMatch()->getParameter('commerce_order')->id();
        $entityManager = \Drupal::entityTypeManager();
		$store_idd = $order->getStoreId();
		$order_item = \Drupal\commerce_order\Entity\OrderItem::load($order->order_items->target_id);
		//kint($order_item->getPurchasedEntityId());kint($order->order_items->target_id);die;
					$id2 = $order_item->getPurchasedEntityId(); 
				$ProductV = ProductVariation::load($id2);
			//$ProductV->set('field_is_payed' , 1);
			//$product->save();
		
			$id22 = $ProductV->getProductId();					//$string = $string->toString();
				//	kint($id22);die;

		$identity = $order_item->getPurchasedEntityId();
		$some_data = null;
		$articleprestationid = null;
		
		if( $store_idd == 2) {
			       
			// $some_data = $_SESSION['commerce_webform_order']['siids'];
				$articleprestationid = $id22;
				$message_4 = $articleprestationid;
         \Drupal::logger('mourad_4')->notice($message_4);
			
			
		}
	//	kint($some_data);die;
		
	
		if( $store_idd == 1) {
			
			$articleprestationid = $id22;
		
		}
			if( $store_idd == 4) {
			
			$articleprestationid = $id22;
		
		}
        $order = $entityManager->getStorage('commerce_order')->load($order_id);
 //$pp = '83';
// $webform_submission->setSticky(!$webform_submission->isSticky())->save();
// $sid = $webform_submission;




//$order_item = $order->getItems();
 //$product_variation = $order_item->getPurchasedEntity();
//$title = $product_variation->getTitle();
//echo "<pre>";print_r($store_idd);echo "</pre>";die;
        $billing = $order->billing_profile->entity->address->getValue();
		//$webform_submission = \Drupal\webform\Entity\WebformSubmission::load($pp);
			//	$tempstore = \Drupal::service('user.private_tempstore')->get('commerce_webform_order');
		//$some_data = $tempstore->get('siids');
		
		//$tempdata = $_SESSION['mymodule']['variablename'];
		//kint(\Drupal\webform\Entity\WebformSubmission::load($order_id));
		//$some_data = $temp_store->get('ssids');
		
		//kint($some_data);
//	die;
				
		$tell = $order->billing_profile->entity->field_tel->value;
        $BillToName = trim(html_entity_decode(html_entity_decode($billing[0]["family_name"].' '.$billing[0]["given_name"])));
        $BillToStreet1 = trim(html_entity_decode(html_entity_decode($billing[0]["address_line1"].' '.$billing[0]["address_line2"])));
        $BillToCity = trim(html_entity_decode(html_entity_decode($billing[0]["locality"])));
        $BillToCountry = trim(html_entity_decode(html_entity_decode($billing[0]["country_code"])));
        $BillToPostalCode = trim(html_entity_decode(html_entity_decode($billing[0]["postal_code"])));
        $BillToStateProv = trim(html_entity_decode(html_entity_decode($billing[0]["administrative_area"])));
        $BillToCompany = trim(html_entity_decode(html_entity_decode($billing[0]["organization"])));
		switch ($lang) {
			case "en":
			case "fr":
			case "ar":
				 $data['lang'] = $lang;
			break;
			default:
				 $data['lang'] = "en";
		}
		// echo "<pre>";
		// var_dump($order->getTotalPrice()); 
		// echo "</pre>";exit;
		//kint($some_data);
		//die;


        $data['clientid'] = $orgClientId;
        $data['amount'] = $amount;
        $data['okUrl'] = $orgOkUrl;
        $data['failUrl'] = $orgFailUrl;
        $data['TranType'] = $orgTransactionType;


        $data['callbackUrl'] = $orgCallbackUrl;
        $data['shopurl'] = $shopurl;
        $data['currency'] ="504";
        $data['rnd'] = $orgRnd;
        $data['storetype'] ="3D_PAY_HOSTING";
        $data['hashAlgorithm'] ="ver3";
        $data['refreshtime'] ="5";
        $data['BillToName'] = $BillToName;
        $data['BillToCompany'] = $BillToCompany;
        $data['BillToStreet1'] = $BillToStreet1;
        $data['BillToCity'] = $BillToCity;
        $data['BillToStateProv'] = $BillToStateProv;
        $data['BillToPostalCode'] = $BillToPostalCode;
        $data['BillToCountry'] = $BillToCountry;
        // $data['email'] = $email;
        $data['email'] = $order->getEmail();
		$data['tel'] = $tell;
		//if(isset($some_data)){ $data['nodeid'] = $some_data; }
		//if(isset($articleprestationid)){ $data['prestationid'] = $articleprestationid; }
		
		
		
		$data['prod_id_e'] = $articleprestationid;

		$data['nodeid'] = $some_data;
		$data['prestationid'] = $articleprestationid;
		

        $data['encoding'] ="UTF-8";
        $data['oid'] = $order_id;
        // $data['symbolCur'] = $symbolCur;
        //$data['amountCur'] ="5";


        $storeKey = $SLKSecretkey;


        $postParams = array();
        foreach ($data as $key => $value){
            array_push($postParams, $key);
        }

        natcasesort($postParams);

        $hashval = "";
        foreach ($postParams as $param){
            $paramValue = trim($data[$param]);
            $escapedParamValue = str_replace("|", "\\|", str_replace("\\", "\\\\", $paramValue));

            $lowerParam = strtolower($param);
            if($lowerParam != "hash" && $lowerParam != "encoding" )	{
                $hashval = $hashval . $escapedParamValue . "|";
            }
        }


        $escapedStoreKey = str_replace("|", "\\|", str_replace("\\", "\\\\", $storeKey));
        $hashval = $hashval . $escapedStoreKey;

        $calculatedHashValue = hash('sha512', $hashval);
        $hash = base64_encode (pack('H*',$calculatedHashValue));
        $data['HASH'] = $hash;


        return $this->buildRedirectForm($form, $form_state, $redirect_url, $data, $redirect_method);

    }



}