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
      <h1> 抽奖活动</h1>
      <div class="buttons"><a  href="<?php echo $add; ?>" class="button">增加</a></div> 
    </div>
    <div class="content">
        <table class="list">
          <thead>
            <tr>
          <!--     <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*='selected']').attr('checked', this.checked);" /></td> -->
              <td class="center"><?php if ($sort == 'id') { ?>
                <a href="<?php echo $sort_id; ?>" class="<?php echo strtolower($order); ?>">ID</a>
                <?php } else { ?>
                <a href="<?php echo $sort_id; ?>">ID</a>
                <?php } ?></td>
              <td class="center"> name</td>
			  <td class="center">start time</td>
			  <td class="center"> end time</td>
              <td class="center"> 类型</td>
              <td class="center">操作</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($lottery_lists) { ?>
            <?php foreach ($lottery_lists as $lottery_name) { ?>
            <tr>
              <td class="center"><?php echo $lottery_name['id']; ?></td>
			  <td class="center"><?php echo $lottery_name['name']; ?></td>
			  <td class="center" style='width:250px'><?php echo $lottery_name['start_time']; ?></td>
			  <td class="center" style='width:250px'><?php echo $lottery_name['end_time']; ?></td>
              <td class="center" style='width:250px'><?php echo $type_arr[$lottery_name['type']]; ?></td>
              <td class="center" style='width:150px'><?php foreach ($lottery_name['action'] as $action) { ?>
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
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>

<?php echo $footer; ?>