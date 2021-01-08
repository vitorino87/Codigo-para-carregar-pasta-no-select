<?php

$path = $_GET['path'];
echo "alert(php: $path)";
$a = is_dir($path);
echo "alert(php: $a)";
if($a){
	echo "1";
}else{
	echo "";
}

?>