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
<link href="/admin/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/admin/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<link href="/admin/static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="/admin/lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="/admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>后台登录</title>
<meta name="keywords" content="H-ui.admin v3.1,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="H-ui.admin v3.1，是一款由国人开发的轻量级扁平化网站后台模板，完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">
</header>
<script>
  window.history.go(1);
</script>
<body style="overflow:-Scroll;overflow-x:hidden">
<input type="hidden" id="TenantId" name="TenantId" value="" />
<!-- <div class="header"></div> -->
<!-- <div class="loginWraper" style="background:#e0e5e8"> -->
<div class="loginWraper" style="background:url({{$system->background_url}});background-size: 100% 100%;
                background-position: center center;
                overflow: auto;">

  <div class="row cl" style="margin-left:70px;line-height:41px;margin-top:40px;margin-right:0;">
      <label class="form-label col-xs-8"><img src="{{$system->logo_url}}" alt="" style="height:80px;width:190px;"></label>
  </div>


  <div id="loginform" class="loginBox" style="padding-top:0;border-bottom:2px solid #6b8db6;width:500px;height:280px;border-right:2px solid #6b8db6;top:55%;left:70%">
    <div class="formControls  toptitle" style="text-align:center;top:10px">
      <h4 style="padding-top:10px;margin-bottom:40px;color:#49d3de;margin-left:20px;font-size: 27px;font-weight:50px;">{{$system->web_title}}</h4>
    </div>
    <form class="form form-horizontal" action="/sms/login" method="post">
        <input type="hidden" name="isVisitor" value="0">
        <div class="row cl" style="margin-left:-80px;">
          <label class="form-label col-xs-3"><i class="Hui-iconfont" style="color:#f7f7f7">&#xe60d;</i></label>
          <div class="formControls col-xs-8">
            <input id="" name="mobile" type="text" placeholder="手机号" style="border: 2px solid #49d3de;
    border-radius: 5px;background-color:#141b47;color:#fff" class="input-text size-L mobile">
          </div>
        </div>

        <div class="row cl" style="margin-left:-80px;">
            <label class="form-label col-xs-3"><i class="Hui-iconfont" style="color:#f7f7f7"></i></label>
            <div class="formControls col-xs-4">
                <input type="button" id="sms" class="btn btn-success radius size-L" style="background-color:#49d3de;border:0;color:#000;" id="sms" placeholder="" value="获取验证码" class="input-text">
            </div>
          <div class="formControls col-xs-4">
            <input id="" name="captch"  type="text" placeholder="输入验证码" style="border: 2px solid #49d3de;
    border-radius: 5px;background-color:#141b47;color:#fff;width: 160px;" class="input-text size-L">
          </div>
        </div>
  <!--       <div class="row cl">
          <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe63f;</i></label>
          <div class="formControls col-xs-8">
            <input class="input-text size-L" type="text" name="captcha" placeholder="验证码" onblur="if(this.value==''){this.value='验证码:'}" onclick="if(this.value=='验证码:'){this.value='';}" value="验证码:" style="width:150px;">
            <img src="{{ captcha_src() }}"> <a id="kanbuq" href="javascript:;">看不清，换一张</a> </div>
        </div> -->
        {{csrf_field()}}
        <!-- <div class="row cl">
          <div class="formControls col-xs-8 col-xs-offset-3">
            <label for="online">
              <input type="checkbox" name="online" id="online" value="1">
              使我保持登录状态</label>
          </div>
        </div> -->
        <div class="row cl">
          <div class="formControls col-xs-8 col-xs-offset-3">
            <input name="" type="submit" class="btn btn-success radius size-L" style="background-color:#49d3de;border:0;color:#000;margin-left:-50px" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
            <a href="/" style="float:right;margin-top:10px;margin-right: 13px;color: white">账号密码登录</a>
            <a href="/register" style="float:right;margin-right:20px;margin-top:10px;;color: white">注册</a>
            <a id="isVisitor" href="javascript:void(0);" style="float:right;margin-right:20px;margin-top:10px;color: white">游客登录</a>
{{--            <input name="" type="reset" class="btn btn-default radius size-L" style="float:right;margin-right:10px;background-color:#141b47;border:0;color:#49d3de" value="&nbsp;注&nbsp;&nbsp;&nbsp;&nbsp;测&nbsp;">--}}
{{--            <input name="" type="reset" class="btn btn-default radius size-L" style="float:right;margin-right:10px;background-color:#141b47;border:0;color:#49d3de" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">--}}
          </div>
        </div>
    </form>
  </div>
</div>
<div style="color:#fff;font-size:15px;text-align:center;position:fixed;bottom:0;width:100%;z-index:100;padding-bottom:30px;">{{$system->company}}</div>
<script type="text/javascript" src="/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/admin/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript">
  //jquery的载入事件
  $(function(){
    //给kanbuq绑定点击事件
    var src = $('img').attr('src');
    $('#kanbuq').click(function(){
      //获取验证码的地址,为了改变缓存而添加
      $('img').attr('src',src + '&_=' + Math.random() );
    });

    //以javascript弹窗形式输出错误的内容
     @if(count($errors) > 0)
        var allError = '';
        @foreach ($errors->all() as $error)
        allError+="{{ $error}}</br>";
       @endforeach
       //使用alert会很丑，可以使用layer插件进行美化，需要引入layer.js文件
       layer.alert(allError,{title:'错误提示',icon:5});
    @endif
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

  $('#isVisitor').click(function () {
      $.ajax({
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          type: 'post',
          data: {'isVisitor':'1'},
          url: "/admin/public/check" ,//自己提交给自己可以不写url
          success: function(data){
              if(data['code'] == 200){
                  layer.msg(data['message'],{icon:1,time:1000},function(){
                      window.location.href = data['data'];
                  });
              }else{
                  layer.msg(data['message'],{icon: 2,time:1000});
              }
          },
          error: function(XmlHttpRequest, textis_nav, errorThrown){
          }
      });
  });

</script>
</body>
</html>
