            <ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="index.php/custom_tag/add?lang_id=<?php echo $lang_id; ?>">
					Add Custom Tags
				</a></li>
				<div class="clear"></div> <!-- End .clear -->
			</ul>
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box" style="height:600px;overflow:auto"><!-- Start Content Box -->
				
                <div class="tab-content default-tab" id="tab1"> 
						<?php if(isset($error_message)&&$error_message){ ?>
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								<?php echo $error_message;?>
							</div>
						</div>
						<?php } ?>
                      <form action='index.php/custom_tag/all_del?lang_id=<?php echo $lang_id; ?>' method='post' enctype="multipart/form-data" id='list_form'>
						<table>
							
							<thead>
								<tr>
								   <th style='width:30px;'><input class="check-all" type="checkbox" /></th>
								   <th style='width:30px;'><a href="<?php echo $sort_id;?>"> ID<a/></th>
								   <th align="left" style="text-align:left">tag</th>
                                   <th align="left" style="text-align:left">link</th>
								   <th>add time</th>
								   <th>action</th>
								</tr>
								
							</thead>
						 
							<tfoot>
								<tr>
									<td colspan="6">
										<div class="bulk-actions align-left">
										
											<a class="button" id='del_all'>Delete Selected</a>
										</div>
										<div class="clear"></div>
									</td>
								</tr>
							</tfoot>
						  <tbody>
								<tr>
                                   <th></th>
								   <th></th>
								   <th><input type="text" name="filter_custom_tag" value="<?php echo $filter_custom_tag;?>"/></th>
								   <th></th>

								   <th><input type="text" name="filter_custom_tag_start_time" value="<?php echo $filter_custom_tag_start_time;?>"/><br />--<input type="text" name="filter_custom_tag_start_time" value="<?php echo $filter_custom_tag_start_time;?>"/></th>
								   <th><a class="button" id='Filter'>Filter</a></th>
								</tr>
							</tbody>
							<tbody>
                                <?php foreach($tag_lists as $item){ ?>
								<tr>
									<td style='width:30px;'><input type="checkbox" name='selected[]' value="<?php echo $item['id'];?>"/></td>
									<td style='width:30px;'><?php echo $item['id'];?></td>
									<td align="left" style="text-align:left"><?php echo $item['tag'];?></td>
									<td align="left" style="text-align:left"><?php echo $item['link'];?></td>
									<td><?php echo $item['add_time'];?></td>          
									<td>
										<!-- Icons -->
										
                                         <a href="javascript:Delete('/guideline/admin/index.php/custom_tag/delete?lang_id=<?php echo $lang_id; ?>&id=<?php echo $item['id'];?>')" title="Delete"><img src="resources/images/icons/cross.png" alt="Delete" /></a>
                                          
									</td>
								</tr>
                             
								<?php } ?>
							</tbody>
							<tr class="propage">
								<td colspan="9" align="center"><?php echo $page_links;?></td>
							<tr>
						</table>
						</form>
					</div>
			</div> <!-- End .content-box -->
			
<script type='text/javascript'>
$('#Filter').click(function(){
	url = '/guideline/admin/index.php/custom_tag/index?lang_id=<?php echo $lang_id; ?>&';
	
	var filter_custom_tag = $('input[name=\'filter_custom_tag\']').attr('value');
	
	if (filter_custom_tag) {
		url += '&filter_custom_tag=' + encodeURIComponent(filter_custom_tag);
	}

	var filter_custom_tag_start_time = $('input[name=\'filter_custom_tag_start_time\']').attr('value');
	
	if (filter_custom_tag_start_time) {
		url += '&filter_custom_tag_start_time=' + encodeURIComponent(filter_custom_tag_start_time);
	}
	
	var filter_custom_tag_end_time = $('input[name=\'filter_custom_tag_end_time\']').attr('value');
	
	if (filter_custom_tag_end_time) {
		url += '&filter_custom_tag_end_time=' + encodeURIComponent(filter_custom_tag_end_time);
	}
	
	

	location = url;
})
$('#del_all').click(function(){
       $('#list_form').submit();
})
$('.show_button').click(function(){
     var id=$(this).attr('id');
       $('.cat_catagory'+id).toggle();
})
</script>
