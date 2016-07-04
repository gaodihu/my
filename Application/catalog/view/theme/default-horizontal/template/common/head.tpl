<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo trim($description); ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo trim($keywords); ?>" />
<?php } ?>

<meta id="ogtitle" property="og:title" content="<?php echo trim($title); ?>"/>
<?php if ($description) { ?>
<meta id="ogdescription" property="og:description" content="<?php echo trim($description); ?>"/>
<?php } ?>
<meta property="og:site_name" content="<?php echo $config_name; ?>"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="<?php echo rtrim($server,'/').$_SERVER['REQUEST_URI'];?>"/>
<meta property="og:local" content="<?php echo $this->session->data['language'];?>"/>

    <?php if($alternate){ ?>
    <?php foreach($alternate as $a){ ?>
<link rel="alternate" hreflang="<?php echo $a['lang']; ?>" href="<?php echo $a['url']; ?>" />
    <?php } ?>
    <?php } ?>

<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<base href="<?php echo $base; ?>" />
<?php foreach ($links as $link) { ?>
<link href="<?php echo STATIC_SERVER.$link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>

<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo STATIC_SERVER.$style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>


<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/jquery/jquery.js"></script>

<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/jquery/ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/jquery/jquery.autocomplete.js"></script>

<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/jquery/jquery.countdown.js?v=1408422260"></script>


<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/common.js?v20150701"></script>

<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/cart.js"></script>
<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/mzp-packed/mzp-packed.js"></script>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo STATIC_SERVER.$script; ?>"></script>
<?php } ?>

<script type="text/javascript" src="<?php echo STATIC_SERVER; ?>js/jquery/jquery.lazyload.js"></script>

<?php echo $google_analytics; ?>

</head>
