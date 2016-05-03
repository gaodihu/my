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
		   <tr><td colspan="2" align="center">选择模板类型</td></tr>
			<tr>
					<td>1</td>
					<td> <a href='/weekend_edm.php?act=temp1'>周末邮件模板1</a>
					</td>
				</tr>
				<tr>
					<td>2</td>
					<td> <a href='/weekend_edm.php?act=temp2'>周末邮件模板2</a><img src='/edm/thumb/week_2.jpg' width='100' height='200'>
					</td>
				</tr>
		</table>
	</form>
</body>
</html>