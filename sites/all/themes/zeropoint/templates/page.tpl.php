<div id="pgwp">
<div id="top_bg">
<div class="sizer0 clearfix"<?php print wrapper_width() ?>>
<div id="top_left">
<div id="top_right">
<div id="headimg">

<div id="header" role="banner">
<div class="clearfix">
  <div id="top-elements-logo">
    <?php if ($logo): ?><a href="<?php print check_url($front_page); ?>" title="<?php print t('Home'); ?>"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" class="logoimg" /></a><?php endif; ?>
  </div>

  <div id="top-elements-left">
    <a href="<?php print check_url($front_page); ?>" title="<?php print t('Home'); ?>"><?php print $site_name; ?></a>
  </div>
  <div id="top-elements-left">
    <div style="margin-top: -10px;">
      <?php if ($page['topreg']): ?><div id="topreg"><?php print render ($page['topreg']); ?></div><?php endif; ?>
    </div>
  </div>

<?php if (theme_get_setting('loginlinks') || $page['topreg']): ?>
  <div id="top-elements">
    <?php if (theme_get_setting('loginlinks')): ?><div id="user_links"><?php print login_links() ?></div><?php endif; ?>
    <?php if ($page['topreg'] &&FALSE): ?><div id="topreg"><?php print render ($page['topreg']); ?></div><?php endif; ?>
  </div>
<?php endif; ?>
<div id="top-elements-search">
  <?php
    $seform = drupal_get_form('search_block_form');
    $sekeys = array();
    if (isset($seform['basic'])) {
      $sekeys = $seform['basic']['keys'];
      $sekeys['#size'] = 12; // <= 40
      $sekeys['#maxlength'] = 20; // <= 255
      $sekeys['#title'] = '';
    }
    $seform['basic']['keys'] = $sekeys;
    print render($seform);
    // print count($seform);
    // print json_encode($seform['basic']['keys']);
    // print json_encode($seform['basic']);
  ?>
  <!-- <img src='https://blog.akaxin.vip:4430/sites/all/themes/zeropoint/images/all/search.svg' /> -->
</div>

  <?php if ($logo && FALSE): ?><a href="<?php print check_url($front_page); ?>" title="<?php print t('Home'); ?>"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" class="logoimg" /></a><?php endif; ?>
  <div id="name-and-slogan">
  <?php if ($site_name && FALSE): ?>
    <?php if ($title && theme_get_setting('page_h1') == '0'): ?>
      <p id="site-name"><a href="<?php print check_url($front_page); ?>" title="<?php print t('Home'); ?>"><?php print $site_name; ?></a></p>
    <?php else: ?>
      <h1 id="site-name"><a href="<?php print check_url($front_page); ?>" title="<?php print t('Home'); ?>"><?php print $site_name; ?></a></h1>
    <?php endif; ?>
  <?php endif; ?>
  <?php if ($site_slogan): ?><div id="site-slogan"><?php print $site_slogan; ?></div><?php endif; ?>
  </div>
</div>
<?php if ($page['header']): ?><?php print render ($page['header']); ?><?php endif; ?>
<div class="menuband clearfix">
  <div id="menu" class="menu-wrapper">
  <?php if ($logo || $site_name): ?>
    <a href="<?php print check_url($front_page); ?>" class="pure-menu-heading" title="<?php if ($site_slogan) print $site_slogan; ?>">
      <?php if ($logo): ?><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" class="logomob" /><?php endif; ?>
      <?php if ($site_name) print $site_name; ?>
    </a>
  <?php endif; ?>
  <?php if ($main_menu): ?>
    <a href="#" id="toggles" class="menu-toggle"><s class="bars"></s><s class="bars"></s><div class="element-invisible">toggle</div></a>
    <div class="pure-menu pure-menu-horizontal menu-transform" role="navigation" aria-label="Menu">
      <div class="element-invisible"><?php print t('Main menu'); ?></div>
      <?php print theme('links__system_main_menu', array('links' => menu_tree(variable_get('menu_main_links_source', 'main-menu')))); ?>
    </div>
  <?php endif; ?>
  </div>
</div>
</div>

</div></div></div></div></div>

<div id="body_bg">
<div class="sizer0 clearfix"<?php print wrapper_width() ?>>
<div id="body_left">
<div id="body_right">

<?php if ($secondary_menu): ?>
  <div role="navigation" aria-label="Submenu">
  <?php print theme('links', array(
    'links' => $secondary_menu,
    'attributes' => array(
      'id' => 'submenu',
      'class' => array('links', 'clearfix'),
    ),
    'heading' => array(
      'text' => t('Secondary menu'),
      'level' => 'div',
      'class' => array('element-invisible'),
    ),
  )); ?>
  </div>
<?php endif; ?>

<?php
  if (!function_exists('_themezp_lang_url')) {
    function _themezp_lang_url($lang) {
      $uo = drupal_parse_url(url(current_path()));
      $uo['query']['lang'] = $lang;
      return url($uo['path'], $uo);
    }
  }
  $thzplangs = array('zh-hans', "简体中文", 'zh-hant', "繁體中文", 'en', "English");
?>
<table border=0><tr><td>
  <div id="breadcrumb" class="clearfix"><?php print $breadcrumb; ?></div>
</td><td></td><td align="right" style="padding-right: 32px;">
  <?php for($i=0;$i<count($thzplangs);$i+=2) { $alang=$thzplangs[$i]; $atitle=$thzplangs[$i+1]; ?>
    <a href="<?php echo _themezp_lang_url($alang);?>" title="<?php echo $atitle;?>">
      <img src="/sites/all/modules/languageicons/flags/<?php echo $alang;?>.png" width="24" height="18"
        alt="<?php echo $atitle;?>" title="<?php echo $atitle;?>" /></a>
  <?php unset($alang); unset($atitle); } ?>
  </td></tr></table>
<?php print dev_link() ?>

<?php if (theme_get_setting('slideshow_display')): ?>
  <?php if ($is_front || theme_get_setting('slideshow_all')): ?>
    <?php include_once 'slider.php'; ?>
  <?php endif; ?>
<?php endif; ?>

<div class="clearfix">

<?php if($page['user1'] || $page['user2'] || $page['user3'] || $page['user4']) : ?>
<div id="section1" class="sections pure-g" role="complementary">
<?php if($page['user1']) : ?><div class="<?php print section_class($page); ?>"><div class="u1"><?php print render ($page['user1']); ?></div></div><?php endif; ?>
<?php if($page['user2']) : ?><div class="<?php print section_class($page); ?>"><div class="u2 <?php print divider() ?>"><?php print render ($page['user2']); ?></div></div><?php endif; ?>
<?php if($page['user3']) : ?><div class="<?php print section_class($page); ?>"><div class="u3 <?php print divider() ?>"><?php print render ($page['user3']); ?></div></div><?php endif; ?>
<?php if($page['user4']) : ?><div class="<?php print section_class($page); ?>"><div class="u4 <?php print divider() ?>"><?php print render ($page['user4']); ?></div></div><?php endif; ?>
</div>
<?php endif; ?>

<div id="middlecontainer" class="pure-g">
<?php if ($page['sidebar_first']) { ?>
  <div class="<?php print first_class(); ?>">
    <div id="sidebar-left" role="complementary"><?php print render($page['sidebar_first']); ?></div>
  </div>
<?php } ?>
  <div class="<?php print cont_class($page); ?>">
    <div id="main" role="main">
      <?php if ($page['highlighted']): ?><div id="mission"><?php print render ($page['highlighted']); ?></div><?php endif; ?>
      <?php print render($title_prefix); ?>
      <?php if ($title): if ($is_front){ print '<h2 class="title">'. $title .'</h2>'; } else { print '<h1 class="title">'. $title .'</h1>'; } endif; ?>
      <?php print render($title_suffix); ?>
      <div class="tabs"><?php print render($tabs); ?></div>
      <?php print render($page['help']); ?>
      <?php print $messages ?>
      <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
      <?php if ($page['content']) : ?><?php print render ($page['content']); ?><?php endif; ?>
      <?php print $feed_icons; ?>
    </div>
  </div>
<?php if ($page['sidebar_second']) { ?>
  <div class="<?php print second_class(); ?>">
    <div id="sidebar-right" role="complementary"><?php print render($page['sidebar_second']); ?></div>
  </div>
<?php } ?>
</div>
</div>

<?php if($page['user5'] || $page['user6'] || $page['user7'] || $page['user8']) : ?>
<div id="section2" class="sections pure-g" role="complementary">
<?php if($page['user5']) : ?><div class="<?php print section_class($page, false); ?>"><div class="u1"><?php print render ($page['user5']); ?></div></div><?php endif; ?>
<?php if($page['user6']) : ?><div class="<?php print section_class($page, false); ?>"><div class="u2 <?php print divider() ?>"><?php print render ($page['user6']); ?></div></div><?php endif; ?>
<?php if($page['user7']) : ?><div class="<?php print section_class($page, false); ?>"><div class="u3 <?php print divider() ?>"><?php print render ($page['user7']); ?></div></div><?php endif; ?>
<?php if($page['user8']) : ?><div class="<?php print section_class($page, false); ?>"><div class="u4 <?php print divider() ?>"><?php print render ($page['user8']); ?></div></div><?php endif; ?>
</div>
<?php endif; ?>

<?php if (($main_menu) && theme_get_setting('menu2')): ?>
  <div role="navigation" aria-label="Menu 2">
  <?php print theme('links', array(
    'links' => $main_menu,
    'attributes' => array(
      'id' => 'menu2',
      'class' => array('links', 'clearfix'),
    ),
    'heading' => array(
      'text' => t('Main menu'),
      'level' => 'div',
      'class' => array('element-invisible'),
    ),)); ?>
    </div>
<?php endif; ?>

</div></div></div></div>

<div id="bottom_bg">
<div class="sizer0 clearfix"<?php print wrapper_width() ?>>
<div id="bottom_left">
<div id="bottom_right">

<div id="footer" class="pure-g" role="contentinfo">
<div class="<?php print resp_class(); ?>1-5"><?php if (theme_get_setting('social_links_display')): ?><div id="soclinks"><?php print social_links(); ?></div><?php endif; ?></div>
<div class="<?php print resp_class(); ?>3-5"><?php if ($page['footer']): ?><?php print render ($page['footer']); ?><?php endif; ?></div>
<div class="<?php print resp_class(); ?>1-5"></div>
</div>
<div id="brand"></div>

</div></div></div></div></div>
