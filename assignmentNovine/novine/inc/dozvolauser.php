<?php
if(!login()){
    echo "<center>";
    echo "<h1>Morate biti ulogovani da biste videli ovu stranicu!!!!!</h1>";
    echo "<a href='../login/login.php'>Prijavite se</a>";
    echo "</center>";
    exit();
}
if($_SESSION['status']!='Administrator'){
    echo "<center>";
    echo "<h1>Morate biti ulogovani kao 'Administrator' da biste videli ovu stranicu!!!!!</h1>";
    echo "<a href='../content.php'>Nazad</a>";
    echo "</center>";
    exit();
}