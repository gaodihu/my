<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>

<?php echo $header; ?>
<div id="content">
	  <div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	  </div>
	    <?php if ($error_warning) { ?>
	  <div class="warning"><?php echo $error_warning; ?></div>
	  <?php } ?>
	  <div class="box">
		<div class="heading">
		  <h1><img src="view/image/order.png" alt="" /> <?php echo $title; ?></h1>
		  <div class="buttons">	
		  <a href="<?php echo $cancel; ?>" class="button">Cancel</a>
		 
		  </div>
		</div>
		<div class="content">
		<form action="<?php echo $form_action;?>" method="post" enctype="multipart/form-data" id='invoice_form'>
		<div style="page-break-after: always;">
		  <table class="address">
		  	<tr class="heading">
			  <td width="50%"><b>Order Info</b></td>
			  <td width="50%"><b>Account Information</b></td>
			</tr>
			<tr>
			  <td>Order Date :<?php echo $order['date_added']; ?><br /><br />
				Order Status :<?php echo $order['status']; ?><br /><br />
				Purchased From :<?php echo $order['store_url']; ?><br /><br />
				
				</td>
			  <td>Customer Name :<?php echo $order['firstname'].' '.$order['lastname']; ?><br /><br />
				Email:<?php echo $order['email']; ?><br /><br />
				Customer Group :<?php echo $order['customer_group']; ?><br /><br />
				
				</td>	
			</tr>
		  </table>
		  <table class="address">
			<tr class="heading">
			  <td width="50%"><b>Billing Address</b></td>
			  <td width="50%"><b>Shipping Address</b></td>
			</tr>
			<tr>
			  <td>
			  	<?php if($order['payment_address']){ ?>
			  	<?php echo $order['payment_address']; ?><br/>
				<?php }else{ ?>
				<?php echo $order['shipping_address']; ?>
				<?php } ?>
				<?php echo $order['email']; ?><br/>
				<?php echo $order['telephone']; ?>
				<?php if ($order['payment_company_id']) { ?>
				<br/>
				<br/>
				<?php echo $text_company_id; ?> <?php echo $order['payment_company_id']; ?>
				<?php } ?>
				<?php if ($order['payment_tax_id']) { ?>
				<br/>
				<?php echo $text_tax_id; ?> <?php echo $order['payment_tax_id']; ?>
				<?php } ?></td>
			  <td><?php echo $order['shipping_address']; ?></td>
			</tr>
		  </table>
		  
		  <table class="address">
			<tr class="heading">
			  <td width="50%"><b>Payment information</b></td>
			  <td width="50%"><b>Shipping Information</b></td>
			</tr>
			<tr>
			  <td>
			  	<?php echo $order['payment_method']; ?></td>
			  <td><?php echo $order['shipping_method']; ?><br /><br />
			  		<table class='address' id='Tracking_Form'>
						<tbody>
							<tr><td>Carrier</td><td>Title</td><td>Number</td><td>Action</td></tr>
						</tbody>
						
					</table>
					<span><a id="Add-Tracking" title="Add Tracking Number"  class="button" ><span>Add Tracking Number</span></a></span>
			  		
			  </td>
			</tr>
		  </table>
		  <table class="product">
			<tr class="heading">
			  <td><b><?php echo $column_product; ?></b></td>
			  <td><b><?php echo $column_model; ?></b></td>
			  <td align="right"><b><?php echo $column_quantity; ?></b></td>
			  <td align="right"><b> Qty to Ship</b></td>
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
			  <td align="right"><input type="text" value="<?php echo $product['quantity']; ?>" name="shipment_items[<?php echo $product['product_id'];?>][qty_shipped]" /></td>
			  <td align="right"><?php echo $product['price']; ?>  (<?php echo $product['base_price'];?> )</td>
			  <td align="right"><?php echo $product['total']; ?>  (<?php echo $product['base_total'];?> )</td>
			  <input type="hidden" name="shipment_items[<?php echo $product['product_id'];?>][price]" value="<?php echo $product['orginal_price'];?>" />
			  <input type="hidden" name="shipment_items[<?php echo $product['product_id'];?>][qty]" value="<?php echo $product['quantity'];?>" />
			  <input type="hidden" name="shipment_items[<?php echo $product['product_id'];?>][name]" value="<?php echo $product['name'];?>" />
			  <input type="hidden" name="shipment_items[<?php echo $product['product_id'];?>][sku]" value="<?php echo $product['model'];?>" />	
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
		 
		  <?php } ?>
		</div>
		<input type="hidden" name="order_number" value="<?php echo $order['order_number'];?>" />
		<input type="hidden" name="customer_id" value="<?php echo $order['customer_id'];?>" />	
	
		</form>
	  </div>
	</div>
</div>
<?php echo $footer; ?>
<script type="text/javascript">
$('#Add-Tracking').click(function(){
	var tr_obj =$('#Tracking_Form tr');
	var length =tr_obj.length;
	var count =length+1;
	var id='track_tr_'+count;
	var html ="<tr id='"+id+"'><td><input type='text' value='' name='tarck["+count+"][carrier]'></td><td><input type='text' value='' name='tarck["+count+"][title]'></td><td><input type='text' value='' name='tarck["+count+"][namber]'></td><td><a href='javascript:void(0)' attr='"+id+"' class='button' id='delete_tarck'>delete</a></td></tr>";
	$("#Tracking_Form").append(html);
});

	$('#delete_tarck').live('click',function(){
		var del_id =$(this).attr('attr');
		$('#'+del_id).remove();
	});
	function submitForm(ID){
		$('#'+ID).submit();
	}
</script>
</body>
</html>