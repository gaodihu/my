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
            <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_merchant_no; ?>(EUR)</td>
                        <td><input type="text" name="globebill_webmoney_merchant_no" value="<?php echo $globebill_webmoney_merchant_no; ?>" />
                            <?php if ($error_merchant_no) { ?>
                            <span class="error"><?php echo $error_merchant_no; ?></span>
                            <?php } ?></td>
                    </tr>
                    
                   <tr>
                        <td><span class="required">*</span> <?php echo $entry_payment_gateway; ?>(EUR)</td>
                        <td><input type="text" name="globebill_webmoney_payment_gateway" value="<?php echo $globebill_webmoney_payment_gateway; ?>" />
                            <?php if ($error_payment_gateway) { ?>
                            <span class="error"><?php echo $error_payment_gateway; ?></span>
                            <?php } ?></td>
                    </tr>
                    
                   <tr>
                        <td><span class="required">*</span> <?php echo $entry_signkey_code; ?>(EUR)</td>
                        <td><input type="text" name="globebill_webmoney_signkey_code" value="<?php echo $globebill_webmoney_signkey_code; ?>" />
                            <?php if ($error_signkey_code) { ?>
                            <span class="error"><?php echo $error_signkey_code; ?></span>
                            <?php } ?></td>
                    </tr>
                    
                    
                    
                  <tr>
                        <td><span class="required">*</span> <?php echo $entry_merchant_no; ?>(USD)</td>
                        <td><input type="text" name="globebill_webmoney_merchant_no_usd" value="<?php echo $globebill_webmoney_merchant_no_usd; ?>" />
                            <?php if ($error_merchant_no_usd) { ?>
                            <span class="error"><?php echo $error_merchant_no_usd; ?></span>
                            <?php } ?></td>
                    </tr>
                    
                   <tr>
                        <td><span class="required">*</span> <?php echo $entry_payment_gateway; ?>(USD)</td>
                        <td><input type="text" name="globebill_webmoney_payment_gateway_usd" value="<?php echo $globebill_webmoney_payment_gateway_usd; ?>" />
                            <?php if ($error_payment_gateway_usd) { ?>
                            <span class="error"><?php echo $error_payment_gateway_usd; ?></span>
                            <?php } ?></td>
                    </tr>
                    
                   <tr>
                        <td><span class="required">*</span> <?php echo $entry_signkey_code; ?>(USD)</td>
                        <td><input type="text" name="globebill_webmoney_signkey_code_usd" value="<?php echo $globebill_webmoney_signkey_code_usd; ?>" />
                            <?php if ($error_signkey_code_usd) { ?>
                            <span class="error"><?php echo $error_signkey_code_usd; ?></span>
                            <?php } ?></td>
                    </tr>
                    
                    
                    
                  <tr>
                        <td><span class="required">*</span> <?php echo $entry_transport_url; ?></td>
                        <td><input type="text" name="globebill_webmoney_transport_url" value="<?php echo $globebill_webmoney_transport_url; ?>" />
                            <?php if ($error_transport_url) { ?>
                            <span class="error"><?php echo $error_transport_url; ?></span>
                            <?php } ?></td>
                    </tr>
                    
                    
                    <tr>
                        <td><?php echo $entry_total; ?></td>
                        <td><input type="text" name="globebill_webmoney_total" value="<?php echo $globebill_webmoney_total; ?>" /></td>
                    </tr>          
                    <tr>
                        <td><?php echo $entry_canceled_reversal_status; ?></td>
                        <td><select name="globebill_webmoney_canceled_reversal_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_canceled_reversal_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_completed_status; ?></td>
                        <td><select name="globebill_webmoney_completed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_completed_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_denied_status; ?></td>
                        <td><select name="globebill_webmoney_denied_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_denied_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_expired_status; ?></td>
                        <td><select name="globebill_webmoney_expired_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_expired_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_failed_status; ?></td>
                        <td><select name="globebill_webmoney_failed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_failed_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_pending_status; ?></td>
                        <td><select name="globebill_webmoney_pending_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_pending_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    
                    <tr>
                        <td><?php echo $entry_payment_review_status; ?></td>
                        <td><select name="globebill_webmoney_payment_review_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_payment_review_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    
                    <tr>
                        <td><?php echo $entry_processed_status; ?></td>
                        <td><select name="globebill_webmoney_processed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_processed_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_refunded_status; ?></td>
                        <td><select name="globebill_webmoney_refunded_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_refunded_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_reversed_status; ?></td>
                        <td><select name="globebill_webmoney_reversed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_reversed_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_voided_status; ?></td>
                        <td><select name="globebill_webmoney_voided_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $globebill_webmoney_voided_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="globebill_webmoney_status">
                                <?php if ($globebill_webmoney_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="globebill_webmoney_sort_order" value="<?php echo $globebill_webmoney_sort_order; ?>" size="1" /></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?> 