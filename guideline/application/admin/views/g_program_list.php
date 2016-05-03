            <ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="index.php/g_program/add">
					Add a program
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
                      <form action='index.php/g_program/all_del' method='post' enctype="multipart/form-data" id='list_form'>
						<table>
							
							<thead>
								<tr>
								 <th style='width:30px;'><input class="check-all" type="checkbox" /></th>
								   <th><a href="<?php echo $sort_id;?>">article ID<a/></th>
								   <th>article name</th>
                                   <th>catagory name</th>
								   <th>catagory Id</th>
								   <th>image </th>
                                   <th>status</th>
                                   <th>sort</th>
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
								   <th><input type="text" name="filter_article_name" value="<?php echo $filter_article_name;?>"/></th>
								   <th><input type="text" name="filter_article_catagory_name" value="<?php echo $filter_article_catagory_name;?>"/></th>
                                   <th><input type="text" name="filter_article_catagory" value="<?php echo $filter_article_catagory;?>"/></th>
								   <th></th>
                                   <th><input type="text" name="filter_article_status" value="<?php echo $filter_article_status;?>"/></th>
                                   <th></th>
								   <th><input type="text" name="filter_article_start_time" value="<?php echo $filter_article_start_time;?>"/><br />--<input type="text" name="filter_article_end_time" value="<?php echo $filter_article_end_time;?>"/></th>
								   <th><a class="button" id='Filter'>Filter</a></th>
								</tr>
							</tbody>
							<tbody>
                                <?php foreach($article_lists as $articel){ ?>
								<tr>
									<td style='width:30px;'><input type="checkbox" name='selected[]' value="<?php echo $articel['article_id'];?>"/></td>
									<td><?php echo $articel['article_id'];?></td>
									<td><?php echo $articel['title'];?></td>
									<td><?php echo $articel['catagory_name'];?></td>
									<td><?php echo $articel['app_catagory_id'];?></td>
									<?php if($articel['effect_image']){ ?>
									<td><img src="/guideline/<?php echo $articel['effect_image'];?>" width='50' height="50" /></td>
									<?php }else{ ?>
									<td>None</td>
									<?php } ?>
									<td><?php echo $articel['status'];?></td> 
                                    <td><?php echo $articel['sort'];?></td> 
									<td><?php echo $articel['add_time'];?></td>          
									<td>
										<!-- Icons -->
										 <a href="index.php/g_program/update?id=<?php echo $articel['article_id'];?>" title="Edit"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
                                          <a href="javascript:Delete('index.php/g_program/delete?id=<?php echo $articel['article_id'];?>')" title="Delete"><img src="resources/images/icons/cross.png" alt="Delete" /></a>
                                          <a href="/guideline/applications/<?php echo $articel['url_path'];?>.html" title="预览" target='_black'>预览</a>
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
	url = 'index.php/g_program/index?';
	
	var filter_article_name = $('input[name=\'filter_article_name\']').attr('value');
	
	if (filter_article_name) {
		url += '&filter_article_name=' + encodeURIComponent(filter_article_name);
	}
	
    var filter_article_catagory_name = $('input[name=\'filter_article_catagory_name\']').attr('value');
	
	if (filter_article_catagory_name) {
		url += '&filter_article_catagory_name=' + encodeURIComponent(filter_article_catagory_name);
	}
	var filter_article_catagory = $('input[name=\'filter_article_catagory\']').attr('value');
	
	if (filter_article_catagory) {
		url += '&filter_article_catagory=' + encodeURIComponent(filter_article_catagory);
	}
	var filter_article_status = $('input[name=\'filter_article_status\']').attr('value');
	
	if (filter_article_status) {
		url += '&filter_article_status=' + encodeURIComponent(filter_article_status);
	}
	
	var filter_article_start_time = $('input[name=\'filter_article_start_time\']').attr('value');
	
	if (filter_article_start_time) {
		url += '&filter_article_start_time=' + encodeURIComponent(filter_article_start_time);
	}
	
	var filter_article_end_time = $('input[name=\'filter_article_end_time\']').attr('value');
	
	if (filter_article_end_time) {
		url += '&filter_article_end_time=' + encodeURIComponent(filter_article_end_time);
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
