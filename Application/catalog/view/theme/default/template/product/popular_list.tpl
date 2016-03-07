<?php echo $header; ?>
<nav class="sidernav">
    <div class="wrap">
        <ul>
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li>
		<span>
		<?php if($breadcrumb['href']){ ?>
            <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            <?php	} else{ ?>
            <?php echo $breadcrumb['text']; ?>
            <?php	} ?>
		</span>
                <?php echo $breadcrumb['separator']; ?>
            </li>
            <?php } ?>

        </ul>
    </div>
    <div class="clear"></div>
</nav>

<section class="wrap product">
    <div class="wrap clearfix">
        <div class="widget aToz-nav">
	<div class="tags-nav">
			<?php foreach($product_tags as $item){ ?>
			<?php 
			if($current_tag==strtolower($item['text'])){ ?>
			<a href="<?php echo $item['href'];?>" title="<?php echo $item['text'];?>" class="current"><?php echo $item['text'];?></a>
			<?php }else{ ?>
			<a href="<?php echo $item['href'];?>" title="<?php echo $item['text'];?>" class=""><?php echo $item['text'];?></a>
			<?php } ?>
			<?php } ?>
			
			<div class="clear"></div>
	</div>
</div>

         <div class="widget aToz-categories aToz-char">
			<?php foreach($populars as $res){ ?>
			<a href="<?php echo $res['href'];?>" title=""><?php echo $res['query_text'];?></a>
			<?php } ?>	
	</div>
	 
			<?php echo $pagination;?>
	 
    </div>
</section>


<div class="fix-layout">
    <div class="gb-operation-area" id="_returnTop_layout_inner">
        <a class="gb-operation-button return-top" id="goto_top_btn" href="javascript:;"><i title="Top"
                                                                                           class="gb-operation-icon"></i>
            <span class="gb-operation-text">Top</span>
        </a>
    </div>
</div>

<script type="text/javascript">
    function show_pop_deal(product_id, price, add_qty, total) {
        $(".add-cart_" + product_id).show();
        $('#add_qty_number').html(add_qty);
        $("span[rel=cart-price-total]").text(price);
        $("b[rel=cart-total]").text(total);
    }
    $(".del,.delbtn").click(function () {
        $(".cart-pop").hide();
    })

</script>

<section class="suport">
    <img src="css/images/foot/footer_shipping_payment.jpg" alt="">

    <div class="clear"></div>
	<div><?php echo $text_copyright;?></div>


</section>