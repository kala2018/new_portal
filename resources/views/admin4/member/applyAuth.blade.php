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
    <title>用户管理</title>
</head>
<style>
    #search{
        margin-left:20px;
        outline-style: none ;
        border: 1px solid #ccc;
        border-radius: 3px;
        width: 200px;
        padding:5px 5px;
        font-size: 10px;
        font-weight: 700;
        font-family: "Microsoft soft";
    }
    #addindex a:hover {
        text-decoration: none;
        color:write;
    }
</style>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户中心 <span class="c-gray en">&gt;</span> 用户管理 <input id="search" type="text" placeholder="请输入搜索报表名称" onchange="InstantSearch(this)"><a class="btn btn-danger radius r" style="line-height:1.6em;margin-top:3px;margin-left:5px;" href="javascript:;" title="退出账户" onClick="suaxin()" ><i class="Hui-iconfont">&#xe726;</i></a><a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container" >
    <div class="cl pd-5 bg-1 bk-gray mt-20 remove">
         <span class="l">

        </span>
        <span class="r">共有数据：<strong>{{count($data)}}</strong> 条</span> </div>
    <div class="mt-20  remove">
        <table class="table table-border table-bordered table-hover table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="15">ID</th>
                <th width="30">用户名</th>
                <th width="100">申请内容</th>
                <th width="40">申请时间</th>
                <th width="20">申请状态</th>
                <th width="20">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $value)
                <tr class="text-c">
                    <td><input type="checkbox" value="{{$value->id}}" name="ids"></td>
                    <td>{{$value->id}}</td>
                    <td>{{$value->memberName}}</td>
                    <td class="td-status">
                        <a onclick="member_auth({{$value->authName}})"><span class="label label-success radius">申请详情查看</span></a>
                    </td>
                    <td>{{$value->created_at}}</td>
                    <td class="td-status">
                        @if($value->status == '1')
                            <span class="label label radius">未处理</span>
                        @else
                            <span class="label label-success radius">已处理</span>
                        @endif
                    </td>
                    <td class="td-manage">
                        <!--判断操作按钮-->
                        <a style="text-decoration:none" onClick="member_stop(this,'{{$value->id}}','2')" href="javascript:;" title="处理"><i class="Hui-iconfont">&#xe6e1;</i></a>
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
            "aaSorting": [[ 4, "desc" ]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
            ]
        });

    });

    function member_auth(data){
        str = '';
        for(i = 0; i < data.length ; i++){
            str += '<span class="label label-success radius" style="margin-left:10px;">'+data[i]+'</span>';
        }
        layer.open({
            title: '详情'
            ,content: str
            ,offset: 'auto'
            ,area: ['500px', '300px']
        });

    }

    function member_stop(obj,id,type){
        layer.confirm('确认要标记为已处理吗？',function(index){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                url: '/adminfour/apply/status',
                data:{'id':id,'status':type},
                dataType: 'json',
                success: function(data){
                    if(data['code'] == 200 ){
                        layer.msg('处理成功!',{icon:1,time:1000},function(){
                            // var index = parent.layer.getFrameIndex(window.name);
                            //刷新
                            // this.window.location = this.window.location;
                            parent.layer.close(index);
                            // window.location.reload();

                        });
                        window.location = window.location;
                        layer.msg('处理成功!',{icon: 6,time:1000});
                    }else{
                        layer.msg('处理失败!',{icon:2,time:2000});
                    }
                },
                error:function(data) {
                    alert('处理失败，请重试！');
                },
            });
        });
    }


    function InstantSearch(obj){
        var conditions = obj.value;
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/adminfour/search/report',
            data:{'conditions':conditions,},
            dataType: 'json',
            success: function(data){

                // console.log(data);
                $('.remove').remove();
                $('.dataTables_wrapper').remove();
                var num = '';
                var c = false;
                for (i=0;i<data.length;i++){
                    var imgurl = data[i]['img'];
                    console.log(data[i]);
                    num += '<div class="col-xs-3 col-sm-3 " style="text-align:center;height:250px;padding:15px;"><a href="/adminfour/table/index?contentUrl='+data[i].contentUrl+'&filter='+data[i].filter+'"><img style="width:100%;height:80%" src="'+imgurl+'"><p style="line-height:50px;">'+data[i].report_name+'</p></a></div>';
                    c = true;
                }
                if(!c){
                    if(data.report_name){
                        var imgurl = data['img'];
                        num = '<div class="col-xs-3 col-sm-3 " style="text-align:center;height:250px;padding:15px;"><a href="/adminfour/table/index?contentUrl='+data.contentUrl+'&filter='+data.filter+'"><img style="width:100%;height:80%" src="'+imgurl+'"><p style="line-height:50px;">'+data.report_name+'</p></a></div>';
                    }
                    // num = '<div class="col-xs-3 col-sm-3 " style="text-align:center;height:250px;padding:15px;"><a href="/adminfour/table/index?contentUrl='+data.contentUrl+'&filter='+data.filter+'"><img style="width:100%;height:80%" src="'+'{{Session::get('tableau_domain')}}'+'/api/3.2/sites/'+'{{Session::get('credentials')}}'+'/workbooks/e51bfd80-8148-49fb-8a23-b177a73beb60/previewImage2'+'"><p style="line-height:50px;">'+data.report_name+'</p></a></div>';
                }
                $('.page-container').append('<div id="addindex" class="col-xs-12 col-sm-12 remove">'+num+'</div>');

            },
            error:function(data) {
                alert('停用失败，请联系管理员是否已经授权');
            },
        });
    }
    function suaxin(){
        layer.confirm('确认要退出账户吗？',function(index){
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                url: '/adminfour/public/logout',
                // data:{'id':id,'type':type},
                dataType: 'json',
                success: function(data){
                    if(data == '1'){
                        // layer.msg('停用成功!',{icon:1,time:1000},function(){
                        // var index = parent.layer.getFrameIndex(window.name);
                        //刷新
                        // this.window.location = this.window.location;
                        // parent.layer.close(index);
                        // window.location.reload();

                        // });
                        top.location.href = top.location.href;
                        // window.location = window.location;
                        // $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="member_start(this,'+'\''+this->tableau_id+'\''+','+'\''+id+'\''+','+'\''+2+'\''+','+'\''+username+'\''+')" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a>');
                        // $(obj).parents("tr").find(".td-status").html('<span class="label label radius">已停用</span>');
                        // $(obj).remove();
                        // layer.msg('已停用!',{icon: 6,time:1000});
                    }else{
                        layer.msg('停用失败!',{icon:2,time:2000});
                    }
                },
                error:function(data) {
                    alert('停用失败，请联系管理员是否已经授权');
                },
            });
        });

//     alert("ss");
//     if (window != top)
//         top.location.href = top.location.href;
//     // window.opener.document.location.reload();//刷新父级页面

// window.parent.window.location.reload()
    }
</script>
</body>
</html>
