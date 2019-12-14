<?php

/**
 * @file
 * Automatic node titles api file.
 */

/**
 * Implements hook_auto_nodetitle_alter().
 */
function hook_auto_nodetitle_alter(&$node) {
  // Alter node title here.
  if (strpos($node->title, 'world') !== false) {
    $node->title = 'Hello world!';
  }
}
