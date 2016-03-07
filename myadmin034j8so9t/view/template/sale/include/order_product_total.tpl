<?php if($is_remote){?>
<div style='height:30px; background:red;font-size:16px;'>当前地址为偏远地址，DHL会加收偏远手续费</div>
<?php } ?>
<?php if($no_shipping){ ?>
以下电池商品不能发单，请重新选择！<br>
<?php
foreach($no_shipping as $no_ship){
  echo $no_ship['model']."<br>";
}
?>
<?php } ?>
          <?php $total_row = 0; ?>
              <?php if ($can_shipping || $order_totals) { ?>
              <?php foreach ($can_shipping as $pk=>$order_product_info) { ?>
			  <div style="color:red;height:40px;font-size:18px;">packgae <?php echo $total_row+1;?></div>
			  <table class="list">
				<tbody>
			
			<thead>
              <tr>
                <td class="left">product name </td>
                <td class="left">model</td>
                <td class="right">quantity</td>
                <td class="right">price</td>
                <td class="right">total</td>
              </tr>
            </thead>
				<?php foreach($order_product_info['package'] as $order_product){ ?>
              <tr>
				 
                <td class="left"><?php echo $order_product['name']; ?><br />
            
                <td class="left"><?php echo $order_product['model']; ?></td>
                <td class="right"><?php echo $order_product['quantity']; ?></td>
                <td class="right"><?php echo $curreny_code;?><?php echo $order_product['currency_price']; ?>(USD<?php echo $order_product['base_price']; ?>)</td>
                <td class="right"><?php echo $curreny_code;?><?php echo $order_product['currency_total']; ?>(USD<?php echo $order_product['base_total']; ?>)</td>
              </tr>
			  <?php } ?>
			
              <tr>
                <td class="left" colspan='2' style='background-color:#CC9999;font-size:17px'>shipping method </td>
				<td class="left" colspan='3' style='background-color:#CC9999;font-size:17px'>
				<select class ='shipping_method' pk="<?php echo $pk;?>" name="shipping[<?php echo $pk;?>]" dom='shipping'>
                    <option value="">please select...</option>
                    <?php if ($order_product_info['methods']) { ?>
					
					<?php foreach($order_product_info['methods'] as $method){ ?>
					
					<option value="<?php echo $method['delivery_method']; ?>" cost="<?php echo $method['price'];?>"><?php echo $method['delivery_type']; ?></option>
					
					<?php } ?>
                    <?php } ?>
                  </select>
                  
                  <?php if ($error_shipping_method) { ?>
                  <span class="error"><?php echo $error_shipping_method; ?></span>
                  <?php } ?></td>
	
              </tr>
			</table>
				<?php $total_row++;?>
				<?php } ?>

		   <table class="list">
				<tr>
                <td class="left">payment method</td>
                <td class="left"><input type="text" name="payment" value="Western Union">
				<input  type="hidden" name="payment_code" value="westernunion">
                    </td>
              </tr>             
              <tr>
                <td class="left">coupon</td>
                <td class="left"><input type="text" name="coupon" value="" /></td>
              </tr>
              <tr>
                <td class="left">order status </td>
                <td class="left"><select name="order_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td class="left">comment</td>
                <td class="left"><textarea name="comment" cols="40" rows="5"><?php echo $comment; ?></textarea></td>
              </tr>
			  </table>
              <?php } else { ?>
              <tr>
                <td class="center" colspan="5">no results</td>
              </tr>
              <?php } ?>
          </tbody>
          </table>
		
		