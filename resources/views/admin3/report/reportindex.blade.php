<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="csrf-token" content="{{ csrf_token() }}">
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
<title>报表管理</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 报表管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="cl pd-5 bg-1 bk-gray mt-20">
        <span class="l">
            <a href="javascript:;" onclick="groups()" class="btn btn-success radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量项目组映射</a>
        </span>
        <span class="l">
            <a href="javascript:;" onclick="expires()" style="margin-left:30px;" class="btn btn-success radius"> 批量过期时间修改</a>
        </span>
    </div>
    <table class="table table-border table-bordered table-hover table-bg table-sort">
        <thead>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="40">ID</th>
                <th width="40">用户名</th>
                <th width="100">项目名</th>
                <th width="100">工作簿名</th>
                <th width="100">报表名</th>
                <th width="40">创建时间</th>
                <th width="100">过期时间</th>
                <th width="100">项目组映射</th>
                <th width="100">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($date as $value)
            <tr class="text-c">
                <td><input type="checkbox" value="{{$value->id}}" name="ids"></td>
                <td>{{$value->id}}</td>
                <td>{{$value->username}}</td>
                <td>{{$value->project_name}}</td>
                <td>{{$value->workBook_name}}</td>
                <td>{{$value->report_name}}</td>
                <td>
                    {{date('Y-m-d H:i:s',$value->created_at)}}
                </td>
                <td>
                    {{date('Y-m-d H:i:s',$value->expire)}}
                </td>
                <td class="td-status">
                    <a onclick="member_auth('项目组映射','/adminthree/report/{{$value->id}}','4','','510')"><span class="label label-success radius">项目组映射</span></a>
                </td>
                <td>
                    <a onclick="updateExpire({{$value->id}})"><span class="label label-success radius">过期时间修改</span></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/admin/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/admin/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/admin/lib/laypage/1.2/laypage.js"></script>
<script type="text/javascript">
$(function(){
    $('.table-sort').dataTable({
        "aaSorting": [[ 1, "desc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
          //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
          {"orderable":false,"aTargets":[0,6]}// 制定列不参与排序
        ]
    });

});
function member_auth(title,url,id,w,h){
    layer_show(title,url,w,h);
}


function updateExpire(id){
    member_auth('过期时间修改','/adminthree/report/expires/'+id,'4','','510');
}

/*报表批量映射*/
function groups(){
    var ids =[];
    $("input[name='ids']:checked").each(function(){
        ids.push($(this).val());
    });
    if(ids == false){
        layer.msg('请选择要批量授权的对象!',{icon:1,time:1000});
    }else{
        member_auth('映射tableau用户','/adminthree/report/groups/'+ids,'4','','510');
    }
}

/*报表批量映射*/
function expires(){
    var ids =[];
    $("input[name='ids']:checked").each(function(){
        ids.push($(this).val());
    });
    if(ids == false){
        layer.msg('请选择要批量过期时间修改的对象!',{icon:1,time:1000});
    }else{
        member_auth('过期时间修改','/adminthree/report/expires/'+ids,'4','','510');
    }
}
</script>
</body>
</html>
