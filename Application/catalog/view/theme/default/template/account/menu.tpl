<aside class="boxLeft left">
		<section class="border">
			<div class="leftprotit bold"><?php echo $text_my_account;?></div>
			<div class="Account_user">
                <div class="text">
					<?php if($customer_info['avatar']){ ?>
            		<img src="<?php echo $customer_info['avatar'];?>" alt="<?php echo $customer_info['nickname'];?>"/>
					<?php }else{ ?>
					<img src="<?php  echo STATIC_SERVER; ?>css/images/user_tx/user_default.png" alt="<?php echo $customer_info['nickname'];?>"/>
					<?php } ?>
                	<ul>
                    	<li><?php echo $customer_info['nickname'];?></li>
						<li>VIP: <?php echo $customer_group_name;?></li>
                       <!-- <li><span class="hua"></span><span class="new"></span></li>-->
                    </ul>
                </div>
                <div class="userPoints">Points:<span class="red bold"><?php echo $points;?></span><!--Coupon:<span class="blue bold">5</span>--></div>
            </div>
			<!--用户菜单-->
            <div class="Account_nav">
            	<ul>
					<?php foreach ($account_menus as $menu){ ?>
						<?php if($menu['is_active']){ ?>
						<li><a href="<?php echo $menu['link'];?>" class="active"><?php echo $menu['text'];?></a></li>
						<?php } else{ ?>
						<li><a href="<?php echo $menu['link'];?>"><?php echo $menu['text'];?></a></li>
						<?php } ?>
					<?php } ?>
                </ul>
            </div>
		</section>
	</aside>