<?php 
//得到随机字符串
 function getRandomStr($strlen){
     $chars = array( 
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",  
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",  
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",  
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",  
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",  
            "3", "4", "5", "6", "7", "8", "9" 
        ); 
        $charsLen = count($chars) - 1; 
        shuffle($chars);// 将数组打乱
        $output = ""; 
        for ($i=0; $i<$strlen; $i++) 
        { 
            $output .= $chars[mt_rand(0, $charsLen)]; //获得一个数组元素
        }  
        return $output;
 }
?>