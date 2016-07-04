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
        <section class="order_search">
			<form action="<?php echo $action;?>" enctype="multipart/form-data" method="post">
        	<div class="orderNumber left"><span class="bold left"><?php echo $text_order_number;?>：</span><div class="input"><input name="order_number" type="text" class="text" value='<?php echo $order_number;?>'/><input name="submit" type="submit" value="<?php echo $text_sreach;?>" class="common-btn-orange tjbtn"/></div>
            </div>	
            <div class="orderDate right">
            	<span class="bold"><?php echo $text_order_dataed;?>：</span><input type="text" value='<?php echo $date_from;?>' class="datepicker" id="from"  name="date_from" style="z-index:100;position:relative;"/>－<input id="to" type="text" value='<?php echo $date_to;?>' class="datepicker"  name="date_to" style="z-index:100;position:relative;"/>
            </div>
			</form>
        </section>
                
                <?php if($text_order_no_reviews) { ?><section class="order_search notice_img" style="height:28px;padding:10px" ><span class="bold"><?php echo $text_order_no_reviews; ?></span></section><?php } ?>
		<?php if($orders){ ?>
        <section class="account_table">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="table-main-box">
                   <tbody >
					<tr>
						<th width="14%"><?php echo $text_date_added;?></th>
						<th><?php echo $text_order_id;?></th>
						<th><?php echo $text_total;?> </th>
						<th><?php echo $text_status;?> </th>
						<th><?php echo $text_shipping_method;?></th>
						<th><?php echo $text_tracking_no;?></th>
						<th><?php echo $text_action;?></th>
					</tr>
                   </tbody>
						<?php foreach($orders as $order){ ?>
                       <tbody >
						<tr>
                            <td class="p-l20 setrelative"><?php if( is_array($order['children']) && count($order['children'])>0){ ?><span class="box-show add-icon absolute-left"></span><?php } ?> <?php echo $order['date_added'];?></td>
							<td class="blue"><a href="<?php echo $order['href'];?>"><?php echo $order['order_number'];?></a></td>
							<td class="red"><?php echo $order['total'];?></td>
							<td><?php if( is_array($order['children']) && count($order['children'])>0){ echo '--';}else{ echo $order['status'];} ?></td>
							<td><?php if($order['shipping_method']){ echo $order['shipping_method'];}else{ echo '--';}?></td>
							<td><?php echo $order['tracking_number'];?></td>
							<td><a href="<?php echo $order['reorder'];?>" class="common-btn-gray">copy</a></td>
                                                        
						</tr>
                       </tbody>
                                                <?php if( is_array($order['children']) && count($order['children'])>0){ ?>
                                                    <tbody class="table-box-detail" style="display: none">
                                                     <?php foreach($order['children'] as $_item){ ?>
                                                                <tr >
                                                                    <td  width="14%">&nbsp;</td>
                                                                    <td class="blue"><a href="<?php echo $_item['href'];?>"><?php echo $_item['order_number'];?></a></td>
                                                                    <td class="red"><?php echo $_item['total'];?></td>
                                                                    <td><?php echo $_item['status'];?></td>
                                                                    <td><?php echo $_item['shipping_method'];?></td>
                                                                    <td><?php echo $_item['tracking_number'];?></td>
                                                                    <td><a href="<?php echo $_item['reorder'];?>" class="common-btn-gray">copy</a></td>
                                                                </tr>
                                                            <?php } ?>
                                                    </tbody>

                                                        <?php } ?>
                                                
						<?php } ?>
					
				</table>
                                <script>
                                       $(".table-main-box .box-show").click(function(){
                                            $(this).parents("tbody").next(".table-box-detail").toggle();
                                            $(this).toggleClass("del-icon");
                                             
                                        }) 
                                </script>    
		</section>
			
				<?php echo $pagination; ?>
		  
		<?php } else{ ?>
		<section class="account_table">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<th><?php echo $text_date_added;?></th>
						<th><?php echo $text_order_id;?></th>
						<th><?php echo $text_total;?> </th>
						<th><?php echo $text_status;?></th>
						<th><?php echo $text_shipping_method;?> </th>
						<th><?php echo $text_tracking_no;?></th>
					</tr>
					
					<tr>
						<td colspan="6"> <?php echo $text_empty;?></td>
					</tr>
					
				</table>
		</section>	
		<?php } ?>
		
        <?php echo $right_bottom;?>
	</section>
</section>

<script type="text/javascript" src="js/jquery/ui/jquery-ui.min.js"></script>
<script>

	$('.datepicker').datepicker({
	  dateFormat: 'yy-mm-dd',
	  changeMonth: true,
	  changeYear: true
	});

</script>
<?php echo $footer; ?>

