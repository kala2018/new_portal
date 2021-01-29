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
<title>报表定位</title>
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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 报表定位<input id="search" type="text" placeholder="请输入搜索报表名称" onchange="InstantSearch(this)"> <a class="btn btn-danger radius r" style="line-height:1.6em;margin-top:3px;margin-left:5px;" href="javascript:;" title="退出账户" onClick="suaxin()" ><i class="Hui-iconfont">&#xe726;</i></a><a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
    <div class="cl pd-5 bg-1 bk-gray mt-20 remove">
        <span class="l">
        </span>
    </div>
    <table class="table table-border table-bordered table-hover table-bg table-sort remove">
        <thead>
            <tr class="text-c">
                <!-- <th width="10">收藏</th> -->
                <th width="100">项目名</th>
                <th width="100">工作簿名</th>
                <th width="100">报表名</th>
                <th width="100">跳转到该页面</th>
            </tr>
        </thead>
        <tbody>
            @foreach($p as $value)
                    <tr class="text-c">
                        <!-- <td>
                            @if($value['collection'] == '0')
                            <i onClick="collectionpush(this,'{{$value['project']}}','{{$value['workBook']}}','{{$value['view']->name}}','{{$value['view']->id}}','{{$value['view']->contentUrl}}','{{$value['filter']}}')" class="Hui-iconfont" id="collec">
                                &#xe69e;
                            </i>
                            @else
                            <i onClick="collectionpop(this,'{{$value['project']}}','{{$value['workBook']}}','{{$value['view']->name}}','{{$value['view']->id}}','{{$value['view']->contentUrl}}','{{$value['filter']}}')" class="Hui-iconfont" id="collec">
                                &#xe630;
                            </i>
                            @endif
                        </td> -->
                        <td>{{$value['project']}}</td>
                        <td>{{$value['workBook']}}</td>
                        <td>{{$value['view']->name}}</td>
                        <td class="td-status"><a href="/adminfour/table/index?contentUrl={{$value['view']->contentUrl}}&filter={{$value['filter']}}" data-title="{{$value['view']->name}}" href="javascript:;"><span class="label label-success radius">跳转</span></a></td>
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

function InstantSearch(obj){
    var conditions = obj.value;
    $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/adminfour/search/report',
            data:{'conditions':conditions,},
            dataType: 'json',
            success: function(data){

                console.log(data);
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

$(function(){
    $('.table-sort').dataTable({
        "aaSorting": [[ 1, "desc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
          //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
          {"orderable":false,"aTargets":[0,2]}// 制定列不参与排序
        ]
    });

});
function member_auth(title,url,id,w,h){
    layer_show(title,url,w,h);
}

// function collectionpush(obj,project,workBook,report_name,report_id,contentUrl,filter){
//     // alter(obj);
//     // console.log(obj.html());
//     $.ajax({
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         type: 'POST',
//         url: '/adminfour/report/collection',
//         data:{'project_name':project,'workBook_name':workBook,'report_id':report_id,'report_name':report_name,'contentUrl':contentUrl,'filter':filter,'co':true},
//         dataType: 'json',
//         success: function(data){
//              if(data == '1'){
//                 // console.log('sss');
//                     layer.msg('收藏成功!',{icon:1,time:1000},function(){
//                         window.location = window.location;
//                         // var index = parent.layer.getFrameIndex(window.name);
//                         // obj.replaceWith('<i onClick="collectionpop('+'this'+','+project+','+workBook+','+report_name+','+report_id+','+contentUrl+','+filter+')" class="Hui-iconfont" id="collec">&#xe69e;</i>');

//                         // $(this).find('i').remove();
//                         // $(this).replaceWith('<i onClick="collectionpop('+'this'+'\''+report_id+'\''+')"'+' class="Hui-iconfont" id="collec"'+'>'+'&#xe630;</i>');

//                         // $(this).text('&#xe630;');
//                         // parent.layer.close(index);
//                     });
//                 }else{
//                     layer.msg('收藏失败!',{icon:2,time:2000});
//                 }
//         },
//         error:function(data) {
//             alert('收藏失败！');
//         },
//     });
// }

// function collectionpop(obj,project,workBook,report_name,report_id,contentUrl,filter){
//     console.log(obj.innerText);
//     $.ajax({
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         type: 'POST',
//         url: '/adminfour/report/collection',
//         data:{'report_id':report_id,'co':false},
//         dataType: 'json',
//         success: function(data){
//              if(data == '1'){
//                 // console.log('sss');
//                     layer.msg('取消收藏成功!',{icon:1,time:1000},function(){
//                         window.location = window.location;
//                         // var index = parent.layer.getFrameIndex(window.name);
//                         // console.log('ssssss');
//                         // obj.innerText = '&#xe69e;';
//                         // $(this).remove();
//                         // obj.after('<i onClick="collectionpush('+'this'+','+project+','+workBook+','+report_name+','+report_id+','+contentUrl+','+filter+')" class="Hui-iconfont" id="collec">&#xe69e;</i>');
//                         // obj.replaceWith("<i onClick="+"\""+"collectionpush()"+"\""+" class="+"\""+"Hui-iconfont"+"\""+" id="+"\""+"collec"+"\""+">&#xe69e;</i>");
//                         // obj.remove();
//                         // obj.replaceWith($('<i onClick="collectionpush('+'this'+','+project+','+workBook+','+report_name+','+report_id+','+contentUrl+','+filter+')" class="Hui-iconfont" id="collec">&#xe69e;</i>').html());
//                         // $(this).text('&#xe69e;');
//                         // parent.layer.close(index);
//                     });
//                 }else{
//                     layer.msg('取消收藏失败!',{icon:2,time:2000});
//                 }
//         },
//         error:function(data) {
//             alert('取消失败！');
//         },
//     });
// }
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
</script>
</body>
</html>
