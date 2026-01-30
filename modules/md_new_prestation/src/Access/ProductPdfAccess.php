<?php
namespace Drupal\md_new_prestation\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\Routing\Route;

class ProductPdfAccess {

  public static function checkAccess(Route $route, AccountInterface $account, $product_id = NULL) {
    // Try loading the node
    $product = \Drupal::entityTypeManager()
  ->getStorage('commerce_product')
  ->load($product_id);

    if (!$product) {
    //  \Drupal::logger('md_new_prestation')->notice('No product found for ID: @id', ['@id' => $product_id]);
      return AccessResult::forbidden();
    }

    // Check if the field exists
    if (!$product->hasField('field_etat')) {
   //   \Drupal::logger('md_new_prestation')->notice('Product has no field_etat field');
      return AccessResult::forbidden();
    }

    // Get the etat value
    $etat_values = $product->get('field_etat')->getValue();
    $etat = !empty($etat_values) ? strtolower(trim($etat_values[0]['value'])) : '';

    \Drupal::logger('md_new_prestation')->notice('Etat for product @id = @etat', [
      '@id' => $product_id,
      '@etat' => $etat
    ]);

    // Allow only if etat = valider or validé
    if (in_array($etat, ['valider', 'validé','archive', 'facturation','facturationavecdate'])) {
      return AccessResult::allowed();
    }

    return AccessResult::forbidden();
  }
}
