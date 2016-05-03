<?php echo $header; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($products) { ?>
  
  <section class="prolist prolist_info" id='display_list'>
			<ul class="clearfix">
				<li>
					<p class="offIcon"><span class="font20">50</span>%<br/>OFF</p>
					<div class="procon">
						<div class="img"><img src="catalog/view/theme/default/images/product1.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<p class="offIcon newIcon"></p>
					<div class="procon">
						<div class="img"><img src="catalog/view/theme/default/images/product2.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<p class="offIcon"><span class="font20">50</span>%<br/>OFF</p>
					<div class="procon">
						<div class="img"><img src="catalog/view/theme/default/images/product3.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<p class="offIcon"><span class="font20">50</span>%<br/>OFF</p>
					<div class="procon">
						<div class="img"><img src="catalog/view/theme/default/images/product4.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<?php foreach($products as $pro){
				?>
				<li>
					<div class="procon">
						<div class="img"><a href="<?php echo $pro['href'];?>"><img src="<?php echo $pro['thumb'];?>" width="207" height="160" alt="<?php echo $pro['name'];?>"/></a><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="javascript:addToCart('<?php echo $pro['product_id'];?>');" class="add_btn"><?php echo $button_cart; ?></a></div></div>
						<p class="tt"><?php echo $pro['name'];?></p>
						<?php if($pro['special']){
						?>
						<p class="howmuch"><span class="xj"><?php echo $pro['special'];?></span><span class="yj"><?php echo $pro['price'];?></span></p>
						
						<?php
						}
						else{
						?>
						<p class="howmuch"><span class="xj"><?php echo $pro['price'];?></span></p>
						<?php
						}
						?>
						<?php if($pro['as_low_as_price']){
						?>
						<p class="green">As low as:<?php echo $pro['as_low_as_price'];?></p>
						
						<?php
						}
						?>
						
						<p class="gray"><span class="star star-s5"></span>(<?php echo $pro['reviews'];?>)</p>
					</div>
				</li>
				<?php
				}
				?>
			</ul>
		</section>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
function display(view) {
	if (view == 'list') {
		$('.product-grid').attr('class', 'product-list');
		
		$('.product-list > div').each(function(index, element) {
			html  = '<div class="right">';
			html += '  <div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '  <div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			html += '  <div class="compare">' + $(element).find('.compare').html() + '</div>';
			html += '</div>';			
			
			html += '<div class="left">';
			
			var image = $(element).find('.image').html();
			
			if (image != null) { 
				html += '<div class="image">' + image + '</div>';
			}
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
					
			html += '  <div class="name">' + $(element).find('.name').html() + '</div>';
			html += '  <div class="description">' + $(element).find('.description').html() + '</div>';
			
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
				
			html += '</div>';
						
			$(element).html(html);
		});		
		
		$('.display').html('<b><?php echo $text_display; ?></b> <?php echo $text_list; ?> <b>/</b> <a onclick="display(\'grid\');"><?php echo $text_grid; ?></a>');
		
		$.totalStorage('display', 'list'); 
	} else {
		$('.product-list').attr('class', 'product-grid');
		
		$('.product-grid > div').each(function(index, element) {
			html = '';
			
			var image = $(element).find('.image').html();
			
			if (image != null) {
				html += '<div class="image">' + image + '</div>';
			}
			
			html += '<div class="name">' + $(element).find('.name').html() + '</div>';
			html += '<div class="description">' + $(element).find('.description').html() + '</div>';
			
			var price = $(element).find('.price').html();
			
			if (price != null) {
				html += '<div class="price">' + price  + '</div>';
			}
						
			var rating = $(element).find('.rating').html();
			
			if (rating != null) {
				html += '<div class="rating">' + rating + '</div>';
			}
						
			html += '<div class="cart">' + $(element).find('.cart').html() + '</div>';
			html += '<div class="wishlist">' + $(element).find('.wishlist').html() + '</div>';
			html += '<div class="compare">' + $(element).find('.compare').html() + '</div>';
			
			$(element).html(html);
		});	
					
		$('.display').html('<b><?php echo $text_display; ?></b> <a onclick="display(\'list\');"><?php echo $text_list; ?></a> <b>/</b> <?php echo $text_grid; ?>');
		
		$.totalStorage('display', 'grid');
	}
}

view = $.totalStorage('display');

if (view) {
	display(view);
} else {
	display('list');
}
//--></script> 
<?php echo $footer; ?>