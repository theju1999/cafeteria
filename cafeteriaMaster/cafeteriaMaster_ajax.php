<?php
session_start();
include_once("../DB/db.php");
$user = $_SESSION[ 'user' ];
$acc_year = $_SESSION[ 'accYear' ];
$type=$_POST['type'];
if($type=="saveForm")
{
  $name = addslashes($_POST['name']);
  $description=addslashes($_POST['description']);
  $order= addslashes($_POST['order_id']);	
  $field=executeDB("select * from foodmenuhead where order_id='$order' and status=1");
  if(rowcount($field)<1)
  {
   $sql = executeDB("insert into foodmenuhead(`name`,`description`,`order_id`,`createdBy`)values('$name','$description','$order','$user')");
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
  $response['status'] = "error";
  $response['msg'] = " error in saving!.";
}
echo json_encode($response);
}
if($type=="getmealtype")
{
  $sql=executeDB("select * from foodmenuhead where status=1");	
  ?>
  <table class="table table-bordered">
   <thead class="table-primary">
    <tr>
     <th scope="col">Sl.No.</th>
     <th scope="col">Name</th>
     <th scope="col">Description</th>
     <th scope="col">Update</th>
     <th scope="col">Delete</th>	
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
      <?=$row['name']?>
    </td>
    <td><?=$row['description']?></td>
    <td><i class="fa fa-pencil"id="editcol"onclick="editmealtype(<?php echo $row['id']?>)" style="cursor: pointer;" data-toggle="modal" data-target="#modaldata"></i></td>
    <td><i class="fa fa-trash-o" title="Delete" style="cursor: pointer;color:red" onclick="deletemealtype(<?php echo $row['id']?>)"></i> </td>
  </tr>
  <?php
}
?>
</table>
<div class="modal fade" id="modaldata" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Meal Type</h5>
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
  $updatequery=executeDB("update foodmenuhead set name='$uname',description='$udescription',order_id='$uorder_id',
    modifiedBy='$user' where id='$uid'");
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
