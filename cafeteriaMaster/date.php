<?php
include_once("../DB/db.php");
if($_REQUEST['add_food_menu']=="add_food_menu")
{
	$a_date=base64_decode($_REQUEST['a_date_row']);
	$datenew=date("Y-m-d",strtotime($a_date));
	echo $weekname=date("l",strtotime($datenew));	
}
?>
