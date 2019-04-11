<?php
$mysqli = include 'sqlinit.php';
$sstype_sql = 'SELECT distinct `sstype` FROM `vdo_data` ORDER BY `sstype`';
$sstype = $mysqli -> query($sstype_sql) -> fetch_all(2);
foreach ($sstype as $k => $v)
    $sstype[$k] = $v[0];
$type_name_sql = 'SELECT `sstype`,`typename` FROM `type_name`';
$type_name = $mysqli -> query($type_name_sql) -> fetch_all(1);
$map = [];
foreach ($type_name as $v) $map[$v['sstype']] = $v['typename'];
?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="referrer" content="never">
    <title>table-demo</title>
    <script src="jquery-2.2.0.js"></script>
    <link rel="stylesheet" href="main.css">
    <script src="main.js"></script>
    <script>var data_map   = {type:<?php echo json_encode($map) ?>,};</script>
</head>
<body>
<div class="container">
    <div class="control">
        <select id="sstype">
            <option value="0">全部</option>
            <?php foreach ($sstype as $s){ ?>
                <option value="<?php echo $s ?>"><?php echo $map[$s] ?></option>
            <?php } ?>
        </select>
        <button id="prev">上一页</button>第<span id="page"></span>页<button id="next">下一页</button>
        <input id="keyword" type="text" placeholder="标题搜索"/>
    </div>
    <table id="table" cellpadding="0" cellspacing="0" width="100%">
        <tr class="title">
            <td data-row-number="x=>x+1"></td>
            <td data-field="title" data-link="url" data-attr="url@link-to url@title" data-hover-image="cover">标题</td>
            <td data-field="sstype" data-map="type" >类型</td>
            <td data-field="danmaku" data-order="OFF">弹幕数</td>
            <td data-field="follow" data-order="DESC">订阅</td>
            <td data-field="view" data-order="OFF">播放量</td>
        </tr>
    </table>
</div>
</body>
</html>