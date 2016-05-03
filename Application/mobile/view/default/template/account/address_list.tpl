<?php echo $header; ?>
<div class="head-title">
	<a class="icon-angle-left left-btn"></a><?php echo $heading_title;?><a class="icon-plus right-btn" href="<?php echo $add_address;?>"></a>
</div>
<?php if($success){ ?>
<div class="msg-info error_bg"><?php echo $success;?></div>
<?php } ?>
	<div class="msg-info"><?php echo $text_shipping_address_book;?></div>
        <?php if(empty($shipping_addresses)){ ?>
        <div class="text-c f24 p_20">Null</div>
        <?php } ?>
        <ul  class="tab-chagne a-block tab-active address-list">
			<?php foreach($shipping_addresses as $address){ ?>
			<?php if($address['default']){ ?>
            <li class="active">
			<?php }else{ ?>
			<li >
			<?php } ?>
                <a class="radius-btn more-info"><span class="icon-angle-down"></span></a>

                <?php echo $address['address_name'];?> <?php if($address['default']){ ?>( <span class="green-color"><?php echo $text_default;?></span> )<?php } ?><br>
                <span class="grey"><?php echo $address['address'];?></span>
                <div class="more-box">
                  <a class="button orange-bg"  href="<?php echo $address['update'];?>"><?php echo $button_edit;?></a> <a class="button green-btn" href="<?php echo $address['default_href'];?>"><?php echo ucwords($text_default);?></a> <a class="button grey-bg-btn address-del" href="<?php echo $address['delete'];?>"><?php echo $button_delete;?></a>
                </div>
            </li>
			<?php } ?>
        
        </ul>
		<div class="msg-info"><?php echo $text_billing_address_book;?></div>
<?php if(empty($billing_addresses)){ ?>
<div class="text-c f24 p_20">Null</div>
            <?php } ?>
        <ul  class="tab-chagne a-block tab-active address-list">
		 <?php foreach($billing_addresses as $b_address){ ?>
            <?php if($b_address['default']){ ?>
            <li class="active">
			<?php }else{ ?>
			<li >
			<?php } ?>
              <a class="radius-btn more-info"><span class="icon-angle-down"></span></a>

                <?php echo $b_address['address_name'];?> <?php if($b_address['default']){ ?>( <span class="green-color"><?php echo $text_default;?></span> )<?php } ?><br>
                <span class="grey"><?php echo $b_address['address'];?></span>
                <div class="more-box">
                   <a class="button orange-bg"  href="<?php echo $b_address['update'];?>"><?php echo $button_edit;?></a> <!--<a class="button green-btn" href="<?php echo $b_address['default_href'];?>"><?php echo ucwords($text_default);?></a>--> <!--<a class="button grey-bg-btn address-del" href="<?php echo $b_address['delete'];?>"><?php echo $button_delete;?></a>-->
                </div>
            </li>
			<?php } ?>
            
        </ul>
<?php echo $footer; ?>