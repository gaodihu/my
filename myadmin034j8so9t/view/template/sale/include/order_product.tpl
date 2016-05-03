 <?php $product_row = 0; ?>
 
 
			<?php $option_row = 0; ?>
			<?php $download_row = 0; ?>
 <?php if ($order_products) { ?>
              <?php foreach ($order_products as $key=>$order_product) { ?>
              <tr id="product-row<?php echo $product_row; ?>">
                <td class="center" style="width: 3px;"><img src="view/image/delete.png" title="remove" alt="remove"   style="cursor: pointer;" onclick="$('#product-row<?php echo $product_row; ?>').remove(); $('#button-update').trigger('click');add_product_delete('<?php echo $key; ?>')" /></td>
                <td class="left"><?php echo $order_product['name']; ?><br />
                  <input type="hidden" name="order_product[<?php echo $product_row; ?>][product_id]" value="<?php echo $order_product['product_id']; ?>" />
                  <input type="hidden" name="order_product[<?php echo $product_row; ?>][name]" value="<?php echo $order_product['name']; ?>" />
     
				</td>
                <td class="left"><?php echo $order_product['model']; ?>
                  <input type="hidden" name="order_product[<?php echo $product_row; ?>][model]" value="<?php echo $order_product['model']; ?>" /></td>
                <td class="right"><?php echo $order_product['quantity']; ?>
                  <input type="hidden" name="order_product[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>" /></td>                 
                <td class="right"><?php echo $curreny_code;?><?php echo $order_product['currency_price']; ?>(USD<?php echo $order_product['price']; ?>)
				  <input type="hidden" name="order_product[<?php echo $product_row; ?>][original_price]" value="<?php echo $order_product['original_price']; ?>" /> 
                  <input type="hidden" name="order_product[<?php echo $product_row; ?>][price]" value="<?php echo $order_product['price']; ?>" />
				  <input type="hidden" name="order_product[<?php echo $product_row; ?>][currency_price]" value="<?php echo $order_product['currency_price']; ?>" />
				  <input type="hidden" name="order_product[<?php echo $product_row; ?>][currency_total]" value="<?php echo $order_product['currency_total']; ?>" />
				  </td>
                <td class="right"><?php echo $curreny_code;?><?php echo $order_product['currency_total']; ?>(USD<?php echo $order_product['total']; ?>)
                  <input type="hidden" name="order_product[<?php echo $product_row; ?>][total]" value="<?php echo $order_product['total']; ?>" />
                  <input type="hidden" name="order_product[<?php echo $product_row; ?>][tax]" value="<?php echo $order_product['tax']; ?>" />
                  <input type="hidden" name="order_product[<?php echo $product_row; ?>][reward]" value="<?php echo $order_product['reward']; ?>" /></td>
              </tr>
              <?php $product_row++; ?>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>