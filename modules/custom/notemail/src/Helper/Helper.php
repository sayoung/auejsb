<?php

namespace Drupal\notemail\Helper;
require_once '/home/auejsb/public_html/vendor/autoload.php';
//require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\notemail\Controller\MainController;
use Drupal\node\Entity\Node;
use Drupal\Core\Render\Markup;


Class Helper{
	  const SETTINGS = 'notemail.settings';
	  

	 // const $aa    = "ACf96e409de49bc52705601b443b73c063";
 // const $aaa  = "1563fc16c14b41e47a14c8c4c98213bf";
public static function sendSMS($number, $code){
     try {
$config = \Drupal::config('notemail.settings');
$sid    = "ACf96e409de49bc52705601b443b73c063";
$token  = "1563fc16c14b41e47a14c8c4c98213bf";
$twilio = new Client($sid, $token);
 //   $twilio = new Client(self::SID, self::TOKEN) 120585282077 ;

    $twilio->messages
                ->create($number,
                           array("from" => "+14012082764", "body" =>str_replace("[code]", $code, $config->get('message_phone')))
                  );
  } catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
   // drupal_set_message($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
  }
public static function sendMail($to, $code, $title,$titre_foncier){
     try {
	  $config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $to = $to;
      $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';
      $messageBody = "<html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'> <title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='. $logo .' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'> " . str_replace("[code]", $code, $config->get('message_mail')['value']) . " </span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
    /*  $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  //$to
         //   return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
 // $to = 'dardari.mourad@gmail.com';
	//  $ccmail = 'mourad.dardari@gmail.com,m.dardari@tequality.ma';
	  $ccmail = $config->get('emailcci');
	    $headers = "From: ". $to . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		$subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	  $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      return mail($to, $subject, $params['message'], $headers);
     */ // new solution 
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $subjectNote = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $titre_foncier;
    $params['node_title'] = $subjectNote;

    $ccmail = $config->get('emailcci');
    $ccmail_m = "mourad.dardari@gmail.com";
    $from_send = 'noreply@auejsb.ma';
    $headers = "From: ". $from_send . "\r\n" .
     // "Bcc: " . $ccmail_m . "\r\n" .
      "Cc: " . $ccmail . "\r\n" .
      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";

    $result = mail($to, $subject, $messageBody, $headers);
    if (!$result) {
      $message = t('There was a problem sending your email notification to @email for creating node @id.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('notemail')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $to)));
    }
     } catch (\Exception $e) {
    \Drupal::logger('notemail')->error("Error in sendMail method: " . $e->getMessage());
  }
  }
public static function sendMailEnEchange($to, $code,$nom,$prenom, $title,$titre_foncier,$observations){
     try {
	  $config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'echange_article';
      $to = $to;
      	  $search  = array('[code]', '[nom]', '[prenom]','[titre_f]','[observations]');
	  $replace = array($code , $nom, $prenom,$titre_foncier,$observations);
      $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';
      $messageBody = "<html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'> <title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='. $logo .' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('en_echange_mail')['value']) . " </span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
    /*  $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  //$to
         //   return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
 // $to = 'dardari.mourad@gmail.com';
	//  $ccmail = 'mourad.dardari@gmail.com,m.dardari@tequality.ma';
	  $ccmail = $config->get('emailcci');
	    $headers = "From: ". $to . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		$subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	  $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      return mail($to, $subject, $params['message'], $headers);
     */ // new solution 
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $subjectNote = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $titre_foncier;
    $params['node_title'] = $subjectNote;

    $ccmail = "ahmedazzeddine@auejsb.ma";
    $ccmail_m = "mourad.dardari@gmail.com";
    $from_send = 'contact@auejsb.ma';
    $headers = "From: ". $from_send . "\r\n" .
      "Bcc: " . $ccmail_m . "\r\n" .
      "Cc: " . $ccmail . "\r\n" .
      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";

    $result = mail($to, $subjectNote, $messageBody, $headers);
   // $result = \Drupal::service('plugin.manager.mail')->mail($module, $key, $to, $langcode, $messageBody, 'ahmedazzeddine@auejsb.ma', TRUE);
    if (!$result) {
      $message = t('en echange here was a problem sending your email notification to @email for creating node @id.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('notemail')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('en echange Your message has been sent to  @email.', array('@email' => $to)));
    }
     } catch (\Exception $e) {
    \Drupal::logger('notemail')->error("en echange Error in sendMail method: " . $e->getMessage());
  }
  }


public static function sendMailFinal($to, $code, $title,$note,$titre_foncier){
    try {
	$config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'send_note';
      $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';
      $messageBody = "<html lang='en'><head><title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='. $logo .' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'> 
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                             <span style='font-size:12pt;'> " . str_replace("[code]", $code, $config->get('sendMailFinal')['value']) . " </span>
                                                        </font>
														<font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'>votre e-note : <a href='" . $note . "'>E-note</a></span>
                                                        </font>
														
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
      /*$params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $ccmail = $config->get('emailcci');
	    $headers = "From: ". $to . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		$subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	  $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      return mail($to, $subject, $params['message'], $headers);
    //  return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send); new solution */
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $subjectNote = "Votre demande de la Note de Renseignements relative au terrain objet du TF : " . $titre_foncier;
    $params['node_title'] = $subjectNote;

    $ccmail = $config->get('emailcci');
    $ccmail_m = "mourad.dardari@gmail.com";
    $from_send = 'noreply@auejsb.ma';
    $headers = "From: ". $from_send . "\r\n" .
     // "Bcc: " . $ccmail_m . "\r\n" .
      "Cc: " . $ccmail . "\r\n" .
      "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
      "MIME-Version: 1.0" . "\r\n" .
      "Content-type: text/html; charset=UTF-8" . "\r\n";
    $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";

    $result = mail($to, $subject, $messageBody, $headers);
    if (!$result) {
      $message = t('There was a problem sending your email notification to @email for creating node @id.', array('@email' => $to));
      \Drupal::messenger()->addMessage($message, 'error');
      \Drupal::logger('notemail')->error($message);
      return;
    } else {
      \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $to)));
    }
    
    
    
    } catch (\Exception $e) {
    \Drupal::logger('notemail')->error("Error in sendMail method: " . $e->getMessage());
  }
}
 public static function sendMailFinal_cci($to, $code, $title,$note,$extrait,$reglement,$nom , $prenom){
	$config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';
      $params['message'] = "<html lang='en'><head><title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='. $logo .' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'> Demande de la part : " . $nom  . " " . $prenom . " </span></br>
                                                        </font>
                                                    </div>
													<div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
															<span style='font-size:12pt;'> E-mail : " . $to . " </span>
                                                        </font>
                                                    </div>
													<div style='margin:0;'> 
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                             <span style='font-size:12pt;'> " . str_replace("[code]", $code, $config->get('sendMailFinal')['value']) . " </span>
                                                        </font>
														<font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'>votre e-note : <a href='" . $note . "'>E-note</a></span>
                                                        </font>
														<font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'>votre Extrait : <a href='" . $extrait . "'>Extrait</a></span>
                                                        </font>
														<font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'>votre Régelement :  <a href='" . $reglement . "'>Régelement</a></span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
      $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $controller = new MainController();
	  $too = array(); 
	  foreach($controller->getMailsByRoleTechVer() as $user){
	    $too[] = $user->get('mail')->value;
	}
foreach($too as $email){                               
									$mailManager->mail($module, $key, $email, $langcode, $params, NULL, $send);
                                }
  }
  
public static function sendSMSFinal($number, $code){
    $config = \Drupal::config('notemail.settings'); 
$sid    = "ACf96e409de49bc52705601b443b73c063";
$token  = "1563fc16c14b41e47a14c8c4c98213bf";
$twilio = new Client($sid, $token);
try {
    $result =  $twilio->messages
                ->create($number,
                           array("from" => "+14012082764", "body" => str_replace("[code]", $code, $config->get('message_phone_final')))
                  );
                  return $result;
  } catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
 //   drupal_set_message($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
  }
public static function sendSMSAnnulerpaiement($number, $code){
 $config = \Drupal::config('notemail.settings');    
$sid    = "ACf96e409de49bc52705601b443b73c063";
$token  = "1563fc16c14b41e47a14c8c4c98213bf";
$twilio = new Client($sid, $token);
try {
    $result =  $twilio->messages
                ->create($number,
                           array("from" => "+14012082764", "body" => str_replace("[code]", $code, $config->get('sendSMSAnnulerpaiement')))
                  );
                  return $result;
  } catch (\Twilio\Exceptions\RestException $e) {
            $message = t('There was a problem sending your sms notification.');
   // drupal_set_message($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
     }
  }
public static function sendMailAnnulerpaiement($to, $code, $title){
	  $config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $key = 'create_article';
      $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';
      $params['message'] = "<html lang='en'><head><title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='. $logo .' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
														<span style='font-size:12pt;'> " . $config->get('sendMailAnnulerpaiement')['value'] . " </span>
                                                           
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
      $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $ccmail = $config->get('emailcci');
	    $headers = "From: ". $to . "\r\n" .
            "Bcc: " . $ccmail . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";

      //  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
		$subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
	  $subject = "=?UTF-8?B?" . base64_encode($config->get('subject')) . "?=";
      return mail($to, $subject, $params['message'], $headers);
      //return $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  }
  
public static function sendMailAnnulerpaiement_cci($to, $code, $title,$nom , $prenom){
	  $config = \Drupal::config('notemail.settings');
      $mailManager = \Drupal::service('plugin.manager.mail');
      $module = 'notemail';
      $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';
      $key = 'create_article';
      $params['message'] = "<html lang='en'><head><title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='" . $logo . "' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'></span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'> Demande de la part : " . $nom  . " " . $prenom . " </span></br>
                                                        </font>
                                                    </div>
													<div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
															<span style='font-size:12pt;'> E-mail : " . $to . " </span>
                                                        </font>
                                                    </div>
													<div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
														<span style='font-size:12pt;'> " . $config->get('sendMailAnnulerpaiement')['value'] . " </span>
                                                           
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:32.8pt;' height='54'>
                                                <td colspan='3' style='width:459.55pt;height:32.8pt;background-color:#F3F3F3;padding:0 3.5pt;' width='765' valign='top'>
                                                    <span style='background-color:#F3F3F3;'>
                                                        <div style='text-align:center;margin-right:0;margin-left:0;' align='center'>
                                                            <font size='3' face='Times New Roman,serif'>
                                                                <span style='font-size:12pt;'>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>".$config->get('footer1')."</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>".$config->get('footer2')."
                                                                        </span>
                                                                    </font>
                                                                </span>
                                                            </font>
                                                        </div>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div></body></html>";
      $params['node_title'] = $title;
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
	  $controller = new MainController();
	  	  $too = array(); 
	  foreach($controller->getMailsByRoleTechVer() as $user){
	    $too[] = $user->get('mail')->value;
	}
foreach($too as $email){                               
									$mailManager->mail($module, $key, $email, $langcode, $params, NULL, $send);
                                }
  }
 
}
