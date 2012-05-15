<html>
<head>
<h1>BD Demo Builder</h1>
<?php include 'obj.php'; include 'button.php'; ?>
</head>
<body>
<?php
//determinePage();
$_GET['demo_id'] = 1;
renderDemoDetailPage();
?>
</body>
</html>

<?php

//exec
//renderUserPage();

function determinePage(){
	if(isset($_GET["page"]) && $_GET["page"] == 'user'){//user Page & temp default
		renderUserPage();
	}
	elseif(isset($_GET["page"]) && $_GET["page"] == 'demo'){
		if (isset($_GET['owner_id'])){
			renderDemoPage();			
		}
		else{
			trigger_error("No Owner Defined", E_USER_ERROR);
		}
	}
	elseif(isset($_GET["page"]) && $_GET["page"] == 'demo_detail'){
		if (isset($_GET['demo_id'])){
			renderDemoDetailPage();
		}
		else{
			trigger_error("No Demo Defined", E_USER_ERROR);
		}
	}
	elseif(isset($_GET["page"]) && $_GET["page"] == 'button_detail'){
		renderButtonDetailPage();
	}
	else{
		renderUserPage();
	}

}
function renderUserPage(){
	$db = new db_connection();
	$root = 'http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/php/index.php';
	$db_response = array();
	$db->exec('select * from users where active = 1;');
	$db->disconnect();
	$db_response = $db->response;

	print '<h4> Users: </h4><div id = "content">';
	foreach ($db_response as $row) {
		print '<a href="'.$root.'?page=demo&owner_id='.$row['id'].'" >'.$row['firstname'].'  '.$row['lastname'].'</a> <br>';
	}
	print '</div>';
}
function renderDemoPage(){
	//Add New!
	print '<div id = "add_demo"> 
			<b>Create New Demo</b>
			<form action="obj.php" method="post" enctype="multipart/form-data">
			Demo Name: <input type="text" name="demo_name" /><br />
			Dashboard ID <i>(lowercase & no spaces)</i>: <input type="text" name="dashboard_id" /><br />
			URL: <input type="text" name="site_url" /><br />
			<input type="hidden" name="meebo_action" value ="create_demo" />
			<input type="hidden" name="owner_id" value ="'.$_GET['owner_id'].'" />
			<input type="submit" value="Submit" />
			</form>
			</div>
			<hr><hr>
		';

	//Show Current
	$db = new db_connection();
	$root = 'http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/php/index.php';
	$db_response = array();
	$db->exec('select id, demo_name, site_url, dashboard_id from demo where owner_id = '.$_GET['owner_id'].';');
	$db->disconnect();
	$db_response = $db->response;

	print '<h4> Demos: </h4><div id = "content">';
	foreach ($db_response as $row) {
		print '<a href="'.$root.'?page=demo_detail&owner_id='.$_GET['owner_id'].'&demo_id='.$row['id'].'" >'.$row['demo_name'].'</a>   '.$row['dashboard_id'].'  <a href="'.$row['site_url'].'">'.$row['site_url'].'</a> <br>';
	}
	print '</div>';
}
function renderDemoDetailPage(){
	//Show Current
	$db_demo = new db_connection();
	$root = 'http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/php/index.php';
	$db_demo_response = array();
	$db_demo->exec('select demo_name, site_url, dashboard_id from demo where id = '.$_GET['demo_id'].';');
	$db_demo->disconnect();
	$db_demo_response = $db_demo->response[0];


	//Name: '.$db_demo_response['demo_name'].'     Dashboard ID: '.$db_demo_response['dashboard_id'].'        URL: '.$db_demo_response['site_url'].' <br/> <br/>";
	//<button type="button" onclick="update_demo_form()">Update</button>
	// 		</script>';
	// //print $update_str;

	echo '<div id="demo_details">
			<b>Demo Basics</b>
			<form action="obj.php" method="post" enctype="multipart/form-data">
			Demo Name: <input type="text" name="demo_name" value="'.$db_demo_response['demo_name'].'"/><br />
			Dashboard ID <i>(lowercase & no spaces)</i>: <input type="text" name="dashboard_id" value="'.$db_demo_response['dashboard_id'].'"/><br />
			URL: <input type="text" name="site_url" value="'.$db_demo_response['site_url'].'"/><br />
			<input type="hidden" name="meebo_action" value ="edit_demo" />
			<input type="hidden" name="demo_id" value ="'.$_GET['demo_id'].'" />
			<input type="hidden" name="owner_id" value ="'.$_GET['owner_id'].'" />
			<input type="submit" value="Update" />

		</div>
		<hr><hr>
		<script type="text/javascript">
		function update_demo_form(){
			alert("hey");

		};
		</script>';

	//Show current buttons
	//Render a link to go to the Button detail page and create new button
	$db_button = new db_connection();
	$db_button_response = array();
	$db_button->exec('select * from buttons where demo_id = '.$_GET['demo_id'].';');
	$db_button->disconnect();
	$db_button_response = $db_button->response;

	print '<h4> Buttons: </h4><div id = "buttons">';
	foreach ($db_button_response as $row) {
		print '<a href="'.$root.'?page=button_detail&owner_id='.$_GET['owner_id'].'&demo_id='.$_GET['demo_id'].'&button_id='.$row['id'].'" >'.$row['title'].'</a>';
		if (isset($row['icon_url']) && !is_null($row['icon_url'])){
			$icon = $row['icon_url'];
		}
		elseif(isset($row['icon_dir']) && !is_null($row['icon_dir'])){
			$icon = $root.$row['icon_dir'];
		}
		else{
			$icon = -1;
		}
		if ($icon != -1){
		print '<img src="'.$icon.'"/>';			
		}
	}
	print '</div>';

//show buttons
}
function renderButtonDetailPage(){
	return true;
}
?>