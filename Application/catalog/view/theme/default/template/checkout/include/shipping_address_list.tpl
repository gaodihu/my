<?php 
if(!empty($shipping_address_list)){
  foreach($shipping_address_list as $address){
?>

<div class="checkout_con  clearfix">
                	<div class="checkout_con_left">
                        <label>
                            <?php if($logged ){ ?>
                                <?php if($checked_address['address_id']==$address['address_id']){ ?>
                                <input name="address" type="radio" checked="checked" value="<?php echo $address['address_id'];?>" dom='address_radio'/>
                                <?php }else{ ?>
                                <input name="address" type="radio" value="<?php echo $address['address_id'];?>" dom='address_radio'/>
                                <?php } ?>
                            <?php } else { ?>
                                <input name="address" type="radio" value="" dom='address_radio' checked="checked"/>
                            <?php } ?>
                    	
                    	<span class="bold"><?php echo $address['firstname']." ".$address['lastname'];?></span>
                        </label>
                    </div>
                    <div class="checkout_con_center" style="width: 50%;">
                        <?php echo $address['address_1']. " " . $address['address_2'] . " ".$address['city']." ".$address['zone']." ".$address['country']."(".$address['postcode'].")"." ".$address['phone'];?>
						<br>
						<?php echo $address['company'];?>
						<?php if($address['company']){ echo ','; } ?>
						<?php echo $address['tax_id'];?>
                    </div>
                    <div class="checkout_con_right">
                    <a href="javascript:void(0)" class="blue" id='shipping-address-edit' address-id="<?php echo $address['address_id'];?>">Edit</a>
					   <?php if($logged){ ?>
                                            <a href="javascript:void(0)" class="blue" id='shipping-address-delete' address-id="<?php echo $address['address_id'];?>">Delete</a>
                                        
                                            <?php if($default_address['address_id']==$address['address_id']){ ?>
                                                <span class="default">Default</span>
                                            <?php }else{ ?>
                                                <a href="javascript:void(0);" id='shipping-address-default' address-id="<?php echo $address['address_id'];?>">Default</a>
                                            <?php }?> 
                                        <?php } ?>
					
                    </div>
                </div>
<?php
   }
}
else{
?>
<div>you have no  address now</div>
<?php
}
?>

<script>

    $(".checkout_con_left  input:checked").parents(".checkout_con").addClass("active");
    $(".checkout_con_left label").click(function(){
        $(".checkout_con").removeClass("active");
        $(this).parents(".checkout_con").addClass("active");

    })
</script>


 
 