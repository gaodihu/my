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
      <h1><img src="view/image/review.png" alt="" />渠道URL</h1>
	  <div class="buttons"><a href="<?php echo $add_url;?>" class="button">增加</a>
	  	<a onclick="$('form').submit();" class="button">删除</a>
	  </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 's_id') { ?>
                <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>">ID</a>
                <?php } else { ?>
                <a href="<?php echo $sort_id; ?>">ID</a>
                <?php } ?></td>
		
              <td class="left">url_link</td>
             
              <td class="right">操作</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($exclusive_url) { ?>
            <?php foreach ($exclusive_url as $exclusive) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($exclusive['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $exclusive['s_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $exclusive['s_id']; ?>" />
                <?php } ?></td>
			   
              <td class="left"><?php echo $exclusive['s_id']; ?></td>
			  <td class="left"><?php echo $exclusive['url']; ?></td>
			      
              <td class="right"><?php foreach ($exclusive['action'] as $action) { ?>
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

<?php echo $footer; ?>