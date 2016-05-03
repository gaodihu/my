            <ul class="shortcut-buttons-set">
				
			<!--   <li><a class="shortcut-button" href="index.php/information/add">
                    Add a program
                </a></li> -->
				<div class="clear"></div> <!-- End .clear -->
			</ul>
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box" style="height:600px;"><!-- Start Content Box -->
				
                <div class="tab-content default-tab" id="tab1"> 
						<?php if(isset($error_message)&&$error_message){ ?>
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								<?php echo $error_message;?>
							</div>
						</div>
						<?php } ?>
                      <form action='index.php/information/all_del' method='post' enctype="multipart/form-data" id='list_form'>
						<table>
							
							<thead>
								<tr>
								 <th style='width:30px;'><input class="check-all" type="checkbox" /></th>
								   <th>information name</th>
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
                                <?php foreach($information_lists as $information){ ?>
								<tr>
									<td style='width:30px;'><input type="checkbox" name='selected[]' value="<?php echo $information['info_id'];?>"/></td>
									<td><?php echo $information['title'];?></td>
									<td><?php echo $information['status'];?></td>
									<td><?php echo $information['created_at'];?></td>          
									<td>
										<!-- Icons -->
										 <a href="index.php/information/update?id=<?php echo $information['info_id'];?>" title="Edit"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
                                  
                                          <a href="/guideline/guideline.html" title="预览" target='_black'>预览</a>
									</td>
								</tr>
                             
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
</script>
