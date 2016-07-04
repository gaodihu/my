<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
</head>
<body bgcolor="#f1f1f1">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
  <tr>
    <td bgcolor="#f1f1f1">
      <table width="700" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td style="padding:12px 0px;">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="143"><a href="<?php echo $store_url;?>"><img src="<?php echo $store_url;?>image/email/elogo.jpg" width="143" height="79" /></a></td>
                <td width="550" align="right">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr><td><img src="<?php echo $store_url;?>image/email/m_03.jpg" width="19" height="14" /><a style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333; text-decoration:none; height:79px; line-height:79px;" href="<?php echo $store_url;?>"><?php echo $text_home;?></a></td><td><img src="<?php echo $store_url;?>image/email/m_05.jpg" width="42" height="14" /><a style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333; height:79px; line-height:79px; text-decoration:none;" href="<?php echo $store_url;?>new_arrivals.html"><?php echo $text_menu_new_arrivals;?></a></td><td><img src="<?php echo $store_url;?>image/email/m_07.jpg" width="42" height="14" /><a style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333; text-decoration:none;" href="<?php echo $store_url;?>top-sellers.html"><?php echo $text_menu_top_sellers;?></a></td><td><img src="<?php echo $store_url;?>image/email/m_09.jpg" width="42" height="14" /><a style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333; text-decoration:none;" href="<?php echo $store_url;?>deals.html"><?php echo $text_menu_deals;?></a></td><td><img src="<?php echo $store_url;?>image/email/m_11.jpg" width="42" height="14" /><a style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333; text-decoration:none;" href="<?php echo $store_url;?>clearance.html"><?php echo $text_menu_clearance;?></a></td></tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td width="698" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333; border:1px solid #dcdcdc; padding:15px 20px; line-height:24px; background-color:#fff;"><?php echo $text_order_welcome;?> <?php echo $spilt_order_tips; ?>
<table width="660" border="0" cellpadding="0" cellspacing="0">
  <tr><td style="border-bottom:2px solid #8fc72f; font-weight:bold; font-size:13px; line-height:30px;"><?php echo $text_order_product_info_text;?></td></tr>
  <tr><td><table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <thead>
                        <tr>
                            <th align="left" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;"><?php echo $text_payment_method;?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
                                <p><?php echo $order['payment_method'];?></p>

            <p><strong><?php echo $text_payment_information;?></strong> <br><?php echo $order['payment_information'];?></p>

    
                            </td>
                        </tr>
                        </tbody>
                    </table></td></tr>
  <tr><td height="10"></td></tr>
  <tr>
                <td><table cellspacing="0" cellpadding="0" border="0">
                        <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;"><?php echo $text_shipping_information;?></th>
                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;"><?php echo $text_shipping_method;?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
                                <?php echo $order['shipping_address'];?>
                            </td>
                            <td>&nbsp;</td>
                            <td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
                                <?php echo $order['shipping_method'];?>
                                &nbsp;
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
            

                    <p style="font-size:12px; margin:0 0 10px 0"></p>
                </td>
            </tr>
  <tr>
  <tr>
    <td style="font-weight:bold; font-size:12px; line-height:30px; border-top:2px solid #8fc72f;">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="360" align="center"><?php echo $col_product;?></td>
          <td width="120" align="center"><?php echo $col_price;?></td>
          <td width="60" align="center"><?php echo $col_qty;?></td>
          <td width="120" align="center"><?php echo $col_total;?></td>
        </tr>
      </table>
    </td>
  </tr>
  <?php foreach($order['products'] as $product){ ?>
  <tr>
    <td style="border-top:1px dashed #e6e6e6;">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td style="padding:10px 0px;"><a href="#"><img src="<?php echo $product['image_email'];?>" width="60" height="60" /></a></td><td width="10"></td><td width="280"><a style="font-size:12px; line-height:18px; color:#666; text-decoration:none;" href="<?php echo $product['href'];?>"><?php echo $product['name'];?></a></td><td width="10"></td><td width="120" align="center"><?php echo $product['price_format'];?></td><td width="60" align="center"><?php echo $product['quantity'];?></td><td width="120" align="center"><?php echo $product['total_format'];?></td>
        </tr>
      </table>
    </td>
  </tr>
  <?php } ?>

  <?php foreach($order['totals'] as $total) { ?>
  <tr>
    <td bgcolor="#f6f6f6" style="line-height:30px;">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="660" align="right"><?php echo $total['title'];?>:<?php echo $total['text'];?></td>
        </tr>
      </table>
    </td>
  </tr>
  <?php } ?>
 
</table><?php echo $text_no_reply;?>
</td>
        </tr>
        <tr><td height="40" style="color:#777; line-height:40px; text-align:center; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-bottom:20px;"><?php echo $text_footer;?></td></tr>
      </table>
    </td>
  </tr>
  
</table>
</body>
</html>
