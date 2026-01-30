<?php

namespace Drupal\md_newsletter\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\md_newsletter\Helper\Helper;


use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class MarieForm.
 *
 * @package Drupal\md_newsletter\Form
 */
class NewsletterForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'md_newsletter';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['email'] = array(
      '#type' => 'textfield',
	  '#required' => TRUE,
      '#attributes' => array('class' => array('text-input', 'mauticform-input'), 'placeholder' => t('Adresse email')),
    );
     $form['submit'] = array(
            '#type' => 'submit',
            '#value' => t('OK'),
			'#attributes' => array('class' => array('ok-css btn btn-success')),
            '#ajax' => [
                'callback' => [$this, 'form_ajax_submit'],
                'method' => 'replace',
                'effect' => 'fade'
            ],
        );
   $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    return $form;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

        if (!valid_email_address($form_state
    ->getValue('email'))) {
    $form_state
      ->setErrorByName('email', $this
      ->t('That e-mail address is not valid.'));
  }

        /* $name = $form_state->getValue('candidate_name');
          if(preg_match('/[^A-Za-z]/', $name)) {
             $form_state->setErrorByName('candidate_name', $this->t('your name must in characters without space'));
          }
*/

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }

  /**
   * {@inheritdoc}
   */
  public function form_ajax_submit(array &$form, FormStateInterface $form_state) {

    $field = $form_state->getValues();
    $email = $field['email'];

    $ajax_response = new AjaxResponse();

    $field  = array(
      'email' =>  $email
    );
    $msg ="";
    $user = Helper::checkEmail($email);
	if(empty($email) ){
		$msg ="<span class=\"error\">Merci de remplire le champs avec votre adresse E-mail.</span>";
	}else if(!$user){
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $query = \Drupal::database();
        $query ->insert('md_newsletter_emails')
           ->fields($field)
           ->execute();
        $msg ="<span class=\"success\"> " .t("Votre adresse a été enregistrée") . "</span>";
      }else{
        $msg ="<span class=\"error\">" .t("L'adresse email") . "  '" . $email . "' " .t("est invalide") . ".</span>";
      }

    }else{
        $msg ="<span class=\"error\"> " .t("L'adresse email") . " '" . $email . "' " .t("existe déjà") . ".</span>";
    } 

    $ajax_response->addCommand(new OpenModalDialogCommand('Newsletter', $msg, ['width' => '400']));
	//$ajax_response->addCommand(new InvokeCommand('Newsletter', $msg, ['width' => '400']));
	//$response->addCommand(new InvokeCommand(NULL, 'customRedirect', ['/']));
  
    return $ajax_response;

   }

}
