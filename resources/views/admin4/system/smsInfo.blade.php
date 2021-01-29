
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

    <title>初始化阿里云短信基本设置</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
    初始化基本设置
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
</nav>
<div class="page-container">
    <form class="form form-horizontal" id="form-article-add">
        <div class="tabBar cl">
            <span>初始化基本设置</span>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">
            <span class="c-red">*</span>
                选择要修改的配置：
            </label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box">
                  <select class="select" size="1" name="demo1" id="conf">
                    <option value="" selected>默认select</option>
                      @foreach($sysConf as $key => $value)
                          <option value="{{$value}}">{{$value->name}}</option>
                      @endforeach
                  </select>
                </span>
            </div>

        </div>
    </form>
    <div class="row cl" id="SmsAdd" style="margin-top:50px;margin-right:150px;float:right">
        <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
            <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 新建短信SDK配置</button>
        </div>
    </div>

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

        $('#conf').change(() => {
            // console.log('change');
            // console.log($('#cont select option:selected').text());
            var v = JSON.parse($('#conf').val());
            $('#tab-system').remove();
            $('#form-article-add').append(
                '        <div id="tab-system" class="HuiTab">\n' +
                '            <input type="hidden" name="id" value="\n'+v.id+'\n">\n' +
                '            <div class="row cl">\n' +
                '                 <label class="form-label col-xs-4 col-sm-2">\n' +
                '                    <span class="c-red">*</span>\n' +
                '                    配置名称：\n' +
                '                 </label>\n' +
                '                 <div class="formControls col-xs-8 col-sm-9">\n' +
                '                     <input type="text" id="website-title" placeholder="配置名称" value="\n'+v.name+'\n" class="input-text" name="name">\n' +
                '                 </div>\n' +
                '            </div>\n' +
                '            <div class="row cl">\n' +
                '                <label class="form-label col-xs-4 col-sm-2">\n' +
                '                    <span class="c-red">*</span>\n' +
                '                    短信签名名称：\n' +
                '                </label>\n' +
                '                <div class="formControls col-xs-8 col-sm-9">\n' +
                '                    <input type="text" id="website-title" placeholder="" value="\n'+v.sign_name+'\n" class="input-text" name="sign_name">\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="row cl">\n' +
                '                <label class="form-label col-xs-4 col-sm-2">\n' +
                '                    <span class="c-red">*</span>\n' +
                '                    模版CODE：\n' +
                '                </label>\n' +
                '                <div class="formControls col-xs-8 col-sm-9">\n' +
                '                    <input type="text" id="website-title" placeholder="" value="\n'+v.template_code+'\n" class="input-text" name="template_code">\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="row cl checkinput">\n' +
                '                <label class="form-label col-xs-4 col-sm-2">\n' +
                '                    <span class="c-red">*</span>access_key：\n' +
                '                </label>\n' +
                '                <div class="formControls col-xs-8 col-sm-9">\n' +
                '                    <input type="password" id="website-title" placeholder="" value="\n'+v.access_key+'\n" class="input-text" name="access_key">\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="row cl checkinput">\n' +
                '                <label class="form-label col-xs-4 col-sm-2">\n' +
                '                    <span class="c-red">*</span>access_secret：\n' +
                '                </label>\n' +
                '                <div class="formControls col-xs-8 col-sm-9">\n' +
                '                    <input type="password" id="website-title" placeholder="" value="\n'+v.access_secret+'\n" class="input-text" name="access_secret">\n' +
                '                </div>\n' +
                '            </div>\n' +
                '            <div class="row cl">\n' +
                '                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">\n' +
                '                    <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>\n' +
                '                </div>\n' +
                '            </div>\n' +
                '        {{csrf_field()}}\n' +
                '        </div>');
        });

        $('#SmsAdd').click(function(){
            layer_show("添加短信配置","/adminfour/sms/creat",700,700);
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
                name:{
                    required:true,
                },
                sign_name:{
                    required:true,
                },
                template_code:{
                    required:true,
                },
                access_key:{
                    required:true,
                },
                access_secret:{
                    required:true,
                },
            },
            onkeyup:false,
            focusCleanup:true,
            success:"valid",
            submitHandler:function(form){
                $(form).ajaxSubmit({
                    type: 'post',
                    url: "/adminfour/sms/confUpdate" ,//自己提交给自己可以不写url
                    success: function(data){
                        if(data['code'] == 200){
                            layer.msg(data['message'],{icon:1,time:1000},function(){
                                window.location.reload()
                            });
                        }else{
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
