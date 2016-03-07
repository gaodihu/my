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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">

          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="battery_status">
                <?php if ($battery_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          
          <tr>
            <td>可以支持的电池类型</td>
            <td><select name="battery_type[]"  multiple="multiple">
                  <option value="1"  <?php if (in_array(1,$battery_type)) { ?>selected="selected"<?php } ?>>纽扣电池</option>
                  <option value="2"  <?php if (in_array(2,$battery_type)) { ?>selected="selected"<?php } ?>>内置电池</option>
                  <option value="3"  <?php if (in_array(3,$battery_type)) { ?>selected="selected"<?php } ?>>配置电池</option>
                  <option value="4"  <?php if (in_array(4,$battery_type)) { ?>selected="selected"<?php } ?>>纯电池</option>
                
              </select></td>
          </tr>

            <tr>
                <td>默认包裹限制重量，单位g</td>
                <td><input type="text" name="battery_package_limit_weight" value="<?php echo $battery_package_limit_weight; ?>" size="10" /></td>
            </tr>



            <tr>
                <td>按照包裹寄送国家限制重量，单位g</td>
                <td>
                    <a onclick="addItem()" style="font-size: 14px;">添加</a>
                    <div dom="itemlist">

                        <?php foreach($battery_package_limit_weight_country as $_k => $item) { ?>

                            <div  dom="additem">
                                <?php if($countries){ ?>
                                <select name="battery_package_limit_weight_country[]">
                                    <option value=""></option>
                                    <?php foreach($countries as $country) { ?>
                                    <option <?php if($_k == $country['iso_code_2']){ echo 'selected="selected"';} ?> value="<?php echo $country['iso_code_2']; ?>"><?php echo $country['name']; ?></option>
                                    <?php } ?>

                                </select>
                                <?php } ?>

                                <input type="text" name="battery_package_limit_weight_country_limit[]" value="<?php echo $item; ?>" size="10" />

                                <a dom="remove">删除</a>
                            </div>

                        <?php } ?>


                    </div>


                </td>
            </tr>
          
          
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="battery_sort_order" value="<?php echo $battery_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<div style="display: none" dom="template">
    <?php if($countries){ ?>
    <select name="battery_package_limit_weight_country[]">
        <option value=""></option>
        <?php foreach($countries as $country) { ?>
        <option value="<?php echo $country['iso_code_2']; ?>"><?php echo $country['name']; ?></option>
        <?php } ?>

    </select>
    <?php } ?>

    <input type="text" name="battery_package_limit_weight_country_limit[]" value="<?php echo $battery_package_limit_weight; ?>" size="10" />

    <a dom="remove">删除</a>
</div>
<script>
    function addItem(){

        var additem = jQuery("div[dom=template]").clone();
        additem.attr("dom","additem");

        jQuery("div[dom=itemlist]").append(additem);
        jQuery("div[dom=itemlist] > div[dom=additem]").show();

        jQuery("a[dom=remove]").click(function() {
            $(this).closest("div").remove()
        });
    }

    jQuery("a[dom=remove]").click(function() {
        $(this).closest("div").remove()
    });

</script>

<?php echo $footer; ?> 