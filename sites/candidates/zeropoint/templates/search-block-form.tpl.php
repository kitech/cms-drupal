
<div id="search" class="container-inline">
<?php if (theme_get_setting('searchimg')): ?>
  <?php if (empty($variables['form']['#block']->subject)): ?>
    <h2 class="element-invisible"><?php print t('Search form'); ?></h2>
  <?php endif; ?>
	<div class="form-item search form-type-textfield form-item-search-block-form">
	<label class="element-invisible" id="edit-search-block-form-img">Search</label>
		<input type="text" maxlength="128" name="search_block_form" id="edit-search-theme-form-img" size="6" value="" placeholder="<?php print t('Search') ?>" title="Enter the terms you wish to search for." class="form-text" />
		<input type="image" alt="Search" id="image-submit" src="<?php echo base_path() . path_to_theme() ?>/images/all/search.svg" class="form-image" />
	</div>
	<input type="hidden" name="form_token" id="edit-search-theme-form-form-token"  value="<?php print drupal_get_token('search_theme_form'); ?>" />
	<input type="hidden" name="form_id" id="edit-search-theme-form" value="search_theme_form" />
<?php else: ?>
  <?php if (empty($variables['form']['#block']->subject)): ?>
    <h2 class="element-invisible"><?php print t('Search form'); ?></h2>
  <?php endif; ?>
  <?php print $search_form; ?>
<?php endif; ?>
</div>
