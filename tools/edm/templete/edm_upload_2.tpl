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
	<form method="post" enctype="multipart/form-data" action="/edm.php?act=upload"  id='edm_upload_form'>
		<table width="1000" border="1" bgcolor="#339966" id='upload_table'>
			<tr><td colspan="2" align="center">EDM邮件模板(不同国家，不同商品)</td></tr>
				<tr>
					<td>选择模板类型</td>
					<td><input type="radio" name='temple_type' value='1' style="width:20px;"/><img src='edm/thumb/2-1-4.jpg' width='100' height='200'>1号模板(1行4个商品)
						   <input type="radio" name='temple_type' value='2' style="width:20px;"/><img src='edm/thumb/2-1-3.jpg' width='100' height='200'>2号模板(1行3个商品)
						   <input type="radio" name='temple_type' value='3' style="width:20px;"/><img src='edm/thumb/2-2-4.jpg' width='100' height='200'>3号模板(1行4个商品)
					</td>
				</tr>
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
							<tr><td>【en】链接:<input type='text' name='top_banner_link[]'>title:<input type='text' name='top_banner_title[]'><input type="file" name='top_banner[]'/></td></tr>
							<tr><td>【de】链接:<input type='text' name='top_banner_link[]'>title:<input type='text' name='top_banner_title[]'><input type="file" name='top_banner[]'/></td></tr>
							<tr><td>【es】链接:<input type='text' name='top_banner_link[]'>title:<input type='text' name='top_banner_title[]'><input type="file" name='top_banner[]'/></td></tr>
							<tr><td>【fr】链接:<input type='text' name='top_banner_link[]'>title:<input type='text' name='top_banner_title[]'><input type="file" name='top_banner[]'/></td></tr>
							<tr><td>【it】链接:<input type='text' name='top_banner_link[]'>title:<input type='text' name='top_banner_title[]'><input type="file" name='top_banner[]'/></td></tr>
						</table>
				</td>
	
				</tr>
				<tr><td colspan="2" align="center">底部部bannaer区</td></tr>
				<tr><td>上传底部bannaer</td><td><table>
							<tr><td>【en】链接:<input type='text' name='foot_banner_link[]'>title:<input type='text' name='foot_banner_title[]'><input type="file" name='foot_banner[]'/></td></tr>
							<tr><td>【de】链接:<input type='text' name='foot_banner_link[]'>title:<input type='text' name='foot_banner_title[]'><input type="file" name='foot_banner[]'/></td></tr>
							<tr><td>【es】链接:<input type='text' name='foot_banner_link[]'>title:<input type='text' name='foot_banner_title[]'><input type="file" name='foot_banner[]'/></td></tr>
							<tr><td>【fr】链接:<input type='text' name='foot_banner_link[]'>title:<input type='text' name='foot_banner_title[]'><input type="file" name='foot_banner[]'/></td></tr>
							<tr><td>【it】链接:<input type='text' name='foot_banner_link[]'>title:<input type='text' name='foot_banner_title[]'><input type="file" name='foot_banner[]'/></td></tr>
						</table></td></tr>
				
				<tr><td colspan="2" align="center">商品区</td></tr>
				<tr class="pro_area"><td align="center">【en】SKU</td><td><input type="text" name='pro_sku[en]'/></td></td></tr>
				<tr class="pro_area"><td align="center">【de】SKU</td><td><input type="text" name='pro_sku[de]'/></td></td></tr>
				<tr class="pro_area"><td align="center">【es】SKU</td><td><input type="text" name='pro_sku[es]'/></td></td></tr>
				<tr class="pro_area"><td align="center">【fr】SKU</td><td><input type="text" name='pro_sku[fr]'/></td></td></tr>
				<tr class="pro_area"><td align="center">【it】SKU</td><td><input type="text" name='pro_sku[it]'/></td></td></tr>
		</table>
		<table width="600" border="1" bgcolor="#339966">
		  <input type='hidden' name='d_lang' value='1'>
			<tr><td><input type="submit" value="上传" id='sub'/><input type="reset" value="重置" />
			</td></tr>
		</table>
	</form>
</body>
</html>