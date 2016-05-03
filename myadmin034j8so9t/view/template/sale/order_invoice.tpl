<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
<?php if(isset($print)){ ?>
	
	<div style="page-break-after: always;">
	  <h1><?php echo $text_invoice; ?></h1>
	  <table class="address">
			<tr class="heading">
			  <td width="50%"><b>Order #<?php echo $order['order_number'];?></b></td>
			  <td width="50%"><b>Account Information</b></td>
			</tr>
			<tr>
			  <td>
			  	Order Date:<?php echo $order['date_added'];?><br />
			  	Order Status:<?php echo $order['status'];?><br />
				  <!--
				Purchased From:<?php echo $order['store_name'];?><br />

				Placed from IP:<?php echo $order['ip'];?><br />
				<?php echo $order['currency_code'];?>/USD rate:<?php echo $order['currency_value'];?><br />
				-->
			</td>
			  <td>
			 <?php if($order['firstname']){ ?>
			Customer Name:<?php echo $order['firstname'];?> <?php echo $order['lastname'];?><br />
			<?php }else{ ?>
			Customer Name:Guest<br />
			<?php } ?>

			Email:<?php echo $order['email'];?><br />
			Customer Group:<?php echo $order['customer_group'];?><br />
			</td>
			</tr>
		  </table>
	  <table class="address">
		<tr class="heading">
		  <td width="50%"><b><?php echo $text_to; ?></b></td>
		  <td width="50%"><b><?php echo $text_ship_to; ?></b></td>
		</tr>
		<tr>
		  <td><?php if($order['payment_address']){echo $order['payment_address'];}else{echo $order['shipping_address']; } ?></td>
		  <td><?php echo $order['shipping_address']; ?></td>
		</tr>
	  </table>
	  <table class="address">
			<tr class="heading">
			  <td width="50%"><b>Payment Information</b></td>
			  <td width="50%"><b>Shipping Information</b></td>
			</tr>
			<tr>
			  <td><?php echo $order['payment_method'];?><br /><?php echo $order['payment_information'];?></td>
			  <td>

				  <?php echo $order['shipping_method']; ?><br/>
				  <?php if($order['tracks']) { ?>
				  <table class="product">

					  <tr class="heading"><td width="20%">Carrier</td>
						  <td width="40%">Track Number</td>
						  <td width="40%">Shipment Date</td></tr>



					  <?php foreach($order['tracks'] as $s) { ?>

					  <tr><td width="20%"><?php echo $s['title']; ?></td><td width="40%"><?php echo $s['track_number'];?></td><td width="40%"><?php echo date('Y-m-d',strtotime($s['created_at'])); ?></td></tr>



					  <?php } ?>




				  </table>

				  <?php } ?>

			  </td>
			</tr>
		  </table>
	  <table class="product">
		<tr class="heading">
		  <td><b><?php echo $column_product; ?></b></td>
		  <td><b><?php echo $column_model; ?></b></td>
		  <td align="right"><b><?php echo $column_quantity; ?></b></td>
		  <td align="right"><b><?php echo $column_price; ?></b></td>
		  <td align="right"><b><?php echo $column_total; ?></b></td>
		</tr>
		<?php foreach ($order['product'] as $product) { ?>
		<tr>
		  <td><?php echo $product['name']; ?>
			<?php foreach ($product['option'] as $option) { ?>
			<br />
			&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
			<?php } ?></td>
		  <td><?php echo $product['model']; ?></td>
		  <td align="right"><?php echo $product['quantity']; ?></td>
		  <td align="right"><?php echo $product['price']; ?> </td>
		  <td align="right"><?php echo $product['total']; ?> </td>
		</tr>
		<?php } ?>
		<?php foreach ($order['voucher'] as $voucher) { ?>
		<tr>
		  <td align="left"><?php echo $voucher['description']; ?></td>
		  <td align="left"></td>
		  <td align="right">1</td>
		  <td align="right"><?php echo $voucher['amount']; ?></td>
		  <td align="right"><?php echo $voucher['amount']; ?></td>
		</tr>
		<?php } ?>
		<?php foreach ($order['total'] as $total) { ?>
		<tr>
		  <td align="right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
		  <td align="right"><?php echo $total['text']; ?><br /></td>
		</tr>
		<?php } ?>
	  </table>
	  <?php if ($order['comment']) { ?>
	  <table class="comment">
		<tr class="heading">
		  <td><b><?php echo $column_comment; ?></b></td>
		</tr>
		<tr>
		  <td><?php echo $order['comment']; ?></td>
		</tr>
	  </table>
	  <?php } ?>
		<!--
	  <?php if ($order['order_comment_history']) { ?>
	  <table class="comment">
		<tr class="heading">
		  <td><b><?php echo $column_comment; ?></b></td>
		</tr>
		<?php foreach($order['order_comment_history'] as $order_comment_history){ ?>
		<tr>
		  <td><?php echo $order_comment_history['comment']; ?></td>
		</tr>
		<?php } ?>
	  </table>
	  <?php } ?>
	  -->

	</div>

<?php }else{ ?>
	<?php echo $header; ?>
	<div id="content">
	  <div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	  </div>
	  <div class="box">
		<div class="heading">
		  <h1><img src="view/image/order.png" alt="" /> <?php echo $title; ?></h1>
		  <div class="buttons">	
		  <a href="<?php echo $cancel; ?>" class="button">Cancel</a>
		 
		  </div>
		</div>
		<div class="content">
		<div style="page-break-after: always;">
		  <h1><?php echo $text_invoice; ?></h1>
		  
		  <table class="address">
			<tr class="heading">
			  <td width="50%"><b>Order #<?php echo $order['order_number'];?></b></td>
			  <td width="50%"><b>Account Information</b></td>
			</tr>
			<tr>
			  <td>
			  	Order Date:<?php echo $order['date_added'];?><br />
			  	Order Status:<?php echo $order['status'];?><br />
				  <!--
				Purchased From:<?php echo $order['store_name'];?><br />

				Placed from IP:<?php echo $order['ip'];?><br />
				<?php echo $order['currency_code'];?>/USD rate:<?php echo $order['currency_value'];?><br />
				-->
			</td>
			  <td>
			 <?php if($order['firstname']){ ?>
			Customer Name:<?php echo $order['firstname'];?> <?php echo $order['lastname'];?><br />
			<?php }else{ ?>
			Customer Name:Guest<br />
			<?php } ?>

			Email:<?php echo $order['email'];?><br />
			Customer Group:<?php echo $order['customer_group'];?><br />
			</td>
			</tr>
		  </table>
		  <table class="address">
			<tr class="heading">
			  <td width="50%"><b><?php echo $text_to; ?></b></td>
			  <td width="50%"><b><?php echo $text_ship_to; ?></b></td>
			</tr>
			<tr>
			  <td><?php if($order['payment_address']){echo $order['payment_address'];}else{echo $order['shipping_address']; } ?></td>
			  <td><?php echo $order['shipping_address']; ?></td>
			</tr>
		  </table>
		  <table class="address">
			<tr class="heading">
			  <td width="50%"><b>Payment Information</b></td>
			  <td width="50%"><b>Shipping Information</b></td>
			</tr>
			<tr>
			  <td><?php echo $order['payment_method'];?><br /><?php echo $order['payment_information'];?></td>
				<td>

					<?php echo $order['shipping_method']; ?><br/>
					<?php if($order['tracks']) { ?>
					<table class="product">

						<tr class="heading"><td width="20%">Carrier</td>
							<td width="40%">Track Number</td>
							<td width="40%">Shipment Date</td></tr>



						<?php foreach($order['tracks'] as $s) { ?>

						<tr><td width="20%"><?php echo $s['title']; ?></td><td  width="40%"><?php echo $s['track_number'];?></td><td  width="40%"><?php echo date('Y-m-d',strtotime($s['created_at'])); ?></td></tr>

						<?php } ?>

					</table>

					<?php } ?>

				</td>
			</tr>
		  </table>
		  <table class="product">
			<tr class="heading">
			  <td><b><?php echo $column_product; ?></b></td>
			  <td><b><?php echo $column_model; ?></b></td>
			  <td align="right"><b><?php echo $column_quantity; ?></b></td>
			  <td align="right"><b><?php echo $column_price; ?></b></td>
			  <td align="right"><b><?php echo $column_total; ?></b></td>
			</tr>
			<?php foreach ($order['product'] as $product) { ?>
			<tr>
			  <td><?php echo $product['name']; ?>
				<?php foreach ($product['option'] as $option) { ?>
				<br />
				&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
				<?php } ?></td>
			  <td><?php echo $product['model']; ?></td>
			  <td align="right"><?php echo $product['quantity']; ?></td>
			  <td align="right"><?php echo $product['price']; ?>  </td>
			  <td align="right"><?php echo $product['total']; ?>  </td>
			</tr>
			<?php } ?>
			<?php foreach ($order['voucher'] as $voucher) { ?>
			<tr>
			  <td align="left"><?php echo $voucher['description']; ?></td>
			  <td align="left"></td>
			  <td align="right">1</td>
			  <td align="right"><?php echo $voucher['amount']; ?></td>
			  <td align="right"><?php echo $voucher['amount']; ?></td>
			</tr>
			<?php } ?>
			<?php foreach ($order['total'] as $total) { ?>
			<tr>
			  <td align="right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
			  <td align="right"><?php echo $total['text']; ?><br /></td>
			</tr>
			<?php } ?>
		  </table>


		  <?php if ($order['comment']) { ?>
		  <table class="comment">
			<tr class="heading">
			  <td><b><?php echo $column_comment; ?></b></td>
			</tr>
			<tr>
			  <td><?php echo $order['comment']; ?></td>
			</tr>
		  </table>
		  <?php } ?>

		  
		  <?php if(isset($add_invoice)){ ?>
		  <form action="<?php echo $form_action;?>" method="post" enctype="multipart/form-data" id='invoice_form'>
		  <table class="comment" width="40%">
			<tr class="heading">
			  <td>Comment</td>
			</tr>
			<tr>
			  <td><textarea name="comment" cols="80" rows="10"></textarea></td>
			</tr>
		  </table>
		  <table class="comment" width="100%">
			<tr class="heading">
			  <td><a class="button" onclick="submitForm('invoice_form')"/> submit</a></td>
			</tr>
		  </table>
		  </form>
		  <?php } ?>
		</div>
		</div>
	  </div>
	</div>
	<?php echo $footer; ?>
<?php } ?>
<script type="text/javascript">
	function submitForm(ID){
		$('#'+ID).submit();
	}
</script>
</body>
</html>