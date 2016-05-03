<?php
/*
  * 
  *  分页参数配置
  *
 ***/
$config['per_page'] = 20;
//分页方法自动测定你 URI 的哪个部分包含页数。如果你需要一些不一样的，你可以明确指定它。
$config['num_links'] = 5;
//默认分页URL中是显示每页记录数,启用use_page_numbers后显示的是当前页码
$config['use_page_numbers'] = TRUE;
$config['page_query_string'] = TRUE;
$config['query_string_segment'] = 'page';
//你希望在分页的左边显示“第一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE 。
$config['first_link'] = '&lt;|';
//你希望在分页的右边显示“最后一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE 。
$config['last_link'] = '&gt;|';
//你希望在分页中显示“下一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE 。
$config['next_link'] = '&gt;';
//你希望在分页中显示“上一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE 。
$config['prev_link'] = '&lt;';
//“当前页”链接的打开标签。
$config['cur_tag_open'] = '<b>';
//“当前页”链接的关闭标签。
$config['cur_tag_close'] = '</b>';
//你想要给每一个链接添加 CSS 类
$config['anchor_class'] = "";