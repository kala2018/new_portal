
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

<title>基本设置</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
    基本设置
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
</nav>
<div class="page-container">
    <form class="form form-horizontal" id="form-article-add">
        <div id="tab-system" class="HuiTab">
            <div class="tabBar cl">
                <span>基本设置</span>
            </div>
            <div class="tabCon">
                <div class="row cl">
                    <label class="form-label col-xs-4 col-sm-2">
                        <span class="c-red">*</span>
                        tableau域名：
                    </label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input type="text" id="website-title" placeholder="新域名" value="{{$default->system_domain}}" class="input-text" name="tableau_domain">
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    网站logo：
                </label>
                <div class="ormControls col-xs-8 col-sm-9">
                    <label>修改图片</label>
                    <div class="file-loading">
                        <input id="file-fr" name="logo_img" type="file" multiple value="">
                        <div>
                            <img id="cropedBigImg" src="{{$default->logo_url}}" value='custom' alt="lorem ipsum dolor sit" data-address='' title="logo图片" width="100" height="100"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    网站logo：
                </label>
                <div class="ormControls col-xs-8 col-sm-9">
                    <label>修改图片</label>
                    <div class="file-loading">
                        <input id="file-fr" name="logo_img" type="file" multiple value="">
                        <div>
                            <img id="cropedBigImg" src="{{$default->logo_url}}" value='custom' alt="lorem ipsum dolor sit" data-address='' title="logo图片" width="100" height="100"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    背景图片：
                </label>
                <div class="ormControls col-xs-8 col-sm-9">
                    <label>选择图片</label>
                    <div class="file-loading">
                        <input id="file-fr" class="file-frs" name="background_url" type="file" multiple value="">
                        <div>
                            <img id="cropedBigImg" class="cropedBigImgs" src="{{$default->background_url}}" value='custom' alt="lorem ipsum dolor sit" data-address='' title="logo图片" width="100" height="100"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    网站标题：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="新域名" value="{{$default->web_title}}" class="input-text" name="web_title">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    底部信息：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="新信息" value="{{$default->company}}" class="input-text" name="company">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    模式选择：
                </label>
                <div class="formControls col-xs-8 col-sm-9" id="model">
                    <input type="checkbox" id="website-title" value="1" class="input-checkbox" name="model" @if($default->model == '1') checked @endif>
                    模式一&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="website-title" value="2" class="input-checkbox" name="model" @if($default->model == '2') checked @endif>
                    模式二&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="website-title" value="3" class="input-checkbox" name="model" @if($default->model == '3') checked @endif>
                    模式三&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="website-title" value="4" class="input-checkbox" name="model"@if($default->model == '4') checked @endif>
                    模式四
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    报表页面的操作导航栏的位置：
                </label>
                <div class="formControls col-xs-8 col-sm-9" id="toolbar">
                    <input type="checkbox" id="website-title" value="top" class="input-checkbox" name="toolbar" @if($default->toolbar == 'top') checked @endif>
                    工具栏在顶部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="website-title" value="yes" class="input-checkbox" name="toolbar" @if($default->toolbar == 'yes') checked @endif>
                    工具栏在底部&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="website-title" value="no" class="input-checkbox" name="toolbar" @if($default->toolbar == 'no') checked @endif>
                    工具栏不显示
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    tableau用户名密码是否修改：
                </label>
                <div class="formControls col-xs-8 col-sm-9" id="same_tableau">
                    <input type="checkbox" id="website-title" value="1" class="input-checkbox  yes_same" name="same_tableau" @if($default->same_tableau == '1') checked @endif>
                    是&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="website-title" value="2" class="input-checkbox no_same" name="same_tableau" @if($default->same_tableau == '2') checked @endif>
                    否&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            @if($default->same_tableau == '1')
            <div class="row cl checkinput">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>tableau账户名：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="" value="{{$default->tableau_username}}" class="input-text" name="tableau_username">
                </div>
            </div
            ><div class="row cl checkinput">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>tableau密码：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="" value="{{$default->tableau_password}}" class="input-text" name="tableau_password">
                </div>
            </div>
            @endif
        </div>
        {{csrf_field()}}
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
                <button onClick="layer_close();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
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
$(function(){

    // $(".yes_same").click(function(){
    //     if(this.checked){
    //         $("#tab-system").append('<div class="row cl checkinput"><label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>tableau账户名：</label><div class="formControls col-xs-8 col-sm-9"><input type="text" id="website-title" placeholder="" value="{{$default->tableau_username}}" class="input-text" name="tableau_username"></div></div><div class="row cl checkinput"><label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>tableau密码：</label><div class="formControls col-xs-8 col-sm-9"><input type="text" id="website-title" placeholder="" value="{{$default->tableau_password}}" class="input-text" name="tableau_password"></div></div>');
    //     }else{
    //         $(".checkinput").remove();
    //     }
    // });
    // $(".no_same").click(function(){
    //     if(this.checked){
    //         $(".checkinput").remove();
    //     }
    // });

    $('#same_tableau').find('input[type=checkbox]').bind('click', function(){

       //当前的checkbox是否选中
        if(this.checked){
            $('#same_tableau input[type="checkbox"]').prop("checked",false)
            $(this).prop("checked",true)
        }
    });


    $('#toolbar').find('input[type=checkbox]').bind('click', function(){

       //当前的checkbox是否选中
        if(this.checked){
            $('#toolbar input[type="checkbox"]').prop("checked",false)
            $(this).prop("checked",true)
        }
    });

    $('#model').find('input[type=checkbox]').bind('click', function(){

       //当前的checkbox是否选中
        if(this.checked){
            $('#model input[type="checkbox"]').prop("checked",false)
            $(this).prop("checked",true)
        }
    });


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
            tableau_domain:{
                required:true,
            },
           web_title:{
                required:true,
            },
            company:{
                required:true,
            },
            log_img:{
                required:true,
            },
            model:{
                required:true,
            },
            toolbar:{
                required:true,
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
                    if(data == '1'){
                        layer.msg('更新成功!',{icon:1,time:1000},function(){
                            var index = parent.layer.getFrameIndex(window.name);
                            // //刷新
                            // window.location = window.location;
                        });
                    }else{
                        layer.msg('更新失败!',{icon:2,time:2000});
                    }
                },
                error: function(XmlHttpRequest, textis_nav, errorThrown){
                    layer.msg('error!',{icon:2,time:1000});
                }
            });
        }
    });

     $('.file-frs').on('change',function(){
        var filePath = $(this).val(),         //获取到input的value，里面是文件的路径
            fileFormat = filePath.substring(filePath.lastIndexOf(".")).toLowerCase(),
            src = window.URL.createObjectURL(this.files[0]); //转成可以在本地预览的格式

        // 检查是否是图片
        if( !fileFormat.match(/.png|.jpg|.jpeg/) ) {
            error_prompt_alert('上传错误,文件格式必须为：png/jpg/jpeg');
            return;
        }

        $('.cropedBigImgs').attr('src',src);
    });
});
</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>
