<div class="protit"><h4><?php echo $heading_title;?></h4></div>
<section class="prolist hotlist">
		<ul class="clearfix">
			<li>
				<div class="ProLED">
					<div class="img">
						<a href="<?php echo $home_hot_cat_banner[0]['link'];?>"><img src="<?php echo $home_hot_cat_banner[0]['image'];?>" alt="<?php echo $home_hot_cat_banner[0]['title'];?>" width="227" height="260"/></a></div>

				</div>
			</li>
                        <?php if(isset($hot_catalogs) && is_array($hot_catalogs) && count($hot_catalogs) > 0){ ?>
			<?php foreach($hot_catalogs as $hot_catalog){
			?>
			<li>
				<div class="hotpro">
					<div class="img"><a href='<?php echo $hot_catalog['url'];?>'><img src="<?php echo $hot_catalog['thumb'];?>" alt="<?php echo $hot_catalog['name'];?>" width="210" height="150"/></a></div>
					<p class="hottit"><?php echo $hot_catalog['name'];?></h3>
					<div class="link">
						<?php if($hot_catalog['child']){
							foreach($hot_catalog['child'] as $child){
						?>
						<a href="<?php echo $child['url'];?>"><?php echo $child['name'];?>(<?php echo $child['pro_total'];?>)</a>
						<?php
							}
						}
						?>
					</div>
				</div>
			</li>
			<?php
			}
                        }
			?>
		</ul>
	</section>