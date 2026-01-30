<?php
namespace Drupal\md_new_prestation\Helper;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Render\Markup;
use Drupal\webform\Entity\WebformSubmission;

require_once '/home/auejsb/public_html/vendor/autoload.php';
//require_once '/var/www/austweb/aust/vendor/autoload.php';


// require_once DRUPAL_ROOT . '/vendor/autoload.php';

use Twilio\Rest\Client;
use Drupal\Core\Url;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\Html;

/**
 * Helper class for notifications (SMS and Email).
 */
class Helper implements ContainerInjectionInterface {
  const SETTINGS = 'eprestation.settings';
  protected $mailManager;
  protected $logger;
  protected $languageManager;

  /**
   * Constructor to inject dependencies.
   */
  public function __construct(MailManagerInterface $mailManager, LoggerChannelFactoryInterface $loggerFactory, LanguageManagerInterface $languageManager) {
    $this->mailManager = $mailManager;
    $this->logger = $loggerFactory->get('md_new_prestation');
    $this->languageManager = $languageManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.mail'),
      $container->get('logger.factory'),
      $container->get('language_manager')
    );
  }

  /**
   * Send an SMS notification using Twilio.
   */
  public static function sendSMS($number) {
    $sid    = "AC0bcd5d9cbbd011e2b507fdc28ae89d49";
    $token  = "3722ec3d0d0cd772b5e8d8cb1c750f11";
    $twilio = new Client($sid, $token);

    try {
      $result = $twilio->messages->create(
        $number,
        [
          "from" => "+12058528207",
          "body" => "L’AUEJ-SB vous informe de la validation de votre demande de paiement RSR sur sa plateforme"
        ]
      );
      return $result;
    } catch (\Twilio\Exceptions\RestException $e) {
      \Drupal::logger('md_new_prestation')->error("SMS failed to send: " . $e->getMessage());
      return;
    }
  }

  /**
   * Send an email notification for payment authorization.
   */
public static function sendMailFirst($email, $commune, $ndeg_dossier, $province, $nom_maitre_ouvrage, $nature_projet, $metrage_du_projet, $architecte) {
    try {
       $config = \Drupal::config('eprestation.settings'); 
      $search  = array('[commun]', '[province]', '[nom]','[nature]','[metrage]','[maitre_ouv]','[n_rokhas]');
	  $replace = array($commune , $province, $nom_maitre_ouvrage,$nature_projet,$metrage_du_projet,$architecte,$ndeg_dossier);
        
        
        $key = 'create_product';
        $subject = "Validation de votre demande de paiement – Autorisations d’urbanisme";
        
        $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';

        // Construct email body
        $params['message'] = Markup::create("
        									<html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'> <title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
										<table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://auejsb.ma/sites/default/files/logo-auto.png' >
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
                                                            <span style='font-size:12pt;'>  
                                                            " . str_replace($search, $replace, $config->get('message_mail')['value']) . " 
																
															</span>
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
                                </div></body></html>");

      $params['node_title'] = "Validation de votre demande de paiement – Autorisations d’urbanisme";
      $langcode ="fr";
      $send = true;
      $ccmail_m = $config->get('emailcci');
      $message['headers']['Cc'] = $ccmail_m;
        $from_send = 'noreply@auejsb.ma';
        $cc_mail = $config->get('emailcci');
	    $headers = "From: ". $from_send . "\r\n" .
	        "Bcc: " . $cc_mail . "\r\n" .
	        "Cc: " . $cc_mail . "\r\n" .
	        "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";
	  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
	  return $result = mail($email, $subject, $params['message'], $headers);
 if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for creating node @id.', array('@email' => $to));
    \Drupal::messenger()->addMessage($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
  }else {
                    \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $to)));
                  }
    } catch (\Exception $e) {
        \Drupal::logger('notemail')->error("Error in sendMailFirst method: " . $e->getMessage());
    }
    
}

public static function sendMailDeclaration($email, $commune, $ndeg_dossier, $province, $nom_maitre_ouvrage, $nature_projet, $metrage_du_projet, $architecte, $product_id) {
    try {
       $config = \Drupal::config('eprestation.settings'); 

      $search  = array('[commun]', '[province]', '[nom]','[nature]','[metrage]','[maitre_ouv]','[n_rokhas]');
	  $replace = array($commune , $province, $nom_maitre_ouvrage,$nature_projet,$metrage_du_projet,$architecte,$ndeg_dossier);
        
        
        $key = 'create_product';
        $subject = "Validation de votre demande de paiement – Autorisations d’urbanisme";
        
        $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';

        // Construct email body
        $params['message'] = Markup::create("
        									<html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'> <title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
										<table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://auejsb.ma/sites/default/files/logo-auto.png' >
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
                                                            <span style='font-size:12pt;'>  
                                                            " . str_replace($search, $replace, $config->get('message_mail_final')['value']) . " 
																
															</span>
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
                                </div></body></html>");

      $params['node_title'] = "Validation de votre demande de paiement – Autorisations d’urbanisme";
      $langcode ="fr";
      $send = true;

      $ccmail_m = $config->get('emailcci');
      $message['headers']['Cc'] = $ccmail_m;
        $from_send = 'noreply@auejsb.ma';
        $cc_mail = $config->get('emailcci');
	    $headers = "From: ". $from_send . "\r\n" .
	        "Bcc: " . $cc_mail . "\r\n" .
	        "Cc: " . $cc_mail . "\r\n" .
	        "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";
	  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
	  return $result = mail($ccmail_m, $subject, $params['message'], $headers);
 if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for creating node @id.', array('@email' => $to));
    \Drupal::messenger()->addMessage($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
  }else {
                    \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $to)));
                  }
    } catch (\Exception $e) {
        \Drupal::logger('notemail')->error("Error in sendMailFirst method: " . $e->getMessage());
    }
    
}

public static function sendMailRejet($email, $commune, $ndeg_dossier, $province, $nom_maitre_ouvrage, $nature_projet, $metrage_du_projet, $architecte, $product_id, $motif) {
    try {
       $config = \Drupal::config('eprestation.settings'); 

      $search  = array('[commun]', '[province]', '[nom]','[nature]','[metrage]','[maitre_ouv]','[n_rokhas]','[motif]');
	  $replace = array($commune , $province, $nom_maitre_ouvrage,$nature_projet,$metrage_du_projet,$architecte,$ndeg_dossier, $motif);
        
        
        $key = 'rejet_product';
        $subject = "État de votre dossier N° : " . $ndeg_dossier;
        
        $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';

        // Construct email body
        $params['message'] = Markup::create("
        									<html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'> <title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
										<table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://auejsb.ma/sites/default/files/logo-auto.png' >
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
                                                            <span style='font-size:12pt;'>  
                                                            " . str_replace($search, $replace, $config->get('message_mail_tech')['value']) . " 
																
															</span>
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
                                </div></body></html>");

      $params['node_title'] = "État de votre dossier : " . $ndeg_dossier;
      $langcode ="fr";
      $send = true;

      $ccmail_m = $config->get('emailcci');
      $message['headers']['Cc'] = $ccmail_m;
        $from_send = 'noreply@auejsb.ma';
        $cc_mail = $config->get('emailcci');
	    $headers = "From: ". $from_send . "\r\n" .
	        "Bcc: " . $cc_mail . "\r\n" .
	        "Cc: " . $cc_mail . "\r\n" .
	        "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";
	  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
	  return $result = mail($email, $subject, $params['message'], $headers);
 if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for creating node @id.', array('@email' => $to));
    \Drupal::messenger()->addMessage($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
  }else {
                    \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $to)));
                  }
    } catch (\Exception $e) {
        \Drupal::logger('notemail')->error("Error in sendMailFirst method: " . $e->getMessage());
    }
    
}

public static function sendMailFacturation($email, $commune, $ndeg_dossier, $province, $nom_maitre_ouvrage, $nature_projet, $metrage_du_projet, $architecte, $product_id) {
    try {
       $config = \Drupal::config('eprestation.settings'); 

      $search  = array('[commun]', '[province]', '[nom]','[nature]','[metrage]','[maitre_ouv]','[n_rokhas]');
	  $replace = array($commune , $province, $nom_maitre_ouvrage,$nature_projet,$metrage_du_projet,$architecte,$ndeg_dossier);
        
        
        $key = 'facturation_product';
        $subject = "Disponibilité de votre facture pour le dossier N° " . $ndeg_dossier;
        
        $logo = 'https://auejsb.ma/themes/auer/images/auejsb-fr.png';

        // Construct email body
        $params['message'] = Markup::create("
        									<html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'> <title>  L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
										<table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://auejsb.ma/sites/default/files/logo-auto.png' >
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
                                                            <span style='font-size:12pt;'>  
                                                            " . str_replace($search, $replace, $config->get('message_mail_Final_client')['value']) . " 
																
															</span>
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
                                </div></body></html>");

      $params['node_title'] = "État de votre dossier : " . $ndeg_dossier;
      $langcode ="fr";
      $send = true;

      $ccmail_m = $config->get('emailcci');
      $message['headers']['Cc'] = $ccmail_m;
        $from_send = 'noreply@auejsb.ma';
        $cc_mail = $config->get('emailcci');
	    $headers = "From: ". $from_send . "\r\n" .
	        "Bcc: " . $cc_mail . "\r\n" .
	        "Cc: " . $cc_mail . "\r\n" .
	        "Content-Type: text/html; charset=UTF-8; format=flowed ". "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";
	  $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
	  return $result = mail($email, $subject, $params['message'], $headers);
 if ( ! $result['result']) {
    $message = t('There was a problem sending your email notification to @email for creating node @id.', array('@email' => $to));
    \Drupal::messenger()->addMessage($message, 'error');
    \Drupal::logger('notemail')->error($message);
    return;
  }else {
                    \Drupal::messenger()->addMessage(t('Your message has been sent to  @email.', array('@email' => $to)));
                  }
    } catch (\Exception $e) {
        \Drupal::logger('notemail')->error("Error in sendMailFirst method: " . $e->getMessage());
    }
    
}


/**
 * Delete a webform submission by matching field_sid with submission ID.
 */
public static function delete_webform_submission_by_field_sid($field_sid) {
  // Load all submissions (you might want to add conditions to limit this)
  $query = \Drupal::entityQuery('webform_submission')
    ->condition('sid', $field_sid)
    ->accessCheck(FALSE);
  
  $sids = $query->execute();

  if (!empty($sids)) {
    foreach ($sids as $sid) {
      $submission = WebformSubmission::load($sid);
      if ($submission) {
        $submission->delete();
        \Drupal::logger('custom_module')->notice('Deleted webform submission with ID: @sid', ['@sid' => $sid]);
      }
    }
    return count($sids); // Return number of deleted submissions
  }
  
  return 0;
}

}
