
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
    权限申请
    <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
</nav>
<div class="page-container">
    <form class="form form-horizontal" id="form-article-add">
        <input type="hidden" class="apply_auth_id" name="apply_auth_id" value="">
        <div id="par">
            <div id="tab-system" class="HuiTab">
                <div class="tabBar cl">
                    <span>权限申请表单</span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">
                    <span class="c-red">*</span>
                    一级配置：
                </label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box">
                      <select class="select" size="1" id="one">
                          <option value="" id="mr" >选择</option>
                          @foreach($res as $k => $v)
                              <option value="{{json_encode($v)}}">{{$v['name']}}</option>
                          @endforeach
                      </select>
                    </span>
                </div>
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button class="btn btn-primary radius" id="add" type="button"><i class="Hui-iconfont">&#xe632;</i> 添加</button>
            </div>
        </div>

        {{csrf_field()}}
        <div class="row cl"  style="float: right;margin-right: 150px;">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 提交</button>
            </div>
        </div>
        <div class="row cl" style="margin-top:30px;">
            <div id="tab-system" class="HuiTab">
                <div class="tabBar cl">
                    <span>已经申请列表：</span>
                </div>
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

        $('#one').change(() => {
            if($('#one  option:selected').attr('id') == 'mr'){
                $('#threeRe').remove();
                $('#twoRe').remove();
                $('#oneRe').remove();
                $(".apply_auth_id").val('');
                return;
            }
            var v = JSON.parse($('#one').val());
            $('#oneRe').remove();
            $('#twoRe').remove();
            $(".apply_auth_id").val(v['id']);
            if(v['list'].length != 0){
                var str = '';
                var three = v['list'][0];
                for(i = 0; i< v['list'].length; i++){
                    if(i == 0){
                        $(".apply_auth_id").val(v['list'][i]['id']);
                    }
                    str += '<option value="'+i+'">'+v['list'][i]['name']+'</option>';
                }
                $('#par').append('' +
                    '       <div class="row cl" id="oneRe">\n' +
                    '            <label class="form-label col-xs-4 col-sm-2">\n' +
                    '                <span class="c-red">*</span>\n' +
                    '                二级配置：\n' +
                    '            </label>\n' +
                    '            <div class="formControls col-xs-8 col-sm-9">\n' +
                    '                <span class="select-box">\n' +
                    '                  <select class="select" size="1" id="two">\n' +
                    '                    '+str+'\n' +
                    '                  </select>\n' +
                    '                </span>\n' +
                    '            </div>\n' +
                    '        </div>'
                );
                if(three['list'].length != 0){
                    str = '';
                    for(i = 0; i< three['list'].length; i++){
                        if(i == 0){
                            $(".apply_auth_id").val(three['list'][i]['id']);
                        }
                        str += '<option value="'+JSON.stringify(three['list'][i])+'">'+three['list'][i]['name']+'</option>';
                    }
                    $('#par').append('' +
                        '       <div class="row cl" id="twoRe">\n' +
                        '            <label class="form-label col-xs-4 col-sm-2">\n' +
                        '                <span class="c-red">*</span>\n' +
                        '                三级配置：\n' +
                        '            </label>\n' +
                        '            <div class="formControls col-xs-8 col-sm-9">\n' +
                        '                <span class="select-box">\n' +
                        '                  <select class="select" size="1" id="three">\n' +
                        '                    '+str+'\n' +
                        '                  </select>\n' +
                        '                </span>\n' +
                        '            </div>\n' +
                        '        </div>'
                    );
                }
            }
        });

        $(document).on("change","#two",function(){
            var v = JSON.parse($('#one').val());
            var id = $('#two').val();
            var da = v['list'][id];
            $(".apply_auth_id").val(id);
            $('#twoRe').remove();
            if(da['list'].length != 0){
                str = '';
                for(i = 0; i< da['list'].length; i++){
                    if(i == 0){
                        $(".apply_auth_id").val(da['list'][i]['id']);
                    }
                    str += '<option value="'+i+'">'+da['list'][i]['name']+'</option>';
                }
                $('#par').append('' +
                    '       <div class="row cl" id="twoRe">\n' +
                    '            <label class="form-label col-xs-4 col-sm-2">\n' +
                    '                <span class="c-red">*</span>\n' +
                    '                三级配置：\n' +
                    '            </label>\n' +
                    '            <div class="formControls col-xs-8 col-sm-9">\n' +
                    '                <span class="select-box">\n' +
                    '                  <select class="select" size="1" name="demo1" id="three">\n' +
                    '                    '+str+'\n' +
                    '                  </select>\n' +
                    '                </span>\n' +
                    '            </div>\n' +
                    '        </div>'
                );
            }
        });
        $(document).on("change","#three",function(){
            var v = $('#three').val();
            $(".apply_auth_id").val(v);
        });

        $(document).on("click","#dele",function(){
            console.log($(this).parent().parent())
            $(this).parent().parent().remove()
        });


        $('#add').click(() => {
            var id = $(".apply_auth_id").val();
            if(!id){
                layer.msg('添加失败，不能为空',{icon:2,time:1000});
                return;
            }
            var isHas = false;
            $(".apply_auth_ids").each(
                function() {
                    if(id == $(this).val())
                        isHas = true;
                }
            )
            if(isHas){
                layer.msg('已经存在',{icon:2,time:1000});
                return;
            }

            str = $('#one option:selected').text()
            if($('#two option:selected').text()){
                str += '--'+$('#two option:selected').text()
            }
            if($('#three option:selected').text()){
                str += '--'+$('#three option:selected').text()
            }
            $('#form-article-add').append(
                '<div class="row cl">\n' +
                '            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2" style="margin-top:15px;">\n' +
                '                <p><button class="btn btn-danger radius" id="dele" type="button" style="margin-right:20px;">删除</button>'+str+'</p>\n' +
                '                <input type="hidden" class="apply_auth_ids" name="apply_auth_ids[]" value="'+id+'">\n' +
                '            </div>\n' +
                '        </div>'
                );


        });


        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
        $("#tab-system").Huitab({
            index:0
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
        $("#form-article-add").validate({
            //表单验证
            rules:{
                apply_auth_id:{
                    required:true,
                },
            },
            onkeyup:false,
            focusCleanup:true,
            success:"valid",
            submitHandler:function(form){
                $(form).ajaxSubmit({
                    type: 'post',
                    url: "/adminfour/member/apply" ,//自己提交给自己可以不写url
                    success: function(data){
                        if(data['code'] == 200){
                            layer.msg(data['message'],{icon:1,time:1000},function(){
                                window.location.reload();
                            });
                        }else if(data['code'] == -100){
                            layer.msg(data['message'],{icon:2,time:2000});
                        }else if(data['code'] == 300){
                            window.location.href = '/adminfour/index/index';
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
