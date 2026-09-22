<?php

namespace Drupal\md_new_prestation\Helper;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Audit log for surface / pricing fields on instruction products.
 */
class SurfaceAudit {

  /**
   * Product fields whose changes must be logged.
   */
  const WATCHED_PRODUCT_FIELDS = [
    'field_metrage_du_projet',
    'field_supp_apres_modi',
    'field_surf_cessible_modi',
    'field_type',
    'field_type_2',
    'field_nature_du_projet',
    'field_nature_du_projet_2',
    'field_ndeg_de_dossier',
  ];

  /**
   * Log creation or field changes on an instruction product.
   */
  public static function logProductChanges(EntityInterface $entity) {
    if ($entity->getEntityTypeId() !== 'commerce_product' || $entity->bundle() !== 'instruction') {
      return;
    }
    if (!$entity instanceof ContentEntityInterface) {
      return;
    }

    $account = \Drupal::currentUser();
    $context_base = [
      '@pid' => $entity->id() ?: 'new',
      '@title' => method_exists($entity, 'getTitle') ? $entity->getTitle() : $entity->label(),
      '@uid' => $account->id(),
      '@user' => $account->getAccountName() ?: 'anonymous',
    ];

    if ($entity->isNew() || empty($entity->original)) {
      $values = self::collectProductValues($entity);
      \Drupal::logger('md_new_prestation_audit')->notice(
        'SURFACE_CREATE product=@pid title=@title uid=@uid user=@user values=@values',
        $context_base + ['@values' => self::formatValues($values)]
      );
      return;
    }

    /** @var \Drupal\Core\Entity\ContentEntityInterface $original */
    $original = $entity->original;
    $changes = [];
    foreach (self::WATCHED_PRODUCT_FIELDS as $field_name) {
      if (!$entity->hasField($field_name) || !$original->hasField($field_name)) {
        continue;
      }
      $old = self::scalarFieldValue($original, $field_name);
      $new = self::scalarFieldValue($entity, $field_name);
      if ((string) $old !== (string) $new) {
        $changes[$field_name] = ['old' => $old, 'new' => $new];
      }
    }

    if (empty($changes)) {
      return;
    }

    \Drupal::logger('md_new_prestation_audit')->warning(
      'SURFACE_CHANGE product=@pid title=@title uid=@uid user=@user changes=@changes',
      $context_base + ['@changes' => self::formatChanges($changes)]
    );
  }

  /**
   * Log price changes on instruction product variations.
   */
  public static function logVariationPriceChanges(EntityInterface $entity) {
    if ($entity->getEntityTypeId() !== 'commerce_product_variation') {
      return;
    }
    if (!$entity instanceof ContentEntityInterface || !$entity->hasField('price')) {
      return;
    }
    // Only instruction variations (bundle may be "instruction").
    if ($entity->bundle() !== 'instruction') {
      return;
    }

    $account = \Drupal::currentUser();
    $product_id = method_exists($entity, 'getProductId') ? $entity->getProductId() : '';
    $context_base = [
      '@vid' => $entity->id() ?: 'new',
      '@sku' => method_exists($entity, 'getSku') ? $entity->getSku() : '',
      '@pid' => $product_id ?: '',
      '@uid' => $account->id(),
      '@user' => $account->getAccountName() ?: 'anonymous',
    ];

    $new_price = self::formatPrice($entity);
    if ($entity->isNew() || empty($entity->original)) {
      \Drupal::logger('md_new_prestation_audit')->notice(
        'PRICE_CREATE variation=@vid sku=@sku product=@pid uid=@uid user=@user price=@price',
        $context_base + ['@price' => $new_price]
      );
      return;
    }

    /** @var \Drupal\Core\Entity\ContentEntityInterface $original */
    $original = $entity->original;
    $old_price = self::formatPrice($original);
    if ($old_price === $new_price) {
      return;
    }

    \Drupal::logger('md_new_prestation_audit')->warning(
      'PRICE_CHANGE variation=@vid sku=@sku product=@pid uid=@uid user=@user old=@old new=@new',
      $context_base + [
        '@old' => $old_price,
        '@new' => $new_price,
      ]
    );
  }

  /**
   * Explicit create log from webform handler (includes calculated total).
   */
  public static function logWebformCreate(ContentEntityInterface $product, $price_total, $metric_used, $field_type) {
    $account = \Drupal::currentUser();
    \Drupal::logger('md_new_prestation_audit')->notice(
      'SURFACE_WEBFORM_CREATE product=@pid title=@title dossier=@dossier type=@type metric=@metric price=@price uid=@uid user=@user values=@values',
      [
        '@pid' => $product->id() ?: 'new',
        '@title' => method_exists($product, 'getTitle') ? $product->getTitle() : $product->label(),
        '@dossier' => self::scalarFieldValue($product, 'field_ndeg_de_dossier'),
        '@type' => $field_type,
        '@metric' => $metric_used,
        '@price' => $price_total,
        '@uid' => $account->id(),
        '@user' => $account->getAccountName() ?: 'anonymous',
        '@values' => self::formatValues(self::collectProductValues($product)),
      ]
    );
  }

  /**
   * Collect watched field values from a product.
   */
  protected static function collectProductValues(ContentEntityInterface $entity) {
    $values = [];
    foreach (self::WATCHED_PRODUCT_FIELDS as $field_name) {
      if ($entity->hasField($field_name)) {
        $values[$field_name] = self::scalarFieldValue($entity, $field_name);
      }
    }
    return $values;
  }

  /**
   * Read a single scalar field value safely.
   */
  protected static function scalarFieldValue(ContentEntityInterface $entity, $field_name) {
    if (!$entity->hasField($field_name) || $entity->get($field_name)->isEmpty()) {
      return '';
    }
    $item = $entity->get($field_name)->first();
    if (!$item) {
      return '';
    }
    $value = $item->getValue();
    if (isset($value['value'])) {
      return (string) $value['value'];
    }
    if (isset($value['target_id'])) {
      return (string) $value['target_id'];
    }
    return json_encode($value);
  }

  /**
   * Format a Commerce price field.
   */
  protected static function formatPrice(ContentEntityInterface $entity) {
    if (!$entity->hasField('price') || $entity->get('price')->isEmpty()) {
      return '';
    }
    $price = $entity->get('price')->first();
    if (!$price) {
      return '';
    }
    $number = method_exists($price, 'toPrice') ? $price->toPrice()->getNumber() : ($price->number ?? '');
    $currency = method_exists($price, 'toPrice') ? $price->toPrice()->getCurrencyCode() : ($price->currency_code ?? '');
    return trim($number . ' ' . $currency);
  }

  /**
   * Human-readable values map.
   */
  protected static function formatValues(array $values) {
    $parts = [];
    foreach ($values as $key => $value) {
      $parts[] = $key . '=' . ($value === '' ? '(empty)' : $value);
    }
    return implode('; ', $parts);
  }

  /**
   * Human-readable changes map.
   */
  protected static function formatChanges(array $changes) {
    $parts = [];
    foreach ($changes as $field => $diff) {
      $old = $diff['old'] === '' ? '(empty)' : $diff['old'];
      $new = $diff['new'] === '' ? '(empty)' : $diff['new'];
      $parts[] = $field . ': "' . $old . '" -> "' . $new . '"';
    }
    return implode('; ', $parts);
  }

}
