
            <ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="index.php/information">
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
					<form action='index.php/information/update?id=<?php echo $information_info['info_id']; ?>' method='post' enctype="multipart/form-data">
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								<p>
									<label><span class='red'>*</span>title</label>
									<input class="text-input medium-input" type="text"  name="title" value="<?php echo $information_info['title']; ?>"/> 	
								</p>
								<p>
									<label>meta keyword</label> 
									<input class="text-input medium-input" type="text"  name="meta_keyword" value="<?php echo $information_info['meta_keyword'];?>"/>
								</p>
								<p>
									<label>meta descrpition</label>
									<input class="text-input medium-input" type="text"  name="meta_description" value="<?php echo $information_info['meta_description'];?>"/>
								</p>
						
								<p>
									<label style="float:left">Content</label>
									<div style="width:700px;float:left">
                                    <textarea style="width:100%" name="content"><?php echo $information_info['content'];?></textarea>
									</div>
									<div class='clear'></div>
								</p>
								<p>
									<label>Status</label>
									<?php if($information_info['status']==1){ ?>
									<input type="radio" name="status" value='1' checked='checked'/> Enbale<br />
									<input type="radio" name="status" value='0'/> Disable
									<?php }else{ ?>
									<input type="radio" name="status" value='1' /> Enbale<br />
									<input type="radio" name="status" value='0' checked='checked'/> Disable
									<?php } ?>
								</p>
							    
								<p>
									<input type='hidden' value="<?php echo $information_info['info_id'];?>" name='info_id'>
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
   
   external_filemanager_path:"/admin/resources/scripts/tinymce/plugins/responsivefilemanager/filemanager/",
   filemanager_title:"Responsive Filemanager" ,
   external_plugins: { "filemanager" : "/admin/resources/scripts/tinymce/plugins/responsivefilemanager/filemanager/plugin.min.js"}
});

</script>