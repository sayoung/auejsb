<?php

namespace Drupal\md_new_prestation\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\md_new_prestation\Helper\SurfaceAudit;

/**
 * Admin search page for e-prestation surface/price audit logs.
 */
class AuditLogForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'md_new_prestation_audit_log_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $request = \Drupal::request();
    $dossier = trim((string) $request->query->get('dossier', ''));
    $product_id = trim((string) $request->query->get('product_id', ''));
    $action = trim((string) $request->query->get('action', ''));

    $form['#method'] = 'get';
    $form['#token'] = FALSE;
    $form['#action'] = Url::fromRoute('eprestation.admin_audit_log')->toString();

    $form['filters'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Recherche audit'),
    ];

    $form['filters']['dossier'] = [
      '#type' => 'textfield',
      '#title' => $this->t('N° de dossier'),
      '#default_value' => $dossier,
      '#size' => 24,
      '#attributes' => ['placeholder' => '73350/2026'],
    ];

    $form['filters']['product_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Product ID'),
      '#default_value' => $product_id,
      '#size' => 10,
    ];

    $action_options = ['' => $this->t('- Toutes les actions -')] + SurfaceAudit::ACTIONS;
    $form['filters']['action'] = [
      '#type' => 'select',
      '#title' => $this->t('Action'),
      '#options' => $action_options,
      '#default_value' => $action,
    ];

    $form['filters']['actions'] = [
      '#type' => 'actions',
    ];
    $form['filters']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Rechercher'),
      // Avoid adding form_build_id to the GET query.
      '#name' => '',
    ];
    $form['filters']['actions']['reset'] = [
      '#type' => 'link',
      '#title' => $this->t('Réinitialiser'),
      '#url' => Url::fromRoute('eprestation.admin_audit_log'),
      '#attributes' => ['class' => ['button']],
    ];

    $table_exists = \Drupal::database()->schema()->tableExists('md_new_prestation_audit_log');
    if (!$table_exists) {
      $form['missing_table'] = [
        '#markup' => '<p><strong>' . $this->t('Table audit absente. Exécutez les mises à jour du module (update.php / drush updb).') . '</strong></p>',
      ];
      return $form;
    }

    $filters = [
      'dossier' => $dossier,
      'product_id' => $product_id,
      'action' => $action,
    ];
    $rows_data = SurfaceAudit::search($filters, 100);
    $date_formatter = \Drupal::service('date.formatter');

    $header = [
      $this->t('Date'),
      $this->t('Dossier'),
      $this->t('Product'),
      $this->t('Action'),
      $this->t('Utilisateur'),
      $this->t('Résumé'),
      $this->t('Détails du changement'),
    ];

    $rows = [];
    foreach ($rows_data as $item) {
      $action_label = SurfaceAudit::ACTIONS[$item['action']] ?? $item['action'];
      $details = $item['details_decoded'];
      $rows[] = [
        $date_formatter->format((int) $item['created'], 'short'),
        $item['dossier'] !== '' ? $item['dossier'] : '—',
        $item['product_id'] ? (string) $item['product_id'] : '—',
        $action_label,
        $item['username'] . ' (uid ' . $item['uid'] . ')',
        Html::escape($item['summary']),
        ['data' => $this->buildDetailsRender($details, $item['action'])],
      ];
    }

    $form['results_count'] = [
      '#markup' => '<p>' . $this->t('@count résultat(s)', ['@count' => count($rows_data)]) . '</p>',
    ];

    $form['results'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('Aucun événement d’audit trouvé pour ces critères.'),
      '#attributes' => ['class' => ['md-new-prestation-audit-log']],
    ];

    return $form;
  }

  /**
   * Build a details render array for change rows.
   */
  protected function buildDetailsRender(array $details, $action) {
    $build = [];

    if (!empty($details['changes_table']) && is_array($details['changes_table'])) {
      $rows = [];
      foreach ($details['changes_table'] as $row) {
        $old = isset($row['old']) && $row['old'] !== '' ? $row['old'] : '—';
        $new = isset($row['new']) && $row['new'] !== '' ? $row['new'] : '—';
        $rows[] = [
          Html::escape($row['label'] ?? $row['field'] ?? ''),
          [
            'data' => Html::escape($old),
            'style' => 'background:#fff3f3;',
          ],
          [
            'data' => Html::escape($new),
            'style' => 'background:#f3fff3;font-weight:bold;',
          ],
        ];
      }
      $build['changes'] = [
        '#type' => 'table',
        '#header' => [
          $this->t('Champ'),
          $this->t('Ancienne valeur'),
          $this->t('Nouvelle valeur'),
        ],
        '#rows' => $rows,
        '#attributes' => [
          'class' => ['md-audit-change-details'],
          'style' => 'margin:0;min-width:320px;',
        ],
      ];
    }
    elseif (!empty($details['values_labeled']) && is_array($details['values_labeled'])) {
      $rows = [];
      foreach ($details['values_labeled'] as $row) {
        $rows[] = [
          Html::escape($row['label'] ?? $row['field'] ?? ''),
          Html::escape($row['value'] ?? ''),
        ];
      }
      $build['values'] = [
        '#type' => 'table',
        '#header' => [
          $this->t('Champ'),
          $this->t('Valeur'),
        ],
        '#rows' => $rows,
        '#attributes' => [
          'class' => ['md-audit-values-details'],
          'style' => 'margin:0;min-width:280px;',
        ],
      ];
    }
    elseif (!empty($details['metric_used']) || isset($details['price_total'])) {
      $build['extra'] = [
        '#markup' => '<ul><li>' . $this->t('Surface facturée: @m', ['@m' => $details['metric_used'] ?? '—']) . '</li><li>' . $this->t('Prix: @p MAD', ['@p' => $details['price_total'] ?? '—']) . '</li></ul>',
      ];
    }
    else {
      $build['empty'] = [
        '#markup' => '<em>' . $this->t('Pas de détail structuré') . '</em>',
      ];
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('eprestation.admin_audit_log', [], [
      'query' => array_filter([
        'dossier' => trim((string) $form_state->getValue('dossier')),
        'product_id' => trim((string) $form_state->getValue('product_id')),
        'action' => trim((string) $form_state->getValue('action')),
      ], function ($value) {
        return $value !== '' && $value !== NULL;
      }),
    ]);
  }

}
