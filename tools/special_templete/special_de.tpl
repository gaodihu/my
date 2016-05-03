<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $header_title;?></title>
<meta name="description" content="<?php echo $header_desc;?>"/>
<meta name="keywords" content="<?php echo $header_keyword;?>" />
<script type="text/javascript" src="http://www.myled.com/catalog/view/javascript/jquery/jquery.js"></script>
<script type="text/javascript" src="http://www.myled.com/catalog/view/javascript/jquery/jquery.cookie.js"></script>
<style>
    @charset "utf-8";
    /* CSS Document */
    body{ margin:0; font: normal 12px/24px "Arial";color:#333;}
    ul,li{ list-style:none;}
    menu,nav,figure,figcaption,header,footer,section,article,img{ display:block}
    dl,dt,dd,ul,li,h1,h2,h3,h4,h5,menu,nav,figure,figcaption,header,footer,section,article,aside,p,input,img{ margin:0; padding:0;}
    a{text-decoration: none;color: #333;cursor: pointer;}
    a:hover{text-decoration: none;color: #24aed5;cursor: pointer;}
    del{color: #666; font-size: 12px;}
    .clearfix:after{content:"\020"; display: block; height:0; clear:both; visibility:hidden}
    .clearfix{zoom:1;}
    .clear{ clear:both; height:0; content:" "; overflow:hidden; width:100%;}
    .page{ width:980px; margin: 0 auto;}
    .bgcolor{ background-color:#e6ecfa;}

    /*   header    */
    .header{ margin: 10px 0; height: 70px;}
    .header .logo{background: url("/special_templete/images/offimg/logo.jpg") no-repeat; width:141px; height:57px; margin-top:20px;  }
    .header ul{ float:right; margin-top: 30px; margin-right:30px;}
    .header ul li{ float: left; margin: 0 10px; font-weight: bold}
    .header ul li:last-child{ margin-right: 0px;}
    .query-input{ width: 278px;height: 25px; border: 1px solid #ccc; float: right; padding: 5px 10px;margin-top: 15px; position:relative}
    .query-input input{border: 0;width: 240px;height: 25px; margin-right: 10px; font-family:Arial, Helvetica, sans-serif; outline:none;
}
    .query-input .serch-btn{ cursor: pointer; border: 0; width: 26px;height: 18px; background: url("/special_templete/images/offimg/serbtn.jpg") center no-repeat!important; display: inline-block;  top: 10px; position: absolute; left:270px; float:right; outline:none;
}

    /* footer */
    footer{ text-align: left;  padding-left:15px; padding-bottom: 50px; margin: 0 auto; background: url("/special_templete/images/offimg/support.jpg") top  no-repeat; border-top: 1px solid #e9e9e9; padding-top: 85px;  }
    .footbanner{ padding: 10px 0;}

    /* banner */
    .banner{ height: 330px; width:100%; margin: 0 auto; background:url(../images/banner.jpg) center center;}
	.banner2{margin:0px auto; width:1020px; height:330px;}

    /* content */
    .content{background: #fff; padding-top: 10px;}
    .content .tit{ background: url("/special_templete/images/offimg/titlebg.png") center center; font-size: 20px; text-align: center; color: #fff; position: relative; width: 1000px; height: 42px; padding-top: 8px; left: -10px;}
    .content .throw-row li{ border: 1px dashed #d0d0d0; width: 220px; line-height: 18px; padding-bottom:15px; float: left; margin-left: 18px; margin-bottom: 18px;}
    .content .throw-row img{ width: 210px; margin: 5px auto; }
    .content .throw-row span, .price{ font-size: 20px; color: #d10000; padding-right: 10px;}
    .content .throw-row{ margin: 10px 15px 10px 0; text-align: center;}
    .content .throw-row p{ margin: 5px; }
    .content .throw-row .btn{ width: 170px; display: inline-block; height: 28px; color: #fff;  font-size: 14px;
        line-height: 28px; border-radius: 5px; background: #3080ff;
    }
    .content .four-row li{ width: 220px; }
	del{color: #666; font-size: 12px; margin-left:12px;}
	.name{height:35px; text-align: center; padding:0 8px; text-overflow: -o-ellipsis-lastline;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;}

    /* footerup */
    .footerup{width:980px; height:160px; margin-bottom: 20px; }
    .about{width:150px; float:left; margin-left:12px; margin-top:10px;}
    .about h2{line-height:30px; font-weight:bold; font-size:12px;}
    .about p a{color:#666666; line-height:20px;}
    .about a:hover{text-decoration:none;}
    .share{width:180px; float:left; margin-left:12px; margin-top:10px;}
    .share p{ font-size: 15px; font-weight: bold;}
    .shareup{width:180px; margin-top:10px;}
    .shareup a{text-decoration:none; margin-left:4px;}
    .sharedown h3{color:#ffae01; height:22px; line-height:22px; font-size:12px; font-weight:normal; margin-top:5px;}
    .sharedown p{color:#cccccc; line-height:16px;}
    .sub #newslettedr{width:128px; height:29px; background-color:#000000; border:none; line-height:29px; font-size:12px; color:#666666; padding:0 5px;}
    .sub a{margin-left:3px;}
    .footerdown p{color:#666666; text-align:center; height:40px; line-height:40px; margin-bottom:20px; font-size:12px;}
    #buttom{border:none; background:none; margin-left:-10px;}


    /* share */
    .share{width:180px; float:left; margin-left:12px; margin-top:10px;}
    .shareup{width:180px; margin-top:10px;}
    .shareup a{text-decoration:none; margin-left:4px;}
    .shareup img{ display: inline-block;}
    .sharedown h3{color:#ffae01; height:22px; line-height:22px; font-size:12px; font-weight:normal; margin-top:5px;}
    .sharedown p{color:#cccccc; line-height:16px;}
	.clear{clear:both;}
	.products{height:auto; width:980px; margin:0 auto; background-color:#FFF;}
.products h2{font-size:16px; font-weight:bold; line-height:40px; color:#333; text-align:center; background:url(images/bt.jpg) no-repeat left top; height:58px;}
.products ul{display:block; padding:5px 0px;}
.products ul li{width:230px; height:310px;  float:left; margin-left:10px; margin-top:12px; border:1px dashed #c9c9c9;}
.products li img{padding:3px 8px;}
.products li .name{color:#565656; height:36px; line-height:18px; text-align:center; overflow:hidden; display:block; padding:0 8px;}
.products li .price{font-size:24px; color:#d90101; text-align:center; height:24px; line-height:24px; margin:8px auto;}
.yj{font-size:12px; color:#888888; text-decoration:line-through; padding-left:8px;}
.products li .buy{width:119px; height:36px; margin:5px auto; background: url('../images/get.jpg') center center no-repeat; display:block; text-align:center; color:#FFF; font-size:14px; line-height:30px; text-decoration:none;}
.coupon{padding-top:15px; color:#ff0000; font-size:15px; line-height:32px; padding-left:18px;}
.coupon .wz{float:left; padding-right:10px; font-size:18px; color:#000;}
.coupon .hs{float:left; width:107px; height:31px; background:url('/special_templete/images/wzbj.jpg') no-repeat left top; line-height:29px; text-align:center; font-size:22px; font-weight:bold;}
.coupon2{font-size:15px; line-height:24px; padding-top:5px; padding-left:18px;}
</style>
</head>
<body>
<div class="page">
			<div  class="header">
				<div class="query-input">
					<input id="search" type="text" name="search" value=""   placeholder="Enter keyword or item number"/>
					<button class="serch-btn" id="search_button"  type="button"/></button>
				</div>
				<ul>
					<li><a href="http://de.myled.com/new_arrivals.html">Neuheiten</a></li>
					<li>|</li>
					<li><a href="http://de.myled.com/top-sellers.html">Bestsellern</a></li>
					<li>|</li>
					<li><a href="http://de.myled.com/deals.html">Sonderangebote</a></li>
					<li>|</li>
					<li><a href="http://de.myled.com/clearance.html">Ausverkauf</a></li>
				</ul>
				<a href="http://de.myled.com/" alt="MyLED.com" target="_blank"><div class="logo"></div></a>
			</div>
		</div>
<div class="banner">
			<div class="banner2"><img src="<?php echo $top_banner['img'];?>" alt="<?php echo $top_banner['title'];?>" height="330" /></div>
</div>
<div class="bgcolor"> 
   <div class="products">
 	<ul>
	<?php foreach($products_info as $pro1){ ?>
	<?php foreach($pro1 as $key=>$value){ ?>
	<?php if($key==0){
		echo '<li class="first">';
	}
	else{
		echo '<li>';
	}
	?>
  					<a href="http://de.myled.com/<?php echo $value['url'];?>"><img src="<?php echo $value['img'];?>"></a> 
					<a class="name" href="http://de.myled.com/<?php echo $value['url'];?>"><?php echo $value['name'];?></a>

					<p class="price"><?php echo $value['special_price'];?>€<span class="yj"><?php echo $value['price'];?>€</span></p>
					<a class="buy" href="http://de.myled.com/<?php echo $value['url'];?>">Jetzt kaufen</a>
		</li>	
	<?php } ?>
	<?php } ?>
	 </ul>
</div>
</div>
<div class="clear"></div>
            <div class="footbanner page"> <a href="<?php echo $foot_banner['link'];?>"><img src="<?php echo $foot_banner['img'];?>" alt="<?php echo $foot_banner['title'];?>"></a>
            </div>
        </div>
	
 <div class="footerup page">
        <div class="about"><h2>Info der Firma</h2><p><a href="http://de.myled.com/about-us.html" target="_blank">Über uns</a></p><p><a href="http://de.myled.com/contact-us.html" target="_blank">Kontaktieren Sie uns</a></p></div>
        <div class="about"><h2>Kundenservice-Anliegen</h2><p><a href="http://de.myled.com/accepted-payment-methods.html" target="_blank">Angenommenen Zahlungsmethode</a></p><p><a href="http://de.myled.com/return-policy.html" target="_blank">Rücknahmegarantie</a></p><p><a href="http://de.myled.com/secure-shopping.html" target="_blank">Sicher Einkaufen</a></p><p><a href="http://de.myled.com/shipping-methods.html">Versandarten</a></p></div>
        <div class="about"><h2>Wichtige</h2><p><a href="http://de.myled.com/privacy-policy.html" target="_blank">Datenschutzrichtlinie</a></p><p><a href="http://de.myled.com/terms-and-conditions.html" target="_blank">Verkaufs - und <br>Lieferbedingungen</a></p><p><a href="http://de.myled.com/tax-policy.html" target="_blank">Zollkosten</a></p></div>
        <div class="about"><h2>Partnerschaft</h2><p><a href="http://de.myled.com/affiliate-marketing.html" target="_blank">Affiliate-Marketing</a></p><p><a href="http://de.myled.com/affiliates-terms-of-use.html" target="_blank">Nutzungsbedingungen</a></p></div>
        <div class="share">
            <p>Fügen Sie unsere Gemeinschaft hinzu</p>
            <div class="shareup">
                <a href="http://www.facebook.com/myledcom" target="_blank">
                    <img src="/special_templete/images/share/f.png" width="32" height="32" border="0" />
                </a>
                <a href="http://www.twitter.com/myledcom" target="_blank">
                    <img src="/special_templete/images/share/t.png" width="32" height="32" border="0" />
                </a>
                <a href="http://vk.com/myledcom" target="_blank">
                    <img src="/special_templete/images/share/v.png" width="32" height="32" border="0" />
                </a>
                <a href="http://www.pinterest.com/myledcom" target="_blank">
                    <img src="/special_templete/images/share/p.png" width="32" height="32" border="0" />
                </a>
            </div>
        </div>
    </div>
        <!-- footer -->
        <footer>
            <div class="page">das Urheberrecht © 2014 Hong Kong MyLed Gruppe Material mit beschränkter Haftung. Alle Rechte vorbehalten.</div>
        </footer>
</div>
<script type="text/javascript">
/* 点击回车跳转 */
$(document).ready(function () {
	var $inp = $('#search'); //所有的input元素
	$inp.keypress(function (e) { //这里给function一个事件参数命名为e，叫event也行，随意的，e就是IE窗口发生的事件。
		var key = e.which; //e.which是按键的值
		if (key == 13) {
			url =  'http://de.myled.com/index.php?route=product/search';

			var search = $('input[name=\'search\']').attr('value');

			if (search) {
				url += '&search=' + encodeURIComponent(search);
			}

			window.location.href = url;
		}
	});
})	
$('#search_button').click(function () {
	url = 'http://de.myled.com/index.php?route=product/search';

	var search = $('input[name=\'search\']').attr('value');

	if (search) {
		url += '&search=' + encodeURIComponent(search);
	}

	window.location.href = url;
  })
</script>
<script>
	// 获取路径参数数据
	function getUrlParams()
	{
		//var search = "http://www.myle123/p2223-10w-dc12v-strobe-and-blasting-light-led-car-light.html?ssid=506edX?ssids=506edsX";
		var search = window.location.search ;
		// 写入数据字典
		var tmparray = search.substr(1,search.length).split("?");
		var paramsArray = new Array;
		if( tmparray != null)
		{
			for(var i = 0;i<tmparray.length;i++)
			{
				var reg = /[=|^==]/;    // 用=进行拆分，但不包括==
				var set1 = tmparray[i].replace(reg,'?');
				var tmpStr2 = set1.split('?');
				var array = new Array ;
				array[tmpStr2[0]] = tmpStr2[1] ;
				paramsArray.push(array);
			}
		}
		// 将参数数组进行返回
		return paramsArray ;    
	}

	// 根据参数名称获取参数值
	function getParamValue(name)
	{
		var paramsArray = getUrlParams();
		if(paramsArray != null)
		{
			for(var i = 0 ; i < paramsArray.length ; i ++ )
			{
				for(var  j in paramsArray[i] )
				{
					if( j == name )
					{
						return paramsArray[i][j] ;
					}
				}
			}
		}
		return null ;
	}
	// 匹配字符串   返回 -1 表示不存在
	function getStringCheck(str)
	{
		 var search = document.referrer  ;
	     var s = search.indexOf(str);
	     return(s);
	}

	$(function(){
	
		if(getParamValue("source") == "webgains"){
			$.cookie("source","webgains",{expires:60,path:"/",domain: "myled.com",secure: false});
		}else if(getParamValue("ssid") == "506edX"){
			$.cookie("source","shareasale",{expires:60,path:"/",domain: "myled.com",secure: false});
		}else if(getParamValue("network") == "adcellled"){
			$.cookie("source","adcellled",{expires:60,path:"/",domain: "myled.com",secure: false});
		}else if(getParamValue("source") == "tdr"){
			$.cookie("source","tdr",{expires:60,path:"/",domain: "myled.com",secure: false});
		}else if(getParamValue("utm_source") == "EDM"){
			$.cookie("source","EDM",{expires:60,path:"/",domain: "myled.com",secure: false});
		}
		if(getStringCheck("myled.com") == -1){
			if(getParamValue("source") != "webgains" && getParamValue("source") != "tdr" && ($.cookie("source")=='webgains'||$.cookie("source")=='tdr')){
				$.cookie("source",null,{expires:-1,path:"/",domain: "myled.com",secure: false});
			}else if(getParamValue("ssid") != "506edX" && $.cookie("source")=='shareasale'){
				$.cookie("source",null,{expires:-1,path:"/",domain: "myled.com",secure: false});
			}else if(getParamValue("network") != "adcellled" && $.cookie("source")=='adcellled'){
				$.cookie("source",null,{expires:-1,path:"/",domain: "myled.com",secure: false});
			}else if(getParamValue("utm_source") == "EDM" && $.cookie("source")=='EDM' ){
				$.cookie("source",null,{expires:-1,path:"/",domain: "myled.com",secure: false});
			}	
		}
		
	}); 
	

			
	</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42585019-1', 'myled.com');
  ga('send', 'pageview');

</script>
<script type="text/javascript">
	var google_tag_params = {
	ecomm_prodid : '', // product's id
	ecomm_pname : '', //  product's name
	ecomm_pcat : '', // product's category
	ecomm_pvalue : '', // price of each product in the cart
	ecomm_pagetype : 'home', // home, product, category, cart, purchase
	ecomm_language : 'en', //{en, fr, es, ....}
	ecomm_currency : 'USD', //currency
	ecomm_ordervalue : '', // filled with the order value[EF] after user purchases
	ecomm_usertype : 'unregistered',//registered, unregistered
	ecomm_gender : '', // user's gender( male, female) available after login
	ecomm_action :'', //favorite,review,question
	};
</script>

<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 982948913;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<div style='height:0;overflow:hidden'>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
</div>
<noscript>
	<div style="display:inline;">
	<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/982948913/?value=0&amp;guid=ON&amp;script=0"/>
	</div>
</body>
</html>
