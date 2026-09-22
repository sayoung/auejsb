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
   * Human labels for watched fields (admin UI).
   */
  const FIELD_LABELS = [
    'field_metrage_du_projet' => 'Métrage du projet',
    'field_supp_apres_modi' => 'Surface supplémentaire après modification',
    'field_surf_cessible_modi' => 'Surfaces cessibles après modification',
    'field_type' => 'Type de projet',
    'field_type_2' => 'Type de projet (2)',
    'field_nature_du_projet' => 'Nature du projet',
    'field_nature_du_projet_2' => 'Nature du projet (2)',
    'field_ndeg_de_dossier' => 'N° de dossier',
    'price' => 'Prix',
  ];

  /**
   * Available action codes for the admin filter.
   */
  const ACTIONS = [
    'SURFACE_CREATE' => 'Création produit',
    'SURFACE_CHANGE' => 'Changement de surface / champs',
    'SURFACE_WEBFORM_CREATE' => 'Création via webform',
    'PRICE_CREATE' => 'Création prix',
    'PRICE_CHANGE' => 'Changement de prix',
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
    $dossier = self::scalarFieldValue($entity, 'field_ndeg_de_dossier');
    $context_base = [
      '@pid' => $entity->id() ?: 'new',
      '@title' => method_exists($entity, 'getTitle') ? $entity->getTitle() : $entity->label(),
      '@uid' => $account->id(),
      '@user' => $account->getAccountName() ?: 'anonymous',
      '@dossier' => $dossier,
    ];

    if ($entity->isNew() || empty($entity->original)) {
      $values = self::collectProductValues($entity);
      $summary = 'Création produit — dossier ' . ($dossier ?: '(vide)');
      \Drupal::logger('md_new_prestation_audit')->notice(
        'SURFACE_CREATE product=@pid dossier=@dossier title=@title uid=@uid user=@user values=@values',
        $context_base + ['@values' => self::formatValues($values)]
      );
      self::persist([
        'action' => 'SURFACE_CREATE',
        'product_id' => (int) ($entity->id() ?: 0),
        'variation_id' => 0,
        'dossier' => $dossier,
        'summary' => $summary,
        'details' => [
          'title' => $context_base['@title'],
          'values' => $values,
          'values_labeled' => self::labelValues($values),
        ],
      ]);
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
        $changes[$field_name] = [
          'old' => $old,
          'new' => $new,
          'label' => self::FIELD_LABELS[$field_name] ?? $field_name,
        ];
      }
    }

    if (empty($changes)) {
      return;
    }

    $summary = 'Changement — ' . self::formatChanges($changes);
    \Drupal::logger('md_new_prestation_audit')->warning(
      'SURFACE_CHANGE product=@pid dossier=@dossier title=@title uid=@uid user=@user changes=@changes',
      $context_base + ['@changes' => self::formatChanges($changes)]
    );
    self::persist([
      'action' => 'SURFACE_CHANGE',
      'product_id' => (int) $entity->id(),
      'variation_id' => 0,
      'dossier' => $dossier,
      'summary' => $summary,
      'details' => [
        'title' => $context_base['@title'],
        'changes' => $changes,
        'changes_table' => self::changesAsRows($changes),
      ],
    ]);
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
    if ($entity->bundle() !== 'instruction') {
      return;
    }

    $account = \Drupal::currentUser();
    $product_id = method_exists($entity, 'getProductId') ? (int) $entity->getProductId() : 0;
    $dossier = self::dossierFromProductId($product_id);
    $context_base = [
      '@vid' => $entity->id() ?: 'new',
      '@sku' => method_exists($entity, 'getSku') ? $entity->getSku() : '',
      '@pid' => $product_id ?: '',
      '@uid' => $account->id(),
      '@user' => $account->getAccountName() ?: 'anonymous',
      '@dossier' => $dossier,
    ];

    $new_price = self::formatPrice($entity);
    if ($entity->isNew() || empty($entity->original)) {
      \Drupal::logger('md_new_prestation_audit')->notice(
        'PRICE_CREATE variation=@vid sku=@sku product=@pid dossier=@dossier uid=@uid user=@user price=@price',
        $context_base + ['@price' => $new_price]
      );
      self::persist([
        'action' => 'PRICE_CREATE',
        'product_id' => $product_id,
        'variation_id' => (int) ($entity->id() ?: 0),
        'dossier' => $dossier,
        'summary' => 'Création prix ' . $new_price,
        'details' => [
          'sku' => $context_base['@sku'],
          'price' => $new_price,
          'changes_table' => [
            [
              'field' => 'price',
              'label' => self::FIELD_LABELS['price'],
              'old' => '',
              'new' => $new_price,
            ],
          ],
        ],
      ]);
      return;
    }

    /** @var \Drupal\Core\Entity\ContentEntityInterface $original */
    $original = $entity->original;
    $old_price = self::formatPrice($original);
    if ($old_price === $new_price) {
      return;
    }

    $changes = [
      'price' => [
        'old' => $old_price,
        'new' => $new_price,
        'label' => self::FIELD_LABELS['price'],
      ],
    ];
    \Drupal::logger('md_new_prestation_audit')->warning(
      'PRICE_CHANGE variation=@vid sku=@sku product=@pid dossier=@dossier uid=@uid user=@user old=@old new=@new',
      $context_base + [
        '@old' => $old_price,
        '@new' => $new_price,
      ]
    );
    self::persist([
      'action' => 'PRICE_CHANGE',
      'product_id' => $product_id,
      'variation_id' => (int) $entity->id(),
      'dossier' => $dossier,
      'summary' => 'Prix: "' . $old_price . '" → "' . $new_price . '"',
      'details' => [
        'sku' => $context_base['@sku'],
        'changes' => $changes,
        'changes_table' => self::changesAsRows($changes),
      ],
    ]);
  }

  /**
   * Explicit create log from webform handler (includes calculated total).
   */
  public static function logWebformCreate(ContentEntityInterface $product, $price_total, $metric_used, $field_type) {
    $account = \Drupal::currentUser();
    $dossier = self::scalarFieldValue($product, 'field_ndeg_de_dossier');
    $values = self::collectProductValues($product);
    \Drupal::logger('md_new_prestation_audit')->notice(
      'SURFACE_WEBFORM_CREATE product=@pid title=@title dossier=@dossier type=@type metric=@metric price=@price uid=@uid user=@user values=@values',
      [
        '@pid' => $product->id() ?: 'new',
        '@title' => method_exists($product, 'getTitle') ? $product->getTitle() : $product->label(),
        '@dossier' => $dossier,
        '@type' => $field_type,
        '@metric' => $metric_used,
        '@price' => $price_total,
        '@uid' => $account->id(),
        '@user' => $account->getAccountName() ?: 'anonymous',
        '@values' => self::formatValues($values),
      ]
    );
    self::persist([
      'action' => 'SURFACE_WEBFORM_CREATE',
      'product_id' => (int) ($product->id() ?: 0),
      'variation_id' => 0,
      'dossier' => $dossier,
      'summary' => 'Webform — type ' . $field_type . ', métrique ' . $metric_used . ' m², prix ' . $price_total . ' MAD',
      'details' => [
        'type' => $field_type,
        'metric_used' => $metric_used,
        'price_total' => $price_total,
        'values' => $values,
        'values_labeled' => self::labelValues($values),
        'changes_table' => [
          [
            'field' => 'metric_used',
            'label' => 'Surface facturée',
            'old' => '',
            'new' => (string) $metric_used,
          ],
          [
            'field' => 'price',
            'label' => self::FIELD_LABELS['price'],
            'old' => '',
            'new' => $price_total . ' MAD',
          ],
        ],
      ],
    ]);
  }

  /**
   * Persist a row in the searchable audit table + keep dblog.
   */
  public static function persist(array $record) {
    if (!\Drupal::database()->schema()->tableExists('md_new_prestation_audit_log')) {
      return;
    }
    $account = \Drupal::currentUser();
    \Drupal::database()->insert('md_new_prestation_audit_log')
      ->fields([
        'created' => REQUEST_TIME,
        'uid' => (int) $account->id(),
        'username' => $account->getAccountName() ?: 'anonymous',
        'product_id' => (int) ($record['product_id'] ?? 0),
        'variation_id' => (int) ($record['variation_id'] ?? 0),
        'dossier' => mb_substr((string) ($record['dossier'] ?? ''), 0, 128),
        'action' => mb_substr((string) ($record['action'] ?? ''), 0, 64),
        'summary' => (string) ($record['summary'] ?? ''),
        'details' => json_encode($record['details'] ?? [], JSON_UNESCAPED_UNICODE),
      ])
      ->execute();
  }

  /**
   * Query audit rows for the admin page.
   *
   * @return array
   *   List of associative rows with decoded details.
   */
  public static function search(array $filters = [], $limit = 50) {
    if (!\Drupal::database()->schema()->tableExists('md_new_prestation_audit_log')) {
      return [];
    }
    $query = \Drupal::database()->select('md_new_prestation_audit_log', 'a')
      ->fields('a')
      ->orderBy('created', 'DESC')
      ->range(0, (int) $limit);

    if (!empty($filters['dossier'])) {
      $query->condition('dossier', $filters['dossier']);
    }
    if (!empty($filters['product_id'])) {
      $query->condition('product_id', (int) $filters['product_id']);
    }
    if (!empty($filters['action'])) {
      $query->condition('action', $filters['action']);
    }

    $rows = [];
    foreach ($query->execute() as $row) {
      $item = (array) $row;
      $item['details_decoded'] = [];
      if (!empty($item['details'])) {
        $decoded = json_decode($item['details'], TRUE);
        if (is_array($decoded)) {
          $item['details_decoded'] = $decoded;
        }
      }
      $rows[] = $item;
    }
    return $rows;
  }

  /**
   * Resolve dossier number from a product ID.
   */
  protected static function dossierFromProductId($product_id) {
    if (!$product_id) {
      return '';
    }
    try {
      $product = \Drupal::entityTypeManager()->getStorage('commerce_product')->load($product_id);
      if ($product instanceof ContentEntityInterface) {
        return self::scalarFieldValue($product, 'field_ndeg_de_dossier');
      }
    }
    catch (\Exception $e) {
      // Ignore.
    }
    return '';
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
      $label = $diff['label'] ?? $field;
      $old = $diff['old'] === '' ? '(empty)' : $diff['old'];
      $new = $diff['new'] === '' ? '(empty)' : $diff['new'];
      $parts[] = $label . ': "' . $old . '" → "' . $new . '"';
    }
    return implode('; ', $parts);
  }

  /**
   * Label map for values display.
   */
  protected static function labelValues(array $values) {
    $labeled = [];
    foreach ($values as $field => $value) {
      $labeled[] = [
        'field' => $field,
        'label' => self::FIELD_LABELS[$field] ?? $field,
        'value' => $value === '' ? '(vide)' : $value,
      ];
    }
    return $labeled;
  }

  /**
   * Rows for the details table in the admin UI.
   */
  protected static function changesAsRows(array $changes) {
    $rows = [];
    foreach ($changes as $field => $diff) {
      $rows[] = [
        'field' => $field,
        'label' => $diff['label'] ?? (self::FIELD_LABELS[$field] ?? $field),
        'old' => $diff['old'] === '' ? '(vide)' : $diff['old'],
        'new' => $diff['new'] === '' ? '(vide)' : $diff['new'],
      ];
    }
    return $rows;
  }

}
