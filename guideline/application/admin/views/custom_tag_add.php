
            <ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button" href="index.php/custom_tag/index?lang_id=<?php echo $lang_id; ?>">
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
					<form action='index.php/custom_tag/add?lang_id=<?php echo $lang_id; ?>' method='post' enctype="multipart/form-data">
							<fieldset> 
								
								<p>
									<label style="float:left">Content</label>
									<div style="float:left">
                                        <textarea rows="30" cols="60"  name="content"><?php echo set_value('catagory_description'); ?></textarea>
									</div>
									<div class='clear'></div>
								</p>
								
                                
							    
								<p>
									<input class="button" type="submit" value="Submit" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
					</div>
				
			</div> <!-- End .content-box -->

