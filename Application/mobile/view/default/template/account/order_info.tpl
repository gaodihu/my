<?php echo $header;?>
<div class="head-title">
	<a class="icon-angle-left left-btn"></a><?php echo $text_order;?>
</div>
<div class="mypoints clearfix">

           <table class="pending" cellspacing="0" cellpadding="0">
				<tbody><tr>
					<td><span><?php echo $text_date_added;?>:</span></td>
					<td><?php echo $date_added;?></td>
				</tr>
				<tr>
					<td><span><?php echo $text_status;?>:</span></td>
					<td><b><?php echo $order_status;?></b></td>
				</tr>
				<tr>
					<td><span><?php echo $text_shipping_address;?>:</span></td>
					<td><?php echo $shipping_address;?></td>
				</tr>
				<tr>
					<td><span><?php echo $text_shipping_method;?>:</span></td>
					<td><?php echo $shipping_method;?></td>
				</tr>
						<tr>
					<td><span><?php echo $text_payment_address;?>:</span></td>
					<td><?php echo $payment_address?$payment_address:$shipping_address;?></td>
				</tr>
						<tr>
					<td><span><?php echo $text_payment_method;?>:</span></td>
					<td><?php echo $payment_method;?></td>
				</tr>
			</tbody>
		</table>
        </div>

        <div class="tab-box">
            <ul class="tab-chagne change-list">
                <li class="t-title"><?php echo $text_products;?></li>
				<?php foreach($products as $pro){ ?>
                <li  class="clearfix">
                    <a href="<?php echo $pro['href'];?>"><img src="<?php echo $pro['image'];?>" width="74"></a>
                    <div class="p-info">
                        <div class="p-t"><a><?php echo $pro['name'];?></a></div>
                        <div><span class="price"><?php echo $pro['price'];?></span><?php echo $text_quantity;?> :<?php echo $pro['quantity'];?></div>
                    </div>

                </li>
				<?php } ?>
            </ul>
        </div>


        <div class="tab-box">
            <ul class="tab-chagne change-list">
                <li class="t-title"><?php echo $text_order_summary;?></li>
				<?php foreach($totals as $total){ ?>
                <li>
                    <div class="clearfix">
					<?php if($total['code']=='total'){ ?>
					<span class="price">
					<?php }else{ ?>
					<span class="price" style="font-size: 1.5em;">
					<?php } ?>
					<?php echo $total['text'];?></span>
					<?php echo $total['title'];?> </div>
                </li>
				<?php } ?>
               
            </ul>
        </div>

        <div class="spacing"></div>
        <div style="padding: 1em;text-align: center" class="checkout-btn">
            <a class="button orange-bg " href="<?php echo $copy;?>"><?php echo $text_copy;?></a>
        </div>
     </div>
<?php echo $footer;?>

