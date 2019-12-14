<?php

/**
 * Implementation of hook_drush_command().
 */
function auto_nodetitle_drush_command() {
  $items = array();

  $items['ant'] = array(
    'callback' => 'drush_auto_nodetitle_drush_update',
    'description' => dt('Update automatic node titles.'),
    'examples' => array(
      'drush ant sometype' => dt('Update all the automatic node titles for content type sometype'),
    ),
  );

  return $items;
}

/**
 * Implementation of hook_drush_help().
 */
function auto_nodetitle_drush_help($section) {
  switch ($section) {
    case 'drush:ant':
      return dt('Recreate auto node titles for one or more content types.');
  }
}

/**
 * Drush callback to perform actual auto_nodetitle preset flush.
 */
function drush_auto_nodetitle_drush_update() {
  global $user;
  $user = user_load(1);
  $args = func_get_args();
  $blocksize = 10;
  if (empty($args)) {
    drush_set_error(dt('You must specify a node type name to recreate or specify "all".'.
      "\n".'Node types are: ').implode(array_keys(_node_types_build()->types),' '));
    return FALSE;
  } else {
    // Implement 'all'
    if (count($args) == 1 && $args[0] == 'all') {
      $args = array_keys(node_type_get_types());
    }
    foreach ($args as $type) {
      $total = db_query("select count(nid) as count from node where type=:type", array(':type' => $type))->fetchField();
      $count = 0;
      drush_log(dt('Updating @total auto node titles for type "@type".', array('@type' => $type, '@total' => $total)), 'ok');
      do {
        $result = db_query_range("select nid from node where type=:type", $count, $blocksize, array(':type' => $type));
        $nids = array();
        foreach ($result as $row) {
          $nids[] = $row->nid;
        }
        auto_nodetitle_operations_update($nids);
        $count += count($nids);
        print "$count/$total done\n";
        drush_log(dt('  @count done', array('@count' => $count)));
      } while ($count < $total);
      drush_log(dt('All done for type "@type".', array('@type' => $type)), 'ok');
    }
    return TRUE;
  }
}
