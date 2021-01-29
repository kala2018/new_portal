
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

    <title>用户注册</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
    用户个人信息
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
</nav>
<div class="page-container">
    <form class="form form-horizontal" id="form-article-add">
        <input type="hidden" name="id" value="{{$user['id']}}">
        <div id="tab-system" class="HuiTab">
            <div class="tabBar cl">
                <span>个人信息表单</span>
            </div>
            <div class="tabCon">
                <div class="row cl">
                    <label class="form-label col-xs-4 col-sm-2">
                        <span class="c-red">*</span>
                        企业名称：
                    </label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <input type="text" id="website-title" placeholder="企业名称" value="{{$user['company_name']}}" class="input-text" name="company_name">
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    姓名：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" id="website-title" placeholder="" value="{{$user['username']}}" class="input-text" name="username">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>性别：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input name="gender" type="radio" id="gender-1" @if($user['gender'] == 1 ) checked @endif value="1">
                        <label for="gender-1">男</label>
                    </div>
                    <div class="radio-box">
                        <input type="radio" id="gender-2" @if($user['gender'] == 2 ) checked @endif  name="gender" value="2">
                        <label for="gender-2" value="2">女</label>
                    </div>
                    <div class="radio-box">
                        <input type="radio" id="gender-3" name="gender" @if($user['gender'] == 3 ) checked @endif value="3">
                        <label for="gender-3" value="3">保密</label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    tableau用户名密码是否修改：
                </label>
                <div class="formControls col-xs-8 col-sm-9" id="same_tableau">
                    <input type="checkbox" id="website-title" value="1" class="input-checkbox  yes_same" name="same_tableau">
                    是&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" id="website-title" value="2" class="input-checkbox no_same" name="same_tableau" checked>
                    否&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
        </div>
        {{csrf_field()}}
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
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

        // $(".no_same").click(function(){
        //     if(this.checked){
        //         $("#tab-system").append('<div class="row cl checkinput"><label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>tableau账户名：</label><div class="formControls col-xs-8 col-sm-9"><input type="text" id="website-title" placeholder="" value="" class="input-text" name="tableau_username"></div></div><div class="row cl checkinput"><label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>tableau密码：</label><div class="formControls col-xs-8 col-sm-9"><input type="text" id="website-title" placeholder="" value="" class="input-text" name="tableau_password"></div></div>');
        //     }else{
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

        $(".yes_same").click(function(){
            if(this.checked){
                $("#tab-system").append('<div class="row cl checkinput pas">\n' +
                    '                <label class="form-label col-xs-4 col-sm-2">\n' +
                    '                    <span class="c-red">*</span>旧密码：\n' +
                    '                </label>\n' +
                    '                <div class="formControls col-xs-8 col-sm-9">\n' +
                    '                    <input type="text" id="website-title" placeholder="" value="" class="input-text" name="old_password">\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '            <div class="row cl checkinput pas">\n' +
                    '                <label class="form-label col-xs-4 col-sm-2">\n' +
                    '                    <span class="c-red">*</span>新密码：\n' +
                    '                </label>\n' +
                    '                <div class="formControls col-xs-8 col-sm-9">\n' +
                    '                    <input type="text" id="website-title" placeholder="" value="" class="input-text" name="password">\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '            <div class="row cl checkinput pas">\n' +
                    '                <label class="form-label col-xs-4 col-sm-2">\n' +
                    '                    <span class="c-red">*</span>确认密码：\n' +
                    '                </label>\n' +
                    '                <div class="formControls col-xs-8 col-sm-9">\n' +
                    '                    <input type="text" id="website-title" placeholder="" value="" class="input-text" name="password_confirmation">\n' +
                    '                </div>\n' +
                    '            </div>');
            }else{
                $(".pas").remove();
            }
        });

        $(".no_same").click(function(){
            if(this.checked){
                $(".pas").remove();
            }
        });



        $("#sms").click(function () {
            var mobile = $(".mobile").val();
            $.ajax({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                url:'/sms/send',
                type:'POST',  // 默认为GET
                data:{
                    'mobile':mobile
                },
                dataType:"json",
                jsonp:"callback",
                timeout:5000, // 超时时间
                beforeSend:function(xhr){
                },
                success:function(result){
                    if(result == '1'){
                        layer.msg("发送成功",{icon:1,time:1000});
                        settime(60);
                    }else{
                        layer.msg("发送失败，请重试！",{icon:2,time:1000});
                    }
                },
                error:function(xhr,textStatus){
                    xhr = JSON.parse(xhr.responseText)
                    var msg = '';
                    for (let key in xhr['errors']) {
                        msg += xhr['errors'][key][0];
                        break;
                    }
                    layer.msg(msg,{icon:2,time:1000});
                }
            });
        });
        function settime(countdown) {
            var t = $("#sms");
            if (countdown == 0) {
                t.removeAttr("disabled");
                t.val("重新获取验证码");
                return;
            } else {
                t.attr("disabled","disabled");
                t.val("重新发送(" + countdown + ")");
                countdown--;
            }
            setTimeout(function() {
                settime(countdown)
            },1000)
        }

        $("#form-article-add").validate({
            //表单验证
            rules:{
                company_name:{
                    required:true,
                },
                username:{
                    required:true,
                },
            },
            onkeyup:false,
            focusCleanup:true,
            success:"valid",
            submitHandler:function(form){
                $(form).ajaxSubmit({
                    type: 'post',
                    url: "/adminthree/member/update" ,//自己提交给自己可以不写url
                    success: function(data){
                        if(data['code'] == 200){
                            layer.msg(data['message'],{icon:1,time:1000},function(){
                                window.location.reload();
                            });
                        }else if(data['code'] == -100){
                            layer.msg(data['message'],{icon:2,time:2000});
                        }
                    },
                    error: function(xhr, textis_nav, errorThrown){
                        xhr = JSON.parse(xhr.responseText)
                        var msg = '';
                        for (let key in xhr['errors']) {
                            msg += xhr['errors'][key][0];
                            break;
                        }
                        layer.msg(msg,{icon:2,time:1000});
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

        $('#file-frs').on('change',function(){
            var filePath = $(this).val(),         //获取到input的value，里面是文件的路径
                fileFormat = filePath.substring(filePath.lastIndexOf(".")).toLowerCase(),
                src = window.URL.createObjectURL(this.files[0]); //转成可以在本地预览的格式

            // 检查是否是图片
            if( !fileFormat.match(/.png|.jpg|.jpeg/) ) {
                error_prompt_alert('上传错误,文件格式必须为：png/jpg/jpeg');
                return;
            }

            $('#cropedBigImgs').attr('src',src);
        });
    });

</script>
<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>
