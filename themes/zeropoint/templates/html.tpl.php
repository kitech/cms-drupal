<!DOCTYPE html>
<!--[if IEMobile 7]><html class="iem7" <?php print $html_attributes; ?>><![endif]-->
<!--[if lte IE 6]><html class="lt-ie9 lt-ie8 lt-ie7" <?php print $html_attributes; ?>><![endif]-->
<!--[if (IE 7)&(!IEMobile)]><html class="lt-ie9 lt-ie8" <?php print $html_attributes; ?>><![endif]-->
<!--[if IE 8]><html class="lt-ie9" <?php print $html_attributes; ?>><![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)]><!--><html<?php print $html_attributes . $rdf_namespaces; ?>><!--<![endif]-->

<title><?php print $head_title ?></title>
<meta property="ver" content="zp7-3.1"/>
    <meta name="Keywords" content="Qt5,C/C++,Linux,PHP,Web架构,高性能,分布式,NullGet,WebNullGet,qtchina,Qt,下载软件,多线程下载软件,多协议下载软件,MMS下载,RTSP下载,HTTP下载,HTTPS下载,FTP下载,分块下载,在线视频下载软件，流媒体下载软件,Qt 4,China,Qt 4 Solution,Qt 4 解决方案" />
    <meta name="robots" content="all" />
    <meta name="googlebot" content="all" />

<?php print $head ?>
<?php print $styles ?>
<?php print $scripts ?>
</head>

<body id="<?php print $body_id; ?>" class="<?php print $classes; ?>" <?php print $attributes;?>>
<div id="skip-nav"><a href="#main"><?php print t('Jump to Navigation'); ?></a></div>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
  <?php print $page_b; ?>

</body>
</html>