<?php
session_start();
require_once('../inc/obaveznifajlovi.php');
require_once("../funkcije.php");
if (!$db = konekcija()) exit();
statistika($db);
$poruka = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <title>NEWS</title>
</head>

<body>

<header>
    <nav class="navigation">
      <div class="logotype">News Velja</div>
      <ul class="categories">
        <li class="category-item"><a href='../index.php'>Početna</a></li>
        <?php $upit = "SELECT * FROM kategorije";
        $rez = mysqli_query($db, $upit);
        while ($red = mysqli_fetch_assoc($rez)) {
            echo "<li class='category-item'><a href='../index.php?kategorija={$red['id']}'>{$red['naziv']}</a></li> ";
        }  ?>
      </ul>
    </nav>
        <div class="search">
        </div>
    </header>
    <div class="wrapper">
        <aside class="sidebar">
            <ul class="sidebar-list">
            </ul>
        </aside>
        <main class="content">
            <div class="feed-grid">
                <div class="center">
                    <h1>Sing Up</h1>
                    <form action="#" method="post">
                    <div class="txt_field">
                            <input type="text" name="ime">
                            <span></span>
                            <label>Name</label>
                        </div>
                        <div class="txt_field">
                            <input type="text" name="prezime">
                            <span></span>
                            <label>Last Name</label>
                        </div>
                        <div class="txt_field">
                            <input type="text" name="email">
                            <span></span>
                            <label>Email</label>
                        </div>
                        <div class="txt_field">
                            <input type="text" name="lozinka">
                            <span></span>
                            <label>Password</label>
                        </div>
                        <input type="submit" name="login" value="Sing Up">
                        <div class="signup_link">
                            You have account? <a href="login.php">Login</a><br>
                            
                        </div>
                    </form>
                    <?php 
                        if(isset($_POST['ime']) AND isset($_POST['prezime']) AND isset($_POST['email']) AND isset($_POST['lozinka']))
                        {
                            $ime=trim($_POST['ime']);
                            $prezime=trim($_POST['prezime']);
                            $email=trim($_POST['email']);
                            $lozinka=trim($_POST['lozinka']);
                            if($ime!="" AND $prezime!="" AND $email!="" AND $lozinka!="")
                            {
                                $ime = mysqli_real_escape_string($db, $ime);
                                $prezime = mysqli_real_escape_string($db, $prezime);
                                $email = mysqli_real_escape_string($db, $email);
                                $lozinka = mysqli_real_escape_string($db, $lozinka);

                                if($email AND $lozinka)//proveri
                                {
                                    $upit="INSERT INTO korisnici (ime, prezime, email, lozinka) VALUES ('{$ime}','{$prezime}','{$email}','{$lozinka}')";
                                    $rez=mysqli_query($db, $upit);
                                    if(!mysqli_errno($db))
                                    {
                                        $id= mysqli_insert_id($db);
                                        $poruka= Poruka::uspeh("Uspešno dodat korisnik sa id: {$id}");
                                        Log::upisi("../logs/".date("Y-m-d")."_korisnici.log", "Uspešno dodat korisnik sa id: {$id} od strane {$email}");    
                                    }
                                    else
                                    {
                                        $poruka = Poruka::greska("Greska pri prijavljivanju!" );
                                        Log::upisi("../logs/".date("Y-m-d")."_logovanja.log", "Korisnik sa email adresom '{$email}' nije registrovan");
                                    }
                                        
                                }
                                else
                                {
                                    $poruka = Poruka::greska("Podaci sadrže nedozvoljene karaktere!!!!");
                                    Log::upisi("../logs/".date("Y-m-d")."_logovanja.log", "Podaci sadrže nedozvoljene karaktere: {$email} {$lozinka} - {$_SERVER['REMOTE_ADDR']}");
                                }

                            }
                            else
                            $poruka = Poruka::greska("Svi podaci su obavezni");
                        }
                        else
                        $poruka = Poruka::info("Dobrodošli na stranicu za registraciju");
                    ?>
                    <div id="center"><?php echo $poruka?></div>
                </div>             
            </div>
                </div>
            </div>
        </main>
    </div>

</body>

</html>