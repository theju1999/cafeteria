<?php
session_start();
include_once("../DB/db.php");
$user = $_SESSION[ 'user' ];
$acc_year = $_SESSION[ 'accYear' ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>MySchoolOne</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="MySchoolOne">
	<meta name="author" content="MySchoolOne">
	<link rel="stylesheet" href="../new_css/assets/css/styles.css?=140">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
	<link href='../new_css/assets/demo/variations/default.css' rel='stylesheet' type='text/css' media='all' id='styleswitcher'>
	<link href='../new_css/assets/demo/variations/default.css' rel='stylesheet' type='text/css' media='all' id='headerswitcher'>
	<link rel='stylesheet' type='text/css' href='../new_css/assets/plugins/bootstro.js/bootstro.min.css' />
	<link rel='stylesheet' type='text/css' href='../new_css/assets/plugins/codeprettifier/prettify.css' />
	<link rel='stylesheet' type='text/css' href='../new_css/assets/plugins/form-toggle/toggles.css' />
	<link rel='stylesheet' type='text/css' href='../new_css/assets/plugins/pines-notify/jquery.pnotify.default.css' />
	<link rel='stylesheet' type='text/css' href='../new_css/assets/plugins/form-select2/select2.css' />
	<script type="text/javascript">
// for displaying from ajax we can use this script from here
modalfunction=()=>{
	if(confirm("Are you sure you want to open this modal ?"))
	{
		$.ajax({
			type: "POST",
			url: "Template_css_ajax.php",
			data: "modalfunction=modalfunction",
			beforeSend:function()
			{
				$("#modalfunctiondiv").append("<i class='fa fa-spinner fa-spin'></i> Loading...");
			},
			success: function (data) {
				$("#modalfunctiondiv").html(data);
			}
		});
	}
}
</script>
</head>
<body>
	<?php include_once('../header1_nw.php');
	?>
	<div id="page-container">
		<?php include_once('../sidebar1_nw.php');?>
		<div id="page-content">
			<div id="wrap" style="background-color:#fff"> 
				<div id="page-heading" style="background-color:#fff">
					<ol class="breadcrumb">
						<li><a href="../index.php">Dashboard</a></li>
							
						<li class="active">Cafeteria Add Menu</li> 
					</ol>
					<br>
				</div>
				<div class="container">
					<div class="form-group row">
						<div class="col-md-12">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h4><i class="fa fa-user"></i>&nbsp;MEAL TYPE</h4>
									<div class="options">
										<a href="javascript:;" class="panel-collapse">
											<i class="fa fa-chevron-down" ></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<!-- form code starts here -->
									<form action="" method="post" enctype="multipart/form-data" id="data">	
										<div class="form-group row">
											<div class="form-group row">
												<div class="col-md-6">
													<label>Date</label>
													<div  class="input-append date form_date"  data-date="" data-date-format="dd-mm-yyyy">
														<input class="form-control" size="16" type="text" value='' id="a_date" name="a_date" value="" onchange="add_food_menu()" required readonly>
														<span class="add-on"><i class="icon-th"></i></span>
													</div>
												</div>
												<div class="col-md-6">
													<label>Day</label>
													<input  class="form-control"  name="a_date_day1" id="a_date_day1" type="text" value="" required readonly >
												</div>
												<div class="col-md-6">
													<br>
													<label for="meal" class="form-label">Meal Type</label> 
													<select name="menuHeadid" class="form-control" id="name" >
														<?php
														$sql=executeDB("SELECT * FROM foodmenuhead where status=1 order by order_id");

														if($sql)
														{
															foreach($sql as $row)
															{
																?> 
																<option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
																<?php	
															}
														}
														?>
													</select>
												</div>
												<div class="col-md-6">
													<br>
													<label for="meal" class="form-label">Sub Meal Type</label> 
													<select name="submenuid" class="form-control" id="name" >
														<?php
														$sql=executeDB("SELECT * FROM foodmenusubhead where status=1 order by order_id");

														if($sql)
														{
															foreach($sql as $row)
															{
																?> 
																<option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
																<?php	
															}
														}
														?>
													</select>
												</div>
											</div> 
											<div class="form-group row">
												<div class="col-md-6">
													<label for="meal" class="form-label">Menu Item</label> 
													<textarea class="form-control" id="meal" name="meal" placeholder="enter Menu"></textarea>
												</div>
											</div>

											<div class="form-group row" >
												<div class="col-md-12" align="center" ><button  id="buttonname" class="btn btn-primary btn-label" type="button" onclick="saveForm2()"><i class="fa fa-check" ></i> Save</button>	
													</div>
												</div>
											</div>
										</form>
									</div>	
								</div>
								<div class="form-group row">
									<div class="col-md-12">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<h4><i class="fa fa-user"></i>&nbsp;View List</h4>
												<div class="options">
													<a href="javascript:;" class="panel-collapse">
														<i class="fa fa-chevron-down" ></i>
													</a>
												</div>
											</div>
											<div class="panel-body collapse in">
												<div id="list">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php 
include_once('../footer1_nw.php');
?>
<script type="text/javascript">
	$(document).ready(function(){
		getmenu(1);
	});
	function add_food_menu()
	{
		var a_date_row=document.getElementById("a_date").value;
		$.ajax({
			type: "POST",
			url: "date.php",
			data: "add_food_menu=add_food_menu&a_date_row="+btoa(a_date_row),
			beforeSend:function()
			{
			},
			success: function (response) {
				$('#a_date_day1').val(response);
			}
		});
	}

	function saveForm2()
	{
		var date=document.getElementById("a_date").value;
		var meal=document.getElementById("meal").value;
		//var order=document.getElementById("order_id").value;
		if(date=='')
		{
			$.pnotify({
				title: 'Date',
				text: 'Please Select the Date',
				type: 'error'
			});
			return false;
		}
		if(meal=='')
		{
			$.pnotify({
				title: 'Menu Item',
				text: 'Please enter the Menu',
				type: 'error'
			});
			return false;
		}

		userForm = document.getElementById('data');
		formData=new FormData(userForm);
		formData.append('type',"saveForm2");
		$.ajax({
			type: 'post',
			url: "cafeteriaAddMenu_ajax.php",
			data: formData,
			mimeType: "multipart/form-data",
			contentType: false,
			cache: false,
			processData: false,
			success:function(response){
				var jsonObj=JSON.parse(response);
				if(jsonObj.status=="success")
				{
					$.pnotify({
						title: 'Save Data',
						text: jsonObj.msg,
						type: jsonObj.status
					});
					getmenu();
					document.getElementById('meal').value='';
				}
				if(jsonObj.status=="error")
				{
					$.pnotify({
						title: 'Save Data',
						text: jsonObj.msg,
						type: jsonObj.status
					});
				}
			}
		});
	}
	function getmenu(page_num)
	{
		$.ajax({
			type:'post',
			url:"cafeteriaAddMenu_ajax.php",
			data:{type:"getmenu",page_num:page_num
		},
		beforeSend:function(){},
		success:function(response){
			$('#list').html(response);
		}
	});
	}

	function editmenu(id)
	{
		$.ajax({
			type: 'post',
			url: "cafeteriaAddMenu_ajax.php",
			data: {type:"editmenu",id:id
		},
		beforeSend:function(){},
		success:function(response){
			$('#editdiv').html(response);
		}
	});
	} 
	function updatemenu() {
		uid=document.getElementById("uid").value;

		umeal=document.getElementById("umeal").value;

		$.ajax({
			type: 'post',
			url: "cafeteriaAddMenu_ajax.php",
			data: {type:"updatemenu",uid:uid,umeal:umeal
		},
		beforeSend:function(){},
		success:function(response){
			var jsonObj = JSON.parse(response);
			$.pnotify({
				title:'Update Data',
				text: jsonObj.msg,
				type: jsonObj.status
			});
			getmenu();
		}
	});
	}
	function deletemenu(id,submenuid)
	{
		if(confirm("Are you sure you want to delete this record ?"))
		{

			$.ajax({
				type: "POST",
				url: "cafeteriaAddMenu_ajax.php",
				data: {
					type:"deletemenu",id:id,submenuid:submenuid
				},
				beforeSend:function()
				{
				},
				success: function (response) 
				{
					var jsonObj = JSON.parse(response);
					$.pnotify({
						title:'Delete Data',
						text: jsonObj.msg,
						type: jsonObj.status
					});
					getmenu();
				}
			});
		}
	}
	function deletemenurow(menudate,menuHeadid)
	{
		if(confirm("Are you sure you want to delete this record ?"))
		{

			$.ajax({
				type: "POST",
				url: "cafeteriaAddMenu_ajax.php",
				data: {
					type:"deletemenurow",menudate:menudate,menuHeadid:menuHeadid
				},
				beforeSend:function()
				{
				},
				success: function (response) 
				{
					var jsonObj = JSON.parse(response);
					$.pnotify({
						title:'Delete Data',
						text: jsonObj.msg,
						type: jsonObj.status
					});
					getmenu();
				}
			});
		}
	}
</script>
</body>
</html>