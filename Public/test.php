<?php

$r = $_REQUEST;
$t = var_export($r,true);
file_put_contents("t.txt", $t);
?>