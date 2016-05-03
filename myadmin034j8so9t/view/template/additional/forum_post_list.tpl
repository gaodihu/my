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
      <h1><img src="view/image/review.png" alt="" />用户发表信息</h1>
	  <div class="buttons"><a href="<?php echo $form_pro_list;?>" class="button">论坛信息列表</a>
	  	<a onclick="$('form').submit();" class="button">删除</a>
	  </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center">ID</td>
              <td class="center">GA link Id</td>
              <td class="center">user email</td>
			  <td class="center">post link</td>
			  <td class="center">ga click</td>
			  <td class="center">make money </td>
			  <td class="center">get points</td>
			  <td class="center">status</td>
			  <td class="center">email_send</td>
			  <td class="center">created at</td>
			  <td class="center">操作</td>
            </tr>
          </thead>
          <tbody>
		  	<tr class="filter">
              <td></td>
              <td></td>
              <td class="center"><input type="text" name="filter_fourm_ga_id" value="<?php echo $filter['filter_fourm_ga_id']; ?>" /></td>	
              <td class="left"><input type="text" name="filter_user_email" value="<?php echo $filter['filter_user_email']; ?>" /></td>  
			  <td></td>
			  <td></td>
			  <td></td>
			  <td></td>
			   <td class="center"><input type="text" name="filter_status" value="<?php echo $filter['filter_status']; ?>" /></td>
			   <td class="center"><input type="text" name="filter_email_send" value="<?php echo $filter['filter_email_send']; ?>" /></td>
			   <td></td>
              <td align="right"><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($forum_user_list) { ?>
            <?php foreach ($forum_user_list as $forum_user) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($forum_user['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $forum_user['forum_user_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $forum_user['forum_user_id']; ?>" />
                <?php } ?></td>
			   
              <td class="center"><?php echo $forum_user['forum_user_id']; ?></td>
			  <td class="center"><?php echo $forum_user['fourm_ga_id']; ?></td>
			  <td class="center"><?php echo $forum_user['user_email']; ?></td>
			  <td class="center"><?php echo $forum_user['forum_link']; ?></td>
			  <td class="center"><?php echo $forum_user['ga_click']; ?></td>
			  <td class="center"><?php echo $forum_user['forum_money']; ?></td>
			  <td class="center"><?php echo $forum_user['forum_get_points']; ?></td>
			  <td class="center"><?php echo $forum_user['status']; ?></td>
			  <td class="center"><?php echo $forum_user['email_send']; ?></td>
			  <td class="center"><?php echo $forum_user['created_at']; ?></td>
			      
              <td class="center"><?php foreach ($forum_user['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="12">无记录</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript">
function filter() {
	url = 'index.php?route=additional/forum&token=<?php echo $token; ?>';
	
	var filter_fourm_ga_id = $('input[name=\'filter_fourm_ga_id\']').attr('value');
	
	if (filter_fourm_ga_id) {
		url += '&filter_fourm_ga_id=' + encodeURIComponent(filter_fourm_ga_id);
	}
	
	var filter_user_email = $('input[name=\'filter_user_email\']').attr('value');
	
	if (filter_user_email) {
		url += '&filter_user_email=' + encodeURIComponent(filter_user_email);
	}
  var filter_status = $('input[name=\'filter_status\']').attr('value');
  
  if (filter_status) {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }
	var filter_email_send = $('input[name=\'filter_email_send\']').attr('value');
	
	if (filter_email_send) {
		url += '&filter_email_send=' + encodeURIComponent(filter_email_send);
	}

	location = url;
}
</script>
<?php echo $footer; ?>