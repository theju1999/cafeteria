<?php
include_once("../DB/db.php");
$type=$_POST['type'];
if($type=="gadedisplay")

{
  
   $grade_id=$_POST['grade_id'];
  // $grade_id=base64_decode($grade_id);
  ?>
  <option value="0">--grade--</option>
  <?php 
  $gradeidsql="select year_name,year_id from course_year where head_id='$grade_id' and status='1'";
  $gradeidsql=$conn->prepare($gradeidsql);
  $gradeidsql->execute();
  while($gradeidrs=fetcharray($gradeidsql))

  
  { 
    ?>
    <option value="<?php echo $gradeidrs["year_id"]?>"><?php echo $gradeidrs["year_name"]?></option>
    <?php
  }
}


if($type=="gradegadedisplay")

{
 
   $grade_id=$_POST['grade_id'];
  // $grade_id=base64_decode($grade_id);

  ?>
  <option value="0">--student--</option>
  <?php 
  $gradeidsql="select student_id from student_m  where  course_yearsem='$grade_id' ";
  $gradeidsql=$conn->prepare($gradeidsql);
  $gradeidsql->execute();



  while($gradeidrs=fetcharray($gradeidsql))
  
  { 
    ?>
    <option value="<?php echo $gradeidrs["student_id"]?>"><?php echo $gradeidrs["student_id"]?></option>
    <?php
  }
}
if($type=="searchdata")
{
  // print_r($_POST);
  $searchgrade =  $_POST['serachvalue'];

  $searchmenuid=$_POST['menuheadid'];


  $menusearch=executeDB("select name from foodmenuhead where id='$searchmenuid' and status=1");
 $menusearch=fetchrow($menusearch);
  
  $sql=executeDB("select * from student_m where course_yearsem='$searchgrade' "); 
  ?>
  <table class="table table-bordered">
   <thead class="table-primary">
    <tr>
    <th>Sl.No.</th>
    <th>Student Name</th>
     <th>Student Id</th>

     <th> Food Availed
       </th>
   </tr>
 </thead>
 <?php
 $i=0;
 while($row=fetcharray($sql))
 {
  $i++;
  ?>
  <tr>
  <td><?=$i?></td>
    <td> 
  <?php

    $menuHeadid=$row['student_id'];
    
     $mm=executeDB("SELECT first_name FROM `student_m` where  student_id='$menuHeadid' ");
     $mm1=fetchrow($mm);
     echo $mm1['first_name'];
    ?>
    </td> 
    <td>
  <?=$row['student_id']?>
 </td>

 <?php
 $menuheadata=addslashes($_POST['menuheadata']);
$searchdate=addslashes($_POST['searchdate']);
$anotherdate2 = date("Y-m-d", strtotime($searchdate));
     //echo "SELECT * FROM `foodmenudetailsatt` where userId='$menuHeadid' ";
    //echo "SELECT * FROM `foodmenudetailsatt` where userId='$menuHeadid' and foodtype='$menuheadata' and fooddate= '$anotherdate2' and status=1";
     $sqlradio=executeDB("SELECT * FROM `foodmenudetailsatt` where userId='$menuHeadid' and foodtype='$menuheadata' and fooddate= '$anotherdate2' and status=1 ");
      $sqlradiobtn=fetchrow($sqlradio);
     //$foodavailed=$mealinfo['foodAvailed'];
      if($sqlradiobtn['foodAvailed']==1)
      {
        $checked1="checked";
        $holdme2="";
      }
      elseif($sqlradiobtn['foodAvailed']==0)
      {
        $checked1="";
        $holdme2="checked";
      }
   
    ?>
 
    <td>
    <input type="radio"   id="yesno" name="yesno<?=$sqlradiobtn['id']?>" value="1" <?=$checked1?> onclick="updatemobileapp(this.value,'<?php echo $row['student_id']?> ')">
  <label for="yes">Yes</label>&nbsp;&nbsp;
  <input type="radio" id="yesno" name="yesno<?=$sqlradiobtn['id']?>" value="0" <?=$holdme2?>   onclick="updatemobileapp(this.value,'<?php  echo $row['student_id']?> ')" >
  <label for="no">No</label><br>  
  
    </td>

  <?php
}
?>
</table>
<div class="modal fade" id="modaldata" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="editdiv"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="updatemealtype()">Update</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}
?>

<?php
if($type=="updatemobileapp")
{
  $studentid=addslashes($_POST['id']);
  $radiodata=addslashes($_POST['radiodata']);

  $menudate=$_POST['newdate'];
  $anotherdate = date("Y-m-d", strtotime($menudate)); 
  $menuheadid=$_POST['menuHeadid'];
  //echo $id ;
  $sqlstudent=executeDB("SELECT course_yearsem FROM `student_m` where student_id='$studentid' ");
  $gradestudent=fetchrow($sqlstudent);
  $studentgrade=$gradestudent['course_yearsem'];

  //echo "select * from foodmenudetailsatt where userId='$studentid' and fooddate='$anotherdate' and foodType ='$menuheadid' and status=1";
//select * from foodmenudetailsatt where fooddate= '$anotherdate'  and foodType ='$menuheadid' and status=1;
$field=executeDB("select * from foodmenudetailsatt where  userId='$studentid' and fooddate='$anotherdate' and foodType ='$menuheadid' and status=1");
if(rowcount($field)<1)
{
  $sql = executeDB("INSERT INTO `foodmenudetailsatt`( `userId`, `grade`, `accYear`, `userType`, `foodType`, `createdby`, `fooddate`, `foodAvailed`) VALUES 
  ('$studentid','$studentgrade','2022','1','$menuheadid','theju','$anotherdate','$radiodata')");
 
 if($sql)
 {
   $response['status'] = "success";
   $response['msg'] = "inserted succesfully.";
 }
 else
 {
  $response['status'] = "error";
  $response['msg'] = "data already exists!.";
}
}
else
{
  $updatequery=executeDB("update foodmenudetailsatt set foodAvailed='$radiodata' where userId='$studentid' and fooddate='$anotherdate' and foodType ='$menuheadid' ");


  if($updatequery)
  {
    $response['status'] = "success";
    $response['msg'] = "updated succesfully.";
  }
  else
  {
   $response['status'] = "error";
   $response['msg'] = "cannot update!.";
 }
} 
  echo json_encode($response);
}
?>

<?php
if($type=="editmealtype")
{
 $id=$_POST['id'];
 $fetchmeal=executeDB("select * from foodmenuhead where id='$id' and status=1");
 $mealinfo=fetchrow($fetchmeal);
 $name=$mealinfo['name'];
 $description=$mealinfo['description'];
 $order_id=$mealinfo['order_id'];
 ?>
 <form action="" method="post" id="update" enctype="multipart/form-data">
   <div class="form-group row">
    <input type="hidden" id="uid" value="<?php echo $id;  ?>"/>
    <div class="col-md-3">
      <label for="uname" class="form-label">Name</label>
      <input type="text" class="form-control" id="uname" name="name" value="<?php echo $name;?>" placeholder="type of meal">
    </div>
    <div class="col-md-3">                          
     <label for="udescription" class="form-label">Description</label>
     <input type="text" class="form-control" id="udescription" name="description" value="<?php echo $description;?>" placeholder="description">
   </div>
   <div class="col-md-3">
     <label for="uorder_id" class="form-label">order No</label>
     <input type="text" class="form-control" id="uorder_id" name="order_id"  value="<?php echo $order_id;?>" placeholder="order_no">
   </div>
 </div>
</form>
<?php
}
if($type=="updatemealtype")
{
  $uid=addslashes($_POST['uid']);
  $uname=addslashes($_POST['uname']);
  $udescription=addslashes($_POST['udescription']);
  $uorder_id=addslashes($_POST['uorder_id']);
  $usearch=executeDB("select * from foodmenuhead where id='$uid' and status=1");
  $usearch=fetchrow($usearch);
  $updatequery=executeDB("update foodmenuhead set name='$uname',description='$udescription',order_id='$uorder_id' where id='$uid'");
  if($updatequery)
  {
    $response['msg']='updated successfully';
    $response['status']='success';
  }
  else
  {
    $response['status']='error';
    $response['msg']='error in updating';
  }
  echo json_encode($response);
}
if ($type=="deletemealtype")
{
  $id=$_REQUEST['id'];
 $updatesql = "UPDATE  foodmenuhead set status=0 WHERE id='$id'";
 $updatesql=executeDB($updatesql);
 if($updatesql)
 {
  $response['status']='success';
  $response['msg']='Delete Successfully';
}
else
{
  $response['status']='error';
  $response['msg']='Error in deleting record';
}
echo json_encode($response);
}
?>
