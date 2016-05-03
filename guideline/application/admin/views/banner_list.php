            <ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="index.php/banner/add">
					Upload a new banner
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
                      <form action='index.php/banner/all_del_type' method='post' enctype="multipart/form-data" id='list_form'>
						<table>
							
							<thead>
								<tr>
								   <th><input class="check-all" type="checkbox" /></th>
								   <th>banner code(不可变更)</th>
								   <th>banner name</th>
								   <th>banner width</th>
								   <th>banner height</th>
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
                                <?php foreach($banner_type_list as $type){ ?>
								<tr>
									<td><input type="checkbox" name='selected[]' value="<?php echo $type['type_id'];?>"/></td>
									<td><?php echo $type['type_code'];?></td>
									<td><?php echo $type['type_name'];?></td>
									<td><?php echo $type['width'];?></td>
									<td><?php echo $type['height'];?></td>
                                    <td><?php  if($type['status']==1){ echo 'Enable';}else{echo 'Disable';}?></td>
									<td>
										<!-- Icons -->
										 <a href="index.php/banner/update?id=<?php echo $type['type_id'];?>" title="Edit"><img src="resources/images/icons/pencil.png" alt="Edit" /></a>
                                          <!-- <a href="index.php/banner/delete_type?id=<?php echo $type['type_id'];?>" title="Delete"><img src="resources/images/icons/cross.png" alt="Delete" /> --></a> 
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
