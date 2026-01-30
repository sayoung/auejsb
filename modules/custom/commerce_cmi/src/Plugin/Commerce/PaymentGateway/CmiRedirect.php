<?php

namespace Drupal\commerce_cmi\Plugin\Commerce\PaymentGateway;

//use Drupal\commerce_cmi\CmiEncryption;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OffsitePaymentGatewayBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * Provides the CMI offsite Checkout payment gateway.
 *
 * @CommercePaymentGateway(
 *   id = "Cmi_redirect",
 *   label = @Translation("CMI (Redirect to cmi)"),
 *   display_label = @Translation("CMI"),
 *    forms = {
 *     "offsite-payment" = "Drupal\commerce_cmi\PluginForm\CmiRedirect\PaymentCmiForm",
 *   },
 * )
 */
class CmiRedirect extends OffsitePaymentGatewayBase {

    public function defaultConfiguration() {
        return [
                'actionslk' => '',
                'merchant_id' => '',
                'SLKSecretkey' => '',
                'confirmation_mode' => '1',
            ] + parent::defaultConfiguration();
    }

    public function buildConfigurationForm(array $form, FormStateInterface $form_state)
    {
        $form = parent::buildConfigurationForm($form, $form_state);

        $form['actionslk'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Gateway'),
            '#description' => $this->t('URL de la passerelle fourni par CMI.'),
            '#default_value' => trim($this->configuration['actionslk']),
            '#required' => TRUE,
        ];
        $form['merchant_id'] = [
            '#type' => 'textfield',
            '#title' => $this->t('ClientId'),
            '#description' => $this->t('Identifiant marchand fourni par CMI.'),
            '#default_value' => trim($this->configuration['merchant_id']),
            '#required' => TRUE,
        ];

        $form['SLKSecretkey'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Clé de hachage'),
            '#description' => $this->t('Vous êtes appelés à renseigner la clé à travers votre espace back office de la plate-forme CMI.'),
            '#default_value' => trim($this->configuration['SLKSecretkey']),
            '#required' => TRUE,
        ];

        $form['confirmation_mode'] = [
            '#type' => 'radios',
            '#id' => 'cmi-method',
            '#title' => $this->t('Mode de confirmation'),
            '#description' => $this->t('Confirmation automatique des transactions CMI.'),
            '#default_value' => $this->configuration['confirmation_mode'],
            '#options' => [
                '1' => $this->t('Automatique'),
                '0' => $this->t('Manuelle'),
            ],
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
        parent::submitConfigurationForm($form, $form_state);
        if (!$form_state->getErrors()) {
            $values = $form_state->getValue($form['#parents']);
            $this->configuration['actionslk'] = trim($values['actionslk']);
            $this->configuration['merchant_id'] = trim($values['merchant_id']);
            $this->configuration['SLKSecretkey'] = trim($values['SLKSecretkey']);
            $this->configuration['confirmation_mode'] = $values['confirmation_mode'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function onReturn(OrderInterface $order, Request $request) {
        die('onReturn');
    }

}