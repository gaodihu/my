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
	<?php echo $menu;?>
	<section class="boxRight">
		<?php echo $right_top;?>
		<div class="protit"><p class="black18"><?php echo $heading_title;?></p></div>
		<?php if ($success) { ?>
			<div class="success"><?php echo $success; ?></div>
		<?php } ?>
		<?php if ($error_warning) { ?>
			<div class="success"><?php echo $error_warning; ?></div>
		<?php } ?>
		<section>
          <p><br/><?php echo $text_addres_used;?></p>
        </section>
		<section class="address">
        	<h4><?php echo $text_shipping_address_book;?><span class="enter_new redbtn addss" onclick="show_add_from()"><a href="javascript:void(0)" class="btn" style="margin:0px;" type='shipping' from='account'><?php echo $button_new_shipping_address;?></a></span></h4>
			<?php if($shipping_addresses){
				foreach($shipping_addresses as $shipping){
			?>
			<section class="border padtop">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td class="number">1</td>
							<td>
                              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr><td class="name"><?php echo $shipping['address_name'];?>
								<?php if($shipping['default']){ ?>
								<span>Default Address</span>
								<?php } ?>
								</td></tr>
                                <tr><td class="namedown"><span>Address:</span><?php echo $shipping['address'];?></td></tr>
                                <tr>
                                  <td>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                      <tr><td class="namedown"><span><?php echo $entry_phone;?></span><?php echo $shipping['phone'];?></td>
									  <td class="namedown padding"><span>Tax Id:</span><?php echo $shipping['tax_id'];?></td>
									  <!--<td class="namedown"><span>E-mail:</span>lucy000000@gmail.com</td></tr>-->
                                    </table>
                                  </td>
                                </tr>
                              </table>
                          </td>
							<td class="edit"><a href="javascript:void(0)" class="common-btn-gray edit_l editbtn" id="shipping-address-edit" address-id="<?php echo $shipping['address_id'];?>" address_type='shipping'  from='account'><?php echo $button_edit;?></a><a href="<?php echo $shipping['delete'];?>" class="common-btn-gray edit_l"><?php echo $button_delete;?></a></td>
						</tr>
					</table>
			</section>
			<?php	
				}
			}
			?>
        	
        </section>
        <section class="Recent_History mt_20">
        	<h4><?php echo $text_billing_address_book;?><span class="enter_new redbtn addss">
                        <?php if(!$billing_addresses || count($billing_addresses) == 0){ ?>
                            <a href="javascript:void(0)" class="btn" style="margin:0px;" type='billing' from='account'><?php echo $button_new_billing_address;?></a>
                        <?php } ?>
                    </span></h4>
			<?php if($billing_addresses){
				foreach($billing_addresses as $shipping){
			?>
        	<section class="border padtop">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td class="number">1</td>
							<td>
                              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr><td class="name"><?php echo $shipping['address_name'];?>
								<?php if($shipping['default']){ ?>
								<span>Default Address</span>
								<?php } ?>
								</td></tr>
                                <tr><td class="namedown"><span>Address:</span><?php echo $shipping['address'];?></td></tr>
                                <tr>
                                  <td>
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                      <tr><td class="namedown"><span>Phone:</span><?php echo $shipping['phone'];?></td>
									  <!--<td class="namedown padding"><span>Moblie:</span>0755-8695241</td>
									  <td class="namedown"><span>E-mail:</span>lucy000000@gmail.com</td></tr>-->
                                    </table>
                                  </td>
                                </tr>
                              </table>
                          </td>
							<td class="edit"><a href="javascript:void(0)" class="common-btn-gray edit_l editbtn" id="shipping-address-edit" address-id="<?php echo $shipping['address_id'];?>" address_type='billing' from='account'><?php echo $button_edit;?></a><a href="<?php echo $shipping['delete'];?>" class="common-btn-gray edit_l"><?php echo $button_delete;?></a></td>
						</tr>
					</table>
				</section>
			<?php	
				}
			}
			?>	
        </section>
	   	
        <?php echo $right_bottom;?>
	</section>	
</section>
<!--弹窗-->
<div id='address_form'>
	<?php include_once(DIR_TEMPLATE.'/default-vertical/template/checkout/include/shipping_address_from.tpl');?>
</div>

<?php echo $footer; ?>