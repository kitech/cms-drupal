<?php

/**
 * @file
 * Default simple view template to display a rows in a grid.
 *
 * - $rows contains a nested array of rows. Each row contains an array of
 *   columns.
 *
 * @ingroup views_templates
 * ##### View is presented as divs instead of table #####
 */
?>
<?php if (!empty($title)) : ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<div class="view8a">
  <div class="<?php print $class; ?>"<?php print $attributes; ?>>
    <?php if (!empty($caption)) : ?>
      <caption><?php print $caption; ?></caption>
    <?php endif; ?>
    <?php foreach ($rows as $row_number => $columns): ?>
    <div <?php if ($row_classes[$row_number]):?> class="pure-g <?php print $row_classes[$row_number]; ?>"<?php endif; ?>>
      <?php foreach ($columns as $column_number => $item): ?>
      <div class="pure-u-1 pure-u-lg-1-<?php print (count($columns)); ?>">
        <div <?php if ($column_classes[$row_number][$column_number]): ?> class="view8b <?php print $column_classes[$row_number][$column_number]; ?>"<?php endif; ?>>
          <div class="view8c">
            <?php print $item; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>
