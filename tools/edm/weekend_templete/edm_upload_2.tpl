<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>商品上传</title>
<style>
input{ width:800px;}
</style>
<script src="/catalog/view/javascript/jquery/jquery.js"></script>
</head>

<body>
	<form method="post" enctype="multipart/form-data" action="/weekend_edm.php?act=upload"  id='edm_upload_form'>
		<table width="1000" border="1" bgcolor="#339966" id='upload_table'>
			<tr><td colspan="2" align="center">EDM周末邮件模板2</td></tr>
				<tr><td>上传页面title</td><td>
						<table>
							<tr><td>en:<input type="text" name='head_title[]'/></td></tr>
							<tr><td>de:<input type="text" name='head_title[]'/></td></tr>
							<tr><td>es:<input type="text" name='head_title[]'/></td></tr>
							<tr><td>fr:<input type="text" name='head_title[]'/></td></tr>
							<tr><td>it:<input type="text" name='head_title[]'/></td></tr>
						</table></td></tr>
				<tr><td>上传edm追踪代码</td><td>
						<table>
							<tr><td>en:<input type="text" name='edm_track[]'/></td></tr>
							<tr><td>de:<input type="text" name='edm_track[]'/></td></tr>
							<tr><td>es:<input type="text" name='edm_track[]'/></td></tr>
							<tr><td>fr:<input type="text" name='edm_track[]'/></td></tr>
							<tr><td>it:<input type="text" name='edm_track[]'/></td></tr>
						</table></td></tr>
				<tr><td colspan="2" align="center">头部bannaer区</td></tr>
				<tr><td>上传头部bannaer</td><td>
					<table>
							<tr><td>
							【en】banner图片<input type="file" name='top_banner[]'/> 
							</td></tr>
							<tr><td>
							【de】banner图片<input type="file" name='top_banner[]'/> 
							</td></tr>
							<tr><td>
								【es】banner图片<input type="file" name='top_banner[]'/> 
							</td></tr>
							<tr><td>
								【fr】banner图片<input type="file" name='top_banner[]'/> 
							</td></tr>
							<tr><td>
								【it】banner图片<input type="file" name='top_banner[]'/> 
							</td></tr>
						</table>
				</td>
				<tr><td colspan="2" align="center">Coupon1 区</td></tr>
				<tr><td>Coupon1 区</td><td>
					<table>
							<tr><td>
							【en】
							coupon1的alt值:<input type='text' name='coupon_1_title[]'><br>
							coupon1的图片:<input type="file" name='coupon_1[]'/>
							</td></tr>
							<tr><td>
							【de】
							coupon1的alt值:<input type='text' name='coupon_1_title[]'> <br>
							coupon1的图片:<input type="file" name='coupon_1[]'/>
							</td></tr>
							<tr><td>
								【es】
								coupon1的alt值:<input type='text' name='coupon_1_title[]'> <br>
								coupon1的图片:<input type="file" name='coupon_1[]'/>
							</td></tr>
							<tr><td>
								【fr】
								coupon1的alt值:<input type='text' name='coupon_1_title[]'> <br>
								coupon1的图片:<input type="file" name='coupon_1[]'/>
							</td></tr>
							<tr><td>
								【it】
								coupon1的alt值:<input type='text' name='coupon_1_title[]'> <br>
								coupon1的图片:<input type="file" name='coupon_1[]'/>
							</td></tr>
							
						</table>
				</td>
				</tr>
				<tr><td colspan="2" align="center">Coupon2 区</td></tr>
				<tr><td>Coupon2 区</td><td>
					<table>
							<tr><td>
							【en】
							coupon2的alt值:<input type='text' name='coupon_2_title[]'><br>
							coupon2的图片:<input type="file" name='coupon_2[]'/>
							</td></tr>
							<tr><td>
							【de】
							coupon2的alt值:<input type='text' name='coupon_2_title[]'><br>
							coupon2的图片:<input type="file" name='coupon_2[]'/>
							</td></tr>
							<tr><td>
								【es】
								coupon2的alt值:<input type='text' name='coupon_2_title[]'><br>
								coupon2的图片:<input type="file" name='coupon_2[]'/>
							</td></tr>
							<tr><td>
								【fr】
								coupon2的alt值:<input type='text' name='coupon_2_title[]'><br>
								coupon2的图片:<input type="file" name='coupon_2[]'/>
							</td></tr>
							<tr><td>
								【it】
								coupon2的alt值:<input type='text' name='coupon_2_title[]'><br>
								coupon2的图片:<input type="file" name='coupon_2[]'/>
							</td></tr>
							
						</table>
				</td>
				</tr>
				<tr><td colspan="2" align="center">SHOP NOW 链接区</td></tr>
				<tr><td>SHOP NOW 链接</td><td>
					<table>
							<tr><td>【en】链接:<input type='text' name='shop_now[]'></td></tr>
							<tr><td>【de】链接:<input type='text' name='shop_now[]'></td></tr>
							<tr><td>【es】链接:<input type='text' name='shop_now[]'></td></tr>
							<tr><td>【fr】链接:<input type='text' name='shop_now[]'></td></tr>
							<tr><td>【it】链接:<input type='text' name='shop_now[]'></td></tr>
						</table>
				</td>
				</tr>
		
				
				<tr><td colspan="2" align="center">商品区</td></tr>
				<tr class="pro_area"><td align="center">【en】SKU</td><td><input type="text" name='pro_sku[en]'/></td></td></tr>
				<tr class="pro_area"><td align="center">【de】SKU</td><td><input type="text" name='pro_sku[de]'/></td></td></tr>
				<tr class="pro_area"><td align="center">【es】SKU</td><td><input type="text" name='pro_sku[es]'/></td></td></tr>
				<tr class="pro_area"><td align="center">【fr】SKU</td><td><input type="text" name='pro_sku[fr]'/></td></td></tr>
				<tr class="pro_area"><td align="center">【it】SKU</td><td><input type="text" name='pro_sku[it]'/></td></td></tr>
		</table>
		<table width="600" border="1" bgcolor="#339966">
			<tr><td><input type="hidden" value="2" name='temple_type'/></td></tr>
			<tr><td><input type="submit" value="上传" id='sub'/><input type="reset" value="重置" />
			</td></tr>
		</table>
	</form>
</body>
</html>