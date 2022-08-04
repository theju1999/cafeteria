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
						
						<li class="active">Cafeteria Sub Meal Type</li> 
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
												<div class="col-md-3">
													<label for="name" class="form-label">Sub Meal Type</label>
													<input type="text" class="form-control" id="name" name="name" placeholder="Type of Sub Meal">
												</div>
												<div class="col-md-6">
													<label for="description" class="form-label">Description</label>
													<input type="text" class="form-control" id="description" name="description" placeholder="Description">
												</div>
												<div class="col-md-3">
													<label for="order_id" class="form-label">Order No</label>
													<input type="text" class="form-control" id="order_id" name="order_id" placeholder="Order No">
												</div>
											</div>
											<div align="center">
												<button class="btn btn-primary btn-label" type="button" onclick="saveSubMeal()"><i class="fa fa-check"></i> Save</button>
												<!-- <button class="btn btn-info" type="submit" name="submit" ><i class='fa fa-check'></i> -->
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
		getSubMealType();	
	});

	function saveSubMeal()
	{

		var name=document.getElementById("name").value;
		var description=document.getElementById("description").value;
		var order=document.getElementById("order_id").value;
		if(name=='')
			{
				$.pnotify({
					title: 'Name',
					text: 'Please enter the Sub Meal Type',
					type: 'error'
				});
				return false;
			}
			if(description=='')
			{
				$.pnotify({
					title: 'Description',
					text: 'Please enter the Description',
					type: 'error'
				});
				return false;
			}
			if(order=='')
			{
				$.pnotify({
					title: 'Order',
					text: 'Please enter the Order Number',
					type: 'error'
				});
				return false;
			}
		userForm = document.getElementById('data');
		formData=new FormData(userForm);
		formData.append('type',"saveSubMeal");
		
		$.ajax({
			type: 'post',
			url: "cafeteriaSubMaster_ajax.php",
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
					getSubMealType();
					$("#data")[0].reset();
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
	function getSubMealType(){
		
		$.ajax({
			type:'post',
			url:"cafeteriaSubMaster_ajax.php",
			data:{type:"getSubMealType"
		},
		beforeSend:function(){},
		success:function(response){
			$('#list').html(response);
		}
	});
	}

	function editSubMealType(id)
	{
		$.ajax({
			type: 'post',
			url: "cafeteriaSubMaster_ajax.php",
			data: {type:"editSubMealType",id:id
		},
		beforeSend:function(){},
		success:function(response){
			$('#editdiv').html(response);
		}
	});
	} 
	function updateSubMealtype() {
		uid=document.getElementById("uid").value;
		uname=document.getElementById("uname").value;
		udescription=document.getElementById("udescription").value;
		uorder_id=document.getElementById("uorder_id").value;
		$.ajax({
			type: 'post',
			url: "cafeteriaSubMaster_ajax.php",
			data: {type:"updateSubMealtype",uid:uid,uname:uname,udescription:udescription,uorder_id:uorder_id
		},
		beforeSend:function(){},
		success:function(response){
			var jsonObj = JSON.parse(response);
			$.pnotify({
				title:'Update Data',
				text: jsonObj.msg,
				type: jsonObj.status
			});
			getSubMealType();
		}
	});
	}
	function deleteSubMealType(id){
		
		if(confirm("Are you sure you want to delete this record ?"))
		{
			
			$.ajax({
				type: "POST",
				url: "cafeteriaSubMaster_ajax.php",
				data: {
					type:"deleteSubMealType",id:id
				},
				beforeSend:function()
				{
					
				},
				success: function (response) 
				{
					var  jsonObj= JSON.parse(response);
					$.pnotify({
						title:'Delete Data',
						text: jsonObj.msg,
						type: jsonObj.status
					});
					getSubMealType();
				}
			});
		}
	}
</script>
</body>
</html>