<?php

//输入验证类
class Validate {

    //处理字符串数据，去除首尾空格，去除tags，去除html标签等
    public function eInput($string)
    {
        $string =trim($string);
        $string = strip_tags ($string);

        $string = htmlspecialchars ($string);
        return $string;
    }
    // 使用正则匹配验证数据格式
	public function volidatFormat($pattern,$string)
    {
		return preg_match($pattern,$string);
	}

    //字符串转为数字
    public function intString($string)
    {
        return intval($string);
    }

    //转义‘\’‘/’
    public function addsString($string)
    {
        return addslashes($string);
    }
    //反转义
    public function stripString($string)
    {
        return stripslashes($string);
    }
    //把 HTML 实体转换为字符
    public function htmlEncode($string){
        return html_entity_decode($string);
    }
    //把 字符转换为HTML 实体
    public function htmlDecode($string){
        return htmlentities($string);
    }
    
    //检查是否是数字
     public function isNumber($val)
    {
        if(preg_match("^[0-9]+$", $val)){
            return true;
        }else{
             return false;
        }       
    }
    //邮箱验证
    public function isEmail($val,$domain="")
    {
        if(!$domain)
        {
            if( preg_match("/^[a-z0-9-_.]+@[\da-z][\.\w-]+\.[a-z]{2,4}$/i", $val) )
            {
                return true;
            }
            else
                return false;
        }
        else
        {
            if( preg_match("/^[a-z0-9-_.]+@".$domain."$/i", $val) )
            {
                return true;
            }
            else
                return false;
        }
    }
}
?>