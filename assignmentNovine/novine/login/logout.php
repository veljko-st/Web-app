<?php
    session_start();
    require_once("../inc/obaveznifajlovi.php");
    Log::upisi("../logs/".date("Y-m-d")."_logovanja.log", "Uspešna odjava za korisnika '{$_SESSION['podaci']}'");
    session_unset();
    session_destroy();
    setcookie("id", "", time()-1, "/");
    setcookie("podaci", "", time()-1, "/");
    setcookie("status", "", time()-1, "/");
                                                
    header("Location: ../index.php");
?>