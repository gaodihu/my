<?php echo $header; ?>
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
	<?php if ($error) { ?>
		<div class="success">
			<?php foreach($error as $value){ 
			 echo $value."<br>";
			 } ?>
		</div>
	<?php } ?>
	<section>
		<div class="protit"><p class="black18"><?php echo $heading_title;?></p></div>
        <section class="order_search" style='height:auto;padding-top:0px;margin-top:0px;'>
			<div style="padding:10px 0;"><?php echo $text_war;?></div>
			<form action="<?php echo $action;?>" enctype="multipart/form-data" method="post">
        	<table>
        		<tr>
        			<td ><?php echo $text_order_number;?>:<span style='color:red;font-size:18px;'>*</span></td>
        			<td ><input type="text" name='order_number' value='<?php echo $order_number;?>'/></td>
					<td width='20'></td>
        			<td><?php echo $text_emial_zip;?>:<span style='color:red;font-size:18px;'>*</span></td>
        			<td><input type="text" name='order_email_zip' value='<?php echo $order_email_zip;?>'/></td>
        			<td><input type="button" value='<?php echo $text_search;?>' id='form_sub' class='common-btn-orange tjbtn'/></td>
        		</tr>
        	</table>
			</form>
        </section>
		
	</section>	
<?php if($order_info){ ?>
<section>
		<div class="protit"><p class="black18"><?php echo $text_order_detail;?></p></div>
		<div class="protit"><p class="black18"><?php echo $text_order_id;?> #<?php echo $order_info['order_number'];?> <?php if($order_info['is_parent'] == 0){ ?>- <?php echo $order_info['order_status'];?><?php } ?></p></div>
        <div class="order-tit"><?php echo $text_date_added;?>: <?php echo date('M d,Y', strtotime($order_info['date_added']));?></div>

        <div class="order-list">
            <div class="right">
                <b><?php echo $text_shipping_method;?></b>
                <p><?php echo $shipping_method;?></p>

				<?php if($tracks){ ?>
					<table class='order_search_table'>
						<tbody>
							<tr><td style="font-weight:700;width:20%"><?php echo $text_shipped_by;?></td><td style="font-weight:700;width:50%" ><?php echo $text_stracking_number;?></td><td style="font-weight:700;width:30%">Shipment Date</td></tr>
						</tbody>
							<?php foreach($tracks as $track){ ?>
								<tbody><tr><td><?php echo $track['title'];?></td><td><?php echo $track['track_number'];?></td><td><?php echo $track['created_at'];?></td></tr>
								<?php if($track['track_number']){ ?>
								<tr><td><?php echo $text_tarcking;?>:</td><td colspan="2"><a href="<?php echo $track['track_url'];?>" target= '_blank'><?php echo $track['track_url'];?></a></td></tr>
								<?php } ?>
								</tbody>
							<?php } ?>
								
					
					</table>
				<?php
					}else{
				?>

                <p>Shipment Date:<?php echo $shipment_creat;?></p>
                <?php } ?>
            </div>

            <div>
                <b><?php echo $text_shipping_address;?></b>
                <p><?php echo $shipping_address;?><br /><?php echo $order_info['order_tax_id'];?></p>
                
            </div>
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

		
        <?php if($order_info['is_parent'] == 0){ ?>
        <div class="order-tit"><b><?php echo $text_items_ordered;?></b></div>
        <table class="order-table" cellpadding="0" cellspacing="0">
            <tr>
				<th width="30%"><?php echo $column_image;?></th>
                <th width="30%"><?php echo $column_name;?></th>
                <th width="10%"><?php echo $column_model;?></th>
                <th width="10%"><?php echo $column_price;?></th>
                <th width="10%"><?php echo $column_quantity;?></th>
                <th width="10%"><?php echo $column_total;?></th>
            </tr>
            
			<?php foreach($products as $pro){ ?>
            <tr>
				<td><a href="<?php echo $pro['href'];?>" title="<?php echo $pro['name'];?>"><img src="<?php echo $pro['image'];?>" /></a></td>
                <td><a href="<?php echo $pro['href'];?>" title="<?php echo $pro['name'];?>"><?php echo $pro['name'];?></a></td>
                <td><?php echo $pro['model'];?></td>
                <td><?php echo $pro['price'];?></td>
                <td><?php echo $pro['quantity'];?></td>
                <td><?php echo $pro['total'];?></td>
            </tr>
        
			<?php } ?>
               
        </table>
        <?php } ?>
        
        <?php if($children && is_array($children) && count($children)>0) { foreach($children as $_c){ ?>
        <div class="order-tit"><b><?php echo $text_order_id .' #<a href="'.$_c['href'].'">'.$_c['order_number'].'</a>';?> - <?php echo $_c['status'];?></b></div>
        <table class="order-table" cellpadding="0" cellspacing="0">
            <tr>
		<th width="30%"><?php echo $column_image;?></th>
                <th width="30%"><?php echo $column_name;?></th>
                <th width="10%"><?php echo $column_model;?></th>
                <th width="10%"><?php echo $column_price;?></th>
                <th width="10%"><?php echo $column_quantity;?></th>
                <th width="10%"><?php echo $column_total;?></th>
            </tr>
	    <?php foreach($_c['product_list'] as $pro){ ?>
            <tr>
				<td><a href="<?php echo $pro['href'];?>" title="<?php echo $pro['name'];?>"><img src="<?php echo $pro['image'];?>" /></a></td>
                <td><a href="<?php echo $pro['href'];?>" title="<?php echo $pro['name'];?>"><?php echo $pro['name'];?></a></td>
                <td><?php echo $pro['model'];?></td>
                <td><?php echo $pro['price'];?></td>
                <td><?php echo $pro['quantity'];?></td>
                <td><?php echo $pro['total'];?></td>
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
        
        
        <?php } } ?>
        
        
        
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
<?php } ?>
</section>
<script type="text/javascript">
	$("#form_sub").click(function(){
		var order_number =$("input[name='order_number']").val();
		var order_email_zip =$("input[name='order_email_zip']").val();
		var empty_order_number ='<?php echo $empty_order_number;?>';
		var empty_order_email_and_zip ='<?php echo $empty_order_email_and_zip;?>';
		var msg='';
		if(!order_number){
			msg+=empty_order_number;
			msg+="\n";
		}
		if(!order_email_zip){
			msg+=empty_order_email_and_zip;
		}
		if(msg){
			alert(msg);
			return false;
		}
		else{
			$(".order_search form").submit();
		}
	})
</script>
<?php echo $footer; ?>

