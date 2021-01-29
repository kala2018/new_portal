<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="Bookmark" href="/favicon.ico" >
<link rel="Shortcut Icon" href="/favicon.ico" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
<meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT">
<!--[if lt IE 9]>
<script type="text/javascript" src="/admin/lib/html5shiv.js"></script>
<script type="text/javascript" src="/admin/lib/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/admin/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="/admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>后台页面</title>
<meta name="keywords" content="H-ui.admin v3.1,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="H-ui.admin v3.1，是一款由国人开发的轻量级扁平化网站后台模板，完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">
</head>
<script type="text/javascript">
</script>
<style>
    .navbar-logo{
        margin-left: 10px;
    }
    #menu-member li a{
        border-bottom:0px;
        color:#b5b7c2;
        text-align：center;
        padding:4px 20px;
        font-size: 16px;
    }

    #menu-article dd .Huifold .item h4:hover{
        color:#fff;
    }

    .Hui-aside .menu_dropdown li a:hover{
        background-color:#000;
        color:#fff;
    }

    #menu-member a:hover{
        background-color:#2d8cf0;
        color:#fff;
    }

    .Hui-aside .parent .tableau-project{
        text-overflow:ellipsis;
        border-bottom: 0px;
        color:#b5b7c2;
        padding:4px 20px;
        font-size:16px;
    }


    .tableau-project:hover {
        background-color:#2d8cf0;
        color:#fff;
    }

    .Hui-aside .menu_dropdown .parent dt:hover{
        color:#fff;
    }

    .Hui-aside .parent .Huifold .item .tableau-workbook{
        color:#909195;
        font-size:12px;
        font-weight:normal;
        text-overflow: ellipsis;
        display:inline-block;
        white-space: nowrap;
        width: 100%;
        padding:12px 20px;
        background-color:#1a1a1d;
        border: 0px;
        font-size:16px;
    }

    .Hui-aside .parent .Huifold .item .tableau-workbook:hover{
        background-color:#2d8cf0;
        color:#fff;
    }
    .Hui-aside  .parent dd ul .item .info .Huifold .item a:hover{
        background-color:#2d8cf0;
        color:#fff;
    }

    .Hui-aside #menu-member #log a:hover{
        background-color:#23262e;
    }

    #menu-member #logo a:hover {
        background-color:#23262e;
    }
</style>
<body>
<header class="navbar-wrapper">
   <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl">
            <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="/adminfour/index/index">logo</a>
            <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>

        </div>
    </div>

</header>
<aside class="Hui-aside" style="top:0;background-color: #23262e">
    <div class="menu_dropdown bk_2" >
        <dl id="menu-member">
            <li id="logo"><a class="logo navbar-logo f-l mr-10 hidden-xs" data-title="首页" data-href="javascript:;" onClick="indexchuxin()" style="width:100%;padding:4px 0;text-align: center;">
                <img src="{{$system->logo_url}}" alt="logo图片位置" style="width:100%;height:80px;margin:0 auto;display: inline-block;">
            </a></li>
            <li>
                <span class="select-box" style="border: 0;display: block;position: relative;">
                  <select class="select" id="conf" size="1" name="demo1" style="border: 0;display: block;position: relative;">
                    @foreach($allSites as $value)
                    <option  value="{{$value->id}}" @if($value->id == Session::get('credentials')) selected @endif>{{$value->name}}</option>
                    @endforeach
                  </select>
                </span>
            </li>

            @if(!Session::get('isVisitor'))
                <li><a data-href="/adminfour/report/collection/index" data-title="收藏列表" href="javascript:;">收藏列表</a></li>
            @else
                <li><a data-href="/register" data-title="前往注册" href="javascript:;">前往注册</a></li>
            @endif

            @if($type == '1')
            <li><a data-href="/adminfour/member/index" data-title="会员列表" href="javascript:;">会员列表</a></li>
            <li><a data-href="/adminfour/user/group" data-title="用户组列表" href="javascript:;">权限组</a></li>
            <li><a data-href="/adminfour/report/index" data-title="报表列表" href="javascript:;">权限控制</a></li>
            <li><a data-href="/adminfour/system/update" data-title="系统设置" href="javascript:void(0)">系统设置</a></li>
            <li><a data-href="/adminfour/sms/update" data-title="系统设置" href="javascript:void(0)">短信SDK配置</a></li>
            <li><a data-href="/adminfour/apply/index" data-title="用户申请列表" href="javascript:void(0)">用户申请列表</a></li>
            <li><a data-href="/adminfour/cache/update" data-title="系统设置" href="javascript:void(0)">更新tableau缓存</a></li>
            @endif
            @if($isexcel =='1')
            <li><a data-href="/adminfour/excel/insert" data-title="数据导入" href="javascript:;">数据导入</a></li>
            @endif
            <li><a data-href="/adminfour/report/select" data-title="报表查询" href="javascript:;">报表查询</a></li>
            @if($type == '2' && !Session::get('isVisitor'))
                <li><a data-href="/adminfour/member/info" data-title="个人信息" href="javascript:;">个人信息</a></li>
                <li><a data-href="/adminfour/member/apply" data-title="权限申请" href="javascript:;">权限申请</a></li>
            @endif
        </dl>
        @if($p)
        @foreach($p as $val)
        <dl id="menu-article" class="parent">
            <dt style="" class="tableau-project"><i class="Hui-iconfont">&#xe616;</i>{{$val['name']}}<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
            <dd>
                <ul id="Huifold1" class="Huifold" style="padding:0px;">
                    @foreach($val["project"] as $value)
                    <li class="item">
                        <h4 class="tableau-workbook" style="">{{$value['name']}}</h4>
                        <div class="info" style="padding:0px">
                            <ul id="Huifold1" class="Huifold" style="padding:0px">
                                @foreach($value['views'] as $vieVule)
                                <li class="item" style="background-color:#000;">
                                    @if($type == '1')
                                    <a class="tableau-workbook-a"  data-href="/adminfour/table/index?contentUrl={{$vieVule->contentUrl}}&filter=iframeSizedToWindow=true&id={{$vieVule->id}}"" data-title="{{$vieVule->name}}" href="javascript:;" style="text-overflow:ellipsis;display:inline-block;white-space: nowrap;width: 100%;overflow:hidden;font-size:13px;padding-left:20px;padding-top:5px;padding-bottom:5px">{{$vieVule->name}}</a>
                                    @else
                                    <a class="tableau-workbook-a" data-href="/adminfour/table/index?contentUrl={{$vieVule->contentUrl}}&filter={{$vieVule->filter}}&id={{$vieVule->id}}" data-title="{{$vieVule->name}}" href="javascript:;" style="text-overflow:ellipsis;display:inline-block;white-space: nowrap;width: 100%;overflow:hidden;font-size:13px;padding-top:5px;padding-bottom:5px">{{$vieVule->name}}</a>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </dd>
        </dl>
        @endforeach
        @endif

    </div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)" style="opacity: 0.5;"></a></div>
<section class="Hui-article-box" style="top:0">

    <div id="Hui-tabNav" class="Hui-tabNav hidden-xs" style="display:none">
        <div class="Hui-tabNav-wp">
            <ul id="min_title_list" class="acrossTab cl">
                <li class="active">
                    <!-- 修改src引入地址 -->
                    <span title="我的收藏" data-href="/adminfour/index//admin/index/welcome">我的桌面</span>
                    <em></em>
                </li>
            </ul>
        </div>
        <div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
    </div>
    <div id="iframe_box" class="Hui-article" style="margin-top:0;position:static">

        <div class="show_iframe">
            <div style="display:none" class="loading"></div>

            <!-- 修改src引入地址 -->
            <iframe scrolling="yes" frameborder="0" src="/adminfour/report/collection/index"></iframe>
        </div>
    </div>
</section>

<div class="contextMenu" id="Huiadminmenu">
    <ul>
        <li id="closethis">关闭当前 </li>
        <li id="closeall">关闭全部 </li>
</ul>
</div>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/admin/lib/jquery/1.9.1/jquery.min.js"></script>

<!-- <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script> -->
 <script type="text/javascript" src="/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/admin/static/h-ui/js/jquery.ba-resize.js"></script>
<script type="text/javascript" src="/admin/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/admin/lib/jquery.contextmenu/jquery.contextmenu.r2.js"></script>
<script type="text/javascript">


function indexchuxin(){
    window.location.reload();
}

window.history.go(1);


jQuery.Huifold = function(obj,obj_c,speed,obj_type,Event){
    if(obj_type == 2){
        // $(obj+":first").find("b").html("-");
        $(obj_c+":first").show()}
    $(obj).bind(Event,function(){
        if($(this).next().is(":visible")){
            if(obj_type == 2){
                return false}
            else{
                $(this).next().slideUp(speed).end().removeClass("selected");
                // $(this).find("b").html("+")
            }
        }
        else{
            if(obj_type == 3){
                $(this).next().slideDown(speed).end().addClass("selected");
                $(this).find("b").html("-")
            }else{
                $(obj_c).slideUp(speed);
                $(obj).removeClass("selected");
                // $(obj).find("b").html("+");
                $(this).next().slideDown(speed).end().addClass("selected");
                // $(this).find("b").html("-")
            }
        }
    })}
// function ajaxindex(obj,contentUrl,filter){
//      $('#tableindex').click(function(){
//         $.ajax({
//             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//             url: "adminfour/table/index",
//             data: {'contentUrl':contentUrl,'filter':filter},
//             type: "POST",
//             dataType: "json",
//             success: function(data) {

//             });
//     })
// }
$(function(){
$("#navtop").css({"width":$("section").width()});
    //监听div大小变化
$("section").resize(function(){
            $("#navtop").css({"width":$("section").width()});
});
    /*$("#min_title_list li").contextMenu('Huiadminmenu', {
        bindings: {
            'closethis': function(t) {
                console.log(t);
                if(t.find("i")){
                    t.find("i").trigger("click");
                }
            },
            'closeall': function(t) {
                alert('Trigger was '+t.id+'\nAction was Email');
            },
        }
    });*/
    $.Huifold("#Huifold1 .item h4","#Huifold1 .item .info","fast",1,"click"); /*5个参数顺序不可打乱，分别是：相应区,隐藏显示的内容,速度,类型,事件*/
});
/*个人信息*/
function myselfinfo(){
    layer.open({
        type: 1,
        area: ['300px','200px'],
        fix: false, //不固定
        maxmin: true,
        shade:0.4,
        title: '查看信息',
        content: '<div>管理员信息</div>'
    });
}


function historyClear(){
    window.history.forward(1);
}

/*资讯-添加*/
function article_add(title,url){
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}
/*图片-添加*/
function picture_add(title,url){
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}
/*产品-添加*/
function product_add(title,url){
    var index = layer.open({
        type: 2,
        title: title,
        content: url
    });
    layer.full(index);
}
/*用户-添加*/
function member_add(title,url,w,h){
    layer_show(title,url,w,h);
}

/*管理员-角色-分派权限*/
function admin_role_assign(title,url,id,w,h){
    layer_show(title,url + '?id=' + id,w,h);
}

$('#conf').change(() => {
    var v = $('#conf').val();
    window.location.href = window.location.pathname+'?siteId='+v
});



</script>
</body>
</html>
