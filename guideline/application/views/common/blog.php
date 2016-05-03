   <ul>
    <?php foreach($laster_blog_array as $blog){ ?>
         <li>
             <div class="cont">
                 <h4 class="green inline"><a href='<?php echo $blog['url_path'];?>' title='<?php echo $blog['title'];?>'><?php echo $blog['title'];?></a></h4><br/>
                 <p><?php echo $blog['content'];?> <a href="<?php echo $blog['url_path'];?>" class="blue">Read More</a></p>
                 <p class="gray"> Posted on <?php echo $blog['post_date'];?></p>
             </div>
             <div class="img"><img src="<?php echo $blog['f_img_src'];?>" width="152" height="94"/></div>
         </li>
         <?php } ?>
</ul>