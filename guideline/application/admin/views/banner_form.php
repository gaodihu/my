
            <ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="index.php/banner">
					cancel
				</a></li>
				<div class="clear"></div> <!-- End .clear -->
			</ul>
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box" style='padding-left:20px;'><!-- Start Content Box -->
				    
                <div class="tab-content" id="tab2">
                        <?php if($error_message){ ?>
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								<?php echo $error_message;?>
							</div>
						</div>
						<?php } ?>
                    <?php if(!isset($banner_info)){ ?>
					<form action='index.php/banner/add' method='post' enctype="multipart/form-data">
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								<p>
									<label><span class='red'>*</span>banner name</label>
										<input class="text-input medium-input" type="text"  name="banner_name" value="<?php echo set_value('banner_name'); ?>"/> 
										<br /><small>banner description for admin read</small>
								</p>
								
								<p>
									<label><span class='red'>*</span>banner code</label>
									<input class="text-input medium-input" type="text"  name="banner_code" value="<?php echo set_value('banner_code'); ?>"/> 
								</p>
								
								<p>
									<label><span class='red'>*</span>width</label>
									<input class="text-input medium-input" type="text" name="width" value="<?php echo set_value('width'); ?>"/>
								</p>
								
								<p>
									<label><span class='red'>*</span>height</label>
									<input class="text-input medium-input" type="text"  name="height" value="<?php echo set_value('height'); ?>"/>
								</p>
								
								<p>
									<label><span class='red'>*</span>Status</label>
									<input type="radio" name="status" value='1'/> Enbale<br />
									<input type="radio" name="status" value='0'/> Disable
								</p>
								<p>日期类输入框请直接输入日期格式，如：2015-06-22 12:00:00 </p>
							    <table width='100%'>
                                    <tr>
                                        <th style='width:50px;'>Title</th><th>Link</th><th>sort order</th><th>image</th><th>Status</th><th>action</th>
                                    </tr>
                                    <tr>
                                        <td><input type='text' class="text-input" name="banner_info[title][]" style='width:100px;'></td>
                                        <td><input type='text' class="text-input" name="banner_info[link][]"></td>
                                        <td><input type='text' class="text-input" name="banner_info[sort_order][]" value='4'></td>
                                        <td><input type='file' name="banner_info[image][]" style='width:100px;'></td>
                                        
                                        <td class="left"><select name="banner_info[status][]" style='width:100px;'><option value="1">enabled</option><option value="0">disabled</option></select></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><input type='text' class="text-input" name="banner_info[title][]" style='width:100px;'></td>
                                        <td><input type='text' class="text-input" name="banner_info[link][]"></td>
                                        <td><input type='text' class="text-input" name="banner_info[sort_order][]" value='4'></td>
                                        <td><input type='file' name="banner_info[image][]" style='width:100px;'></td>
                                        
                                        <td class="left"><select name="banner_info[status][]" style='width:100px;'><option value="1">enabled</option><option value="0">disabled</option></select></td>
                                        <td><input type='button' value='delete' class='delete'></td>
                                    </tr>
                                    <tr>
                                        <td ></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type='button' value='add' id='add'></td>
                                    </tr>
                                </table>
								<p>
									<input class="button" type="submit" value="Submit" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
                        <?php } ?>

                    <?php if(isset($banner_info)){ ?>
					<form action='index.php/banner/update?id=<?php echo $banner_info['type_info']['type_id'];?>' method='post' enctype="multipart/form-data">
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								<p>
									<label><span class='red'>*</span>banner name</label>
										<input class="text-input medium-input" type="text"  name="banner_name" value="<?php echo $banner_info['type_info']['type_name']; ?>"/> 
										<br /><small>banner description for admin read</small>
								</p>
								
								<p>
									<label><span class='red'>*</span>banner code</label>
									<input class="text-input medium-input" type="text"  name="banner_code" value="<?php echo $banner_info['type_info']['type_code']; ?>"/> 
								</p>
								
								<p>
									<label><span class='red'>*</span>width</label>
									<input class="text-input medium-input" type="text" name="width" value="<?php echo $banner_info['type_info']['width']; ?>"/>
								</p>
								
								<p>
									<label><span class='red'>*</span>height</label>
									<input class="text-input medium-input" type="text"  name="height" value="<?php echo $banner_info['type_info']['height']; ?>"/>
								</p>
								
								<p>
									<label><span class='red'>*</span>Status</label>
                                    <?php if($banner_info['type_info']['status']==1){ ?>
									<input type="radio" name="status" value='1'  checked='checked'/> Enbale<br />
									<input type="radio" name="status" value='0'/> Disable
                                    <?php }else{ ?>
                                    <input type="radio" name="status" value='1'/> Enbale<br />
									<input type="radio" name="status" value='0' checked='checked'/> Disable
                                    <?php } ?>
								</p>
								<p>日期类输入框请直接输入日期格式，如：2015-06-22 12:00:00 </p>
							    <table width='100%'>
                                    <tr>
                                        <th style='width:50px;'>Title</th><th>Link</th><th>sort order</th><th>image</th><th>Status</th><th>action</th>
                                    </tr>
                                    <?php foreach($banner_info['banner_info'] as $banner){ ?> 
                                    <tr>
                                        <td><input type='text' class="text-input" name="banner_info[title][]" value="<?php echo $banner['banner_name'];?>" style='width:100px;'></td>
                                        <td><input type='text' class="text-input" name="banner_info[link][]" value="<?php echo $banner['banner_url'];?>" ></td>
                                        <td><input type='text' class="text-input" name="banner_info[sort_order][]" value="<?php echo $banner['sort'];?>"></td>
                                        <td>
                                        
                                        <img src='/guideline/<?php echo $banner['banner_image'];?>' width='50' height='50'>
                                        
                                        <input type='file' name="banner_info[image][]" style='width:100px;'></td>
                                        <td class="left"><select name="banner_info[status][]" style='width:100px;'>
                                        <?php if($banner['status']==1){ ?>
                                        <option value="1" selected='selected'>enabled</option>
                                        <option value="0">disabled</option>
                                        <?php }else{ ?>
                                        <option value="1">enabled</option>
                                        <option value="0" selected='selected'>disabled</option>
                                        <?php } ?>
                                        </select></td>
                                        <td><input type='button' value='delete' class='delete' id='<?php echo $banner['banner_id'];?> '></td>
                                        <input type='hidden' value="<?php echo $banner['banner_id'];?>" name='banner_info[banner_id][]'>
                                    </tr>
                                    <?php } ?>
                                    
                                    <tr>
                                        <td ></td><td></td><td></td><td></td><td></td><td></td><td></td><td><input type='button' value='add' id='add'></td>
                                    </tr>
                                </table>
								<p>
									<input class="button" type="submit" value="Submit" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
                        <?php } ?>
						
					</div>
				
			</div> <!-- End .content-box -->
			
<script type='text/javascript'>
$('#add').live('click',function(){
        var inner ='<tr>'+'<td><input type="text" class="text-input" name="banner_info[title][]" style="width:100px;"></td>'+
                                        '<td><input type="text" class="text-input" name="banner_info[link][]"></td>'+
                                        '<td><input type="text" class="text-input" name="banner_info[sort_order][]" value="4"></td>'+
                                        '<td><input type="file" name="banner_info[image][]" style="width:100px;"></td>'+
                                        '<td class="left"><select name="banner_info[status][]" style="width:100px;"><option value="1">enabled</option><option value="0">disabled</option></select></td>'+
                                        '<td><input type="button" value="delete" class="delete"></td>'+
                                    '</tr>';
       $(this).parents('tr').prev().after(inner);
})
$('.delete').live('click',function(){
    if(confirm("Are you sure delete?"))
     {
        var banner_id =$(this).attr('id');
        $.ajax({
            url: '/admin/index.php/banner/delete_banner_img',
            type: 'post',
            data: 'banner_id=' + banner_id,
            dataType: 'json',
            success: function(json) {
                               
                
            }
        });
        $(this).parents('tr').remove();
     }
    
    
})
</script>	
