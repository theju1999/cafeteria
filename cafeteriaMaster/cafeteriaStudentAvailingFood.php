<?php 
//session_start();

include_once("../DB/db.php");
//$_SESSION['user'] = 'administrator';
// $acc_year=$_SESSION['AcademicYear'];

// $AcademicYear=$_SESSION['AcademicYear'];
// $user=$_SESSION['user'];
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
  <link rel='stylesheet' type='text/css' href='../new_css/assets/plugins/charts-morrisjs/morris.css' />
  <script type="text/javascript">
    gadedisplay=(grade_id)=>{
      var type="gadedisplay";
      $.ajax({
        type: "POST",
        url: "cafeteriaStudentAvailingFood_ajax.php",
        data: {type:"gadedisplay",grade_id:grade_id
      },
      beforeSend:function()
      {
            //   console.log(grade_id);
            $("#grade_id").empty();
          },
          success: function (response) {
 //alert(response);
 $("#grade_id").append(response);
}
});
    }

    
    
    getgrade=(grade_id)=>{
      var type="gradegadedisplay";
      $.ajax({
        type: "POST",
        url: "cafeteriaStudentAvailingFood_ajax.php",
        data: {type:"gradegadedisplay",grade_id:grade_id
      }, 
      beforeSend:function()
      {
        console.log(grade_id);
            //   $("#stugradeid").empty();
            $("#valuehidden").val(grade_id);

          },
          success: function (response) {
              //alert(response);
              $("#stugradeid").append(response);

            }
          });
    }

    
  </script> 
</head>
<body>
  <?php include_once('../header1_nw.php');?>
  <div id="page-container">
    <?php include_once('../sidebar1_nw.php');?>
    <div id="page-content">
      <div id="wrap" style="background-color:#fff"> 
        <div id="page-heading" style="background-color:#fff">
          <ol class="breadcrumb">
            <li><a href="../index.php">Dashboard</a></li>
            <li><a href="GB_creation.php">Gradebook</a></li>
            <li class="active">Student Food Availed</li>
          </ol>
          <br>
        </div>
        <div class="container">
          <div class="form-group row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h4><i class="fa fa-bar-chart-o"></i>&nbsp;Food Availed</h4>
                  <div class="options">
                    <a href="javascript:;" class="panel-collapse">
                      <i class="fa fa-chevron-down" ></i>
                    </a>
                  </div>
                </div>
                <div class="panel-body">
                  <form action="" method="post" enctype="multipart/form-data" id="food">
                    <div class="form-group row">
                      <div class="col-md-4">
                        <label>Division Name</label>
                        <select name="course_id" id="course_id"class="form-control" onchange="gadedisplay(this.value)">
                          <?php
                          $schoolids="select course_id,coursename from course_m where status='1'";
                          $schoolids=$conn->prepare($schoolids);
                          $schoolids->execute();
                          ?>
                          <option value="0">--Select School--</option>
                          <?php 
                          while($schoolRow=$schoolids->fetch(PDO::FETCH_ASSOC))
                          { 
                            ?>
                            <option value="<?php echo $schoolRow['course_id']?>"><?php echo$schoolRow['coursename']?></option>
                            <?php 
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label>Grade<font color="red">*</font></label>
                        <select name="grade_id" id="grade_id" class="form-control" onchange="getgrade(this.value)">
                          <option value="0">--Grade--</option>
                        </select>
                      </div>

                                        <!-- <div class="col-md-4">
                                            <label>Student id<font color="red">*</font></label>
                                            <select name="stugradeid" id="stugradeid" class="form-control" onchange="getstuid(this.value)">
                                                <option value="0">Select register number</option>
                                            </select>
                                        </div> 
                                      -->

                                      <div class="col-md-4">
                                        <label>Date</label>
                                        <div  class="input-append date form_date"  data-date="" data-date-format="dd-mm-yyyy">
                                          <input class="form-control" size="16" type="text" value="<?=date("d-m-Y");?>" id="a_date" name="a_date"  onchange="add_food_menu()" required readonly>
                                          <span class="add-on"><i class="icon-th"></i></span>
                                        </div>
                                      </div>
                                      <br>
                                      <div class="col-md-4">
                                        <br>
                                        <label for="meal" class="form-label">Meal Type</label> 
                                        <select name="menuHeadid" class="form-control" id="menuHeadid" onchange="searchdata()"> 
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
                                    </div>
                                    <input type="hidden" name="menudisplayid" id="menudisplayid" value="1" >
                                    <input type="hidden" name="valuehidden" id="valuehidden" >




                                    <div align="center">
                                      <button class="btn btn-primary btn-label" type="button" onclick="searchdata()"><i class="fa fa-search"></i> Search</button>
                                    </div>


                                    <!-- <div class="form-group row"> -->

                                            <!-- <div align="center">
                                             <label for="schstd" class="form-label">&nbsp;</label> -->
                                            <!-- <button type="button" class="btn btn-primary btn-label" name="schstd" id="schstd" onclick="saveForm()">Search</button>
                                                            
                                            </div> --> 
                                            <!-- </div>
                                            -->


                                          </div>
                                        </form>
                                      </div>
                                      <div class="form-group row">
                                        <div class="col-md-12">
                                          <div class="panel panel-primary">
                                            <div class="panel-heading">
                                              <h4><i class="fa fa-user"></i>&nbsp;Students</h4>
                                              <div class="options">
                                                <a href="javascript:;" class="panel-collapse">
                                                  <i class="fa fa-chevron-down" ></i>
                                                </a>
                                              </div>
                                            </div>
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
                          <?php include_once('../footer1_nw.php');?>
                        </body>

                        <script type="text/javascript">
                          $(document).ready(function () {
                            viewdata();

                          });

                          function saveForm()
                          {
                            userForm = document.getElementById('food');
                            formData=new FormData(userForm);
                            formData.append('type',"saveForm");

                            $.ajax({
                              type: 'post',
                              url: "cafeteriaStudentAvailingFood_ajax.php",
                              data: formData,
                              mimeType: "multipart/form-data",
                              contentType: false,
                              cache: false,
                              processData: false,
                              success:function(response){

                                var jsonObj=JSON.parse(response);

                                if(jsonObj.status=="success")
                                { 
                    //alert(response);
                    $.pnotify({
                      title: 'Save Data',
                      text: jsonObj.msg,
                      type: jsonObj.status
                    });

                    viewdata();
                    $("#food")[0].reset();
                    
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
                          function updatemobileapp(yesno,rowid)
                          {
                           var newdate=document.getElementById("a_date").value;
                           var  menuHeadid=document.getElementById("menuHeadid").value;
                           $.ajax({
                            type: 'post',
                            url: "cafeteriaStudentAvailingFood_ajax.php",
                            data: {type:"updatemobileapp",id:rowid,radiodata:yesno,newdate:newdate,menuHeadid:menuHeadid

                          },
                          beforeSend:function(){


                          },

                          success:function(response){

            //alert(response);
            var jsonObj = JSON.parse(response);
            $.pnotify({
              title:'insert Data',
              text: jsonObj.msg,
              type: jsonObj.status
            });
      //getmealtype();
    }
  });
                         }




                         menudisplay=(menudisplayid)=>{
        //alert(menudisplayid);
        $("#menudisplayid").val(menudisplayid);

      }





      function add_food_menu()
      {
        var a_date_row=document.getElementById("a_date").value;
        alert(a_date_row);
        $.ajax({
          type: "POST",
          url: "date2.php",
          data: "add_food=add_food&a_date_row="+btoa(a_date_row),
          beforeSend:function()
          {
          },
          success: function (response) {
            $('#storedate').val(response);
          }
        });
      }

      function searchdata()
      {


        var menuheadid=$('#menudisplayid').val();
        var serachvalue=$('#valuehidden').val();
        var  menuheadata=$("#menuHeadid").val();
                        //  var  menuheadata=$("#menuHeadid").val();
                        var searchdate=$('#a_date').val();

            //  var searchgrade= document.getElementById("course_id").value;  

            $.ajax({
              type:'POST',
              url:'cafeteriaStudentAvailingFood_ajax.php',
              data:{type:"searchdata",serachvalue:serachvalue,menuheadid:menuheadid,menuheadata:menuheadata,searchdate:searchdate},
              beforeSend:function()
              {   

                                //console.log(menuheadid);
                              },
                              success:function(response)  {
                                $('#list').html(response);
                              }
                            });
          }





          function viewdata(){

            $.ajax({
              type: 'post',
              url: "cafeteriaStudentAvailingFood_ajax.php",
              data: {type:"viewdata"
            },
            beforeSend:function(){},
            success:function(response){
              $('#list').html(response);
            }
          });
          }

        </script>
        </html>


