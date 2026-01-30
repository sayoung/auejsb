<?php

namespace Drupal\md_social\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'SocialBlock' block.
 *
 * @Block(
 *  id = "fnis_social_block",
 *  admin_label = @Translation("Social block Network"),
 * )
 */
class SocialBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
$request_url = \Drupal::request()->getUri();
$path = \Drupal::request()->attributes->get('_system_path');
$current_uri = \Drupal::request()->getRequestUri();
$current_path = \Drupal::service('path.current')->getPath();
$result = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
    $config = \Drupal::config("social.setting");
    return array(
      '#theme' => 'theme_md_social',
      '#social' => (object)array(
                      "facebook" => $config->get('facebook'),
                      "twitter"  => $config->get('twitter'),
                      "youtube"  => $config->get('youtube'),
                      "google"   => $config->get('google'),
					  "url"      => $request_url,
                    ),
	  '#cache' => [
          'max-age' => 0,
      ],
	  '#attached' => array(
                'library' => array(
                    'md_social/md-social-hp'
                )
            )
    );
  }

}
