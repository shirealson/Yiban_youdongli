<?php
/**
 * Created by jhz
 * Date: 2017/11/6
 * Time: 12:46
 * 3.活动发布表单处理
 */
$token = isset($token) ? $token : $_REQUEST['token'];
/* 包含SDK */
require("./classes/yb-globals.inc.php");
// 配置文件
require_once 'config.php';
//初始化配置信息，并获取token
$api = YBOpenApi::getInstance()->init($config['AppID'], $config['AppSecret'], $config['CallBack']);
$api->bind($token);
//获取用户信息
class user{
    var $uid ;//易班id
    var $uname;//用户名
    var $usex;//性别
    var $usid;//学校id
    var $usname;//学校名称
    function __construct($putter)
    {
        $this->uid = $putter['info']['yb_userid'];
        $this->uname = $putter['info']['yb_username'];
        $this->usex = $putter['info']['yb_sex'];
        $this->usid = $putter['info']['yb_schoolid'];
        $this->usname = $putter['info']['yb_schoolname'];
    }
}
$user = new user($api->request('user/me'));
//var_dump($user);
//var_dump($_REQUEST);
//获取活动信息
class activity{
    var $name;
    var $place;
    var $org;
    var $img;
    var $start;
    var $end;
    var $score;  //数组,是否有德育分
    var $link;   //数组,报名链接
    var $ticket;
    var $ticket_time;
    var $ticket_link;
    function __construct()
    {
        $this->name = $_REQUEST['activity_name'];
        $this->place = $_REQUEST['activity_place'];
        $this->start = $_REQUEST['activity_start'];
        $this->end= $_REQUEST['activity_end'];
        $this->score = $_REQUEST['scoretxt'];
        $this->link = $_REQUEST['infotxt'];
        $this->ticket = $_REQUEST['ticket'];
        $this->ticket_time = $_REQUEST['ticket-time'];
        $this->ticket_link = $_REQUEST['ticket-link'];
        //活动所属组织复选框的处理
        $this->org = array();
        foreach($_REQUEST['org'] as $item)
        {
            if($item != "")
            {
                array_push($this->org,$item);
            }
        }
        $this->org = implode(',',$this->org);
        //上传图片的处理
        if ($_FILES["file"]["error"] > 0)
        {
            echo "图片上传错误: " . $_FILES["file"]["error"] . "<br>";
        }
        else
        {
            global $user;                                                        //用户信息类
            $extension = end( explode(".", $_FILES["activity_img"]["name"]) );//上传文件的扩展名
            //若目录不存在则创建目录，每个组织单位一个目录，目录下图片名用uid+时间(到秒）表示
            $upath = './img/';
            foreach($_REQUEST['org'] as $item)
            {
                if($item != "")
                {
                    $upath = $upath.$item.'/';
                    break;
                }
            }
            if( !file_exists ( $upath ) ){
                mkdir($upath);
            }
            $this->img = $upath.$user->uid."_".time().".".$extension;
            move_uploaded_file($_FILES["activity_img"]["tmp_name"],$this->img);
        }
    }
}
$acty = new activity();
//var_dump($acty->org);
//echo "<br>";

//链接数据库
$servername = "haozhe";
$username = "jhz";
$password = "280137";
$dbname = "yiban";
$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error)
    die("Connection failed: ".$conn->connect_error."<br>");
$stmt = $conn->prepare("INSERT INTO putter (aname,aplace,aorg,aimg,astart,aend,ascore,alink,aticket,uid,uname,usex,aticket_time,aticket_link) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param('ssssssdssissss',$acty->name,$acty->place,$acty->org,$acty->img,$acty->start,$acty->end,$acty->score,$acty->link,$acty->ticket,$user->uid,$user->uname,$user->usex,$acty->ticket_time,$acty->ticket_link);
$stmt->execute();

$stmt->free_result();
$stmt->close();
$conn->close();
?>