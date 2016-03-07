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
<link rel="stylesheet" type="text/css" href="mobile/view/stylesheet/base.css" >
<?php if($this->session->data['language'] == 'EN'){?>
<meta name="google-translate-customization" content="4f4518a676db6689-7f99473be5215457-ge010d236a6190745-d"></meta>
<?php } ?>
<?php if(isset($order_info['payment_code'])&&$order_info['payment_code']!='bank_transfer'){ ?>
<?php if(isset($cj_status)&&$cj_status){ ?>
<script>
var MasterTmsUdo = {
'CJ' : {
'CID': '1531960',
'TYPE': '375699',
'DISCOUNT' : "<?php echo $order_info['discount_amount']; ?>",
'OID': "<?php echo $order_info['order_number']; ?>",
'CURRENCY' : "<?php echo $order_info['currency_code']; ?>",
'COUPON' : "<?php echo $order_info['coupon_code']; ?>",
<?php if(isset($_COOKIE['source'])&&$_COOKIE['source']=='CJled') { ?>
'FIRECJ' : 'TRUE',
<?php }else{ ?>
'FIRECJ' : 'FALSE',
<?php } ?>

PRODUCTLIST : [
	<?php
	$count =count($order_product_info);
	foreach($order_product_info as $count_key=>$product){ ?>
		{ 'ITEM' : "<?php echo $product['model'];?>",
		'AMT' : "<?php echo $product['price'];?>",
		'QTY' : "<?php echo $product['quantity'];?>"
		}<?php  if($count_key+1 !=$count){ ?>,<?php  } ?>
	<?php }  ?>
]
} };
</script>
<script>
(function(e){var t="1617",n=document,r,i,s={http:"http://cdn.mplxtms.com/s/MasterTMS.min.js",https:"https://secure-cdn.mplxtms.com/s/MasterTMS.min.js"},o=s[/\w+/.exec(window.location.protocol)[0]];i=n.createElement("script"),i.type="text/javascript",i.async=!0,i.src=o+"#"+t,r=n.getElementsByTagName("script")[0],r.parentNode.insertBefore(i,r),i.readyState?i.onreadystatechange=function(){if(i.readyState==="loaded"||i.readyState==="complete")i.onreadystatechange=null}:i.onload=function(){try{e()}catch(t){}}})(function(){});</script>
<?php } ?>
<?php } ?>




<script type="text/javascript" src="mobile/view/js/jquery.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
</head>

 <?php
 if($this->request->get['route']!=='checkout/success'&&$this->request->get['route']!=='checkout/fail'){ ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42585019-1', 'auto');
  ga('require', 'displayfeatures');
  <?php  if($this->session->data['customer_id']){ ?>
  ga('set', '&uid', "<?php echo $this->session->data['customer_id'];?>");
  <?php  }  ?>
  ga('send', 'pageview');
</script>
<?php } ?>

