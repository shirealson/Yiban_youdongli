<?php
/**
 * Created by jhz.
 * Date: 2017/11/12
 * Time: 13:36
 * 1.首页加载时标签的详情查询与用户选择活动时加入user数据库,用mode来控制
 */
$servername = "haozhe";
$username = "jhz";
$password = "280137";
$dbname = "yiban";
$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error)
    die("Connection failed: ".$conn->connect_error."<br>");
if( $_GET['mode'] == '0' ){//首页加载时整个标签的查询
    $init = array();
    $results = $conn->query("SELECT id,aname,aimg,astart,aend,ascore,aticket FROM putter  ORDER BY id DESC");
    if ($results->num_rows > 0) {
        while ($row = $results->fetch_assoc()) {
            array_push($init,$row);
        }
    }
    echo json_encode($init);
    $conn->close();
}
else if( $_GET['mode'] == '1' )//单个标签详情的查询
{
    $result = array("id"=>"","name"=>"","place"=>"","org"=>"","img"=>"","start"=>"","end"=>"","score"=>"","link"=>"","ticket"=>"","ticket_time"=>"","ticket_link");
    $stmt = $conn->prepare("SELECT id,aname,aplace,aorg,aimg,astart,aend,ascore,alink,aticket,aticket_time,aticket_link FROM putter WHERE id=?");
    $stmt->bind_param('i',$_GET['id']);
    $stmt->bind_result($result['id'],$result['name'],$result['place'],$result['org'],$result['img'],$result['start'],$result['end'],$result['score'],$result['link'],$result['ticket'],$result['ticket_time'],$result['ticket_link']);
    $stmt->execute();
    $stmt->store_result();
    if( $stmt->num_rows > 0 ) {
        $stmt->fetch();
        echo json_encode($result);
    }
    $stmt->close();
    $conn->close();
}
else {//用户加入单个活动
    $token = isset($token) ? $token : $_GET['token'];
    /* 包含SDK */
    require("./classes/yb-globals.inc.php");
    // 配置文件
    require_once 'config.php';
    //初始化配置信息，并获取token
    $api = YBOpenApi::getInstance()->init($config['AppID'], $config['AppSecret'], $config['CallBack']);
    $api->bind($token);
    //获取用户信息
    class user{
        var $actyid;//活动标签在putter中的id
        var $uid ;//易班id
        var $uname;//用户名
        var $usex;//性别
        var $usid;//学校id
        var $usname;//学校名称
        function __construct($putter){
            $this->uid = $putter['info']['yb_userid'];
            $this->uname = $putter['info']['yb_username'];
            $this->usex = $putter['info']['yb_sex'];
            $this->usid = $putter['info']['yb_schoolid'];
            $this->usname = $putter['info']['yb_schoolname'];
            $this->actyid = $_GET['actyid'];
        }
    }
    $user = new user($api->request('user/me'));
    if( $_GET['mode'] != '42' ){

    $result = $conn->query("SELECT * FROM users WHERE actyid=".$user->actyid);//有注入可能
    if($result->num_rows != 0)
    {
        echo "repeated";
    }else{
        $stmt = $conn->prepare("INSERT INTO users (uid,uname,usex,usid,actyid) VALUES (?,?,?,?,?)");
        $stmt->bind_param('issii',$user->uid,$user->uname,$user->usex,$user->usid,$user->actyid);
        $stmt->execute();
        echo "success";
        $stmt->close();
    }
    $conn->close();

    }//end if( $_GET['mode'] != '42' ){
    else{//我的日历页面删除活动
        $conn->query("DELETE FROM users WHERE uid={$user->uid} AND actyid={$user->actyid}");
        $result = $conn->query("SELECT * FROM users WHERE uid={$user->uid} AND actyid={$user->actyid}");
        if( $result->num_rows == 0 ){
            echo "删除成功!";
        }
    }
}
?>