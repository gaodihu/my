<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="">
<title><?php echo $title;?></title>
<base href="<?php echo $this->config->item('base_url');?>">
<meta name="description" content="<?php echo $description;?>" />
<meta name="keywords" content="<?php echo $keywords;?>" />
<?php 
$css_array =array('css/public.css','css/banner.css');
foreach($css_array as $href){
    $time =filemtime($href);
    if($time){
        $href = $href."?v=".$time;
    }
?>
<link rel="stylesheet" href="<?php echo $href;?>" type="text/css">
<?php
}
?>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/banner.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42585019-1', 'auto');
  ga('send', 'pageview');
  </script>
</head>
<body>
    <div class="wrap">
            <div class="head">
                <!-- <div class="serch"><input type="text" value="" placeholder="Search" class="serchinp"/><input class="serchbtn" type="submit" value=""></div> -->
                <div class="logo"><a href="/guideline/"><img src="./images/public/logo.jpg" width="302" height="53" /></a></div>
            </div>

            <ul class="nav">
                <li><a href='/guideline/'> <!-- <img src="images/public/home.jpg"  /> --> HOME</a></li> 
				<li>
					<a class="more-link" href='applications.html'>APPLICATION</a>
					<div class="nav-apl">
                        <?php foreach($all_app_info as $all_cata){ ?>
						<a href="applications/c/<?php echo $all_cata['url_path'];?>.html"><?php echo $all_cata['catagory_name'];?></a>
                        <?php } ?>
					</div>
					<div class="nav-apl-link">
                    <?php foreach($all_app_info as $all_cata){ ?>
						<div class="nav-box">
							<b><?php echo $all_cata['catagory_name'];?></b>
                            <?php foreach($all_cata['child'] as $child_cata){ ?>
							<a href="applications/c/<?php echo $child_cata['url_path'];?>.html"><?php echo $child_cata['catagory_name'];?></a>
                            <?php }  ?>
						
						</div>
                        <?php } ?>
					</div>
				</li>
                <li><a href='https://www.myled.com/'>STORE</a></li>
                <li><a href='guideline.html'>GUIDELINE</a></li>
                <li><a href='https://www.myled.com/ledblog/'>BLOG</a></li>
                <li><a href="faq.html">FAQ</a></li>
            </ul>