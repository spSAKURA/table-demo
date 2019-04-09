<?php
/**
 * Created by PhpStorm.
 * User: zhaoj
 * Date: 2019/4/8
 * Time: 下午 2:27
 */

$mysqli = include 'sqlinit.php';

$page = 0;
$max = 30;
$sstype = 0;
if(isset($_REQUEST['p']) && !empty($_REQUEST['p']) && is_numeric($_REQUEST['p']))
    $page = $_REQUEST['p'];
if(isset($_REQUEST['m']) && !empty($_REQUEST['m']) && is_numeric($_REQUEST['p']))
    $max = $_REQUEST['m'];
if(isset($_REQUEST['sstype']) && !empty($_REQUEST['sstype']) && is_numeric($_REQUEST['sstype']))
    $sstype = $_REQUEST['sstype'];
$start = $page * $max;
$limit = "LIMIT $start , $max";
$sstype_sql = '';
if($sstype)
    $sstype_sql = "WHERE `sstype` = '$sstype'";
$order = 'ORDER BY `'.$_REQUEST['order']['field'].'` '.$_REQUEST['order']['order'];
$sql = "SELECT * FROM `vdo_data` $sstype_sql $order  $limit";
$mr = $mysqli->query($sql);
$data = $mr->fetch_all(1);
echo json_encode($data);
