<?php echo $header; ?>
<div class="head-title"><a class="icon-angle-left left-btn"></a><?php echo $heading_title;?></div>
<section class="mypoints  wrap clearfix">
		<section class="mt_20">
                <!-- <div><a href="javascript:void (0);" class="min-btn orange-bg" style="padding: 0.2em 1em"><?php echo $text_write_review;?></a></div> -->
        	<section class="account_table m-t20">
				<section>
				<?php if($reviews_list){ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="mypoints-table">
						<tr>
							<th><?php echo $column_products;?></th>
							<th><?php echo $column_title;?></th>
							<!--<th><?php echo $column_gained_points;?></th>-->
							<th><?php echo $column_reviews;?></th>
						</tr>
						<?php foreach($reviews_list as $review ){ ?>
							<tr>
							<td><img src="<?php echo $review['thumb'];?>" alt="<?php echo $review['name'];?>"/></td>
							<td><a class="blue" id="ledjj" href="<?php echo $review['href'];?>"><?php echo $review['name'];?></a></td>
							<!--<td>20</td>-->
							<td><?php echo $review['text'];?></td>
						</tr>
						<?php } ?>
						
						
					</table>
					<div id="lodding"  class="lodding" style="display: none"><span><i class="icon-spinner"></i></span></div>
				<?php } else{ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="mypoints-table">
						<tr>
							<th><?php echo $column_products;?></th>
							<th><?php echo $column_title;?></th>
							<!--<th><?php echo $column_gained_points;?></th>-->
							<th><?php echo $column_reviews;?></th>
						</tr>
						<tr>
							<td colspan="4"><?php echo $text_empty;?></td>
							
						</tr>
						
					</table>
				<?php } ?>
				</section>
			</section>
        	
        </section>
    <?php echo $text_riviews_verify;?>
</section>
<?php if($show_ajax_list){ ?>
   <script type="text/javascript" >
        $.pagescroll.ajax_fn=function(){
            $.ajax({
                type: "get",
                url: '<?php echo htmlspecialchars_decode($json_list_url);?>',
    			data: 'page=' + ($.pagescroll.index+1),
                dataType: "json",
                success: function (data) {
                    var tempArr = [],HTML;
                    $.pagescroll.index = $.pagescroll.index +1;
                    if($.pagescroll.index == 0){
                        $.pagescroll.setHtml("");
                        return false;
                    }
					if(data['error']==0){
						$.each( data['data'], function(index, content)
						{
							var inHtml=
							'<tr>'+
							'<td><img src="'+content.thumb+'" alt="'+content.name+'"/></td>'+
							'<td><a class="blue" id="ledjj" href="'+content.href+'">'+content.name+'</a></td>'+
							'<td>'+content.text+'</td>'+
							'</tr>';
							tempArr.push(inHtml);
		
						});
						HTML = tempArr.join('');
						$.pagescroll.setHtml(HTML);
					}else{
						$.pagescroll.setHtml(data['message']);
					}
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    //console.log(errorThrown);
                }
            });
    
       }
    
        /*  page lodding  */
       $.pagescroll.init(".mypoints-table");
    </script> 
<?php } ?>
<?php echo $footer; ?>

