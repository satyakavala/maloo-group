<?php
$conn = mysqli_connect("localhost","root","");
$db_selected = mysqli_select_db($conn,'maloo_group_task');
if (!$db_selected) {
  // If we couldn't, then it either doesn't exist, or we can't see it.
  $sql = 'CREATE DATABASE maloo_group_task';
  echo $sql;
  mysqli_query($conn,$sql);
  $db_selected = mysqli_select_db($conn,'maloo_group_task');
}
?>