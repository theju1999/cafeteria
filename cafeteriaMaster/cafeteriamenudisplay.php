<?php
include_once("../DB/db.php");
	// $user = $_SESSION[ 'user' ];
	// $acc_year = $_SESSION[ 'AcademicYear' ];
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
						<li class="active">Cafeteria Menu Display</li> 
						</ol>
						<br>
					</div>
					<div class="container">
						<div class="form-group row">
							<div class="col-md-12">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h4><i class="fa fa-user"></i>&nbsp;SEARCH MENU</h4>
										<div class="options">
											<a href="javascript:;" class="panel-collapse">
												<i class="fa fa-chevron-down" ></i>
											</a>
										</div>
									</div>
									<div class="panel-body">
										<form action="" method="post" enctype="multipart/form-data" id="data">
											<div class="form-group row">
												<div class="col-md-2">
													<label>&nbsp;</label>
													<div  class="input-append date form_date"  data-date="" data-date-format="yyyy-mm-dd"> 
														<input class="form-control" size="16" type="text" value="<?=date("Y-m-d");?>" id="addfood" name="a_date"  onchange="add_food()" placeholder="Select the Date" required readonly>
														<span class="add-on"><i class="icon-th"></i></span> 
													</div>
												</div>
												 <input type="hidden" id="addfoodMenu">
												<div class="col-md-3">
													<label>&nbsp;</label>
													<div>
														<button class="btn btn-primary btn-label" type="button" onclick="searchdata()"><i class="fa fa-search"></i>SEARCH</button>		
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
												<h4><i class="fa fa-user"></i>&nbsp;VIEW MENU</h4>
												<div class="options">
													<a href="javascript:;" class="panel-collapse">
														<i class="fa fa-chevron-down" ></i>
													</a>
												</div>
											</div>
											<br>
											<button class="btn btn-primary btn-label" type="button" onclick="searchdata2()"><i class="fa fa-angle-double-left"></i> PREVIOUS</button>		
											<button class="btn btn-primary btn-label" type="button" onclick="searchdata()"  style="float: right;"><i class="fa fa-angle-double-right"></i>NEXT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>		
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
<?php 
include_once('../footer1_nw.php');
?>
<script type="text/javascript">
	$(document).ready(function(){
		searchdata();
	});
	function add_food()
	{
		var a_date_row=document.getElementById("addfood").value;
		$.ajax({
			type: "POST",
			url: "date1.php",
			data: "add_food=add_food&a_date_row="+btoa(a_date_row),
			beforeSend:function()
			{
			},
			success: function (response) {
				$('#addfoodMenu').val(response);
			}
		});
	}
	function searchdata()
	{
		var tddate= document.getElementById("addfood").value;		
		$.ajax({
			type:'POST',
			url:'cafeteriamenudisplay_ajax.php',
			data:{type:"searchdata",tddate:tddate},
			beforeSend:function()
			{       
			},
			success:function(response)  {
				$('#list').html(response);
			}
		});
	}
	function searchdata2()
	{
		var searchdata= document.getElementById("addfood").value;		
		$.ajax({
			type:'POST',
			url:'cafeteriamenudisplay_ajax.php',
			data:{type:"searchdata2",searchdata:searchdata},
			beforeSend:function(){
			},
			success:function(response){
				$('#list').html(response);
			}
		});
	}
</script>
</body>
</html>