<?php
namespace Drupal\md_newsletter\Newsletter;

use Drupal\md_newsletter\Helper\Helper;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

Class Newsletter{

  public function openModalForm() {
    $response = new AjaxResponse();
    $modal_form = \Drupal::formBuilder()->getForm('Drupal\md_newsletter\Form\SendForm');
    $response->addCommand(new OpenModalDialogCommand(t('Send Newsletter'), $modal_form, ['width' => '1200']));
    return $response;
  }

  public static function newsLetterHTML(){
    $config = \Drupal::config(Helper::SETTINGS);
    $host = \Drupal::request()->getSchemeAndHttpHost();
    $news = \Drupal\node\Entity\Node::loadMultiple($config->get('news'));
    $rendered = "";
    $rendered.= " <center>
<table width=\"751\" height=\"160\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
  
    <td><img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/infolettre.png\" width=\"800\" height=\"104\" alt=\"\"></td>
  </tr>
</table></center>";
    if($news){
      foreach ($news as $new) {
        $file = $new->field_service_images->entity;
        $img = \Drupal::request()->getSchemeAndHttpHost() . file_url_transform_relative(file_create_url($file->getFileUri()));
        $alias = \Drupal::service('path.alias_manager')->getAliasByPath('/node/' . $new->id());
        $rendered.= "
        
        <table width=\"751\" height=\"160\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
    <td colspan=\"6\">
      <img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/border-top.jpg\" width=\"751\" height=\"7\" alt=\"\"></td>
  </tr>
  <tr>
    <td rowspan=\"4\">
      <img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/border-left.jpg\" width=\"7\" height=\"146\" alt=\"\"></td>
    <td width=\"131\" height=\"146\" rowspan=\"4\" valign=\"center\" align=\"center\" ><img  style=\"display:block;border:0px;max-width:88px;max-height:100px;\" width=\"88\"  src=\"" . $img . "\" /></td>
    <td width=\"606\" height=\"32\" colspan=\"3\" style=\"font-family:Arial;font-size:12px;color:#dbbe72\"><b>" . $new->getTitle() . "</b></td>
    <td rowspan=\"4\">
      <img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/border-right.jpg\" width=\"7\" height=\"146\" alt=\"\"></td>
  </tr>
  <tr>
    <td width=\"606\" height=\"81\" colspan=\"3\" style=\"font-family:Arial;color:#4a52a1\" valign=\top\" >" . Unicode::truncate(strip_tags($new->get('body')->value), 250) . "...</td>
  </tr>
  <tr>
    <td width=\"13\" height=\"33\" rowspan=\"2\">
      <img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/spacer.gif\" width=\"13\" height=\"33\" alt=\"\"></td>
    <td><a href=\"" . $host . "/" . $alias."\">
      <img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/boutton.jpg\" width=\"82\" height=\"23\" alt=\"\"></a></td>
    <td width=\"511\" height=\"23\">
      <img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/spacer.gif\" width=\"511\" height=\"23\" alt=\"\"></td>
  </tr>
  <tr>
    <td width=\"593\" height=\"10\" colspan=\"2\">
      <img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/spacer.gif\" width=\"593\" height=\"10\" alt=\"\"></td>
  </tr>
  <tr>
    <td colspan=\"6\">
      <img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/border-bottom.jpg\" width=\"751\" height=\"7\" alt=\"\"></td>
  </tr>
</table><tr>";
        }
    }  
    $rendered.= "</td>
  </tr>
  <tr><tr>
</table>
</center>
<center>
<table width=\"751\" height=\"160\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
  
    <td><img src=\"" . $host . "/" . drupal_get_path("module", "md_newsletter") . "/assets/images/footer.jpg\" width=\"800\" height=\"104\" alt=\"\"></td>
  </tr>
</table></center>
</body>
</html>";
    $head_msg = "<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
  <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0;\">
  <meta name=\"format-detection\" content=\"telephone=no\"/>
  <style>
body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
a { padding: 0; }
@media all and (min-width: 560px) {
  .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
}
 </style>
<title>" . \Drupal::config('system.site')->get('name') . " - " .$config->get('subject') . "</title>
</head>
<body  topmargin=\"0\" rightmargin=\"0\" bottommargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" width=\"100%\" style=\"border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;\">
<center><table width=\"800\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
    <td>
          <p  style=\"font-family:Arial;color:#4a52a1\">" . $config->get('intro')['value'] . "</p><center>";
          
    return $head_msg.$rendered;
  }
  
  public static function sendNewsLetter()
    {
        $result = "";
        $config = \Drupal::config(Helper::SETTINGS);
        $emails = Helper::listEmails();
        foreach ($emails as $email) {
            $result = static::mail_utf8($email->email, \Drupal::config('system.site')->get('name'), \Drupal::config('system.site')->get('mail'), $config->get('subject'), static::newsLetterHTML());
        }
        return $result;
   }

  public static function mail_utf8($to, $from_user, $from_email, $subject, $message = '')
    {
        $from_user = "=?UTF-8?B?" . base64_encode($from_user) . "?=";
        $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        $headers = "From: $from_user <$from_email>\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=UTF-8" . "\r\n";
        return mail($to, $subject, $message, $headers);
    }

}
