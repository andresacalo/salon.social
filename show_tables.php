<?php
$db=new mysqli('127.0.0.1','root','','salon_social');
if($db->connect_errno){die('conn');}
$res=$db->query('SHOW TABLES');
while($row=$res->fetch_row()) echo $row[0],"\n";
?>
