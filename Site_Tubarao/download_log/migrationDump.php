<?php
/**
 * This file is a standalone mysqldump script which is uploaded automatically
 * to the remote host in the case that a direct mysql connecion cannot be made.
 * 
 * This file is not executed locally.
 *
 * @author jgalley
 */
ini_set('error_reporting',E_ERROR);
ini_set('display_errors', false);
header('Content-Type: text/plain; charset=UTF-8');

$required=array(
    'host',
    'user',
    'pass',
    'name'
);
foreach($required as $req){
    if(!isset($_REQUEST[$req])){
        die('ERROR:601: missing post feld '.$req);
    }
}

$conn=false;
$creds=false;
$output='';

if(connect(
        $_REQUEST['host'],
        $_REQUEST['user'],
        $_REQUEST['pass'],
        $_REQUEST['name'])){
    echo dump(true);
}else{
    die('ERROR:602: cant connect');
}

function connect($host,$user,$pass,$db){
    global $conn;
    global $creds;
    
    $conn=getConnection($host, $user, $pass, $db); 
    if($conn){
        $creds['host']=$host;
        $creds['user']=$user;
        $creds['pass']=$pass;
        $creds['db']=$db;
        return true;
    }else{
        $conn=false;
        return false;
    }
}

function dump($dtie=false){
    global $creds;
    global $output;

    // Set $dtie flag for adding drop table statments.
    $output.="-- PHP MySQL Dump\n-- Version 1.0";
    $output.="\n-- Dumped on   : ".date("Y-m-d H:i:s");
    $output.="\n-- Dumped from : ".$creds['user']."@".$creds['host'];
    $output.="\n-- From DB w/  : ".$creds['db']." with pass '".$creds['pass']."'";
    $output.="\n-- For Domain  : ";
    $output.="\n\n-- DUMP BEGIN";
    $this->output.="\n\nSET NAMES utf8;";
    $this->output.="-- DUMPING...\n";
    
    $tables=list_tables();
    foreach($tables as $table){
        dump_table($table,$dtie);
    }
    $output.="\n\n-- DUMP END";
    return $output;
}


function getConnection($host,$user,$pass,$db){
    $conn=mysql_connect($host, $user, $pass);
    mysql_select_db($db);
    return $conn;
}

function list_tables() {
    global $conn;
    
    if(!$conn){return false;}
    $tables=array();
    $sql="SHOW TABLES";
    $res=mysql_query($sql,$conn);
    while($row=mysql_fetch_array($res)) {
        array_push($tables,$row[0]);
    }
    return $tables;
}

function list_databases(){
    global $conn;
   
    if(!$conn){return false;}
    $dbs=array();
    $sql="SHOW DATABASES";
    $res=mysql_query($sql,$conn);
    while($row=mysql_fetch_array($res)){
        array_push($dbs,$row[0]);
    }
    return $dbs;
}

function list_values($tablename){
    global $output;
    global $conn;
    
    $sql="SELECT * FROM ".$tablename;
//        $res=mysql_query($sql,$conn);
    $res=mysql_query($sql,$conn);
    $output.="\n\n-- Dumping data for table: $tablename\n\n";
//        while($row=mysql_fetch_array($res)) {
    while($row=mysql_fetch_array($res)) {
        $b=count($row)/2;
        $output.="INSERT INTO `$tablename` VALUES(";
        $buffer='';
        for($i=0;$i < $b;$i++){
            $v=$row[$i];
            if(!is_integer($v)){
                $v="'".addslashes($v)."'";   
            } 
            $buffer.=$v.', ';
        }
        $buffer=substr($buffer,0,count($buffer)-3);
        $output.=$buffer.");\n";
        $output.="-- DUMPING...\n";
    }	
}

function dump_table($tablename,$dtie) {
    get_table_structure($tablename,$dtie);	
    list_values($tablename);
}

function get_table_structure($tablename,$dtie) {
    global $conn;
    global $output;
    
    $output.="\n\n-- Dumping structure for table: ".$tablename."\n\n";
    if($dtie){
        $output .= "DROP TABLE IF EXISTS `".$tablename."`;\n";
        $output.="-- DUMPING...\n";
    }
    $sql="SHOW CREATE TABLE `".$tablename."`";
    $res=mysql_query($sql,$conn);
    $row=mysql_fetch_assoc($res);
//    $output.=$row['Create Table'].";\n";
    $this->output.=preg_replace(
                '/DEFAULT CHARSET=(.*);/',
                '/DEFAULT CHARSET=utf8;/',
                $row['Create Table'],
                1)
                .";\n";
    $output.="-- DUMPING...\n";
}

?>      
