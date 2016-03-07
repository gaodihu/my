  <!--首页大banner图片切换-->
			<div id="playBox">
				<div class="pre"></div>
				<div class="next"></div>
				<div class="smalltitle">
				  <ul>
					<?php foreach($flash_banner_list as $banner){ ?>
                    <li></li>
                <?php } ?>
				  </ul>
				</div>
				<ul class="oUlplay">
				     <?php foreach($flash_banner_list as $banner){ ?>
                    <li> <a href="<?php echo $banner['banner_url'];?>" title="<?php echo $banner['banner_name'];?>"><img src="<?php echo $banner['banner_image'];?>" width="<?php echo $banner['width'];?>" height="<?php echo $banner['height'];?>" alt="<?php echo $banner['banner_name'];?>"/></a></li>
                    <?php } ?>
				</ul>
			  </div>
   <!--首页大banner图片切换结束-->
			
			
            <div class="content">
                 <div class="con-info">
                    <div class="latest">
                        <h1 class="title-green"><a href="applications.html" class="more">MORE</a><span>Latest Applications</span></h1>
                        <ul>

                            <?php foreach($lasted_applications as $application){ ?>
                            <li>
                                <div class="cont">

                                    <h4 class="font16 inline"><a href="applications/<?php echo $application['url_path'];?>.html"><?php echo $application['title'];?></a></h4><br/>
                                    <div>
                                    <?php if(strlen($application['content'])>400){
                                        echo sub($application['content'],400)."...";
                                      }else{ 
                                          echo $application['content'];
                                       }?></div>
                                </div>
                                <div class="img"><a href="applications/<?php echo $application['url_path'];?>.html"><img src="<?php echo $application['effect_image'];?>" width="420" height="260"/></a></div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>

                     <div class="blos">
                         <h1 class="title-green"><a href="https://www.myled.com/ledblog/" class="more">MORE</a><span>Latest Blogs</span></h1>
						  <div style="padding-top:30px;" id="showlodding"><center><img src="./images/public/loader_32x32.gif"  width="32"  height="32"/></center></div>
                         <div id='blog_content'></div>
                     </div>

                 </div>


                <div class="faq">
                    <h1 class="title-green"><a href="faq.html" class="more">MORE</a><span class="green">FAQ</span></h1>
                    <ul>
                        <?php foreach($lasted_faqs as $faq){ ?>
                        <li>
                            <div class="faq-title"><a href="faq/<?php echo $faq['url_path'];?>.html" title='<?php echo $faq['title'];?>'><?php echo $faq['title'];?></a></div>
                            
                        </li>
                        <?php } ?>
                        

                    </ul>
                </div>
            </div>
            <div class="clear"></div>

            <div class="featured" >
                <h1 class="title-green"><span>Featured MyLED Products</span></h1>
                <div class="f-img">
                    <?php foreach($featured_banner_list as $f_list){ ?>
                    <div class="img"><a href="<?php echo $f_list['banner_url'];?>"><img src="<?php echo $f_list['banner_image'];?>" width="390" height="150" alt="<?php echo $f_list['banner_name'];?>"/></a></div>
                    <?php } ?>
                </div>
            </div>
           <div class="clear"></div>
    </div>
<script type="text/javascript">

    $(function(){
        
		
         $(".blos").ajaxStart(function(){
              $("#showlodding").show();
         }).ajaxStop(function(){
			  $("#showlodding").hide();
         });
		 $.ajax({
            url: '/guideline/home/getLasterBlogs/',
            type: 'post',
            data: '',
            dataType: 'json',
            success: function(json) {
               $('#blog_content').html(json);
                
            }
        });

    });

</script>