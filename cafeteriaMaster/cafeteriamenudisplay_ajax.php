<?php
include_once("../DB/db.php");
$type=$_POST['type'];
if($type=="searchdata")
{
 $searchdate=$_POST['tddate'];
 $todate=date('Y-m-d' ,strtotime($searchdate.'+7 days'));
 $sqlselectMaster=executeDB("select * from  foodmenudetails where  (menudate between   '$searchdate'and  '$todate' ) and status=1  group by menudate,menuHeadid ORDER BY `menudate` ASC ");
 while($rowM = $sqlselectMaster->fetch())
 {
   $condate=$rowM['menudate'];
   $newDate = date("d-m-Y", strtotime($condate)); 
   ?>
   <h4 class="timeline-month"><span><?=$newDate?></span></h4>
   <ul class="timeline">
    <li class="timeline-orange">
      <div class="timeline-body">
        <div class="timeline-header">
          <span class="date"><?=$newDate?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><?=$rowM['day']?></span>
        </div>
        <div class="timeline-content">
          <h3> <?php
          $menuHeadid=$rowM['menuHeadid'];
          $menudate=$rowM['menudate'];
          $mm=executeDB("SELECT name FROM `foodmenuhead` where id='$menuHeadid' and  status='1'");
          $mm1=fetchrow($mm);
          echo $mm1['name'];
          ?></h3>
          <p>
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
              &nbsp;&nbsp;&nbsp;&nbsp;
              <br>
              <?php
            }
            ?>
          </p>
        </div>
      </div>
    </li>
  </ul>
  <?php
}
?>
<?php
}
$type=$_POST['type'];
if($type=="searchdata2")
{
 $searchdate=$_POST['searchdata'];
 $todate=date('Y-m-d' ,strtotime($searchdate.'-7 days'));
 $sqlselectMaster=executeDB("select * from  foodmenudetails where  (menudate between   '$todate' and '$searchdate') and status=1  group by menudate,menuHeadid ORDER BY `menudate` ASC ");
 while($rowM = $sqlselectMaster->fetch())
 {
   $condate=$rowM['menudate'];
   $newDate = date("d-m-Y", strtotime($condate)); 
   ?>
   <h4 class="timeline-month"><span><?=$newDate?></span></h4>
   <ul class="timeline">
    <li class="timeline-orange">
      <div class="timeline-body">
        <div class="timeline-header">
          <span class="date"><?=$newDate?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><?=$rowM['day']?></span>
        </div>
        <div class="timeline-content">
          <h3> <?php
          $menuHeadid=$rowM['menuHeadid'];
          $menudate=$rowM['menudate'];
          $mm=executeDB("SELECT name FROM `foodmenuhead` where id='$menuHeadid' and  status='1'");
          $mm1=fetchrow($mm);
          echo $mm1['name'];
          ?></h3>
          <p>
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
              &nbsp;&nbsp;&nbsp;&nbsp;
              <br>
              <?php
            }
            ?>
          </p>
        </div>
      </div>
    </li>
  </ul>
  <?php
}
}
?>
