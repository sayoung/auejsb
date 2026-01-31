<?php

namespace Drupal\commerce_cmi\Helper;

use Drupal\notemail\Helper\Helper;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\commerce_order\Entity\Order;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\user\Entity\User;
use Drupal\webform\Entity;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\webform_product\Plugin\WebformHandler;
use Drupal\webform\Entity\Webform;
use Drupal\webform\WebformSubmissionForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\webform\WebformInterface;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform_product\Plugin\WebformHandler\WebformProductWebformHandler;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Datetime;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductVariation;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Site\Settings;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Component\Serialization\Json;

class Aunote {

  use MessengerTrait;

  const SETTINGS = 'notemail.settings';

  /**
   * Create note from product.
   */
  public static function createnote($prod_id_e, $order_id) {
    $items = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties(['field_product_id' => $prod_id_e]);

    $node_note = Node::load(reset($items)->id());
    $new_state = 'checking';
    $node_note->field_n_command = $order_id;
    $node_note->field_cheking = 1;
    $node_note->field_is_payed = 1;
    $node_note->set('moderation_state', $new_state);
    $node_note->save();

    $message = t('An email notification has been sent to and @orfdr et @id_prod', [
      '@orfdr' => $order_id,
      '@id_prod' => $prod_id_e,
    ]);

    \Drupal::messenger()->addStatus($message);
    \Drupal::logger('notemail')->notice($message);

    $product = Product::load($prod_id_e);
    $product->set('field_is_payed', 1);
    $product->save();
  }

  /**
   * Mark dossier as paid.
   */
  public static function dossierpaye($prestation_id) {
    $product = Product::load($prestation_id);
    $product->set('field_is_payed', 1);
    $product->save();
  }

  /**
   * Send document sale email to client.
   */
  public static function ventedocum($vnomcomplet, $vmail, $vtel) {
    $config = \Drupal::config('notemail.settings');
    $mailManager = \Drupal::service('plugin.manager.mail');

    $module = 'commerce_cmi';
    $key = 'node_insert';
    $to = $vmail;
    $params['message'] = "<html lang='fr'><head><title> L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://auejsb.ma/themes/auer/images/auejsb-fr.png' >
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'>Cher client " . $vnomcomplet . " ,</span>
                                                </td>
                                            </tr>
                                            <tr style='height:3.75pt;' height='6'>
                                                <td colspan='3' style='height:3.75pt;background-color:#D8512D;padding:0;'>
                                                    <span style='background-color:#D8512D;'>Si vous recevez cet Email, c'est que vous avez renseigné le formulaire de demande d'achat de documents en ligne.</span>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'>A ce sujet, l'agence urbaine d'El jadida Sidi Bennour vous informe que votre demande a été bien déposée sur sa plateforme  </span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'> Vous recevrez  votre commande  sur votre e-mail et en cas de compléments de votre dossier, un courriel de l'agence vous parviendra à ce sujet.</span>
                                                        </font>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style='height:275.25pt;' height='458'>
                                                <td colspan='3' style='width:459.55pt;height:275.25pt;padding:0 3.5pt;' width='765' valign='top'>
                                                    <div style='margin:0;'>
                                                        <font size='3' face='Helvetica,sans-serif''>
                                                            <span style='font-size:12pt;'>D'autres parts, vous pouvez suivre votre demande sur le site web : <a href='wwww.auejsb.ma'>wwww.auejsb.ma</a> .</span>
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
                                                                        <span style='font-size:8.5pt;'>" . $config->get('footer1') . "</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>" . $config->get('footer2') . "
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
    $params['node_title'] = "Votre demande d'achat de vente de documents en ligne.";
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = TRUE;

    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if (!$result['result']) {
      $message = t('There was a problem sending your email notification to @email for creating node @id.');
      \Drupal::logger('notemail')->error($message);
      return;
    }

    $message = t('An email notification has been sent to @email for creating node @id.');
    \Drupal::logger('notemail')->notice($message);
  }

  /**
   * Send document sale email to back office.
   */
  public static function ventedocumbo($vnomcomplet, $vmail, $vtel) {
    $config = \Drupal::config('notemail.settings');
    $mailManager = \Drupal::service('plugin.manager.mail');
    $bo_mail = $config->get('documentcci');

    $module = 'commerce_cmi';
    $key = 'node_insert';
    $to = $bo_mail;
    $search = ['[client]', '[tel]', '[mail]'];
    $replace = [$vnomcomplet, $vtel, $vmail];

    $params['message'] = "<html lang='fr'><head><title> L'Agence Urbaine d'ElJadida Sidi Bennour </title></head><body><div>
                                    <table style='width:525pt;' cellspacing='0' cellpadding='0' border='0' width='875'>
                                        <tbody>
                                            <tr style='height:30pt;' height='50'>
                                                <td valign='top'>
                                                    <div style='margin:0;'>
                                                        <img src='https://auejsb.ma/themes/auer/images/auejsb-fr.png' >
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
                                                            <span style='font-size:12pt;'> " . str_replace($search, $replace, $config->get('document_mail_bo')['value']) . "</span>
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
                                                                        <span style='font-size:8.5pt;'>" . $config->get('footer1') . "</span>
                                                                    </font>
                                                                    <font size='2' color='#666666' face='Helvetica,sans-serif'>
                                                                        <span style='font-size:8.5pt;'>
                                                                            <br>" . $config->get('footer2') . "
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
    $params['node_title'] = "Achat de documents en ligne.";
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = TRUE;

    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
    if (!$result['result']) {
      $message = t('There was a problem sending your email notification to @email for creating node @id.');
      \Drupal::logger('notemail')->error($message);
      return;
    }

    $message = t('An email notification has been sent to @email for creating node @id.');
    \Drupal::logger('notemail')->notice($message);
  }

  /**
   * Mark online dossier as paid.
   */
  public static function dossierpaye_online($prestation_id, $order_id) {
    $product = Product::load($prestation_id);

    if ($product) {
      $product->set('field_is_payed', 1);
      $product->set('field_n_command', $order_id);
      $product->set('field_etat', ['facturation']);
      $product->save();
    }
  }

}
