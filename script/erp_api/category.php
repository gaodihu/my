<?php

//include_once('/home/www/new_myled.com/script/conf.php');
include_once('/home/www/new_myled.com/script/conf.php');
ini_set('memory_limit','1024M');
set_time_limit(0);

function  getCategory(){
    $sql = "SELECT c.category_id, d.name, c.parent_id, c.path, c.level
FROM oc_category c
LEFT JOIN oc_category_description d ON c.category_id = d.category_id
WHERE language_id =1";
    $rs = mysql_query($sql);
    $data = array();
    while($row = mysql_fetch_assoc($rs)){
        $_item = array(
            'category_id' => $row['category_id'],
            'name'     => $row['name'],
            'parent_id' => $row['parent_id'],
            'path' => $row['path'],
            'level' => $row['level']
         );
        $data[] = $_item;
    }
    return $data;
}

$data = getCategory();
$json = json_encode($data);
echo $json;
