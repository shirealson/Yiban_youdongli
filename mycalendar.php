<?php
/**
 * Created by jhz.
 * Date: 2017/11/14
 * Time: 16:32
 * 4.我的日历页面的处理
 */
$token = isset($token) ? $token : $_GET['token'];
/* 包含SDK */
require("./classes/yb-globals.inc.php");
// 配置文件
require_once 'config.php';
//初始化配置信息，并获取token
$api = YBOpenApi::getInstance()->init($config['AppID'], $config['AppSecret'], $config['CallBack']);
$api->bind($token);
//获得用户id
$userinfo = $api->request('user/me');
$uid = $userinfo['info']['yb_userid'];
//数据库
$servername = "haozhe";
$username = "jhz";
$password = "280137";
$dbname = "yiban";
$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error."<br>");
}
//按用户id在users数据库查询该用户在对应时间的活动id,然后通过活动id和时间在putter数据库中查活动详情
$actyid = array();
class acty{
    var $eventDates=array();//该用户哪天有活动
    var $seed=array();//该用户加入的所有的活动的详情
    var $log=array();
}
$a = new acty();
array_push($a->log,$_GET['date']);
$result = $conn->query("SELECT actyid FROM users WHERE uid=".$uid);
if( $result->num_rows > 0 ){//查询该用户的活动id
    while( $row = $result->fetch_assoc() ) {
        array_push($actyid,$row['actyid']);
    }
}
if ( count($actyid) != 0 ) {
    $actyid = implode(',', $actyid);
    $day = "aa";
    $result = $conn->query("SELECT id,aname,aplace,aorg,astart,aend,ascore,aticket,aticket_time,aticket_link FROM putter WHERE id IN ({$actyid})");
    if ($result->num_rows > 0) {//查询该用户的活动id
        while ($row = $result->fetch_assoc()) {
            $day = $row['astart'][8].$row['astart'][9];
            if ( !in_array($day,$a->eventDates) ){
                array_push( $a->eventDates,$day );
            }
           if( strpos( $row['astart'],$_GET['date'] ) !== false ) {
               array_push( $a->seed,$row );
           }
        }
    }
}
echo json_encode($a);
$conn->close();
?>