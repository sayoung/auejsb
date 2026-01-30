<?php
namespace Drupal\md_newsletter\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\md_newsletter\Newsletter\Newsletter;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;
/**
 * SendForm class.
 */
class SendForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {
    $form['#prefix'] = '<div id="modal_send_form">' . Newsletter::newsLetterHTML()."<br><br><br>";
    $form['#suffix'] = '<br><br></div>';
    
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
        '#button_type' => 'primary',
        /*'#ajax' => [
            'callback' => [$this, 'form_ajax_submit'],
            'method' => 'append',
            'effect' => 'fade'
        ]*/
    ];
    
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      $result = Newsletter::sendNewsLetter();
      if($result){
        drupal_set_message("Newsletter a été bien envoyée aux destinataires");
      }
      $form_state->setRedirect('newsletter.admin_synchroniser');
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'newsletter_modal_form_send_form';
  }
  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames() {
    return ['config.modal_form_send_form'];
  }
  
  
  public function form_ajax_submit(array &$form, FormStateInterface $form_state) {
      $result = Newsletter::sendNewsLetter();
       drupal_set_message("Newsletter a été bien envoyée aux destinataires");
      $ajax_response = new AjaxResponse();
      $ajax_response->addCommand(new AppendCommand('#modal_send_form', "<span style=\"color:green\">Newsletter a été bien envoyée aux destinataires</span>"));
  }
  
}