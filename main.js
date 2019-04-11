$(function () {
    sstype     = 0,
        page       = 0,
        keyword    = '',
        data_order = {field:'follow',order:'desc'},
        $template  = $('.title').clone(),
        $table     = $('#table'),
        $page      = $('#page');

    $template.attr('class','line');
    var loadData = function(){
        $.get('ajax.php',{p:page,sstype:sstype,order:data_order,keyword:keyword},function (data) {
            $('.line').remove();
            for (var i =0;i<data.length;i++){
                $tmp = $template.clone();
                $tmp.children('td').each(function (index,obj) {
                    var $obj = $(obj),
                        field = $obj.attr('data-field')?$obj.attr('data-field'):false,
                        attr = $obj.attr('data-attr')?$obj.attr('data-attr'):false,
                        value = '';
                    if(field){
                        value = $obj.attr('data-map')?data_map[$obj.attr('data-map')][data[i][field]]:data[i][field];
                        $obj.html(value);
                    }
                    if(attr){
                        attr = attr.split(' ');
                        for(var j=0;j<attr.length;j++){
                            //if(!attr[j]) continue;
                            var tmp = attr[j].split('@');
                            $obj.attr(tmp[1],data[i][tmp[0]]);
                        }
                        $obj.removeAttr('data-attr');
                    }
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
    var image = new Image();
    image.id = 'cover';
    $img = $(image);
    $(document).on('mouseenter','.line td[title]' , function () {
        var src = this.title;
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
    $(document).on('mouseleave','.line td[title]' , function () {
        $img.remove();
    });
});