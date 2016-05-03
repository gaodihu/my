
    <?php $total_row = 0; ?>
              <?php if ($order_products || $order_vouchers || $order_totals) { ?>
              <?php foreach ($order_products as $order_product) { ?>
              <tr>
                <td class="left"><?php echo $order_product['name']; ?><br />
                  <?php foreach ($order_product['option'] as $option) { ?>
                  - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                  <?php } ?></td>
                <td class="left"><?php echo $order_product['model']; ?></td>
                <td class="right"><?php echo $order_product['quantity']; ?></td>
                <td class="right"><?php echo $curreny_code;?><?php echo $order_product['currency_price']; ?>(USD<?php echo $order_product['price']; ?>)</td>
                <td class="right"><?php echo $curreny_code;?><?php echo $order_product['currency_total']; ?>(USD<?php echo $order_product['total']; ?>)</td>
              </tr>
              <?php } ?>
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
              <?php } else { ?>
              <tr>
                <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>