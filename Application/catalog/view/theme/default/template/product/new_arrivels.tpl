<?php echo $header; ?>
<nav class="sidernav">
	<div class="wrap">
	<ul>
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li>
		<span>
		<?php if($breadcrumb['href']){
		?>
		<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php
		}
		else{
		?>
		<?php echo $breadcrumb['text']; ?>
		<?php
		}
		?>
		</span>
		<?php echo $breadcrumb['separator']; ?>
	</li>
	<?php
	}
	?>
	</ul>
	</div>
	<div class="clear"></div>
</nav>
<section class="box wrap clearfix">
	<aside class="boxLeft left">
		<section class="leftpro border">
			<div class="leftprotit bold"><?php echo $text_hot;?></div>
			<ul>
				<?php foreach($special_lists as $special_list){ ?>
				<li><a href="<?php echo $special_list['link'];?>"><img src="<?php echo $special_list['image'];?>" alt="<?php echo $special_list['title'];?>"/></a>
				</li>
				<?php } ?>
			</ul>
		</section>
		<section class="gg_right">
			<?php foreach($side_banner as $banner){ ?>
			<figure class="gg_img"><a href="<?php echo $banner['link'];?>"><img src="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>"/></a></figure>
			<?php } ?>
		</section>
	</aside>
	<section class="boxRight">
		<div class="protit"><p class="black18"><?php echo $heading_title;?></p></div>
		<section class="pro_sort">
			<?php foreach($sorts as $sort_list){
				if($sort==$sort_list['code']){
			?>
			<div class="sort_child"><a href="<?php echo $sort_list['href'];?>" style="color:#68a500;"><?php echo $sort_list['text'];?>
			<?php
				}
				else{
			?>
			<div class="sort_child"><a href="<?php echo $sort_list['href'];?>"><?php echo $sort_list['text'];?>
			<?php
				}
			?>

			<?php if($order&&$order=='ASC'){
			?>
			<span class="c_a a1"></span>
			<?php
			}
			else{
			?>
			<span class="c_a a2"></span>
			<?php
			}
			?>
			</a></div>
			<?php
			}
			?>
			<div class="sort_child"><a href="javascript:display('list');" class="on" id='list_show'><span class="c_a a3"></span><?php echo $text_list; ?></a></div>
			<div class="sort_child"><a href="javascript:display('grid');" id='grid_show'><span class="c_a a4"></span><?php echo $text_grid; ?></a></div>
			<span class="gray" style="margin-left:10px;"><?php echo $text_limit; ?> <select onchange="location = this.value;">
					<?php foreach ($limits as $limits) { ?>
					<?php if ($limits['value'] == $limit) { ?>
					<option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
					<?php } ?>
					<?php } ?>
			  </select></span>
			<!--<div class="sort_child"><input name="text" type="text" placeholder="40" style="width:20px" /> <span class="xia_sj_gray"></span></div>-->
		</section>
		<section class="prolist prolist_info">
			<ul class="clearfix">
				<li>
					<p class="offIcon"><span class="font20">50</span></p>
					<div class="procon">
						<div class="img"><img src="images/product1.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<p class="offIcon newIcon"></p>
					<div class="procon">
						<div class="img"><img src="images/product2.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<p class="offIcon"><span class="font20">50</span></p>
					<div class="procon">
						<div class="img"><img src="images/product3.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<p class="offIcon"><span class="font20">50</span></p>
					<div class="procon">
						<div class="img"><img src="images/product4.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product1.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product2.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product3.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product4.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product1.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product2.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product3.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product4.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product1.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product2.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product3.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product4.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product1.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product2.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product3.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
				<li>
					<div class="procon">
						<div class="img"><img src="images/product4.jpg" width="207" height="160" alt=""/><div class="img_Text"><a href="#" class="f"></a><a href="#" class="tqq"></a><a href="#" class="n"></a><a href="#" class="add_btn">Add to Cart</a></div></div>
						<p class="tt">LED Ball Bulb E27 5W 0-380LM Warm White Dimmable(AC110V,Black)</p>
						<p class="howmuch"><span class="xj">$3.34</span><span class="yj">$6.67</span></p>
						<p class="green">As low as: $3.02</p>
						<p class="gray"><span class="star star-s5"></span>(9)</p>
					</div>
				</li>
			</ul>
		</section>
		<section class="pro_sort propage">
			<a href="#">< Prev</a><a href="#">1</a><a href="#">2</a><a href="#">3</a><a href="#">...</a><a href="#">17</a><a href="#">Next ></a><span class="ml_10 font13 bold">1/17</span>
			<div class="right">Go to page<input name="text" type="text" size="1"/><input name="submit" type="submit" value="Go" class="go"/></div>
	  </section>
	</section>
</section>
<?php echo $footer; ?>