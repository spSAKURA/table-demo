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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>bilidata</title>
    <script src="jquery-2.2.0.js"></script>
    <link rel="stylesheet" href="main.css">
</head>
<body>
<div class="container">
    <select id="sstype">
        <option value="0">全部</option>
        <?php foreach ($sstype as $s){ ?>
            <option value="<?php echo $s ?>"><?php echo $map[$s] ?></option>
        <?php } ?>
    </select>
    <button id="prev">上一页</button>第<span id="page"></span>页<button id="next">下一页</button>
    <table id="table" cellpadding="0" cellspacing="0" width="100%">
        <tr class="title">
            <td data-field="title" link-to="url">标题</td>
            <td data-field="sstype" data-map="type_map">类型</td>
            <td data-field="danmaku" data-order="OFF">弹幕数</td>
            <td data-field="follow" data-order="DESC">订阅</td>
            <td data-field="view" data-order="OFF">播放量</td>
            <td data-field="cover">封面</td>
        </tr>
    </table>
</div>
</body>
<script>
    var type_map  = <?php echo json_encode($map) ?>,
        sstype    = 0,
        page      = 0,
        data_order = {field:'follow',order:'desc'},
        $template = $('.title').clone(),
        $table    = $('#table'),
        $page     = $('#page');

    $template.attr('class','line');
    var loadData = function(){
        $.get('ajax.php',{p:page,sstype:sstype,order:data_order},function (data) {
            $('.line').remove();
            for (var i =0;i<data.length;i++){
                $tmp = $template.clone();
                $tmp.children('td').each(function (index,obj) {
                    var $obj = $(obj);
                    var field = $obj.attr('data-field');
                    var value = $obj.attr('data-map')?eval($obj.attr('data-map'))[data[i][field]]:data[i][field];
                    if($obj.attr('link-to')){
                        value = '<a target="_blank" href="'+ data[i][$obj.attr('link-to')] +'">' + value + '</a>'
                    }
                    $obj.html(value);
                });
                $table.append($tmp);
            }
        },'json');
    };
    loadData(page);
    $page.html(page+1);
    $('#next').click(function () {
        $page.html(++page +1);
        loadData();
    });
    $('#prev').click(function () {
        if (page<1) page = 1;
        $page.html(--page +1);
        loadData();
    });
    $('#sstype').change(function () {
        sstype = this.value;
        page = 0;
        $page.html(page +1);
        loadData();
    });
    $('td[data-order]').click(function () {
        var order = $(this).attr('data-order');
        $('td[data-order]').attr('data-order','OFF');
        switch (order) {
            case 'OFF':
                order = 'DESC';
                break;
            case 'DESC':
                order = 'ASC';
                break;
            case 'ASC':
                order = 'DESC';
                break;
        }
        $(this).attr('data-order',order);
        data_order = {field:$(this).attr('data-field'),order:order};
        page = 0;
        $page.html(page +1);
        loadData();
    })
</script>
</html>
