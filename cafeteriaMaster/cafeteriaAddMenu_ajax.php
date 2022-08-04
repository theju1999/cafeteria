<?php
session_start();
include_once("../DB/db.php");
$user = $_SESSION[ 'user' ];
$acc_year = $_SESSION[ 'accYear' ];
$type=$_POST['type'];
if($type=="saveForm2")
{
  $date =  addslashes($_POST['a_date']);
  $day = addslashes($_POST['a_date_day1']);
  $datenew = date("Y-m-d",strtotime($date));
  $maintype= addslashes($_POST['menuHeadid']);
  $subtype= addslashes($_POST['submenuid']);
  $meal = addslashes($_POST['meal']);
  $field=executeDB("select foodMenuDetails from foodmenudetails where `menudate`='$datenew' and `menuHeadid`='$maintype' and `submenuid`='$subtype' and status=1");
  if(rowcount($field)>0)
  {
    $oldvalue=fetchrow($field);
    if($oldvalue['foodMenuDetails'])
    {
      $meal=$oldvalue['foodMenuDetails'].", ".$meal;
    }
    $sqlupdate="UPDATE  foodmenudetails SET foodMenuDetails='$meal',modifiedBy='$user' where `menudate`='$datenew' and status='1'";
    $isql=executeDB($sqlupdate);
    if($isql)
    {
      $response['status'] = "success";
      $response['msg'] = "updated succesfully.";
      echo json_encode($response);
    }
  }
  else
  {
    $sql = executeDB("insert into foodmenudetails(`menudate`,`day`,`menuHeadid`,`submenuid`,`foodMenuDetails`,`createdBy`,`accYear`)values('$datenew','$day','$maintype','$subtype','$meal','$user','$acc_year ') ");
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
    echo json_encode($response);
  }
}
if($type=="getmenu")
{
  $record_per_page = 7;
  if(array_key_exists('page_num',$_POST))
  {
    $page_num=$_POST['page_num'];
  }
  else
  {
    $page_num=1;
  }
  $start_from = ($page_num-1)*$record_per_page;
  $sqlselectMaster=executeDB("select * from foodmenudetails where status=1 group by menudate,menuHeadid ORDER BY `menudate` DESC LIMIT $start_from, $record_per_page");	
  ?>
  <table class="table table-bordered">
    <thead class="table-primary">
      <tr>
        <th scope="col">Sl.No.</th>
        <th scope="col">Date</th>
        <th scope="col">Day</th>
        <th scope="col">Meal type</th>
        <th scope="col" >Menu</th>
        <th scope="col">Delete</th>	
      </tr>
    </thead>
    <?php
    $i=($page_num-1)*$record_per_page+0;
    while($rowM=fetcharray($sqlselectMaster))
    {
      $i++;
      $condate=$rowM['menudate'];
      $newDate = date("d-m-Y", strtotime($condate));  
      ?>
      <tr>
        <td><?=$i?></td>
        <td><?=$newDate?></td>
        <td><?=$rowM['day']?></td>
        <td>
          <?php
          $menuHeadid=$rowM['menuHeadid'];
          $menudate=$rowM['menudate'];
          $mm=executeDB("SELECT name FROM `foodmenuhead` where id='$menuHeadid' and  status='1'");
          $mm1=fetchrow($mm);
          echo $mm1['name'];
          ?>
        </td>
        <td >
          <?php
          $sqlselect=executeDB("select * from foodmenudetails where status=1 and menuHeadid='$menuHeadid' and menudate='$menudate' ORDER BY `submenuid` ");	
          while($row=fetcharray($sqlselect))
          {
            $submenuid=$row['submenuid'];
            $mm=executeDB("SELECT name FROM `foodmenusubhead` where id='$submenuid' and status='1'");
            $mm1=fetchrow($mm);
            echo "<b>".$mm1['name']."</b> : ".$row['foodMenuDetails'];
            ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <i class="fa fa-pencil"id="editcol"onclick="editmenu(<?php echo $row['id']?>)" style="cursor: pointer;" data-toggle="modal" data-target="#modaldata"></i>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <i class="fa fa-trash-o" title="Delete" style="cursor: pointer;color:red" onclick="deletemenu(<?php echo $row['id']?>,<?php echo $row['submenuid']?>)"></i>
            <br>
            <?php
          }
          ?>
        </td>
        <td><i class="fa fa-trash-o" title="Delete" style="cursor: pointer;color:red" onclick="deletemenurow(<?php echo $rowM['menudate']?>,<?php echo $rowM['menuHeadid']?>)"></i></td>
      </tr>
      <?php
    }
    ?>
  </table>
  <div class="row">
    <div class="col-md-12" align="center"> 
      <?php
      $totalmenu = $conn->prepare("select * from foodmenudetails where status=1 group by menudate,menuHeadid ORDER BY `menudate` DESC ");
      $totalmenu->execute();
      $totalmenulist=$totalmenu->rowCount();
      $total_pages = ceil($totalmenulist/$record_per_page);
      $start_loop = $page_num;
      $difference = $total_pages - $page_num;
      $class_page='';
      if($totalmenulist> 10)
      {
        if($difference <= 5) {
          $start_loop = $total_pages - 5;
        }
        $end_loop = $start_loop + 4;
        ?>
        <a class='pagination_a' <?php if($page_num==1){ echo 'style="background-color: #ddddda;"';} ?> onclick="getmenu(1)" href='javascript:void(0);'>First</a>
        <?php
        if($page_num > 1) {
          $first=$page_num-1;
          ?>
          <a class='pagination_a' <?php if($page_num==$first){ echo 'style="background-color: #ddddda;"';} ?> onclick="getmenu(<?=$first?>)" href='javascript:void(0);'><<</a>
          <?php
        }
        for($i=$start_loop; $i<=$end_loop; $i++) {
          if($i>0)
          {
            ?>
            <a class='pagination_a' <?php if($page_num==$i){ echo 'style="background-color: #ddddda;"';} ?> onclick="getmenu(<?=$i?>)" href='javascript:void(0);'><?=$i?></a>
            <?php
          }
        }
        if($page_num <= $end_loop) {
          $last=$page_num+1;
          ?>
          <a class='pagination_a' <?php if($page_num==$last){ echo 'style="background-color: #ddddda;"';} ?> onclick="getmenu(<?=$last?>)" href='javascript:void(0);'>>></a>
          <a class='pagination_a' <?php if($page_num==$total_pages){ echo 'style="background-color: #ddddda;"';} ?>  onclick="getmenu(<?=$total_pages?>)" href='javascript:void(0);'>Last</a>
          <?php
        }
      }
      ?>
    </div>
  </div> 
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
          <button type="button" class="btn btn-primary" onclick="updatemenu()">Update</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>
<?php
if($type=="editmenu")
{
  $id=$_POST['id'];
  $fetchmeal=executeDB("select * from foodmenudetails where id='$id' and status=1");
  $mealinfo=fetchrow($fetchmeal);
  $date=$mealinfo['menudate'];
  $day=$mealinfo['day']; 
  $maintype=$mealinfo['menuHeadid'];
  $subtype=$mealinfo['submenuid'];
  $meal = $mealinfo['foodMenuDetails'];

  $fetchmealType=executeDB("select name from foodmenuhead where order_id='$maintype' and status=1");
  $mealtype=fetchrow($fetchmealType);
$menuhead=$mealtype['name'];
 $fetchsubType=executeDB("select name from foodmenusubhead where order_id='$subtype' and status=1");
  $mealsubtype=fetchrow($fetchsubType);
$menusubhead=$mealsubtype['name'];

  ?>
  <form action="" method="post" enctype="multipart/form-data" id="update">
    <div class="form-group row">
      <div class="form-group row">
        <input type="hidden" id="uid" value="<?php echo $id;  ?>"/>
        <div class="col-md-6">
          <label>Date</label>
          <div class="input-append date form_date"  data-date="" data-date-format="dd-mm-yyyy">
            <input class="form-control" size="16" type="text"  id="ua_date" name="ua_date" value="<?php echo $date;?>" required readonly>
            <span class="add-on"><i class="icon-th"></i></span>
          </div>
        </div>
        <div class="col-md-6">
          <label>Day</label>
          <input  class="form-control"  name="ua_date_day1" id="ua_date_day1" type="text" value="<?php echo $day;?>" required readonly >
        </div>
      </div>
    </div>
    <div class="form-group row" >
      <div class="col-md-12">
        <label for="meal" class="form-label">Meal type</label> 
        <input type="text" class="form-control" id="ubreakfast" name="umeal" value="<?php echo $menuhead;?>" placeholder="name" required readonly><br>
      </div>
      <div class="col-md-12">
        <label for="meal" class="form-label">Sub meal type</label> 
        <input type="text" class="form-control" id="ulunch" name="umeal" value="<?php echo $menusubhead;?>" placeholder="name" required readonly><br>
      </div>
      <div class="col-md-12" >
        <label for="meal" class="form-label">Menu Item</label> 
        <input type="text" class="form-control" id="umeal" name="umeal" value="<?php echo $meal;?>" placeholder="name" ><br> 
      </div>
    </div>																
  </form>
  <?php
}
if($type=="updatemenu")
{
  $uid=addslashes($_POST['uid']);
  $umeal=addslashes($_POST['umeal']);
  $usearch=executeDB("select * from foodmenudetails where id='$uid' and status=1");
  $usearch=fetchrow($usearch);
  $updatequery=executeDB("update foodmenudetails set foodMenuDetails='$umeal',modifiedBy='$user' where id='$uid' and status=1");
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
if ($type=="deletemenu")
{
  $id=$_POST['id'];
  $submenuid=$_POST['submenuid'];
  $updatesql = "UPDATE  foodmenudetails set status=0 WHERE id='$id' and submenuid='$submenuid' ";
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
if ($type=="deletemenurow")
{ 
  $selectrow=executeDB("SELECT * FROM `foodmenudetails` where status='1'");
  $selectfields=fetchrow($selectrow);
  $menudate=$selectfields['menudate'];
  $menuHeadid=$selectfields['menuHeadid'];
  $updatesqldel = "UPDATE  foodmenudetails set status=0 WHERE menudate='$menudate' and menuHeadid='$menuHeadid' ";
  $updatesqldel=executeDB($updatesqldel);
  if($updatesqldel)
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