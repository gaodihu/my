</div>
<footer>
    <div class="foot-link">
        <a href='/'><?php echo $text_home;?></a>
        <a href='<?php echo $all_category;?>'><?php echo $text_categories;?></a>
        <a href='about-us.html'><?php echo $text_about;?></a>
        <a onclick="$(window).scrollTop(0)" ><?php echo $text_top;?></a>
    </div>
    <div class="copyright"><?php echo $text_copyright;?></div>
</footer>


	<script type="text/javascript" >
	      common.sliding_event('slider');
	</script>
<?php
	$route =isset($_GET['route'])?$_GET['route']:'';
	switch($route){
		case 'common/home':
			$pagetype ='home';
			break;
		case 'product/product':
			$pagetype ='product';
			break;
		case 'product/category':
			$pagetype ='category';
			break;
		case 'checkout/cart':
			$pagetype ='cart';
			break;
		case 'checkout/checkout':
			$pagetype ='purchase';
			break;
		default:
			$pagetype ='';
			break;
	}
	$language =$this->session->data['language'];
	$currency =$this->currency->getCode();
	if(isset($this->session->data['customer_id'])){
		$usertype ="registered";
	}
	else{
		$usertype ="unregistered";
	}
?>
<script type="text/javascript">

	if(typeof(ecomm_prodid)=='undefined'){
		ecomm_prodid='';
	}
	if(typeof(ecomm_pname)=='undefined'){
		 ecomm_pname='';
	}
	if(typeof(ecomm_pcat)=='undefined'){
		 ecomm_pcat='';
	}
	if(typeof(ecomm_pvalue)=='undefined'){
		 ecomm_pvalue='';
	}
	if(typeof(ecomm_ordervalue)=='undefined'){
		 ecomm_ordervalue='';
	}
	var google_tag_params = {
	ecomm_prodid :ecomm_prodid, // product's id
	ecomm_pname : ecomm_pname, //  product's name
	ecomm_pcat : ecomm_pcat, // product's category
	ecomm_pvalue :  ecomm_pvalue, // price of each product in the cart
	ecomm_pagetype : "<?php echo $pagetype;?>", // home, product, category, cart, purchase
	ecomm_language : "<?php echo $language;?>", //{en, fr, es, ....}
	ecomm_currency : "<?php echo $currency;?>", //currency
	ecomm_totalvalue : ecomm_ordervalue, // filled with the order value[EF] after user purchases
	ecomm_usertype : "<?php echo $usertype;?>",//registered, unregistered
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
</noscript>


<?php if($this->session->data['language'] == 'EN'){?>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'el,iw,ja,ko,nl,pt,ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false, gaTrack: true, gaId: 'UA-42585019-1'}, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php } ?>

</body>
</html>