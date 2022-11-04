<?php
function konekcija(){
    $db=@mysqli_connect("localhost","root", "", "news");
    if(!$db)
    {
        echo "Greška prilikom konekcije na bazu!!!!!<br>";
        echo mysqli_connect_error()."<br>";
        echo mysqli_connect_errno();
        return false;
    }
    mysqli_query($db, "SET NAMES utf8");
    return $db;
}

function validanString($str){
    if(strlen($str)<3) return false;
    $nedozvoljeni=array("="," ","(",")","'",'"');
    foreach($nedozvoljeni as $v)
        if(strpos($str, $v)!==false)return false;
    return true;
}

function statistika($db, $tekst=NULL){
    $upit="INSERT INTO statistika (ipadresa, stranica, parametri, tekst) VALUES ('{$_SERVER['REMOTE_ADDR']}', '{$_SERVER['SCRIPT_NAME']}', '{$_SERVER['QUERY_STRING']}', '{$tekst}')";
    mysqli_query($db, $upit);
    if(mysqli_error($db))
        echo "Došlo je do greške!!!<br>".mysqli_error($db)."<br>".$upit."<br>";
}

function login(){
    if(isset($_SESSION['id']) and isset($_SESSION['podaci']) AND isset($_SESSION['status']))
        return true;
    else if(isset($_COOKIE['id']) AND isset($_COOKIE['podaci']) AND isset($_COOKIE['status']))
        {
            $_SESSION['id']=$_COOKIE['id'];
            $_SESSION['podaci']=$_COOKIE['podaci'];
            $_SESSION['status']=$_COOKIE['status'];
            return true;
        }
    else
        return false;
}

?>