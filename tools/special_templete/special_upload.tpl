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
	<form method="post" enctype="multipart/form-data" action="/special.php?act=upload">
		<table width="1000" border="1" bgcolor="#339966" id='upload_table'>
				<tr><td colspan="2" align="center">special 商品上传</td></tr>
				<tr><td>专题文件夹名称</td><td><input type="text" name='special_dir_name'/></td></tr>
				<tr><td>上传页面head信息</td><td>
						<table>
							<tr><td>
								【en】title<input type="text" name='head_title[]'/>
								description:<input type="text" name='head_desc[]'/>
								keyword:<input type="text" name='head_keyword[]'/>
							</td></tr>
							<tr><td>【de】title<input type="text" name='head_title[]'/>description<input type="text" name='head_desc[]'/>keyword<input type="text" name='head_keyword[]'/></td></tr>
							<tr><td>【es】title<input type="text" name='head_title[]'/>description<input type="text" name='head_desc[]'/>keyword<input type="text" name='head_keyword[]'/></td></tr>
							<tr><td>【fr】title<input type="text" name='head_title[]'/>description<input type="text" name='head_desc[]'/>keyword<input type="text" name='head_keyword[]'/></td></tr>
							<tr><td>【it】title<input type="text" name='head_title[]'/>description<input type="text" name='head_desc[]'/>keyword<input type="text" name='head_keyword[]'/></td></tr>
						</table></td></tr>
				<tr><td colspan="2" align="center">头部bannaer区</td></tr>
				<tr><td>上传头部背景bannaer</td><td>
					<table>
							<tr><td><input type="file" name='top_background_banner'/></td></tr>
						</table>
				</td>
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
				<tr class="pro_area">
					<td align="center">SKU</td>
					<td>
						<table>
							<tr><td><input type="text" name='pro_sku'/></td> </tr>
						</table>
					</td>
				</tr>
		</table>
		<table width="600" border="1" bgcolor="#339966">
			<tr><td><input type="submit" value="上传" id='submit'/><input type="reset" value="重置" />
			</td></tr>
		</table>
	</form>
</body>
</html>