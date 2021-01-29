
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="Bookmark" href="/favicon.ico" >
<link rel="Shortcut Icon" href="/favicon.ico" />
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
<!--/meta 作为公共模版分离出去-->

<title>excel数据导入</title>
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
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
    excel数据导入<input id="search" type="text" placeholder="请输入搜索报表名称" onchange="InstantSearch(this)"><a class="btn btn-danger radius r" style="line-height:1.6em;margin-top:3px;margin-left: 5px" href="javascript:;" title="退出账户" onClick="suaxin()" ><i class="Hui-iconfont">&#xe726;</i></a>
</nav>
<div class="page-container">
    <form class="form form-horizontal  remove" id="form-article-add">
        <div id="tab-system" class="HuiTab">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    excel数据导入：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input  name="file" type="file" value="选择文件"">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    服务器ip或域名：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="服务器ip或域名" value="" class="input-text" name="host">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    端口号：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="端口号" value="" class="input-text" name="port">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    数据库用户名：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="用户名" value="" class="input-text" name="user">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    密码：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="密码" value="" class="input-text" name="password">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    连接的库名：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="连接的库名" value="" class="input-text" name="database_name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    连接的表名：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="连接的库名" value="" class="input-text" name="table_name">
                </div>
            </div>
            <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">
                <span class="c-red">*</span>
                选择提交的方式：
            </label>
            <div class="formControls col-xs-8 col-sm-9">
                <div class="radio-box">
                    <input name="type" type="radio" id="status-1" checked value="1">
                    <label for="status-1">更新已有数据</label>
                </div>
                <div class="radio-box">
                    <input type="radio" id="status-2" name="type" value="2">
                    <label for="status-2" value="0">插入新的数据</label>
                </div>
                <div class="radio-box">
                    <input name="type" type="radio" id="status-3" checked value="3">
                    <label for="status-3">完全覆盖</label>
                </div>
            </div>
        </div>
        </div>
        {{csrf_field()}}
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i>提交</button>
            </div>
        </div>
    </form>
</div>

<!--_footer 作为公共模版分离出去-->
<script type="text/javascript" src="/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/admin/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
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

                // console.log(data);
                $('.remove').remove();
                $('.dataTables_wrapper').remove();
                var num = '';
                var c = false;
                for (i=0;i<data.length;i++){
                    num += '<div class="col-xs-3 col-sm-3 " style="text-align:center;height:250px;padding:15px;"><a href="/adminfour/table/index?contentUrl='+data[i].contentUrl+'&filter='+data[i].filter+'"><img style="width:100%;height:80%" src="/images/siXaqiL5bi.jpg"><p style="line-height:50px;">'+data[i].report_name+'</p></a></div>';
                    c = true;
                }
                if(!c){
                    if(data.report_name){
                            num = '<div class="col-xs-3 col-sm-3 " style="text-align:center;height:250px;padding:15px;"><a href="/adminfour/table/index?contentUrl='+data.contentUrl+'&filter='+data.filter+'"><img style="width:100%;height:80%" src="/images/siXaqiL5bi.jpg"><p style="line-height:50px;">'+data.report_name+'</p></a></div>';
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

$(function(){


    $('.skin-minimal input').iCheck({
        checkboxClass: 'icheckbox-blue',
        radioClass: 'iradio-blue',
        increaseArea: '20%'
    });
    $("#tab-system").Huitab({
        index:0
    });

    $("#form-article-add").validate({
        //表单验证
        rules:{
            file:{
                required:true,
            },
            host:{
                required:true,
            },
            port:{
                required:true,
            },
            user:{
                required:true,
            },
            password:{
                required:true,
            },
            database_name:{
                required:true
            },
            table_name:{
                required:true
            }
        },
        onkeyup:false,
        focusCleanup:true,
        success:"valid",
        submitHandler:function(form){
            $(form).ajaxSubmit({
                type: 'post',
                url: "" ,//自己提交给自己可以不写url
                success: function(data){
                    if(data['status'] == '1'){
                        layer.msg('添加成功!',{icon:1,time:1000},function(){
                            var index = parent.layer.getFrameIndex(window.name);
                            //刷新
                            parent.window.location = parent.window.location;
                            parent.layer.close(index);
                        });
                    }else{
                        var cont = '';
                        for(var i=0;i<data['error'].length;i++){
                            cont += data['error'][i]+'、';
                        }
                        layer.msg('添加失败,以下名字出现重复：'+cont,{icon:2,time:2000});
                    }
                },
                error: function(XmlHttpRequest, textis_nav, errorThrown){
                    layer.msg('error!',{icon:2,time:1000});
                }
            });
        }
    });

     $('#file-fr').on('change',function(){
        var filePath = $(this).val(),         //获取到input的value，里面是文件的路径
            fileFormat = filePath.substring(filePath.lastIndexOf(".")).toLowerCase(),
            src = window.URL.createObjectURL(this.files[0]); //转成可以在本地预览的格式

        // 检查是否是图片
        if( !fileFormat.match(/.png|.jpg|.jpeg/) ) {
            error_prompt_alert('上传错误,文件格式必须为：png/jpg/jpeg');
            return;
        }

        $('#cropedBigImg').attr('src',src);
    });
});
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>
