
            <ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="index.php/g_catagory">
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
					<form action='index.php/g_catagory/add' method='post' enctype="multipart/form-data">
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								<p>
									<label><span class='red'>*</span>catagory name</label>
									<input class="text-input medium-input" type="text"  name="catagory_name" value="<?php echo set_value('catagory_name'); ?>"/> 	
								</p>
								<p>
									<label><span class='red'>*</span>catagory level</label>
									<select name='catagory_level'>
										<option value='1'>1级分类</option>
										<option value="2">2及分类</option>
									</select> 	
								</p>
								<p>
									<label><span class='red'>*</span>parent catagory</label>
									<select name='parent_id'>
										
										<option>无</option>
										<?php foreach($catagory_lists as $catagory){ ?>
										<option value="<?php echo $catagory['catagory_id'];?>"> <?php echo $catagory['catagory_name'];?></option>
											<?php if($catagory['child']){ ?>
											<?php foreach($catagory['child'] as $child){ ?>
											<option value="<?php echo $child['catagory_id'];?>"> &nbsp;&nbsp;--<?php echo $child['catagory_name'];?></option>
											<?php } ?>
											<?php } ?>
										<?php } ?>
									</select> 
								</p>
								
								<!-- <p>
                                    <label><span class='red'>*</span>url path</label>
                                    <input class="text-input medium-input" type="text" name="url_path" value="<?php echo set_value('url_path'); ?>"/>
                                </p> -->
								<p>
									<label>image</label>
									<input class="text-input medium-input" type="file"  name="image"/>
								</p>
								<p>
									<label>meta keyword</label>
									<input class="text-input medium-input" type="text"  name="meta_keyword" value="<?php echo set_value('meta_keyword'); ?>"/>
								</p>
								<p>
									<label>meta description</label>
									<input class="text-input medium-input" type="text"  name="meta_description" value="<?php echo set_value('meta_description'); ?>"/>
								</p>
						
								<p>
									<label style="float:left">catagory description</label>
									<div style="width:700px;float:left">
                                    <textarea style="width:100%" name="catagory_description"><?php echo set_value('catagory_description'); ?></textarea>
									</div>
									<div class='clear'></div>
								</p>
								<p>
									<label>sort</label>
									<input class="text-input medium-input" type="text"  name="sort" value="<?php echo set_value('sort'); ?>"/>
								</p>
								
								<p>
									<label>Status</label>
									<input type="radio" name="status" value='1' checked='checked'/> Enbale<br />
									<input type="radio" name="status" value='0'/> Disable
								</p>
								<p> </p>
							    
								<p>
									<input class="button" type="submit" value="Submit" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
					</div>
				
			</div> <!-- End .content-box -->
			
<script type="text/javascript">
tinymce.init({
    convert_urls:false,
  selector: "textarea",theme: "modern",width: 880,height: 300,
    plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor responsivefilemanager"
   ],
    toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
   image_advtab: true ,
   
   external_filemanager_path:"/guideline/filemanager/",
   filemanager_title:"Responsive Filemanager" ,
   external_plugins: { "filemanager" : "/guideline/filemanager/plugin.min.js"}
});

</script>

