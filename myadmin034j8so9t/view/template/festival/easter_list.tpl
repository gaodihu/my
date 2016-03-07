<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1> 得奖详情</h1>
      <div class="buttons"><a href="<?php echo $to_excel;?>" class="button">导出为excel</a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'id') { ?>
                <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>">ID</a>
                <?php } else { ?>
                <a href="<?php echo $sort_id; ?>">ID</a>
                <?php } ?></td>
              <td class="left" >活动</td>
			  <td class="left" >order number</td>
              <td class="left" >email</td>
			  <td class="left" >name </td>
              <td class="left"> <?php if ($sort == 'prize_id') { ?>
                <a href="<?php echo $sort_prize_id; ?>" class="<?php echo strtolower($order); ?>">奖项</a>
                <?php } else { ?>
                <a href="<?php echo $sort_prize_id; ?>">奖项</a>
                <?php } ?></td>
              <td class="left"> <?php if ($sort == 'is_send') { ?>
                <a href="<?php echo $sort_is_send; ?>" class="<?php echo strtolower($order); ?>">是否已发送</a>
                <?php } else { ?>
                <a href="<?php echo $sort_is_send; ?>">是否已发送</a>
                <?php } ?></td>
              <td class="left ">中奖时间</td>
			  <td class="left ">订单生成时间</td>
              <td class="right">操作</td>
            </tr>
          </thead>
          <tbody>
		  <tr class="filter">
              <td></td>
              <td></td>
              <td class="left">

                <select name="filter_prize_name_id">
                  <option value="">---All---</option>
                  <?php  foreach($prize_list as $item){ ?>
                    <option value="<?php echo $item['id']; ?>" <?php  if($filter['filter_prize_name_id'] == $item['id']){echo 'selected="selected"';} ?>><?php echo $item['name']; ?></option>
                  <?php } ?>
                </select>

               </td>

              <td class="left"><input type="text" name="filter_prize_token" value="<?php echo $filter['filter_prize_token']; ?>" /></td>
              <td ><input type="text" name="filter_email" value="<?php echo $filter['filter_email']; ?>" /></td>
			  <td><input type="text" name="filter_nickname" value="<?php echo $filter['filter_nickname']; ?>" /></td>
			  <td><select name="filter_prize_id">
                  <option value="" >All</option>
                  <option value="1" <?php if($filter_prize_id == 1){echo "selected=selected";} ?>>1</option>
                  <option value="2" <?php if($filter_prize_id == 2){echo "selected=selected";} ?>>2</option>
                  <option value="3" <?php if($filter_prize_id == 3){echo "selected=selected";} ?>>3</option>
                  <option value="4" <?php if($filter_prize_id == 4){echo "selected=selected";} ?>>4</option>
                  <option value="5" <?php if($filter_prize_id == 5){echo "selected=selected";} ?>>5</option>
                  <option value="6" <?php if($filter_prize_id == 6){echo "selected=selected";} ?>>6</option>
                  <option value="7" <?php if($filter_prize_id == 7){echo "selected=selected";} ?>>7</option>
                  <option value="8" <?php if($filter_prize_id == 8){echo "selected=selected";} ?>>8</option>
                  </select>
              </td>
			  <td>
                  <select name="filter_is_send">
                    <option value="">All</option>
                    <option value="1" <?php if($filter['filter_is_send'] == 1){echo 'selected="selected"';} ?>>是</option>
                    <option value="0" <?php if($filter['filter_is_send'] == 0){echo 'selected="selected"';} ?>>否</option>
                    </select>
              </td>

            <td></td>
            <td></td>
              <td align="right"><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($prize_detai_info) { ?>
            <?php foreach ($prize_detai_info as $prize) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($prize['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $prize['id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $prize['id']; ?>" />
                <?php } ?></td>
			   
              <td class="left"><?php echo $prize['id']; ?></td>
              <td class="left"><?php echo $prize['prize_name']; ?></td>
			  <td class="left"><?php echo $prize['prize_token']; ?></td>
              <td class="left"><?php echo $prize['email']; ?></td>
              <td class="left"><?php echo $prize['nickname']; ?></td>
              <td class="left"><?php echo $prize['prize_id']; ?></td>
              <td class="right"><?php echo $prize['is_send']; ?></td>
              <td class="left"><?php echo $prize['add_time']; ?></td>
			  <td class="left"><?php echo $prize['order_created_time']; ?></td>
              <td class="right"><?php foreach ($prize['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="9">无记录</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=festival/easter&token=<?php echo $token; ?>';
	
	var filter_prize_name_id = $('select[name=\'filter_prize_name_id\']').val();
	
	if (filter_prize_name_id!="") {
		url += '&filter_prize_name_id=' + encodeURIComponent(filter_prize_name_id);
	}
	
	var filter_prize_token = $('input[name=\'filter_prize_token\']').val();
	
	if (filter_prize_token!="") {
		url += '&filter_prize_token=' + encodeURIComponent(filter_prize_token);
	}
  var filter_nickname = $('input[name=\'filter_nickname\']').val();
  
  if (filter_nickname!="") {
    url += '&filter_nickname=' + encodeURIComponent(filter_nickname);
  }


  var filter_email = $('input[name=\'filter_email\']').val();

  if (filter_email!="") {
    url += '&filter_email=' + encodeURIComponent(filter_email);
  }


	var filter_is_send = $('select[name=\'filter_is_send\']').val();
	
	if (filter_is_send !="") {
		url += '&filter_is_send=' + encodeURIComponent(filter_is_send);
	}

  var filter_prize_id = $('select[name=\'filter_prize_id\']').val();

  if (filter_prize_id !="") {
    url += '&filter_prize_id=' + encodeURIComponent(filter_prize_id);
  }
	location = url;
}
//--></script>
<?php echo $footer; ?>