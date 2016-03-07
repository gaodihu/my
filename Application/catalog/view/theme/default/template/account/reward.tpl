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
		<?php if($rewards){ ?>
		<section class="account_table">
    		<table width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<th><?php echo $column_order_number;?></th>
                	<th><?php echo $column_points;?></th>
                	<th><?php echo $column_points_spent;?></th>
                	<th><?php echo $column_status;?></th>
                	<th><?php echo $column_date_added;?></th>
					<th><?php echo $column_date_confirm;?></th>
                    <th><?php echo $column_action;?></th>
                </tr>
				
					<?php foreach($rewards as $reward){ ?>
					<tr>
						<td><a href="<?php echo $reward['href'];?>"><?php echo $reward['order_number'];?></a></td>
						<td class="blue"><?php echo $reward['points'];?></td>
						<td class="red"><?php echo $reward['points_spent'];?></td>
						<td><?php echo $reward['status_des'];?></td>
						<td><?php echo $reward['date_added'];?></td>
						<td><?php echo $reward['date_confirm'];?></td>
						<td><a href="<?php echo $reward['href'];?>"><?php echo $column_view;?></a> </td>
					</tr>
					<?php } ?>
				
            </table>	
		</section>	
		
			<?php echo $pagination; ?>
	  
		<?php } else{ ?>
		<section class="account_table">
    		<table width="100%" cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<th><?php echo $column_order_number;?></th>
                	<th><?php echo $column_points;?></th>
                	<th><?php echo $column_points_spent;?></th>
                	<th><?php echo $column_status;?></th>
                	<th><?php echo $column_date_added;?></th>
                    <th><?php echo $column_action;?></th>
                </tr>
					<tr>
						<td colspan="6"> <?php echo $text_empty;?></td>
					</tr>	
            </table>	
		</section>	
		
		<?php } ?>
		
        <?php echo $right_bottom;?>
	</section>
<div class="fix-layout">
	<div class="gb-operation-area" id="_returnTop_layout_inner">
		<a class="gb-operation-button return-top" id="goto_top_btn" href="javascript:;"><i title="返回顶部" class="gb-operation-icon"></i>
		<span class="gb-operation-text">顶部</span>
		</a>
		<a class="gb-operation-button hot-msg" id="site_hot_btn" href="javascript:;">
		<i title="空间热点" class="gb-operation-icon"></i>
		<span class="gb-operation-text">热点</span>
		</a>
	</div>
</div>
<?php echo $footer; ?>



  
