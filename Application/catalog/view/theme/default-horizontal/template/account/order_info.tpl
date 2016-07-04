<?php echo $header;?>

<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span>
		<?php if($breadcrumb['href']){
		?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php
		}
		else{
		?>
		<?php echo $breadcrumb['text']; ?>
		<?php	
		}
		?>
		</span>
		<?php echo $breadcrumb['separator']; ?>
	</li>
	<?php
	}
	?>
	
	</ul>
	</div>
	<div class="clear"></div>
</nav>
<section class="box wrap clearfix">
	<?php echo $menu;?>

    <!----- boxRight ------>
	<section class="boxRight">
		<?php echo $right_top;?>
		<div class="protit"><p class="black18"><?php echo $text_order_id;?> #<?php echo $order['order_number'];?> <?php if($order['is_parent'] == 0) { ?>- <?php echo $order['order_status'];?><?php } ?></p></div>

        <div class="order-tit"><?php echo $text_date_added;?>: <?php echo date('M d,Y', strtotime($order['date_added']));?></div>

        <div class="order-list">
            <div class="right">
                <b><?php echo $text_shipping_method;?></b>
                <p><?php echo $shipping_method;?></p>
				<?php if($tracks_info){ ?>
				<table class="order_search_table">
					<tbody><td style="font-weight:700;width:20%"><?php echo $text_shipped_by;?></td><td style="font-weight:700;width:50%"><?php echo $text_stracking_number;?></td><td style="font-weight:700;width:30%">Shipment Date</td></tbody>
					<?php foreach($tracks_info as $track){ ?>
					<tr><td><?php echo $track['title'];?></td><td><?php echo $track['track_number'];?></td><td><?php echo $track['created_at'];?></td></tr>
					<?php if($track['track_number']){ ?>
					<tr><td><?php echo $text_tarcking;?>:</td><td colspan='2'><a href="<?php echo $track['track_url'];?>" target= '_blank'><?php echo $track['track_url'];?></a></td></tr>
					<?php } ?>
					<?php } ?>
				</table>
			   <?php } ?>
            </div>

            <div class="left">
                <b><?php echo $text_shipping_address;?></b>
                <p><?php echo $shipping_address;?><br /><?php echo $order['order_tax_id'];?></p>
                
            </div >
			<p class="clear"></p>
        </div>

        <div class="order-list">
            <div class="right">
                <b><?php echo $text_payment_method;?></b>
                <p><?php echo $payment_method;?></p>
            </div>
            <div class="left">
                <b><?php echo $text_payment_address;?></b>
                <p><?php if($payment_address){ echo $payment_address; }else{ echo $shipping_address;} ?></p>
            </div>
			
			<div class="clear"></div>
        </div>


       <?php if($order['is_parent'] == 0) { ?>
        <div class="order-tit"><b><?php echo $text_items_ordered;?></b></div>
        <table class="order-table" cellpadding="0" cellspacing="0">
            <tr>
		<th width="30%"><?php echo $column_image;?></th>
                <th width="20%"><?php echo $column_name;?></th>
                <th width="10%"><?php echo $column_model;?></th>
                <th width="9%"><?php echo $column_price;?></th>
                <th width="9%"><?php echo $column_quantity;?></th>
                <th width="9%"><?php echo $column_total;?></th>
                <th width="13%"><?php echo $column_review;?></th>
            </tr>
			<?php foreach($products as $pro){ ?>
            <tr>
				<td><a href="<?php echo $pro['href'];?>" title="<?php echo $pro['name'];?>"><img src="<?php echo $pro['image'];?>" /></a></td>
                <td><a href="<?php echo $pro['href'];?>" title="<?php echo $pro['name'];?>"><?php echo $pro['name'];?></a></td>
                <td><?php echo $pro['model'];?></td>
                <td><?php echo $pro['price'];?></td>
                <td><?php echo $pro['quantity'];?></td>
                <td><?php echo $pro['total'];?></td>
                <td><?php echo $pro['review_link'];?></td>
            </tr>
			<?php } ?>
        </table>
        <?php } else { ?>
        
        <?php foreach($children as $_c){ ?>
        <div class="order-tit"><b><?php echo $text_order_id .' #<a href="'.$_c['href'].'">'.$_c['order_number'].'</a>';?></b></div>
        <table class="order-table" cellpadding="0" cellspacing="0">
            <tr>
		<th width="30%"><?php echo $column_image;?></th>
                <th width="20%"><?php echo $column_name;?></th>
                <th width="10%"><?php echo $column_model;?></th>
                <th width="9%"><?php echo $column_price;?></th>
                <th width="9%"><?php echo $column_quantity;?></th>
                <th width="9%"><?php echo $column_total;?></th>
                <th width="13%"><?php echo $column_review;?></th>
                
            </tr>
	    <?php foreach($_c['product_list'] as $pro){ ?>
            <tr>
		<td><a href="<?php echo $pro['href'];?>" title="<?php echo $pro['name'];?>"><img src="<?php echo $pro['image'];?>" /></a></td>
                <td><a href="<?php echo $pro['href'];?>" title="<?php echo $pro['name'];?>"><?php echo $pro['name'];?></a></td>
                <td><?php echo $pro['model'];?></td>
                <td><?php echo $pro['price'];?></td>
                <td><?php echo $pro['quantity'];?></td>
                <td><?php echo $pro['total'];?></td>
                <td><?php echo $pro['review_link'];?></td>
            </tr>
	    <?php } ?>
        </table>
         <div class="order-subtotal">
             <p><b><?php echo $text_shipping_method;?></b> : <?php echo $_c['shipping_method'];?></p>
				<?php if($_c['tracks_info_list']){ ?>
				<table class="order_search_table">
					<tbody><td style="font-weight:700;width:20%"><?php echo $text_shipped_by;?></td><td style="font-weight:700;width:50%"><?php echo $text_stracking_number;?></td><td style="font-weight:700;width:30%">Shipment Date</td></tbody>
					<?php foreach($_c['tracks_info_list'] as $track){ ?>
					<tr><td><?php echo $track['title'];?></td><td><?php echo $track['track_number'];?></td><td><?php echo $track['created_at'];?></td></tr>
					<?php if($track['track_number']){ ?>
					<tr><td><?php echo $text_tarcking;?>:</td><td colspan='2'><a href="<?php echo $track['track_url'];?>" target= '_blank'><?php echo $track['track_url'];?></a></td></tr>
					<?php } ?>
					<?php } ?>
				</table>
			   <?php } ?>
            </div>
        
        
        <?php } ?>
        
        <?php } ?>
        <div  class="order-subtotal" >
			<?php foreach($totals as $total){ ?>
			<?php if($total['code']!='total'){ ?>
            <?php echo $total['title'];?>:  <?php echo $total['text'];?><br/>
			<?php }else{ ?>
			<b><?php echo $total['title'];?>:  <?php echo $total['text'];?></b>
			<?php } ?>
			<?php } ?> 
        </div>


        <?php echo $right_bottom;?>
	</section>	
</section>
<?php echo $footer;?>
</body>
</html>
