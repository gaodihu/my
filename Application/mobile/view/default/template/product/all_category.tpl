<?php echo $header;?>
<!-- all-categories -->
		<div class="categories-list m-b20">
		<?php foreach($categories as $top_cat){ ?>
        <a href="<?php echo $top_cat['href'];?>"><i class="icon-caret-down"></i><?php echo $top_cat['name'];?></a>
            <!-- secondary-list -->
             <ul class="secondary-list" >
				<?php foreach($top_cat['children'] as $child_cat){ ?>
                 <li><a href="<?php echo $child_cat['href'];?>"><?php echo $child_cat['name'];?></a></li>
				 <?php } ?>
              
             </ul>
		<?php } ?>
		</div>

     </div>
	<script type="text/javascript">
        $(".categories-list .icon-caret-down").on("click",function(event){
			var that = $(this).parents("a");
            if(that.is(".active")){
                that.removeClass("active");
                that.next("ul").toggle();
                that.find("i").attr("class","icon-caret-down");
				event.stopPropagation();
                return false;
            }

            $(".secondary-list").hide();
            that.next("ul").toggle();
            that.find("i").attr("class","icon-caret-up");
            that.addClass("active").siblings("a").removeClass("active");
			event.stopPropagation();
			return false;
        })
     </script>
<?php echo $footer;?>