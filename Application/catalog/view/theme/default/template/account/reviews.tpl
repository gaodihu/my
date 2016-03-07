<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?><img src="css/images/close.png" alt="" class="close" /></div>
<?php } ?>
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
		<section>
          <?php echo $text_riviews_verify;?>
          <?php if($text_order_no_reviews) { ?><section class="order_search notice_img" style="height:28px;padding:10px" ><span class="bold"><?php echo $text_order_no_reviews; ?></span></section><?php } ?>
        </section>

		<section class="mt_20">
        	<!--<ul class="tabs-list">
                <li class="active"><a href="javascript:;">Write a Review</a></li>
                <li><a href="javascript:;">Post Images</a></li>
                <li><a href="javascript:;">Post a Video</a></li>
            </ul>-->
			<ul class="tabs-list">
                <li class="active"><a href="javascript:;"><?php echo $text_write_reviews;?></a></li>
            </ul>
        	<section class="account_table">
				<section>
				<?php if($reviews){ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th width='15%'><?php echo $column_products;?></th>
							<th width='25%'><?php echo $column_title;?></th>
							<!--<th><?php echo $column_gained_points;?></th>-->
							<th width='40%'><?php echo $column_reviews;?></th>
							<th width='10%'>Status</th>
							<th width='10%'>Points</th>
						</tr>
						<?php foreach($reviews as $review ){ ?>
							<tr>
							<td><img src="<?php echo $review['thumb'];?>" alt="<?php echo $review['name'];?>"/></td>
							<td  class="wordbreak"><a class="blue" id="ledjj" href="<?php echo $review['href'];?>"><?php echo $review['name'];?></a></td>
							<!--<td>20</td>-->
							<td class="wordbreak "><div class="maxheigt2"><?php echo $review['text'];?></div></td>
							<td><?php echo $review['status_text'];?></td>
							<td><?php if($review['status']==1){echo 20;} else{ echo 0;} ?></td>
						</tr>
						<?php } ?>
						
						
					</table>
                    
						<?php echo $pagination;?>
					
				<?php } else{ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th width='30%'><?php echo $column_products;?></th>
							<th width='20%'><?php echo $column_title;?></th>
							<!--<th><?php echo $column_gained_points;?></th>-->
							<th width='30%'><?php echo $column_reviews;?></th>
							<th width='10%'>Status</th>
							<th width='10%'>Points</th>
						</tr>
						<tr>
							<td colspan="5"><?php echo $text_empty;?></td>
							
						</tr>
						
					</table>
				<?php } ?>
				</section>
			</section>
        	
        </section>
        
	   	
        <?php echo $right_bottom;?>
	</section>	
</section>
<?php echo $footer; ?>

