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
      <h1><img src="view/image/review.png" alt="" />论坛发布信息</h1>
	  <div class="buttons"><a href="<?php echo $form_user_list;?>" class="button">用户发布列表</a>
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
              <td class="center">forum name</td>
              <td class="center">forum url</td>
			  <td class="center">profile link</td>
			  <td class="center">user name</td>
			  <td class="center">paypal account</td>
			  <td class="center">contact email</td>
			  <td class="center">contact name</td>
			  <td class="center">created at</td>
			  <td class="center">操作</td>
            </tr>
          </thead>
          <tbody>
		  	<tr class="filter">
              <td></td>
              <td></td>
              <td class="center"><input type="text" name="filter_forum_name" value="<?php echo $filter['filter_forum_name']; ?>" /></td>	
              <td class="left"><input type="text" name="filter_forum_url" value="<?php echo $filter['filter_forum_url']; ?>" /></td>  
			  <td></td>
			  <td class="center"><input type="text" name="filter_user_name" value="<?php echo $filter['filter_user_name']; ?>" /></td>
			  <td></td>
			  <td class="center"><input type="text" name="filter_contact_email" value="<?php echo $filter['filter_contact_email']; ?>" /></td>
			   <td class="center"><input type="text" name="filter_contact_name" value="<?php echo $filter['filter_contact_name']; ?>" /></td>
			   <td></td>
              <td align="right"><a onclick="filter();" class="button">filter</a></td>
            </tr>
            <?php if ($forum_pro_infolist) { ?>
            <?php foreach ($forum_pro_infolist as $forum_pro) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($forum_pro['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $forum_pro['forum_program_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $forum_pro['forum_program_id']; ?>" />
                <?php } ?></td>
			   
              <td class="left"><?php echo $forum_pro['forum_program_id']; ?></td>
			  <td class="left"><?php echo $forum_pro['forum_name']; ?></td>
			  <td class="left"><?php echo $forum_pro['forum_url']; ?></td>
			  <td class="left"><?php echo $forum_pro['profile_link']; ?></td>
			  <td class="left"><?php echo $forum_pro['user_name']; ?></td>
			  <td class="left"><?php echo $forum_pro['paypal_account']; ?></td>
			  <td class="left"><?php echo $forum_pro['contact_email']; ?></td>
			  <td class="left"><?php echo $forum_pro['contact_name']; ?></td>
			  <td class="left"><?php echo $forum_pro['created_at']; ?></td>
			      
              <td class="right"><?php foreach ($forum_pro['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10">无记录</td>
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
	
	var filter_forum_name = $('input[name=\'filter_forum_name\']').attr('value');
	
	if (filter_forum_name) {
		url += '&filter_forum_name=' + encodeURIComponent(filter_forum_name);
	}
	
	var filter_forum_url = $('input[name=\'filter_forum_url\']').attr('value');
	
	if (filter_forum_url) {
		url += '&filter_forum_url=' + encodeURIComponent(filter_forum_url);
	}
  var filter_user_name = $('input[name=\'filter_user_name\']').attr('value');
  
  if (filter_user_name) {
    url += '&filter_user_name=' + encodeURIComponent(filter_user_name);
  }
	var filter_contact_email = $('input[name=\'filter_contact_email\']').attr('value');
	
	if (filter_contact_email) {
		url += '&filter_contact_email=' + encodeURIComponent(filter_contact_email);
	}
	var filter_contact_name = $('input[name=\'filter_contact_name\']').attr('value');
	
	if (filter_contact_name) {
		url += '&filter_contact_name=' + encodeURIComponent(filter_contact_name);
	}

	location = url;
}
</script>
<?php echo $footer; ?>