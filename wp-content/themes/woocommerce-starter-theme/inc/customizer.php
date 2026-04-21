<?php

/**
 * Register custom theme options displayed in customizer
 *
 * @package StarterTheme
 */

starter_security_check();

function theme_asset_manifest($asset) {
  // Path to manifest
  $manifest_path = get_template_directory() . '/assets/manifest.json';

  // Check if manifest exist
  if (file_exists($manifest_path)) {
    static $manifest = null;

    // Load manifest if not loaded before
    if ($manifest === null) {
      $json = file_get_contents($manifest_path);
      $manifest = json_decode($json, true);
      if (!is_array($manifest)) {
        error_log('🚨 manifest.json is not valid JSON');
        $manifest = [];
      }
    }

    if (isset($manifest[$asset])) {
      return esc_url(get_template_directory_uri() . '/' . ltrim($manifest[$asset], '/'));
    }
  }

  // If manifest or file doesn't exist, change path to _src
  return esc_url(get_template_directory_uri() . '/_src/' . ltrim($asset, '/'));
}

