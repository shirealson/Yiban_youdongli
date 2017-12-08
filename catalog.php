<?php
/**
 * Created by jhz.
 * Date: 2017/11/15
 * Time: 22:42
 * 2.活动分类界面的处理
 */
$servername = "haozhe";
$username = "jhz";
$password = "280137";
$dbname = "yiban";
$conn = new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error)
    die("Connection failed: ".$conn->connect_error."<br>");

class catalog{
    var $acty = array();
    var $log = array();
}
$catalog = new catalog();
$result = $conn->query("SELECT id,aname,aimg,aplace,astart,aend,ascore,aticket,aorg FROM putter WHERE aorg REGEXP '{$_GET['org']}'");
array_push($catalog->log,$result->num_rows);
if( $result->num_rows > 0 ){
    while($row = $result->fetch_assoc()){
        array_push($catalog->acty,$row);
    }
}
echo json_encode($catalog);
$conn->close();
?>