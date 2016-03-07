<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="">
	<title><?php echo $page_title; ?></title>
	<meta name="description" content="<?php echo $page_description; ?>" />
	<meta name="keywords" content="<?php echo $page_keyword; ?>" />
	<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.js"></script>
	<script type="text/javascript" src="catalog/view/javascript/jquery/jQueryRotate.2.1.js"></script>

	<style>
		@charset "utf-8";
		/* CSS Document */
		body{ margin:0 auto;padding:0; font: normal 12px/24px "Arial";color:#333;}
		ul,li{ list-style:none;}
		menu,nav,figure,figcaption,header,footer,section,article{ display:block}
		dl,dt,dd,ul,li,h1,h2,h3,h4,h5,menu,nav,figure,figcaption,header,footer,section,article,aside,p,input,img{ margin:0; padding:0;border:0;}
		a{text-decoration: none;color: #333;cursor: pointer;}
		a:hover{text-decoration: none;color: #24aed5;cursor: pointer;}
		del{color: #666; font-size: 12px;}
		.clearfix:after{content:"\020"; display: block; height:0; clear:both; visibility:hidden}
		.clearfix{zoom:1;}
		.clear{ clear:both; height:0; content:" "; overflow:hidden; width:100%;}
		.wrap{ width:980px; margin: 0 auto;position:relative;}
		.bgcolor{ }
		.redbg{background:#d40000;}
		.c_title{color:#ae6112;font-size:16px;font-weight:bold;display:none;}
		.bg_ed7f88{background:#ed7f88; padding:10px 0;}
			/*   header    */
		.header{ margin: 10px 0; height: 70px;}
		.header .logo{background: url("https://www.myled.com//special_templete/images/offimg/logo.jpg") no-repeat; width:141px; height:57px; margin-top:20px;  }
		.header ul{ float:right; margin-top: 30px; margin-right:30px;}
		.header ul li{ float: left; margin: 0 10px; font-weight: bold}
		.header ul li:last-child{ margin-right: 0px;}
		.query-input{ width: 278px;height: 25px; border: 1px solid #ccc; float: right; padding: 5px 10px;margin-top: 15px; position:relative}
		.query-input input{border: 0;width: 240px;height: 25px; margin-right: 10px; font-family:Arial, Helvetica, sans-serif; outline:none;
}
		.query-input .serch-btn{ cursor: pointer; border: 0; width: 26px;height: 18px; background: url("/special_templete/images/offimg/serbtn.jpg") center no-repeat!important; display: inline-block;  top: 10px; position: absolute; left:270px; float:right; outline:none;
		}
		.content{height:667px;background:url("catalog/view/theme/default/images/activity/turntable/top_img.png") top center no-repeat;}
		.bottom_bg{background:url("catalog/view/theme/default/images/activity/turntable/bottom_img.png") bottom center no-repeat;}

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
		.right{float:right}
		.left{float:left}

			/* share */
		.share{width:180px; float:left; margin-left:12px; margin-top:10px;}
		.shareup{width:180px; margin-top:10px;}
		.shareup a{text-decoration:none; margin-left:4px;}
		.shareup img{ display: inline-block;}
		.sharedown h3{color:#ffae01; height:22px; line-height:22px; font-size:12px; font-weight:normal; margin-top:5px;}
		.sharedown p{color:#cccccc; line-height:16px;}

		.content .right{background:url("catalog/view/theme/default/images/activity/turntable/right_title.png") top center no-repeat;width:351px;min-height:360px;position:absolute;right:0;margin-top:360px;}
		.content .r_list{background:#fff0a7;width:301px;padding-left:20px;margin:0 auto;min-height:360px;height:360px;margin-top:24px;position:relative;left:-3px;font-size:15px;text-align:left;color:#703c0a;line-height:35px;overflow:hidden;}
		.content .r_btn{;width:351px;height:23px;overflow:hidden;background:url("catalog/view/theme/default/images/activity/turntable/right_bottom.png") bottom center  no-repeat;position:relative;left:-3px;}
		.content .left{position:relative;}

		.redbg h4{font-size:30px;color:#ffcc00;margin-bottom:15px;margin-top:150px;}
		.redbg li{font-size:18px;color:#fff;margin-bottom:15px;width:900px;overflow:hidden;}
		.redbg{padding:30px 0;}
		.redbg li i.left{width:25px;height:25px;background:#ffcc00;border-radius:30px;color:#753d07;display:inline-block;text-align:center;line-height:25px;}
		.redbg li div.right{width:865px;}


		.youhave{
			position:absolute;
			color:#fff;
			font-weight:bold;
			height:30px;
			line-height:30px;
			top:123px;
			left:195px;
			font-size:14px;
		}
		#zhuanpan{
			width:567px;
			height:600px;
			background:url("catalog/view/theme/default/images/activity/turntable/bg2.gif") top  center no-repeat;
			top:235px;

			position:absolute;
			overflow:hidden;
			z-index:1;

		}
		#tip{position:absolute;left:183px;;top:177px;cursor:pointer;z-index:100;}
		#img{position:absolute;left:28px;;top:30px;z-index:10;}
		footer{text-align:center;}
		.bottom_line{position:absolute;bottom:0;left:150px;}

		.start_pop.pop{border:5px solid #e44856;border-radius:10px; width:670px;padding:10px 0px 30px 0px;background:#fff;position:fixed; top:20%;z-index:1005; text-align:center;display:none;margin-left:-335px;left:50%}
		.start_pop.pop .info1{font-size:20px; color:#222;padding-top:10px;}
		.start_pop.pop .info2{font-size:13px; padding:10px 0;color:#222;}
		.start_pop.pop h5{font-size:36px; color:#f22f2f;line-height:40px;padding:10px 0;}
		.start_pop.pop span{ color:#e44856;}
		.start_pop.pop a{ color:#209bce;}
		.start_pop.pop .close{background: url("catalog/view/theme/default/images/activity/turntable/close.png") center no-repeat; display:inline-block;width:15px; height:15px;float:right;cursor:pointer;margin-right:10px;}
		.grey-bg{ background-color: #333; filter:alpha(opacity=50); opacity:0.5; z-index:1004; position:fixed; width: 100%;height: 100%;top:0;left:0;display:none;}
		#win_info {text-align:center;padding:5px;}
		.start_pop .p-code{text-align:left;width:390px;margin:0px auto 10px auto;}
		.start_pop .p-code .mail{height:35px;line-height:35px; border:1px solid #ccc;padding-left:10px;float:left;width:250px;border-top-left-radius:5px;border-bottom-left-radius:5px;font-size:13px;}
		.start_pop .p-code .p-title{color:#222; font-size:16px; padding:5px 0;color:#fff;}
		.start_pop .p-code .enter{background:#dd0200;height:37px;padding:0 20px; font-size:16px; color:#fff; float:left;border-top-right-radius:5px;
border-bottom-right-radius:5px;cursor:pointer;}
		.start_pop .p-code .mail_error{color:#ff0000;font-size:13px; padding-top:10px;display:none;}
		.red_border{border:1px solid #ed1300!important;}
		.close_link{position:relative;top:15px;font-size:16px;text-decoration:underline!important;font-weight:bold;}

		#tryagain.pop{border:5px solid #e44856; width:550px;padding:10px 10px 30px 10px;background:#fff;position:fixed;left:29%; top:30%;z-index:1005; text-align:center;display:none;}
		#tryagain.pop .info1{font-size:18px; color:#222;padding-top:10px;}
		#tryagain.pop .info2{font-size:13px; color:#666;padding:10px 0;}
		#tryagain.pop h5{font-size:32px; color:#222;line-height:40px;padding:10px 0;}
		#tryagain.pop span{ color:#e44856;}
		#tryagain.pop a{ color:#209bce;}
		#tryagain.pop .close{background: url("/catalog/view/theme/default/images/activity/easter/close.png") center no-repeat; display:inline-block;width:15px; height:15px;float:right;cursor:pointer;}

		#tryagain .p-code{text-align:left;margin-left:20px;}
		#tryagain .p-code .code-input{height:35px;line-height:35px; border:1px solid #ccc;padding-left:10px;float:left;width:250px;}
		#tryagain .p-code .p-title{color:#222; font-size:18px; line-height:30px;padding-bottom:10px;}
		#tryagain .p-code .enter{background:#e44856;border-radius:10px;height:35px;padding:0 20px; font-size:22px; color:#fff; margin-left:10px; float:left;cursor:pointer;}
		#tryagain .p-code .error{color:#ff0000;font-size:13px; padding-top:10px;}
		.title_h4{font-size:30px;color:#e10000;text-align:center;}
		#message .info1{padding:10px;}
	</style>
</head>

<body>








<div class="grey-bg"></div>
<div id="getemail" class="pop start_pop">
	<span class="close"></span>
	<div class="clear"></div>
	<p class="info1"><?php echo $text_sucess_01; ?></p>
	<h5><?php echo $text_sucess_02; ?></h5>
	<div id="win_info"></div>

	<!--<p class="info2" style="display:none;"><?php echo $text_sucess_03; ?></p>-->
	<div class="bg_ed7f88">
		<div class="p-code">
			<div class="p-title"><?php echo $text_sucess_04;?> </div>
			<div class="clearfix mail_input"><input name="email" type="text" value=""  class="mail" placeholder="<?php echo $text_05; ?>"/><input type="submit" value="<?php echo $text_06; ?>" class="enter" id="mailsend"/></div>
			<div class="mail_error"></div>
		</div>
	</div>
	<a  class="close_link" dom="try"><?php echo $text_07; ?></a>
</div>

<div id="message" class="pop start_pop">
	<span class="close"></span>
	<div class="clear"></div>
	<p class="info1">wetwetwet</p>
	<a  class="close_link" dom="try"><?php echo $text_07; ?></a>

</div>


<div id="tryagain" class="pop tryagain" style="display: none;">
	<span class="close"></span>
	<div class="clear"></div>
	<div class="p-code">
		<div class="p-title"><?php echo $text_08; ?> </div>
		<div><input type="text" class="code-input" name="order_number" value=""><input type="button" class="enter" value="<?php echo $text_06; ?>" id="trybyorder"></div>
		<div class="clear"></div>
		<div id="order_error" class="error"></div>
	</div>
</div>

<!-- header -->
<div class="wrap">
	<div  class="header">
		<div class="query-input">
			<input id="search" type="text" name="search" value=""   placeholder="Enter keyword or item number"/>
			<button class="serch-btn" id="search_button"  type="button"/></button>
		</div>
		<ul>
			<li><a href="/new_arrivals.html"><?php echo $text_new_arrives; ?></a></li>
			<li>|</li>
			<li><a href="/top-sellers.html"><?php echo $text_top_sellers; ?></a></li>
			<li>|</li>
			<li><a href="/deals.html"><?php echo $text_deals; ?></a></li>
			<li>|</li>
			<li><a href="/clearance.html"><?php echo $text_clearance; ?></a></li>
		</ul>
		<a href="/" target="_blank"><div class="logo"></div></a>
	</div>
</div>

<!-- content -->
<div class="bottom_bg">
	<div class="content">
		<div class="wrap">
			<div class="left">
				<div class="banner">
					<img src="catalog/view/theme/default/images/activity/turntable/banner_<?php echo $lang_code; ?>.png" />
				</div>

				<div id="mian">



					<div id="zhuanpan">

						<img id="img" src="catalog/view/theme/default/images/activity/turntable/four_<?php echo $lang_code; ?>.png" width="510" height="510" style="-webkit-transform: rotate(45deg);">
						<?php if($try){ ?>
						<img id="tip" src="catalog/view/theme/default/images/activity/turntable/try.png" width="197" height="208">
						<?php } else { ?>
						<img id="tip" src="catalog/view/theme/default/images/activity/turntable/four1.png" width="197" height="208">
						<?php } ?>
						<img src="catalog/view/theme/default/images/activity/turntable/bottom_line.png" class="bottom_line"/>
					</div>

				</div>


			</div>

			<div class="right" >
				<h4 class="title_h4"><?php echo $text_awards_list; ?></h4>
				<div class="r_list" id="scrollDiv">
					<ul>
						<?php foreach($prize_get_list as $value){ ?>
						<li><?php echo $value;?></li>
						<?php } ?>

					</ul>
				</div>
				<div class="r_btn">
				</div>
			</div>
		</div>
	</div>
</div>

<!-- info -->
<div class="redbg">
	<div class="wrap">
		<h4><?php  echo $text_conditions;?></h4>
		<ul>
			<li><i class="left">1</i><div class="right"><?php echo $text_rule_01; ?></div></li>
			<li><i class="left">2</i><div class="right"><?php echo $text_rule_02; ?></div></li>
			<li><i class="left">3</i><div class="right"><?php echo $text_rule_03; ?></div></li>
			<li><i class="left">4</i><div class="right"><?php echo $text_rule_04; ?></div></li>
		</ul>
	</div>
</div>





<!-- footerup -->
<div class="footerup wrap page clearfix" >
	<?php foreach($informations as $values){ ?>
	<div class="about">
		<h2><?php echo $values['name'];?></h2>
		<?php foreach($values['information'] as $value){ ?>
		<p><a href="<?php echo $value['href'];?>" target="_blank"><?php echo $value['title'];?></a></p>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="share">
		<p><?php echo $text_join_our_community;?></p>
		<div class="shareup">
			<a href="http://www.facebook.com/myledcom" target="_blank">
				<img src="catalog/view/theme/default/images/activity/share/f.png" width="32" height="32" border="0" />
			</a>
			<a href="http://www.twitter.com/myledcom" target="_blank">
				<img src="catalog/view/theme/default/images/activity/share/t.png" width="32" height="32" border="0" />
			</a>
			<a href="https://www.youtube.com/user/myledcom" target="_blank">
				<img src="catalog/view/theme/default/images/activity/share/youtube.jpg" width="32" height="32" border="0" />
			</a>
			<a href="http://www.pinterest.com/myledcom" target="_blank">
				<img src="catalog/view/theme/default/images/activity/share/p.png" width="32" height="32" border="0" />
			</a>
		</div>
	</div>
</div>
<!-- footer -->
<footer>
	<div class="page"><?php echo $text_copyright;?></div>
</footer>



<script type="text/javascript">

	$("#mailsend").click(function(){

		var mail_str = $(".mail").val();
		if(!checkMail(mail_str)){
			$(".mail_error").text("Please enter a correct E-mail address!");
			$(".mail").addClass("red_border");
			$(".mail_error").show();
			return false;
		}else{
			$(".mail_error").hide();
			$(".mail").removeClass("red_border");
			send(mail_str);
			$(this).parents(".pop").hide();
			$(".grey-bg").hide();
			reset();
			return true;
		}

	})
	function checkMail(str){
		var reg = /^(.)*@[\s\S]+((\.\w+)+)$/;
		return reg.test(str);
	}
	/* 弹出窗口 */
	function pop(msg){
		$("#win_info").html(msg);
		$(".grey-bg").show();
		$("#getemail").fadeIn(2000);
	}

	function showmessage(msg,tryagain){
		$("#message .info1").html(msg);
		if(tryagain==1){
			$("a[dom='try']").show();
		}
		$(".grey-bg").show();
		$("#message").fadeIn(2000);

	}

	$(".pop .close_link").click(function(){
		$(this).parents(".pop").hide();
		$(".grey-bg").hide();
		reset();
		tryagain("");

	});
	function reset(){
		$("#win_info").text("");
		$(".info2").hide();
	}

	function GetRandomNum(Min,Max){

		var Range = Max - Min;
		var Rand = Math.random();  // 随机数
		return(Min + Math.round(Rand * Range));

	}

	function tryagain(msg){
		$(".grey-bg").show();
		$(".pop").hide();
		if(msg != ''){
			$('#order_error').html(msg);
		}else{
			$('#order_error').html('');
			$('input[name=order_number]').val("");
		}

		$('#tryagain').fadeIn(2000);
	}

	function flushList(){
		$.ajax({
			url: 'index.php?route=activity/turntable/flushList',
			type: 'get',

			dataType: 'json',
			success: function (json) {
				if(json) {
					var list = '';
					for (var i in json) {
						list = list + "<li>" + json[i] + "</li>";
					}
					$("#scrollDiv ul").html(list);
				}
			}
		});
	}
	function GetPrize(roll,fromtype){
		var order_number = $("input[name=order_number]").val();

		$.ajax({
			url: 'index.php?route=activity/turntable/get',
			type: 'post',
			data: 'order_number=' + order_number,
			dataType: 'json',
			success: function (json) {
				var rs = json;
				if (rs['error'] == 1 ) {
					/*
					if(fromtype == 'email') {
						$("#tip").bind("click", roll);
					}

					if(fromtype == 'order') {
						$("#trybyorder").bind("click", roll);
					}
					*/

					tryagain("");

					return;
				}
				if (rs['error'] == 2 ) {
					/*
					if(fromtype == 'email') {
						$("#tip").bind("click", roll);
					}

					if(fromtype == 'order') {
						$("#trybyorder").bind("click", roll);
					}
					*/

					tryagain(rs['message']);

					return;
				}


				//var winning_number;

				// 中奖号码 Rand_num
				var prize_id = rs['prize_id'];
				var prize_name = rs['prize_name'];
				var login = rs['login'];
				var mymail = rs['email'];
				// 中奖转盘度数预计
				switch (prize_id) {
					case "1":
						var dushu= 1125;
						break;
					case "2":
						var dushu= 1395;
						break;
					case "3":
						var dushu= 1305;
						break;
					case "4":
						var dushu= 1215;
						break;
					case "5":
						var dushu= 1170;
						break;
					case "6":
						var dushu= 1260;
						break;
					case "7":
						var dushu= 1350;
						break;
					case "8":
						var dushu= 1080;
						break;
					default:
						var dushu= 1125;
						break;
				}


				// 转动效果
				var angle = 0;
				var zhuan = setInterval(function () {
					var last150 = dushu- 50;
					var last50 = dushu- 10;
					if (angle <= 300) {
						angle += 15;
					}
					if (angle > 300 && angle <= 700) {
						angle += 13;
					}

					if (angle > 700 && angle <= 800) {
						angle += 11;
					}
					if (angle > 800 && angle <= 1000) {
						angle += 9;
					}
					if (angle > 1000 && angle <= last150) {
						angle += 5;
					}

					if (angle >= last150 && angle <= last50) {
						angle += 3;
					}

					if (angle >= last50) {
						angle += 1;
					}

					$("#img").rotate(angle);

					if (angle >= dushu) {
						clearInterval(zhuan);
						if(login){
							$(".bg_ed7f88").hide();
						}else{
							$(".bg_ed7f88").show();
							if(mymail){
								$("input[name=email]").val(mymail);
							}
						}
						//$(".p-title").text("	Please confirm your e-mail ! ");
						switch (prize_id) {

							case "8":
								pop("<p class='c_title'>" + prize_name + "</p><img src='catalog/view/theme/default/images/activity/turntable/product/iphone.png' />");
								$(".info2").show();
								break;
							case "6":
								pop("<p class='c_title'>" + prize_name + "</p><img src='catalog/view/theme/default/images/activity/turntable/product/led_bulb.png' />");
								$(".info2").show();
								break;
							case "7":
								pop("<p class='c_title'>" + prize_name + "</p><img src='catalog/view/theme/default/images/activity/turntable/product/pen.png' />");
								$(".info2").show();
								break;
							case "5":
								pop("<p class='c_title'>" + prize_name + "</p><img src='catalog/view/theme/default/images/activity/turntable/product/usb.png' />");
								$(".info2").show();
								break;
							case "4":
								//$(".p-title").text("<?php echo $text_sucess_04; ?> ");
								pop("<p class='c_title'>" + prize_name + "</p><img src='catalog/view/theme/default/images/activity/turntable/product/off_num4.png' />");
								break;
							case "3":
								//$(".p-title").text("<?php echo $text_sucess_04; ?> ");
								pop("<p class='c_title'>" + prize_name + "</p><img src='catalog/view/theme/default/images/activity/turntable/product/off_num3.png' />");
								break;
							case "2":
								//$(".p-title").text("<?php echo $text_sucess_04; ?> ");
								pop("<p class='c_title'>" + prize_name + "</p><img src='catalog/view/theme/default/images/activity/turntable/product/off_num2.png' />");
								break;
							case "1":
								//$(".p-title").text("<?php echo $text_sucess_04; ?> ");
								pop("<p class='c_title'>" + prize_name + "</p><img src='catalog/view/theme/default/images/activity/turntable/product/off_num1.png' />");
								break;
							default:
						}

						$(".coud_num").html($(".coud_num").html() - 1);


						$("#tip").bind("click", roll);


						$('#tip').attr("src", "catalog/view/theme/default/images/activity/turntable/try.png");
						flushList();
					}
				}, 50);


			}


		});
	}

    function send(email){

		$.ajax({
			url: 'index.php?route=activity/turntable/send',
			type: 'post',
			data: 'email=' + email,
			dataType: 'json',
			success: function (json) {
				showmessage("<?php echo $text_mail_send_tip;?>",1);

			}
		});
	}

	$(document).ready(function() {
		if(!(document.cookie || navigator.cookieEnabled))
		{
			$(".grey-bg").show();
			alert("<?php echo $no_cookie_tip;?>");
		}




		var roll = function () {
			$(this).unbind('mouseenter').unbind('mouseleave');
			//$(this).attr("src", "catalog/view/theme/default/images/activity/turntable/four1.png");


			$(this).unbind("click", roll);

			$("input[name=order_number]").val("");

			GetPrize(roll,'email');


		}
		$("#tip").bind("click", roll);


		var roll2 = function () {

			//$(this).attr("src", "catalog/view/theme/default/images/activity/turntable/four1.png");
			if($("input[name=order_number]").val() == ""){
				$("input[name=order_number]").focus();
				return ;
			}

			$(this).parents(".pop").hide();
			$(".grey-bg").hide();
			reset();

			$(this).unbind('mouseenter').unbind('mouseleave');



			$("#tip").unbind("click", roll);
			GetPrize(roll,'email');


		}

		$("#trybyorder").bind("click",roll2 );

		/* 关闭弹出 */
		$(".pop .close").click(function(){
			$(this).parents(".pop").hide();
			$(".grey-bg").hide();
			reset();
			$("#tip").bind("click", roll);

		});

	});


	/*
	 document.onselectstart=new Function("event.returnValue=false;");
	 document.oncontextmenu=new Function("event.returnValue=false;");
	 */
</script>
<script type="text/javascript">
	$(document).ready(function () {


		/* 点击回车跳转 */
		var $inp = $('#search'); //所有的input元素
		$inp.keypress(function (e) { //这里给function一个事件参数命名为e，叫event也行，随意的，e就是IE窗口发生的事件。
			var key = e.which; //e.which是按键的值
			if (key == 13) {
				url =  'http://www.myled.com/index.php?route=product/search';

				var search = $('input[name=\'search\']').attr('value');

				if (search) {
					url += '&search=' + encodeURIComponent(search);
				}

				window.location.href = url;
			}
		});

		$('#search_button').click(function () {
			url = 'http://www.myled.com/index.php?route=product/search';

			var search = $('input[name=\'search\']').attr('value');

			if (search) {
				url += '&search=' + encodeURIComponent(search);
			}

			window.location.href = url;
		})


		/*滚动插件大于16条触发*/
		if($('#scrollDiv li').length>=10){
			$("#scrollDiv").Scroll({line:1,speed:1000,timer:2000});
		}

	});

	/*滚动插件*/
	(function($){
		$.fn.extend({
			Scroll:function(opt,callback){
				//参数初始化
				if(!opt) var opt={};
				var _this=this.eq(0).find("ul:first");
				var        lineH=_this.find("li:first").height(), //获取行高
						line=opt.line?parseInt(opt.line,10):parseInt(this.height()/lineH,10), //每次滚动的行数，默认为一屏，即父容器高度
						speed=opt.speed?parseInt(opt.speed,10):500, //卷动速度，数值越大，速度越慢（毫秒）
						timer=opt.timer?parseInt(opt.timer,10):3000; //滚动的时间间隔（毫秒）
				if(line==0) line=1;
				var upHeight=0-line*lineH;
				//滚动函数
				scrollUp=function(){
					_this.animate({
						marginTop:upHeight
					},speed,function(){
						for(i=1;i<=line;i++){
							_this.find("li:first").appendTo(_this);
						}
						_this.css({marginTop:0});
					});
				}
				//鼠标事件绑定
				_this.hover(function(){
					clearInterval(timerID);
				},function(){
					timerID=setInterval("scrollUp()",timer);
				}).mouseout();
			}
		})
	})(jQuery);

</script>
</body>
</html>
