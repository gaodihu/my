<?php echo $header;?>
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
<section class="points-box wrap">
	<div class="points-top mt_12">
		<h4><?php echo $text_1;?></h4>
		<p><?php echo $text_2;?></p>
		<a class="points-top-pointer bold"><?php echo $text_3;?></a>
	</div>
	
	<div class="points-content">
		<h4><?php echo $text_4;?></h4>
		<ul class="points-list clearfix">
			<li>
				<div class="p-left">
				<div class="tit"><?php echo $text_5;?></div>
				<img src="<?php  echo STATIC_SERVER; ?>css/images/points/img1.png" />
				<div class="p-con-tit">** <?php echo $text_6;?></div>
				<p><?php echo $text_7;?></p>
				</div>
			</li>
			<li >
				<div class="p-mid">
				<div class="tit"><?php echo $text_8;?></div>
				<img src="<?php  echo STATIC_SERVER; ?>css/images/points/img2.png" />
				<div class="p-con-tit"><?php echo $text_9;?></div>
				<p><?php echo $text_10;?></p>
				</div>
			</li>
			<li >
				<div class="p-right">
				<div class="tit"><?php echo $text_11;?></div>
				<img src="<?php  echo STATIC_SERVER; ?>css/images/points/img3.png" />
				<div class="p-con-tit">** <?php echo $text_6;?></div>
				<p><?php echo $text_12;?></p>
				</div>
			</li>
		</ul>
		
		<div class="points-msg">
			<p class="p-msg-tit"><?php echo $text_13;?></p>
			<p><?php echo $text_14;?></p>

			<p  class="p-msg-tit m-t15"><?php echo $text_15;?></p>
			<p><?php echo $text_16;?></p>

			<p  class="p-msg-tit m-t15"><?php echo $text_17;?></p>
			<p><?php echo $text_18;?></p>


			<h4 class="m-t50 m-b10"><?php echo $text_19;?></h4>
			<p><?php echo $text_20;?></p>
			<p class="points-img-right"><img src="<?php  echo STATIC_SERVER; ?>css/images/points/points_<?php echo strtolower($this->session->data['language']);?>.jpg" /></p>
		
		
			<p class="fontsize16 bold m-b10"><?php echo $text_21;?></p>
			<p><?php echo $text_22;?> </p>
			<p><?php echo $text_23;?></p>
            <p><?php echo $text_24;?></p>
		</div>
	</div>
</section>	
<?php echo $footer;?>
