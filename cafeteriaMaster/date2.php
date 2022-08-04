<?php
include_once("../DB/db.php");
if($_REQUEST['add_food']=="add_food")
{
	$a_date=base64_decode($_REQUEST['a_date_row']);
	echo $datenew=date("Y-m-d",strtotime($a_date));
}
?>