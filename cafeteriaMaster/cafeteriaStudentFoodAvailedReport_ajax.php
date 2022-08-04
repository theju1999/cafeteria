<?php
include_once("../DB/db.php");
$type=$_POST['type'];
if($type=="searchdata")
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
$searchdata =  $_POST['insearch'];
$search=date('Y-m-d' ,strtotime($searchdata));
$searchdata2 =  $_POST['insearch2'];
$search2=date('Y-m-d' ,strtotime($searchdata2));
$foodtype=$_POST['foodtype'];
$start_from = ($page_num-1)*$record_per_page;
$sqlselectMaster=executeDB("select *   from  foodmenudetailsatt where (fooddate  between '$search' and '$search2' and foodType='$foodtype') and status=1 LIMIT $start_from, $record_per_page ");	
$count=rowcount($sqlselectMaster);
if ($count>0){
  ?>
  <table class="table table-bordered">
   <thead class="table-primary">
    <tr>
      <th scope="col">Sl.No.</th>
      <th scope="col">Student name</th>
      <th scope="col">Student id</th>
      <th scope="col">Meal Type</th> 
      <?php 
      $fromdate=explode('-',$search);
      $pfdate=$fromdate[2]."-".$fromdate[1]."-".$fromdate[0];
      $totdate=explode('-',$search2);
      $ptdate=$totdate[2]."-".$totdate[1]."-".$totdate[0];
      $date_entered_email_sec=strtotime($search2);
      $date_modified_email_sec=strtotime($search); 
      $turn_around_time_sec = $date_entered_email_sec - $date_modified_email_sec;
      $daysTotal = ceil((date("z") - date("w")) / 7);
      $daysTotal = ceil((date("j") - date("w")) / 7); 
      $tot_day = floor($turn_around_time_sec / 86400)+1;
      echo $tot_day[1];
      echo $tot_day[0];
      for($c=0;$c<$tot_day;$c++)
      {								
        $attview = mktime(0,0,0,$fromdate[1],$fromdate[2]+$c,$fromdate[0]);
        $viewdate_att=date("Y-m-d", $attview);
        $day_view_date=date("Y-m-d", $attview);
        $viewdate_sunday=date("D", $attview);
        $sqlfoodB=executeDB("select *  from  foodmenudetailsatt where  fooddate='$day_view_date' and  foodType='$foodtype'  and status=1 ");	
        $rowMM=fetchrow($sqlfoodB);
        if($rowMM['id'])
        {
          $condate=$rowMM['fooddate'];
          $newDate = date("d-m-Y", strtotime($condate));
          $totaldatecount[]=$newDate;
          ?>
          <th align='center' scope="col" nowrap><?=$newDate?></th>
          <?php
        }
        else
        {
          ?>
          <?php
        }
      }
      $arrlength=sizeof($totaldatecount);
      $colspan=$arrlength+4;
      ?>
      <th scope="col">Total</th> 
    </thead>
    <?php
    $alldaycount=0;
    $i=($page_num-1)*$record_per_page+0;
    while($row=fetcharray($sqlselectMaster))
    {
     $i++;
     ?>
     <tr>
      <td><?=$i?></td>
      <td><?php
      $menuHeadid=$row['userId'];
      $mm=executeDB("SELECT first_name FROM `student_m` where  student_id='$menuHeadid' ");
      $mm1=fetchrow($mm);
      echo $mm1['first_name'];
      ?></td>
      <td><?=$row['userId']?></td> 
      <td>
        <?php
        $menuHeadid=$row['foodType'];
        $mm=executeDB("SELECT name FROM `foodmenuhead` where id='$menuHeadid' and  status='1'");
        $mm1=fetchrow($mm);
        echo $mm1['name'];
        ?></td>
        <?php
        $daysTotalCnt=0;
        for($c=0;$c<$tot_day;$c++)
        {
          $attview = mktime(0,0,0,$fromdate[1],$fromdate[2]+$c,$fromdate[0]);
          "<br>".$viewdate_att=date("Y-m-d", $attview);
          "<br>".$day_view_date=date("Y-m-d", $attview);
          "<br>".$viewdate_sunday=date("D", $attview);
          $menuHeadid=$row['userId'];
          $foodtype=$row['foodType'];
          $sqlfoodA=executeDB("select *  from  foodmenudetailsatt where  fooddate='$day_view_date' and  userId='$menuHeadid' and foodType='$foodtype' and status=1 ");	
          $rowM=fetchrow($sqlfoodA);
          $checked1="";
          if ($rowM['userId']){
            if($rowM['foodAvailed']==1)
            {
              $checked1="Yes";
              $daysTotalCnt=$daysTotalCnt+1;
                  // $holdme2="";
            }
            elseif($rowM['foodAvailed']==0)
            {
              $checked1="No";
            }
          }
          else{
            $checked1=" - ";
          }
          $sqlfoodB=executeDB("select *  from  foodmenudetailsatt where  fooddate='$day_view_date' and  foodType='$foodtype'  and status=1 ");	
          $rowMM=fetchrow($sqlfoodB);
          if($rowMM['id'])
          {
            ?>
            <td  nowrap><?=$checked1?></td>
            <?php
          }
          else
          {
          }
        }
        ?>
        <td>
          <?=$daysTotalCnt?>
        </td>
      </tr>
      <?php
      $alldaycount=$daysTotalCnt+ $alldaycount;
    }
    ?>
    <tr>
      <td colspan='<?=$colspan?>'><center>Grand Total</center></td>
      <td ><?=$alldaycount?></td>
    </tr>
  </table>
  <?php
}
else{
  echo "<br><center>NO RECORDS";
}
}
?>
