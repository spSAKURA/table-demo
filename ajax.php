<?php
/**
 * Created by PhpStorm.
 * User: zhaoj
 * Date: 2019/4/8
 * Time: 下午 2:27
 */

$mysqli = include 'sqlinit.php';
//默认值设置
$page = 0;
$max = 20;
$sstype = 0;
$where = [];
//判断是否请求参数是否合法
if(isset($_REQUEST['p']) && !empty($_REQUEST['p']) && is_numeric($_REQUEST['p']))
    $page = $_REQUEST['p'];
if(isset($_REQUEST['m']) && !empty($_REQUEST['m']) && is_numeric($_REQUEST['p']))
    $max = $_REQUEST['m'];
if(isset($_REQUEST['sstype']) && !empty($_REQUEST['sstype']) && is_numeric($_REQUEST['sstype']))
    $sstype = $_REQUEST['sstype'];
//拼接sql
$start = $page * $max;
$limit = "LIMIT $start , $max";

$sstype_sql = '';
if($sstype)
    $where[] = "`sstype` = '$sstype'";
$order = 'ORDER BY `'.$_REQUEST['order']['field'].'` '.$_REQUEST['order']['order'];

$title_like = '';
if(isset($_REQUEST['keyword']) && !empty($_REQUEST['keyword']))
    $where[] = "`title` LIKE '%$_REQUEST[keyword]%'";

if($where)
    $where = 'WHERE ' . join(' AND ',$where);
else
    $where = '';

$sql = "SELECT * FROM `vdo_data` $where $order  $limit";
//echo $sql; exit;
$mr = $mysqli->query($sql);
$data = $mr->fetch_all(1);
echo json_encode($data);