            <ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="index.php/faq/add">
					Add a faq
				</a></li>
				<div class="clear"></div> <!-- End .clear -->
			</ul>
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
				
                <div class="tab-content default-tab" id="tab1"> 
						<?php if(isset($error_message)&&$error_message){ ?>
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								<?php echo $error_message;?>
							</div>
						</div>
						<?php } ?>
                      <form action='index.php/faq/all_del' method='post' enctype="multipart/form-data" id='list_form'>
						<table>
							
							<thead>
								<tr>
								   <th style='width:30px;'><input class="check-all" type="checkbox" /></th>
								   <th><a href="<?php echo $sort_id;?>">ID<a/></th>
								   <th>Faq name</th>
                                   <th>Faq catagory</th>
								   <th>Faq catagory ID</th>
                                   <th>status</th>
								   <th>add time</th>
								   <th>action</th>
								</tr>
								
							</thead>
						 
							<tfoot>
								<tr>
									<td colspan="6">
										<div class="bulk-actions align-left">
										
											<a class="button" id='del_all'>Apply to selected</a>
										</div>
										<div class="clear"></div>
									</td>
								</tr>
							</tfoot>
						 	<tbody>
								<tr>
								   <th></th>
								   <th></th>
								   <th><input type="text" name="filter_faq_name" value="<?php echo $filter_faq_name;?>"/></th>
								   <th></th>
                                   <th><input type="text" name="filter_faq_catagory" value="<?php echo $filter_faq_catagory;?>"/></th>
                                   <th><input type="text" name="filter_faq_status" value="<?php echo $filter_faq_status;?>"/></th>
								   <th><input type="text" name="filter_faq_start_time" value="<?php echo $filter_faq_start_time;?>"/><br />--<input type="text" name="filter_faq_end_time" value="<?php echo $filter_faq_end_time;?>"/></th>
								   <th><a class="button" id='Filter'>Filter</a></th>
								</tr>
							</tbody>
							<tbody>
                                <?php foreach($faq_lists as $faq){ ?>
								<tr>
									<td style='width:30px;'><input type="checkbox" name='selected[]' value="<?php echo $faq['faq_id'];?>"/></td>
									<td style='width:50px;'><?php echo $faq['faq_id'];?></td>
									<td><?php echo $faq['title'];?></td>
									<td><?php echo $faq['catagory_name'];?></td>
									<td><?php echo $faq['faq_catagory_id'];?></td>
									<td><?php echo $faq['status'];?></td> 
									<td><?php echo $faq['add_time'];?></td>          
									<td>
										<!-- Icons -->
										 <a href="index.php/faq/update?id=<?php echo $faq['faq_id'];?>" title="Edit"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
                                          <a href=" javascript:Delete('index.php/faq/delete?id=<?php echo $faq['faq_id'];?>')" title="Delete"><img src="resources/images/icons/cross.png" alt="Delete" /></a>
                                          <a href="/guideline/faq/<?php echo $faq['url_path'];?>.html" title="预览" target='_black'>预览</a>
									</td>
								</tr>
                             
								<?php } ?>
							</tbody>
							<tr class="propage">
								<td colspan="8" align="center"><?php echo $page_links;?></td>
							<tr>
								
						</table>
						
						</form>
					</div>
			</div> <!-- End .content-box -->
			
<script type='text/javascript'>
$('#Filter').click(function(){
	url = 'index.php/faq/index?';
	
	var filter_faq_name = $('input[name=\'filter_faq_name\']').attr('value');
	
	if (filter_faq_name) {
		url += '&filter_faq_name=' + encodeURIComponent(filter_faq_name);
	}
	
	var filter_faq_catagory = $('input[name=\'filter_faq_catagory\']').attr('value');
	
	if (filter_faq_catagory) {
		url += '&filter_faq_catagory=' + encodeURIComponent(filter_faq_catagory);
	}
	var filter_faq_status = $('input[name=\'filter_faq_status\']').attr('value');
	
	if (filter_faq_status) {
		url += '&filter_faq_status=' + encodeURIComponent(filter_faq_status);
	}
	
	var filter_faq_start_time = $('input[name=\'filter_faq_start_time\']').attr('value');
	
	if (filter_faq_start_time) {
		url += '&filter_faq_start_time=' + encodeURIComponent(filter_faq_start_time);
	}
	
	var filter_faq_end_time = $('input[name=\'filter_faq_end_time\']').attr('value');
	
	if (filter_faq_end_time) {
		url += '&filter_faq_end_time=' + encodeURIComponent(filter_faq_end_time);
	}
	
	

	location = url;
})
$('#del_all').click(function(){
       $('#list_form').submit();
})
</script>
