      <table class="list">
				<?php $total_row=1;?>
			  <?php foreach ($order_totals as $order_total) { ?>
			  
              <tr id="total-row<?php echo $total_row; ?>">
                <td class="right" colspan="4"><?php echo $order_total['title']; ?>:
                  <input type="hidden" name="order_total[<?php echo $total_row; ?>][order_total_id]" value="<?php echo $order_total['order_total_id']; ?>" />
                  <input type="hidden" name="order_total[<?php echo $total_row; ?>][code]" value="<?php echo $order_total['code']; ?>" />
                  <input type="hidden" name="order_total[<?php echo $total_row; ?>][title]" value="<?php echo $order_total['title']; ?>" />
                  <input type="hidden" name="order_total[<?php echo $total_row; ?>][text]" value="<?php echo $order_total['text']; ?>" />
                  <input type="hidden" name="order_total[<?php echo $total_row; ?>][value]" value="<?php echo $order_total['value']; ?>" />
                  <input type="hidden" name="order_total[<?php echo $total_row; ?>][sort_order]" value="<?php echo $order_total['sort_order']; ?>" /></td>
                <td class="right"><?php echo $curreny_code;?><?php echo $order_total['text']; ?>(USD<?php echo $order_total['value']; ?>)</td>
              </tr>
			 
              <?php $total_row++; ?>
              <?php } ?>
		</table>