<?php
/**
 * Created by jhz
 * Date: 2017/11/3
 * Time: 9:16
 * 活动发布主页
 */

/* 包含配置文件 */
require 'config.php';
/* appUrl为轻应用入口地址(主页地址)*/
$appUrl = isset($config['CallBack'])?$config['CallBack']:'javaScript:;';

/* 包含SDK */
try
{
 	 require("./classes/yb-globals.inc.php");
}
//catch exception,抛出异常，一般为服务器缺少相应模块
catch(Exception $e)
{
 	echo 'Message: ' .$e->getMessage();
}

//初始化,iapp代表当前app
$api = YBOpenApi::getInstance()->init($config['AppID'], $config['AppSecret'], $config['CallBack']);
$iapp  = $api->getIApp();

try {
   //轻应用获取access_token，未授权则跳转至授权页面
   $info = $iapp->perform();
}
catch (YBException $ex) {
    //重定向对app授权
    Header('Location: '.$appUrl);
    exit();
   echo $ex->getMessage();
}

//轻应用获取的token
$token = $info['visit_oauth']['access_token'];
?>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>邮动历</title>
    <!-- 自定义css -->
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/department_handler.css">
    <!-- FrozenUI模板样式 -->
    <link rel="stylesheet" href="./css/frozen.css">
    <script src="./lib/zepto.min.js"></script>
    <script src="./js/frozen.js"></script>
    <!-- 日期选项插件 -->
    <script src="./js/jquery.1.7.2.min.js"></script>
    <script src="./js/mobiscroll_002.js" type="text/javascript"></script>
    <script src="./js/mobiscroll_004.js" type="text/javascript"></script>
    <link href="./css/mobiscroll_002.css" rel="stylesheet" type="text/css">
    <link href="./css/mobiscroll.css" rel="stylesheet" type="text/css">
    <script src="./js/mobiscroll.js" type="text/javascript"></script>
    <script src="./js/mobiscroll_003.js" type="text/javascript"></script>
    <script src="./js/mobiscroll_005.js" type="text/javascript"></script>
    <link href="./css/mobiscroll_003.css" rel="stylesheet" type="text/css">
    <!-- 日历插件 -->
    <link href="./css/datepicker.min.css" rel="stylesheet" type="text/css">
    <script src="./js/datepicker.js"></script>
    <script src="./js/datepicker.zh.js"></script>
</head>

<body ontouchstart>
<div id="wrapper" class="ui-tab">

    <div id="container">
        <ul class="ui-tab-content">
            <!-- 1.主页,全部活动 -->
            <li>
                <div id="loading1" class="ui-loading-wrap" style="background-color: white;display: none;">
                    <p>正在加载中...</p>
                    <i class="ui-loading"></i>
                </div>
                <ul id="mainPage" class="ui-list ui-border-tb ui-list-active ui-list-cover" style="overflow-x:hidden; overflow-y:auto;height: 95%;">
                </ul>
            </li>
            <!-- 2.活动分类页面-->
            <li id="catalog-wrapper" style="">
            <ul id="catalog-list" class="ui-list ui-border-tb ui-list-active ui-list-cover" style="overflow-x:hidden; overflow-y:auto;height: 95%;">
            </ul>
            </li>
            <!-- 3.活动表单发布 -->
            <li style="">
                <div id="putterForm">
                </div>
            </li>
            <!-- 4.我的日历界面 -->
            <li style="background-color: white;">
                <div class="mycalendar">
                    <div id="custom-cells" class="datepicker-here" data-language='zh'></div>
                    <ul id="myacty" class="ui-list ui-list-pure ui-border-tb" style="overflow-x:hidden; overflow-y:auto;height: 300px;">
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <!-- 底部的导航栏 -->
    <div id="footer">
        <ul class="ui-tab-nav ui-border-b">
            <li id="main" class="current" >首页</li>
            <li id="catalog"  >分类</li>
            <li id="putter" >我要发布</li>
            <li id="calendar">我的日历</li>
        </ul>
    </div>
    <!-- token的传输的隐藏标签 -->
    <?php echo '<p id="tokentmp" style="display:none;">';echo $token;echo"</p>" ?>
    <!-- 首页活动标签的隐藏层 -->
    <div class="ui-dialog tagdetail" >
        <div class="ui-dialog-cnt" style="background-image: url('../img/bg.png');">

            <header class="ui-dialog-hd ui-border-b">
                <h1 class="tagheader">秋之韵</h1>
                <i class="ui-dialog-close" data-role="button"></i>
            </header>

            <div class="ui-dialog-bd" >
                <img class="tagimg" src="" alt="活动图片" height="130px" width="100%">
                <div>
                    <h3>活动时间</h3>
                    <p class="tagtime">11/13 12:00 - 11/13 12:00</p>

                    <h3 class="tagticketime">抢票时间:</h3>
                    <p class="tagticketime">11/13 12:00</p>

                    <h4 class="tagticketlink" style="display:inline;">抢票链接:</h4>
                    <a class="ui-txt-feeds tagticketlink" style="font-size: 15px;" href="#" target="_blank">请点击...</a><br>

                    <h3 style="display:inline;">活动地点:</h3>
                    <p class="tagplace" style="display:inline; font-size: 15px;">报告厅</p><br>


                    <h4 class="taglink" style="display:inline;">报名链接:</h4>
                    <a class="ui-txt-feeds taglink" style="font-size: 15px;" href="#" target="_blank">请点击...</a><br>

                    <h4 class="tagscore" style="display:inline;">德育分: </h4>
                    <p class="tagscore" style="display:inline;font-size: 15px;">2.0分</p> <br>

                    <h4 style="display:inline;">组织单位: </h4>
                    <p class="tagorg" style="display:inline;font-size: 15px;">学校 电子工程学院</p>
                </div>
            </div>

            <div class="ui-dialog-ft ui-btn-group">
                <button type="button" data-role="button"  class="select">取消</button>
                <button type="button"  id="addcalendar">加入日历</button><!-- data-role="button"  class="select" -->
            </div>
        </div>
    </div>
    <!-- 我的日历界面删除某个活动时的温馨提示层 -->
    <div class="ui-dialog deletenote">
        <div class="ui-dialog-cnt">
            <div class="ui-dialog-bd">
                <div>
                    <h4>温馨提示:</h4>
                    <div>真的要从日历中删除活动吗?</div>
                </div>
            </div>
            <div class="ui-dialog-ft ui-btn-group">
                <button type="button" data-role="button"  class="select" id="deleteyes">删除</button>
                <button type="button" data-role="button"  class="select" id="deleteno">取消</button>
            </div>
        </div>
    </div>
    <!-- 隐藏层结束 -->
</div>

<script>
    var $j=$.noConflict(),token = $('#tokentmp').html(),seed="";
    var times = 0,seed2 = "";
    //解析php返回的json中的组织单位
    function paseOrg(str) {
        switch(str)
        {
            case "sch":  return "学校";         case "xt":   return "信息与通信工程学院";    case "ee":  return "电子工程学院";
            case "cs" :  return "计算机学院";   case "lxy":  return "理学院";                case "zdh": return "自动化学院";
            case "rj" :  return "软件学院";     case "shm":  return"数字媒体与设计艺术学院"; case "wa":  return "网络空间安全学院";
            case "yz" :  return "现代邮政学院"; case "jg":   return"经济管理学院";           case "rw":  return "人文学院";
            case "gy" :  return "国际学院";     case "mks":  return "马克思主义学院";
        }
    }
    //主页标签的加载函数
    function getMainPage(){
        $j.get("actytag.php",
            {"mode":0},
            function (data,status) {
                if( status == "success" ){
                    data = JSON.parse(data);
                    for (var i=0; i<data.length; i++){
                        $j("#mainPage").append("<li class=\"ui-border-t tag\" id=\""+data[i].id+"\"></li>");
                        $j("li#"+data[i].id).append("<div class=\"ui-list-img\">" + "<span style=\"background-image:url("+data[i].aimg+");\"></span>" + "</div>");
                        var date = data[i].astart.replace("2017-",""),ticket = ( data[i].aticket == "ticket" ? "需要抢票" : "无需抢票" );
                        $j("li#"+data[i].id).append("<div class=\"ui-list-info\">" + "<h1 class=\"ui-nowrap\">"+data[i].aname+"</h1>" + "<p class=\"ui-nowrap\" style=\"display: block;\">"+date+"&emsp;"+ticket+"</p>" + "</div>");
                    }
                }else{
                }
            });
    }
    //标签详情的浮层加载函数
    function getTagDetails(mode,ID) {
        $j.get("actytag.php",
            {"id":ID,"mode":mode},
            function(data,status){
                if(status == 'success')
                {
                    seed = JSON.parse(data);//得到的是一个对象
                    console.log(seed);
                    //利用seed创建活动的详情页
                    $(".tagheader").html(seed.name);
                    $(".tagimg").attr("src",seed.img);
                    var date = new Date();
                    var year = date.getFullYear();
                    var end = seed.end.replace(year+"-","");
                    $(".tagtime").html( seed.start + " - " + end );
                    $(".tagplace").html(seed.place);
                    //报名链接
                    if( seed.link != "" ) {
                        $("h4.taglink").css("display","inline");
                        $("a.taglink").css("display","inline");
                        $("a.taglink").attr("href",seed.link);
                    } else {
                        $(".taglink").css("display","none");
                    }
                    //抢票的处理
                    if( seed.ticket == null ){
                        $j(".tagticketime").css("display","none");
                        $j(".tagticketlink").css("display","none");

                    }else{
                        console.log(seed.ticket_time);
                        $j("p.tagticketime").html(seed.ticket_time);
                        $j("a.tagticketlink").attr("href",seed.ticket_link);
                        $j("h3.tagticketime").css("display","block");
                        $j("p.tagticketime").css("display","block");
                        $j("h4.tagticketlink").css("display","inline");
                        $j("a.tagticketlink").css("display","inline");
                    }
                    //德育分
                    if( seed.score != 0 ) {
                        $(".tagscore").css("display","inline");
                        $("p.tagscore").html(seed.score);
                    } else {
                        $(".tagscore").css("display","none");
                    }
                    //组织单位
                    var orgtmp = seed.org.split(",");
                    var org = "";
                    for(var i=0; i<orgtmp.length; i++) {
                        org += paseOrg(orgtmp[i])+"&ensp;";
                    }
                    $(".tagorg").html(org);/* */
                    $('#addcalendar').html("加入日历");
                    $(".tagdetail").dialog("show");
                }
            });
    }
    //点击我的日历界面和后的处理,返回json:1.哪些天有活动eventDates 2.seed创建标签的种子
    function getActivities(date,success,fail){
        $j.get("mycalendar.php",
            {"date":date,"token":token},
            function (data,status) {
                if(status == 'success') {
                    success(data);
                }else{
                    fail(data);
                }});
    }
    function createActyTags(seed){
        console.log( "-----------creating----------" );
        $j('#myacty').empty();
        for( var i=0; i<seed.length; i++){
            ss = seed[i];
            console.log( ss );
            var $ul = $j("#myacty"),$li=$j("<li class=\"ui-border-t\"></li>");
            $ul.append($li);
            var tmpyear = new Date().getFullYear();
            var end = ss.aend.replace( tmpyear+"-","" );
            var $h2 = $j("<div class=\"myacty-nav\"><h2 class=\"myacty-title\" style='width: 70px;display: inline-block;'>"+ss.aname+"</h2>"+"<span class=\"myacty-time\">"+ss.astart+" 到 "+end+"</span></div>");
            $li.append($h2);
            var $div = $j("<div class=\"myacty-detail\" style=\"display: none;\"></div>");
            var $h31= $j("<h3 class=\"myacty-place\">活动地点:</h3>");
            var $h32 = $j("<h4 class=\"myacty-score\"></h>");
            $li.append($div);
            $div.append($h31).append("<span>"+ss.aplace+"</span><br>");
            if( ss.aticket == "ticket"){
                $h32 = $j("<h3 class=\"myacty-ticket\">抢票时间:</h3>");
                $div.append($h32).append("<span>"+ss.aticket_time+"</span><br>");
                $h33 = $j("<h3 class=\"myacty-ticket\">抢票链接:</h3>");
                $div.append($h33).append("<a target='_blank' href='"+ss.aticket_link+"'>请点击"+"</a><br>");
            }
            var $h41 = $j("<h3 class=\"myacty-score\">德育分:</h3>"); $div.append($h41);
            if( ss.ascore != "0.00"){
                $div.append("<span>"+ss.ascore+"</span><br>");
            }else{
                $div.append("<span>无德育分</span><br>");
            }
            var $h42 = $j("<h4 class=\"myacty-org\">组织单位:</h>");
            var orgtmp4 = ss.aorg.split(",");
            console.log( "orgtmp4: " + orgtmp4);
            var org4 = "";
            for(var j=0; j<orgtmp4.length; j++) {
                org4 += paseOrg(orgtmp4[j])+"&ensp;";
            }
            $div.append($h42).append("<span>"+org4+"</span>");
            $div.append("<div style=\"padding-top: 0;padding-left: 0;height: 10px;\" class=\"ui-btn-wrap\">" + "<button id=\"d"+ss.id+"\" class=\"ui-btn ui-btn-primary deletebtn\">" + "删除活动" + "</button>" + "</div>");
        }
        $j(".myacty-nav").click(function () {
            $j(this).next().slideToggle("slow");
        });
    }
    //整个页面的加载
    window.addEventListener('load', function(){
        /* --------------0导航栏---------------- */
        //0.1导航栏配置
        var tab = new fz.Scroll('.ui-tab', {
            role: 'tab',
            autoplay: false
        });
        //0.2导航栏转换刷新
        tab.on('beforeScrollStart', function(from, to) {
            if(from == 0 && to == 0){
                times++;
            }
            if( from == 0 && to != 0){
                times --;
            }
            if( $j("#catalog").html() == "返回" && from == 1 ) {
                $j(".catalog-org").remove();
                $j(".departments").show();
                $j("#catalog").html("分类");
            }
            if( from == 2 && to == 0 || times == 2){
                $j("#loading1").slideDown(300);
                $j("#mainPage").empty();
                getMainPage();
                $j("#loading1").slideUp(300);
            }
            if(times == 2 ){ times = 0;}
        });
        /* --------------1主页---------------- */
        //1.加载主页的标签
        getMainPage();
        //1.1主页标签详情的浮层加载
        $j("#mainPage").on("tap",".tag",function(){
            var id = $(this).attr('id');
            getTagDetails(1,id);
        });
        //1.2用户选择某个活动加入数据库,重复加入返回提示
        $('#addcalendar').click(function () {
            $('#addcalendar').html("正在发送请求...");
            $j.get("actytag.php",
                {"mode":2,"actyid":seed.id,"token":token},
                function (data,status) {
                    if(status == 'success') {
                        if(data == "success") {
                            $('#addcalendar').html("加入成功!");
                        }else if(data == "repeated"){
                            $('#addcalendar').html("活动已加入!");
                        }
                        setTimeout(function () {
                            $(".tagdetail").removeClass("show");
                        },1200);
                    }
                });
        });
        /* --------------2分类---------------- */
        //2.加载分类页面
        var departments = ['sch','xt','ee','cs','lxy','zdh','rj','shm','wa','yz','jg','gy','mks',"rw"];
        $j('#catalog-list').empty();
        for(var i=0; i<departments.length; i++) {
            var name = paseOrg(departments[i]);
            $ul2 = $j('#catalog-list');
            $ul2.append("<li class=\"ui-border-t departments\" id="+departments[i]+"></li>");
            $li2 = $j("#"+departments[i]);
            $li2.append("<div class=\"ui-list-thumb\"></div>");
            $li2.children("div .ui-list-thumb").append("<span style=\"background-image:url(../img/logo/"+departments[i]+".png)\"></span>");
            $li2.append("<div class=\"ui-list-info\"></div>");
            $li2.children("div .ui-list-info").append("<h2 class=\"ui-nowrap\" style=\"position:relative;text-align: center;\">"+name+"</h2>");
        }
        //2.1在分类页面点击各院标签的处理
        $j(".departments").click(function () {
            $j("#catalog").html("返回");
            $j(".departments").hide();
            $j.get("catalog.php",
                {"org":$j(this).attr('id')},
                function (data,status) {
                    if(status == 'success') {
                        //创建分类页面
                        console.log("------------catalog click back-------------");
                        var acty = JSON.parse(data).acty;
                        $ul2 = $j('#catalog-list');
                        for(var i=0; i<acty.length; i++){
                            $ul2.append("<li class=\"ui-border-t catalog-org\" id=\"a"+acty[i].id+"\"></li>");
                            $li2 = $j("#a"+acty[i].id);
                            $li2.append("<div class=\"ui-list-img\"></div>");
                            $li2.children("div .ui-list-img").append("<span style=\"background-image:url("+acty[i].aimg+");\"></span>");
                            $li2.append("<div class=\"ui-list-info\"></div>");
                            var tmpyear = new Date().getFullYear();
                            var end = acty[i].aend.replace( tmpyear+"-","" );var start = acty[i].astart.replace( tmpyear+"-","" );
                            var date = start +"到"+end;
                            $li2.children("div .ui-list-info").append("<h1 class=\"ui-nowrap\">"+acty[i].aname+"</h1>").append("<p class=\"ui-nowrap\" style=\"display: block;\">"+date+"&emsp;需要抢票 </p>");
                        }
                    }
                });
        });
        //2.1.1每个院下面的活动标签点击
        $j("#catalog-list").on("tap",".catalog-org",function () {
            console.log("------------2.院下活动标签点击--------------");
            var id = $(this).attr('id');
            id = id.replace('a','');
            getTagDetails(1,id);
        });
        /* --------------3我要发布---------------- */
        //3.加载发布页面
        $('#putterForm').load('putter.html');
        //3.1点击我要发布,传递token到发布活动部分
        $('#putter').click(function () {
            $('#token').attr('value',token);
        });
        /* --------------4我的日历---------------- */
        //4.1我的日历页面点击后
        $("#calendar").click(function () {
            var date = new Date();
            var year = date.getFullYear(),month = date.getMonth()+1,currentDate = date.getDate(),daytmp=date.getDate();
            if(month < 10){ month = "0"+month;}
            if(daytmp <10){daytmp = "0"+daytmp;}
            var DATE = year+"-"+month+"-"+daytmp;
            getActivities(DATE,
                function (data) {
                    data = JSON.parse(data);
                    var eventDates = data.eventDates,$picker = $j('#custom-cells'),$content=$j('#content');
                    for(var i=0; i<eventDates.length; i++) {
                        eventDates[i] = Number(eventDates[i]);
                    }
                    console.log("初始化请求成功 ");
                    console.log(eventDates);

                    $picker.datepicker({
                        language: 'zh',
                        onRenderCell: function (date, cellType) {
                            console.log("onRenderCell初始化创建");
                            var currentDay = date.getDate();
                            //对有活动的日期加上小点
                            if ( cellType == 'day' && eventDates.indexOf(currentDay) != -1 ) {
                                //console.log(currentDate);
                                return {
                                    html: currentDay + '<span class="dp-note"></span>'
                                }
                            }
                        },
                        onSelect: function onSelect(fd, date) {
                            console.log("selected  "+fd+date);
                            getActivities(fd,function (data) {
                                console.log("----------selected成功返回数据-----------");
                                data = JSON.parse(data);
                                console.log(data);
                                createActyTags(data.seed);
                            }),function () {
                                console.log("selected请求失败2");
                            }
                        }
                    });
                    //当前天选中涂蓝
                    $picker.data('datepicker').selectDate(new Date(date.getFullYear(), date.getMonth(), date.getDate()));
                    //创建标签
                    createActyTags(data.seed);
                },
                function (data) {
                    console.log("初始化请求失败");
                })
        });
        //4.2当选择删除某元素时
        $j("#myacty").on("tap",".deletebtn",function () {
            $(".deletenote").dialog("show");
            var id = $j(this).attr("id");
            var btn = $j(this);
            $("#deleteyes").tap(function () {
                id = id.replace("d","");
                console.log(id);
                $j.get("actytag.php",{"actyid":id,"mode":"42","token":$('#tokentmp').html()},
                function (data,status) {
                    if( status == "success" ){
                        btn.html(data);
                        setTimeout(function () {
                            $("#calendar").click();
                        },600);

                    }
                });
            });
        });
    });

</script>
</body>
</html>