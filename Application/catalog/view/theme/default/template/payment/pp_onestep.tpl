<html>
<head>
<style>
	.center{width:300px;margin:0 auto; margin-top:10%;}
</style>
</head>
<body>
You will be redirected to the PayPal website in a few seconds.<br/>

<a href="<?php echo $to_url; ?>">Click here if you are not redirected within 10 seconds... </a>
<script>
    window.location.href = "<?php echo $to_url;?>" ;
</script>
<div class="center"><img src="css/images/lodding.gif"  /></div>
</body>
</html>

