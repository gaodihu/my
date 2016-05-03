
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
					<form action='index.php/information/add' method='post' enctype="multipart/form-data">
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								
								<p>
									<label><span class='red'>*</span>title</label>
									<input class="text-input medium-input" type="text"  name="title" value="<?php echo set_value('title'); ?>"/> 	
								</p>
								<p>
									<label>meta keyword</label>
									<input class="text-input medium-input" type="text"  name="meta_keyword" value="<?php echo set_value('meta_keyword'); ?>"/>
								</p>
								<p>
									<label>meta descripition</label>
									<input class="text-input medium-input" type="text"  name="meta_description" value="<?php echo set_value('meta_description'); ?>"/>
								</p>
						
								<p>
									<label style="float:left">Content</label>
									<div style="width:700px;float:left">
									<script id="container" name="content" type="text/plain"><?php echo set_value('catagory_description'); ?></script>
									</div>
									<div class='clear'></div>
								</p>
								<p>
									<label>Status</label>
									<input type="radio" name="status" value='1' checked='checked'/> Enbale<br />
									<input type="radio" name="status" value='0'/> Disable
								</p>
							    
								<p>
									<input class="button" type="submit" value="Submit" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
					</div>
				
			</div> <!-- End .content-box -->
			
<script type="text/javascript">
        var ue = UE.getEditor('container');
    </script>

