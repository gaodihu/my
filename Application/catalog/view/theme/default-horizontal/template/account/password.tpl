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
			<div class="success"><?php echo $success; ?><img src="<?php  echo STATIC_SERVER; ?>css/images/close.png" alt="" class="close" /></div>
		<?php } ?>
		<section class="mt_20 possword">
		<form action="" method="post" enctype="multipart/form-data">
          <div class="form pw_list" >
        	<ul>
            	<li class="text w-xl"><span class="left"><?php echo $entry_old_password;?></span><input name="old_password" type="password" value="<?php echo $old_password;?>"/>
					<span class="info"><?php echo $error_old_password;?></span>
				</li>
                <li class="text w-xl"><span class="left"><?php echo $entry_password;?></span><input name="password" type="password" value="<?php echo $password;?>"/>
					<span class="info"><?php echo $error_password;?></span>
				</li>
            	<li class="text w-xl"><span class="left"><?php echo $entry_confirm;?></span><input name="confirm" type="password" value="<?php echo $confirm;?>"/>
					<span class="info"><?php echo $error_confirm;?></span>
				</li>
            </ul>
            <div class="p_b20">
                <input type="submit" class="common-btn-orange btn-big" value="Save"/>
            </div>
        </div>
		</form>
        </section>
        <?php echo $right_bottom;?>
	</section>	
</section>


<?php echo $footer; ?>