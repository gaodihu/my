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
		<div class="protit"><p class="black18"><?php echo $text_account_dashboard;?></p></div>
        <section>
        	<div class="intro">
        	<p><?php echo $text_account_introducing;?></p>
			</div>
            <div class="account_table acc_details_table">
			<div class="bold font13"><?php echo $text_vip_level; ?></div>
            <table width="85%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<th><?php echo $text_vip_grades;?></th>
                	<?php foreach($customer_groups as $customer_group){ ?>
					<th><?php echo $customer_group['name'];?></th>
					<?php } ?>
                </tr>
                <tr>
                	<td><?php echo $text_vip_reward_points_required; ?></td>
                	<?php foreach($customer_groups as $key=>$customer_group){ ?>
					<?php if($key<4){ ?>
					<td><?php echo $customer_group['point'];?>-<?php echo $customer_groups[$key+1]['point']-1;?> </td>
					<?php }else{ ?>
					<td><?php echo $customer_group['point'];?>+ </td>
					<?php } ?>
					<?php } ?>
                </tr>
                <tr>
                	<td><?php echo $text_vip_discounts; ?></td>
                    <td class="red"><span class="font14 mr_5">Up to</span><span class="font36">0</span><span class="aa"><span class="font24">%</span><br/>OFF</span></td>
                    <td class="red"><span class="font14 mr_5">Up to</span><span class="font36">2</span><span class="aa"><span class="font24">%</span><br/>OFF</span></td>
                    <td class="red"><span class="font14 mr_5">Up to</span><span class="font36">5</span><span class="aa"><span class="font24">%</span><br/>OFF</span></td>
                    <td class="red"><span class="font14 mr_5">Up to</span><span class="font36">7</span><span class="aa"><span class="font24">%</span><br/>OFF</span></td>
                    <td class="red"><span class="font14 mr_5">Up to</span><span class="font36">8</span><span class="aa"><span class="font24">%</span><br/>OFF</span></td>
                </tr>
            </table>
            <div class="intro">
                <div class="font13 bold"><?php echo $text_welcome;?></div>
                    <p><?php echo $text_dashboard_view;?></p>
                </div>
            </div>
        </section>
        <section class="accountDetails">
        	<div class="column">
                <article class="border left">
                    <div class="leftprotit bold"><?php echo $text_my_points;?></div>
                    <div class="columncon">
                    	<p><?php echo $text_available_points;?> <?php echo $available_points;?></p>
                        <p><?php echo $text_accumulated_points;?><?php echo $accumulated_points;?></p>
                        <p><?php echo $text_points_spent;?> <?php echo $points_spent;?></p>
                        <p><?php echo $text_validation_points;?> <?php echo $validation_points;?></p>
                    </div>
                </article>
        <!--         <article class="border right">
                    <div class="leftprotit bold"><?php echo $text_my_coupon;?></div>
                    <div class="columncon">
                    	<p><?php echo $text_my_coupon_code;?></p>
                        <p><span class="blue bold">mon001</span>;<span class="blue bold">mon002</span>;<span class="blue bold">mon003</span>;<span class="blue bold">mon004</span>;<span class="blue bold">mon005</span>;</p>
                    </div>
                </article> -->
                <div class="clear"></div>
        	</div>
        	<div class="column column2">
                <div class="border clearfix">
                    <div class="leftprotit bold"><?php echo $text_my_account_information;?></div>
                    <div class="columncon">
                    	<div class="clearfix">
                    	<article class="left">
                        	<div class="t"><span class="bold"><?php echo $text_contact_information;?></span><span class="right"><a href="/index.php?route=account/profile" class="gray"><?php echo $text_edit;?></a></span></div>
                            <div class="con"><p><?php echo $customer_info['firstname'];?></p><p><?php echo $customer_info['email'];?></p><p><a href="/index.php?route=account/password"><?php echo $text_change_password;?></a></p></div>
                        </article>
                        <article class="right">
                        	<div class="t"><span class="bold"><?php echo $text_newsletters;?></span><span class="right"><a href="/index.php?route=account/newsletter" class="gray"><?php echo $text_edit;?></a></span></div>
                            <div class="con"><p><?Php echo $text_newsletters_to;?></p></div>
                        </article>
                        </div>
                        <div class="clearfix">
                    	<article class="left">
                        	<div class="t"><span class="bold"><?php echo $text_address_book;?></span></div>
                            <div class="con"><p class="bold"><?php echo $text_default_biiling;?></p>
							<?php if($default_billing_address){ ?> 
							<?php echo $default_billing_address;?>
							<?php }else{ ?>
							<p><?php echo $text_default_biiling_empty;?></p>
							<?php } ?>
							
							
							<p><a href="/index.php?route=account/address"><?php echo $text_edit;?></a></p></div>
                        </article>
                        <article class="right">
                        	<div class="t"><span class="right"><a href="/index.php?route=account/address" class="gray"><?php echo $text_Manage_Addresses;?></a></span></div>
                            <div class="con"><p class="bold"><?php echo $text_default_shipping;?></p>
							<?php if($default_shipping_address){ ?>
							<?php echo $default_shipping_address;?> 
							<?php }else{ ?>
							<p><?php echo $text_default_shipping_empty;?></p>
							<?php } ?>
							<p><a href="/index.php?route=account/address"><?php echo $text_edit;?></a></p></div>
                        </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php echo $right_bottom;?>
	</section>	
</section>

<div class="fix-layout">
	<div class="gb-operation-area" id="_returnTop_layout_inner">
		<a class="gb-operation-button return-top" id="goto_top_btn" href="javascript:;"><i title="Return Top" class="gb-operation-icon"></i>
		<span class="gb-operation-text">Top</span>
		</a>
		
	</div>
</div>

<?php echo $footer; ?>

