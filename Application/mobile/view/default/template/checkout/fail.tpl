<?php echo $header; ?>
<div class="head-title"><a class="icon-angle-left left-btn"></a><?php echo $heading_title;?></div>
<section class='mypoints'  >
    <p class="black26"><?php echo $heading_title; ?></p>

    <p > <?php echo $text_message; ?> </p>
    <p > <?php echo $text_error_tips; ?>  </p>
    <p > <a href="<?php echo $continue; ?>"  class="min-btn orange-bg"><?php echo $button_continue; ?></a></p>
</section>



<?php  
		$this->load->model('catalog/product');
		$orderItems = '';
		$ebay_item= '';
		$webgain_item= array();
		foreach($order_product_info as $product){
			$catagory_info =$this->model_catalog_product->getCategoryInfo($product['product_id']);

			//$orderItems .= "ga('ecommerce:addItem', {'id': '".$order_info['order_number']."','name': '".$product['name']."','sku': '".$product['model']."','category': '".$catagory_info['name']."','price': '".$product['price']."','quantity': '".(int)$product['quantity']."'});\n";
			$orderItems .= "ga('ec:addProduct', {
				'id': '".$product['model']."',
				'name': '".$product['name']."',
				'category': '".$catagory_info['name']."',
				'brand': '',
				'variant':'',
				'price': '".$this->currency->convert($product['price'],'USD',$order_info['currency_code'])."',
				'quantity': '".(int)$product['quantity']."'
			});\n";
			$ebay_item .="_roi.push(['_addItem', '".$product['model']."', '".$product['name']."', '".$catagory_info['category_id']."', '".$catagory_info['name']."', '".$product['price']."', '".(int)$product['quantity']."' ]);"; 
			$webgain_item[]= "16213::".$product['price']."::".$product['name']."::".$product['model']."::".$order_info['coupon_code'];
		}
		$webgain_item_string =implode('|',$webgain_item);
?>

<script>
/* ga 增强性代码*/
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74239275-1', 'auto');
  ga('require', 'displayfeatures');
  <?php  if($this->session->data['customer_id']){ ?>
  ga('set', '&uid', "<?php echo $this->session->data['customer_id'];?>"); 
  <?php  }  ?>
var dimensionValue ="<?php echo $order_info['coupon_code']; ?>";
ga('set', 'dimension1', dimensionValue);
ga('require', 'ec');


function checkout() {
  <?php echo $orderItems; ?>
}
// In the case of checkout actions, an additional actionFieldObject can
// specify a checkout step and option.
ga('ec:setAction','checkout', {
    'step': 1,            // A value of 1 indicates this action is first checkout step.
    'option': "<?php echo $order_info['payment_method'];?>"      // Used to specify additional info about a checkout stage, e.g. payment method.
});
ga('ec:setAction','checkout', {'step': 2});

// Called when user has completed shipping options.

  ga('ec:setAction', 'checkout_option', {
    'step': 2,
    'option': "<?php echo $order_info['shipping_method'];?>"
  });

ga('set', '&cu', "<?php echo $order_info['currency_code'];?>");              // Set tracker currency to Euros
<?php echo $orderItems; ?>
ga('ec:setAction', 'purchase', {
  id: "<?php echo $order_info['order_number']; ?>",
  affiliation: "<?php echo $order_info['payment_method']; ?>",
  revenue: "<?php echo $order_info['grand_total']; ?>",
  tax: '',
  shipping:"<?php echo $order_info['shipping_amount']; ?>",
  coupon: "<?php echo $order_info['coupon_code']; ?>"
});

ga('send', 'pageview');
</script>
<!-- Google Code for Order Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 979549056;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "kktTCMi5pgcQgPeK0wM";
var google_conversion_value = <?php echo $order_info['grand_total']; ?>;
var google_conversion_currency = "<?php echo $order_info['currency_code'];?>";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/979549056/?value=<?php echo $order_info['grand_total'] ?>&amp;currency_code=<?php echo $order_info['currency_code'];?>&amp;label=kktTCMi5pgcQgPeK0wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<script type="text/javascript"> if (!window.mstag) mstag = {loadTag : function(){},time : (new Date()).getTime()};</script> <script id="mstag_tops" type="text/javascript" src="//flex.msn.com/mstag/site/62cb90b8-37dc-4cd3-9436-9c4c7ab6abba/mstag.js"></script> <script type="text/javascript"> mstag.loadTag("analytics", {dedup:"1",domainId:"2632634",type:"1",actionid:"167893"})</script> <noscript> <iframe src="//flex.msn.com/mstag/tag/62cb90b8-37dc-4cd3-9436-9c4c7ab6abba/analytics.html?dedup=1&domainId=2632634&type=1&actionid=167893" frameborder="0" scrolling="no" width="1" height="1" style="visibility:hidden;display:none"> </iframe> </noscript>
<!-- 法国ebay比价网 -->
<?php 
if($this->session->data['language']=="FR"){
?>
<script type="text/javascript">
	var _roi = _roi || [];

	// Etape 1 : ajout des détails de base de la commande

	_roi.push(['_setMerchantId', '522428']); // obligatoire
	_roi.push(['_setOrderId', "<?php echo $order_info['order_number'];?>"]); // identifiant de commande client unique
	_roi.push(['_setOrderAmount', "<?php echo $order_info['currency_code'].$order_info['grand_total'] ?>"]); // total de la commande
	_roi.push(['_setOrderNotes', '']); // instructions pour la commande, 50 caractères maximum

	// Etape 2 : ajout de l'ensemble des objets de la commande
	// où votre moteur de commerce électronique passe en revue tous les objets du panier et imprime _addItem pour chacun d'entre eux
	// l'ordre des valeurs doit être respecté pour garantir la précision du rapport

	<?php echo $ebay_item ?>
	// Etape 3 : soumission de la transaction à l'outil de Suivi des Ventes (RSI) de ECN

	_roi.push(['_trackTrans']);
</script>
<script type="text/javascript" src="https://stat.dealtime.com/ROI/ROI2.js"></script>
<?php } ?>
<?php if(in_array($order_info['payment_code'],array('westernunion','pp_express','pp_standard','globebill_credit'))){ ?>
	<!-- adcell -->
	<?php if(isset($_COOKIE['source'])&&$_COOKIE['source']=='adcellled') { ?>
	<!-- adcell -->
	<script type="text/javascript" src="//www.adcell.de/js/jsadlib.js"></script>
	<script type="text/javascript">
	Adcell.user.track({
		'eventid' : 4425,
		'referenz' : "<?php echo $order_info['order_number']; ?>",
		'betrag' : <?php echo $order_info['subtotal']-$order_info['discount_amount']; ?>,
		
		'pid' : 3559
	});
	</script>
	<noscript>
		<img src="//www.adcell.de/event.php?pid=3559&eventid=4425&referenz=<?php echo $order_info['order_number']; ?>&betrag=<?php echo $order_info['subtotal']-$order_info['discount_amount']; ?>" border="0" width="1" height="1">
	</noscript>
	<?php } ?>
	<!-- shareasale -->
	<?php if(isset($_COOKIE['source'])&&$_COOKIE['source']=='shareasale') { ?>
		<img src="https://shareasale.com/sale.cfm?tracking=<?php echo $order_info['order_number']; ?>&amount=<?php echo $order_info['base_subtotal']-$order_info['base_discount_amount'];?>&transtype=sale&merchantID=50601" width="1" height="1">
	<?php } ?>
	<!-- webgains -->
	<?php 
	if(isset($_COOKIE['source'])&&$_COOKIE['source']=='webgains'){
		$order_value =$order_info['subtotal']-$order_info['discount_amount'];
		$wgOrderValue =$order_value ;
		$wgOrderReference = rawurlencode($order_info['order_number']);
		$wgEventID=16213; 
		$wgComment= ''; 
		$wgMultiple=1;
		$wgItems= rawurlencode($webgain_item_string);
		$wgCustomerID= '';
		$wgProductID= '';
		$wgSLang = 'php';
		$wgLang = 'en_US';
		$wgPin = 2553;
		$wgProgramID = 9787; 
		$wgVoucherCode = rawurlencode($order_info['coupon_code']); 
		$wgCurrency = 'USD';
		$wgVersion = '1.2';
		$wgSubDomain="track";
		$wgCheckString ="wgver=$wgVersion&wgsubdomain=$wgSubDomain&wglang=$wgLang&wgslang=$wgSLang&wgprogramid=$wgProgramID&wgeventid=$wgEventID&wgvalue=$wgOrderValue&wgorderreference=$wgOrderReference&wgcomment=$wgComment&wgmultiple=$wgMultiple&wgitems=$wgItems&wgcustomerid=$wgCustomerID&wgproductid=$wgProductID&wgvouchercode=$wgVoucherCode";
		$wgCheckSum=md5($wgPin.$wgCheckString); 
		$wgQueryString = $wgCheckString."&wgchecksum=".$wgCheckSum."&wgCurrency=".$wgCurrency;
		$wgUri = '://'.$wgSubDomain.".webgains.com/transaction.html?".$wgQueryString;
	?>
	<script language="javascript" type="text/javascript">
	if(location.protocol.toLowerCase() == "https:") wgProtocol="https";
	else wgProtocol="http";
	wgUri = wgProtocol + "<?php echo($wgUri);?>" + "&wgprotocol=" + wgProtocol + "&wglocation=" + location.href;
	document.write('<sc'+'ript language="JavaScript"  type="text/javascript" src="'+wgUri+'"></sc'+'ript>');
	</script>
	
	<noscript>
	<img src="http://<?php echo($wgSubDomain);?>.webgains.com/transaction.html?wgrs=1&<?php echo($wgQueryString);?>&wgprotocol=https" alt="" width="1" height="1"/>
	</noscript>
	<?php 
	}
	?>
	<!-- tradedoubler -->
	<?php 
	if(isset($_COOKIE['source'])&&$_COOKIE['source']=='tdr'){
		$order_value =$order_info['subtotal']-$order_info['discount_amount'];
		$order_eur_value =$this->currency->convert($order_value,$order_info['currency_code'],'EUR');
		if($order_eur_value<500){
	?>
	<img src="https://tbs.tradedoubler.com/report?organization=1946057&event=319951&orderNumber=<?php echo $order_info['order_number']; ?>&orderValue=<?php echo $order_eur_value; ?>&currency=EUR" height="1" width="1" border="0"/>
	<?php
		}
		elseif($order_eur_value>=500&&$order_eur_value<=1000){
	?>
	<img src="https://tbs.tradedoubler.com/report?organization=1946057&event=321136&orderNumber=<?php echo $order_info['order_number']; ?>&orderValue=<?php echo $order_eur_value; ?>&currency=EUR" height="1" width="1" border="0"/>
	<?php
		}elseif($order_eur_value>1000&&$order_eur_value<=2000){
	?>
	<img src="https://tbs.tradedoubler.com/report?organization=1946057&event=321138&orderNumber=<?php echo $order_info['order_number']; ?>&orderValue=<?php echo $order_eur_value; ?>&currency=EUR" height="1" width="1" border="0"/>
	<?php
		}elseif($order_eur_value>2000){
	?>
	<img src="https://tbs.tradedoubler.com/report?organization=1946057&event=321140&orderNumber=<?php echo $order_info['order_number']; ?>&orderValue=<?php echo $order_eur_value; ?>&currency=EUR" height="1" width="1" border="0"/>
	<?php
		}
	?>
	
	
	<?php
	}
	?>

<?php } ?>
<?php echo $footer; ?>