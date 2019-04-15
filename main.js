$(function () {
    var sstype     = 0,
        page       = 0,
        keyword    = '',
        max_line   = 20,
        data_order = {field:'follow',order:'desc'},
        $template  = $('.title').clone().attr('class','line'),
        $table     = $('#table'),
        $page      = $('#page');

    //加载数据的核心方法
    var loadData = function(){
        $.get('ajax.php',{p:page,sstype:sstype,order:data_order,keyword:keyword,m:max_line},function (data) {
            $('.line').remove();
            for (var i =0;i<data.length;i++){
                $tmp = $template.clone();
                $tmp.children('td').each(function (index,obj) {
                    //数据格式化处理
                    //数据初始化
                    var $obj        = $(obj),
                        field       = $obj.attr('data-field')?$obj.attr('data-field'):false,
                        attr        = $obj.attr('data-attr')?$obj.attr('data-attr'):false,
                        row_number  = $obj.attr('data-row-number')?$obj.attr('data-row-number'):false,
                        hover_image = $obj.attr('data-hover-image')?$obj.attr('data-hover-image'):false,
                        value       = '';
                    //将字段值填入
                    if(field){
                        //检测字段值是否有映射 有映射就替换
                        value = $obj.attr('data-map')?data_map[$obj.attr('data-map')][data[i][field]]:data[i][field];
                        $obj.html(value);
                    }
                    //自定义标签
                    if(attr){
                        //空格切割
                        attr = attr.split(' ');
                        //遍历
                        for(var j=0;j<attr.length;j++){
                            // 字段值@标签
                            var tmp = attr[j].split('@');
                            $obj.attr(tmp[1],data[i][tmp[0]]);
                        }
                        $obj.removeAttr('data-attr');
                    }
                    //处理行号
                    if(row_number) {
                        var r = eval(row_number),
                            type = typeof r,
                            content = '';
                        if (type == 'function')
                            content = r(i);
                        else
                            content = r;
                        $(this).html(content);

                    }
                    //图片
                    if(hover_image){
                        $(this).attr('data-hover-image',data[i][hover_image]);
                    }
                });
                $table.append($tmp);
            }
        },'json');
    };
    loadData(page);
    $page.html(page+1);
    //按钮处事件处理
    $('#next').click(function () {
        $page.html(++page +1);
        loadData();
    });
    $('#prev').click(function () {
        if (page < 1) page = 1;
        $page.html(--page +1);
        loadData();
    });
    $('#sstype').change(function () {
        sstype = this.value;
        page = 0;
        $page.html(page +1);
        loadData();
    });
    $('#max').change(function () {
        max_line = this.value;
        page = 0;
        $page.html(page +1);
        loadData();
    });
    //数据排序
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
    });
    //链接跳转
    $table.on('click','[link-to]',function () {
        window.open($(this).attr('link-to'));
    });
    var search = function () {
        page = 0;
        $page.html(page+1);
        keyword = $('#keyword').val();
        loadData();
    };
    $('#keyword').keyup(search);
    //封面图片处理
    var image = new Image();
    image.id = 'cover';
    $img = $(image);
    $(document).on('mouseenter','.line td[data-hover-image]' , function () {
        var src = $(this).attr('data-hover-image');
        $img.attr('src',src);
        $(this).append($img);
        image.style.left = $(this).width() +"px";
        image.style.bottom = '';
        image.style.height = '';
        if($img.offset().top + $img.height()  > $(document).scrollTop()+ $(window).height()) {
            image.style.bottom = 0;
            if($(document).scrollTop()>$img.offset().top){
                image.style.height = $(this).offset().top - $(document).scrollTop()  + 'px';
            }
        }
    });
    $(document).on('mouseleave','.line td[data-hover-image]' , function () {
        $img.remove();
    });
});