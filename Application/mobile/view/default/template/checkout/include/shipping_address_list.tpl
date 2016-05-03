<?php echo $header;?>
 <div class="head-title">
	<a class="icon-angle-left left-btn"></a><?php echo $heading_title;?> <a class="icon-plus right-btn" href="<?php echo $this->url->link('account/address/add','','SSL');?>"></a>
</div>
<div class="spacing"></div>

<ul  class="tab-chagne a-block tab-active address-list">
	<?php if(!empty($shipping_address_list)){
	  foreach($shipping_address_list as $address){ 
	 ?>
	 <?php if($checked_address['address_id']==$address['address_id']){ ?>
	<li class="active">
	<?php }else{ ?>
	<li >
	<?php } ?>
		<a class="radius-btn more-info"><span class="icon-angle-down"></span></a>
		<?php if($logged ){ ?>
			<?php if($checked_address['address_id']==$address['address_id']){ ?>
			<input name="address" type="radio" checked="checked" value="<?php echo $address['address_id'];?>" dom='address_radio' class="shipping_radio"/><?php echo $address['firstname']." ".$address['lastname'];?> ( <span class="green-color">Default</span> )<br>
			<?php }else{ ?>
			<input name="address" type="radio" value="<?php echo $address['address_id'];?>" dom='address_radio' class="shipping_radio"/><?php echo $address['firstname']." ".$address['lastname'];?> <br>
			<?php } ?>
		<?php } else { ?>
			<input name="address" type="radio" value="" dom='address_radio' checked="checked" class="shipping_radio"/><?php echo $address['firstname']." ".$address['lastname'];?><br>
		<?php } ?>
		<!-- <b class="icon-check"></b> -->
		
		<span class="grey"> <?php echo $address['address_1']. " " . $address['address_2'] . " ".$address['city']." ".$address['zone']." ".$address['country']."(".$address['postcode'].")"." ".$address['phone'];?></span>
		<div class="more-box">
		   <a class="button orange-bg"  href="<?php echo $this->url->link('account/address/update','address_id='.$address['address_id'],'SSL');?>">Edit</a> <a class="button green-btn" href="<?php echo $this->url->link('account/address/defaultAddress','address_id='.$address['address_id'],'SSL');?>">Default</a> <a class="button grey-bg-btn address-del" href="<?php echo $this->url->link('account/address/delete','address_id='.$address['address_id'],'SSL');?>">Del</a>
		</div>
	</li>
	<?php } ?>
	<?php }else {?>
	<div class="no_address">you have no  address now</div>
	<?php } ?>
</ul>
<script>
$( ".address-list li").on("click",function() {

        var address_id = $(this).find("input[name='address']").val();
		
        $.ajax({
            url: 'index.php?route=checkout/address/changeAddress',
            type: 'post',
            data: 'address_id=' + address_id,
            dataType: 'json',
            success: function(json) {
				
                window.location.href=json['redirect'];
            }
        });


})
</script>
<?php echo $footer;?>


 
 