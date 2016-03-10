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
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo STATIC_SERVER; ?>mobile/view/stylesheet/base.css" >

  <script type="text/javascript" src="<?php echo STATIC_SERVER; ?>mobile/view/js/jquery.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo STATIC_SERVER . $script; ?>"></script>
<?php } ?>
</head>



