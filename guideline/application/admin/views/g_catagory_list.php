            <ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="index.php/g_catagory/add">
					Add a catagory
				</a></li>
				<div class="clear"></div> <!-- End .clear -->
			</ul>
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box" style="height:600px;"><!-- Start Content Box -->
				
                <div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
						<?php if(isset($error_message)&&$error_message){ ?>
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								<?php echo $error_message;?>
							</div>
						</div>
						<?php } ?>
                      <form action='index.php/g_catagory/all_del' method='post' enctype="multipart/form-data" id='list_form'>
						<table>
							
							<thead>
								<tr>
								 <th style='width:30px;'><input class="check-all" type="checkbox" /></th>
								   <th>catagory name</th>
                                   <th>articles count</th>
                                   <th>sort</th>
                                   <th>Status</th>
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
                                <?php foreach($catagory_lists as $catagory){ ?>
								<tr>
									<td style='width:30px;'><input type="checkbox" name='selected[]' value="<?php echo $catagory['catagory_id'];?>"/></td>
									<td style="position:relative;padding-left:15px;">
                                    <?php if($catagory['child']){ ?>
									<span class="display_imgs show_button" id="<?php echo $catagory['catagory_id'];?>"></span>
									<?php } ?>
                                    <?php echo $catagory['catagory_name'];?></td>
                                     <td><?php echo $catagory['article_count'];?></td>
                                     <td><?php echo $catagory['sort'];?></td>   
									 <td><?php echo $catagory['status'];?></td>      
									<td>
										<!-- Icons -->
										 <a href="index.php/g_catagory/update?id=<?php echo $catagory['catagory_id'];?>" title="Edit"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
                                          <a href="javascript:Delete('index.php/g_catagory/delete?id=<?php echo $catagory['catagory_id'];?>')" title="Delete"><img src="resources/images/icons/cross.png" alt="Delete" /></a>
									</td>
								</tr>
                                <?php foreach($catagory['child'] as $child_cat){ ?>
                                <tr class="none cat_catagory<?php echo $catagory['catagory_id'];?>">
									<td style='width:30px;'></td> 
									<td>>><input type="checkbox" name='selected[]' value="<?php echo $child_cat['catagory_id'];?>"/><?php echo $child_cat['catagory_name'];?></td>
                                    <td><?php echo $child_cat['article_count'];?></td>
                                    <td><?php echo $child_cat['sort'];?></td>
                                    <td><?php echo $child_cat['status'];?></td>      
									<td>
										<!-- Icons -->
										 <a href="index.php/g_catagory/update?id=<?php echo $child_cat['catagory_id'];?>" title="Edit"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
                                        <a href="javascript:Delete('index.php/g_catagory/delete?id=<?php echo $child_cat['catagory_id'];?>')" title="Delete"><img src="resources/images/icons/cross.png" alt="Delete" /></a>
									</td>
								</tr>

                                <?php } ?>
								<?php } ?>
							</tbody>
							
						</table>
						</form>
					</div>
				
			</div> <!-- End .content-box -->
			
<script type='text/javascript'>
$('#del_all').click(function(){
       $('#list_form').submit();
})
$('.show_button').click(function(){
     var id=$(this).attr('id');
       $('.cat_catagory'+id).toggle();
})
</script>
