<?php

/**
 * @file
 * Administration form setup for Neim File System.
 */

/**
 * Builds the Settings form.
 */
function neimfs_settings() {
  $form = array();

  /////////////////////////////////////
  // CREDENTIALS
  /////////////////////////////////////
  $form['neimfs_appkey'] = array(
    '#type'        => 'textfield',
    '#title'       => t('Nim appkey'),
    '#default_value' => _neimfs_get_setting('appkey'),
    '#description' => t(  'Application key, 32 bytes, like 88a95c92f48c8298b1743fc23643a122'  ),
    '#collapsible' => FALSE,
  );
  $form['neimfs_seckey'] = array(
    '#type'        => 'textfield',
    '#title'       => t('Nim secret key'),
    '#default_value' => _neimfs_get_setting('seckey'),
    '#description' => t(  'Application secret key, 12 bytes, like 60eb63f32e67'  ),
    '#collapsible' => FALSE,
  );

  $form = system_settings_form($form);
  return $form;
}

/**
 * Validates the values on the admin form.
 */
function neimfs_settings_validate($form, &$form_state) {
  $vals = $form_state['values'];
  if (strlen($vals['neimfs_appkey']) != 32) {
    form_set_error('neimfs_appkey', 'You must 32 bytes appkey');
    return FALSE;
  }
  if (strlen($vals['neimfs_seckey']) != 12) {
    form_set_error('neimfs_seckey', 'You must 12 bytes seckey');
    return FALSE;
  }
  return TRUE;
}
