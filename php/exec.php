<html>
<head>Hi there!</head>
<body>
<div id="edit">
<form action="obj.php" method="post" enctype="multipart/form-data">

</div></body>
</html>

<?php
include 'obj.php';
$test = new Demo(); 
//$test->id = 1;

// $t1 = array("foo" => "bar", "hi" => "ho");
// foreach($t1 as $key => $value){
// 	print $key;
// }

$test->saveDemo();


//works
// $db = new db_connection();
// $db->exec("select * from demo;");
// print $db->response['owner_id'];
// $db->disconnect();

?>