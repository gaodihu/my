 <?php if($products){ ?>
 <section class="product">
            <div class="title">
                <div class="deal-time" time="<?php echo $left_time_js;?>" state="false"><i class="icon-time"></i> <span class="time_box"><b>0</b> days <b>00</b>:<b>00</b>:<b>00</b></span></div>

               <?php echo $heading_title;?>
            </div>
            <ul class="con-box clearfix">
				<?php foreach($products as $sp_pro){ ?>
                <li>
                    <a href="<?php echo $sp_pro['href'];?>">
                           <div class="product-img">
                              <img src="<?php echo $sp_pro['thumb'];?>" />
                           </div>
                            <div class="product-title">
                              <?php echo $sp_pro['name'];?>
                            </div>
                           <div class="product-cost">
                               <b><?php echo $sp_pro['special'];?></b>
                               <del><?php echo $sp_pro['price'];?></del>
                           </div>
                    </a>
                </li>
				<?php } ?>
              
            </ul>
        </section>
<?php } ?>

<script type="text/javascript">

        if($(".deal-time").attr("state") == "false"){
            common.timer($(".deal-time").find(".time_box"),$(".deal-time").attr("time"),"day");
            //状态激活
            $(".deal-time").attr("state","true");
        }

</script>