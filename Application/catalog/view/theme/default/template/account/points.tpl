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
		 <section class="account_table">
        	<table width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<th><?php echo $column_total_points;?></th>
                	<th><?php echo $column_available_points;?></th>
                	<th><?php echo $column_pending_points;?></th>
					<th><?php echo $column_used_points;?></th>
                    <th class="red"><?php echo $column_points_for;?></th>
                </tr>
                <tr>
                	<td><?php echo $total_points;?></td>
                	<td><?php echo $available_points;?></td>
                	<td><?php echo $pending_points;?></td>
					<td><?php echo $used_points;?></td>
                	<td></td>
                </tr>
                
            </table>
        </section>
		<section class="Recent_History mt_20" style='height:auto'>
        	<ul class="tabs-list">
                <li class="active"><a href="javascript:void(0);"><?php echo $column_reward_points;?></a></li>
                <li><a href="javascript:void(0);"><?php echo $column_available_points;?></a></li>
                <li><a href="javascript:void(0);"><?php echo $column_pending_points;?></a></li>
                <li><a href="javascript:void(0);"><?php echo $column_used_points;?></a></li>
            </ul>
        	<section class="account_table">
				<section>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						
						<tr>
							<th><?php echo $column_date;?></th>
							<th style='width:110px;'><?php echo $column_point;?></th>
							<th><?php echo $column_from;?></th>
							<th><?php echo $column_status;?></th>
							<th class='last_td'><?php echo $column_notes;?></th>
						</tr>
						<?php if($rewards['list']){ ?>
							<?php foreach($rewards['list'] as $reward){ ?>
							<tr>
								<td><?php echo $reward['date_added'];?></td>
								<td class="red" style='width:110px;'><?php echo $reward['points'];?></td>
								<td><a class="blue" id="ledjj" href="<?php echo $reward['href'];?>"><?php echo $reward['from'];?></a></td>
								<td><?php echo $reward['status_des'];?></td>
								<td class='last_td'><?php echo $reward['note'];?></td>
							</tr>
							
							<?php } ?>
					
						<?php } else{ ?>
							<tr>
								<td colspan="5"><?php echo $text_empty;?></td>
							</tr>
						<?php }?>
						
					</table>
					<?php if($rewards['pagination']){ ?>
						
							<?php echo $rewards['pagination']; ?>
	   					 
				    <?php } ?>
				</section>
				<section style="display:none;">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th><?php echo $column_date;?></th>
							<th style='width:110px;'><?php echo $column_point;?></th>
							<th><?php echo $column_from;?></th>
							<th><?php echo $column_status;?></th>
							<th class='last_td'><?php echo $column_notes;?></th>
						</tr>
						<?php if($available_list['list']){ ?>
							<?php foreach($available_list['list'] as $reward){ ?>
							<tr>
								<td><?php echo $reward['date_added'];?></td>
								<td class="red" style='width:110px;'><?php echo $reward['points'];?></td>
								<td><a class="blue" id="ledjj" href="<?php echo $reward['href'];?>"><?php echo $reward['from'];?></a></td>
								<td><?php echo $reward['status_des'];?></td>
								<td class='last_td'><?php echo $reward['note'];?></td>
							</tr>
							
							<?php } ?>
					
						<?php } else{ ?>
							<tr>
								<td colspan="5"><?php echo $text_empty;?></td>
							</tr>
						<?php }?>
					</table>
					<?php if($available_list['pagination']){ ?>
						
							<?php echo $available_list['pagination']; ?>
	   					 
				    <?php } ?>
				</section>
				<section style="display:none;">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th><?php echo $column_date;?></th>
							<th style='width:110px;'><?php echo $column_point;?></th>
							<th><?php echo $column_from;?></th>
							<th><?php echo $column_status;?></th>
							<th class='last_td'><?php echo $column_notes;?></th>
						</tr>
						<?php if($pending_list['list']){ ?>
							<?php foreach($pending_list['list'] as $reward){ ?>
							<tr>
								<td><?php echo $reward['date_added'];?></td>
								<td class="red" style='width:110px;'><?php echo $reward['points'];?></td>
								<td><a class="blue" id="ledjj" href="<?php echo $reward['href'];?>"><?php echo $reward['from'];?></a></td>
								<td><?php echo $reward['status_des'];?></td>
								<td class='last_td'><?php echo $reward['note'];?></td>
							</tr>
							
							<?php } ?>
					
						<?php } else{ ?>
							<tr>
								<td colspan="5"><?php echo $text_empty;?></td>
							</tr>
						<?php }?>
					</table>
					<?php if($pending_list['pagination']){ ?>
						
							<?php echo $pending_list['pagination']; ?>
	   					 
				    <?php } ?>
				</section>
				<section style="display:none;">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th><?php echo $column_date;?></th>
							<th style='width:110px;'><?php echo $column_point;?></th>
							<th><?php echo $column_from;?></th>
							<th><?php echo $column_status;?></th>
							<th class='last_td'><?php echo $column_notes;?></th>
						</tr>
						<?php if($used_list['list']){ ?>
							<?php foreach($used_list['list'] as $reward){ ?>
							<tr>
								<td><?php echo $reward['date_added'];?></td>
								<td class="red" style='width:110px;'><?php echo $reward['points_spent'];?></td>
								<td><a class="blue" id="ledjj" href="<?php echo $reward['href'];?>"><?php echo $reward['from'];?></a></td>
								<td><?php echo $reward['status_des'];?></td>
								<td class='last_td'><?php echo $reward['note'];?></td>
							</tr>
							
							<?php } ?>
					
						<?php } else{ ?>
							<tr>
								<td colspan="5"><?php echo $text_empty;?></td>
							</tr>
						
						<?php }?>
					</table>
					<?php if($used_list['pagination']){ ?>
						
							<?php echo $used_list['pagination']; ?>
	   					 
				    <?php } ?>
				</section>
			</section>
        	
        </section>
        <section>
        <?php echo $text_how_to_get_bonus_point_in_myled;?>
        </section>
		
        <?php echo $right_bottom;?>
	</section>
</section>
<script type="text/javascript">
$(".Recent_History .tabs-list li").click(function(){
		var Index = $(this).index();
		$(this).addClass("active").siblings().removeClass("active");
		$(this).parent("ul").nextAll(".Recent_History").children(".flexslider").show().eq(Index).css("visibility","visible").siblings(".flexslider").hide().css("visibility","hidden");
		$(this).parent("ul").parent(".Recent_History").children(".account_table").children("section").show().eq(Index).css("visibility","visible").siblings("section").hide().css("visibility","hidden");
})
</script>
<?php echo $footer; ?>



  
