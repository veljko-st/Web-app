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
                    <h1>Login</h1>
                    <form action="#" method="post">
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
                        <input type="checkbox" name="zapamti" class="pass"> Zapamti me na ovom računaru <br>    <br>
                        <input type="submit" name="login" value="Login">
                        <div class="signup_link">
                            Not a member? <a href="singup.php">Sign up</a><br>
                            
                        </div>
                    </form>
                    <?php 
                        if(isset($_POST['email']) AND isset($_POST['lozinka']))
                        {
                            $email=trim($_POST['email']);
                            $lozinka=trim($_POST['lozinka']);
                            if($email!="" AND $lozinka!="")
                            {
                                $email = mysqli_real_escape_string($db, $email);
                                $lozinka = mysqli_real_escape_string($db, $lozinka);

                                if($email AND $lozinka)//proveri
                                {
                                    $upit="SELECT * FROM korisnici WHERE email='{$email}'";
                                    $rez=mysqli_query($db, $upit);
                                    if(mysqli_num_rows($rez)==1)
                                    {
                                        $red=mysqli_fetch_object($rez);
                                        //$red=$db->fetch_object($rez);
                                        if($red->lozinka==$lozinka)
                                        {
                                            if($red->aktivan==1)
                                            {
                                                $_SESSION['id']=$red->id;
                                                $_SESSION['podaci']=$red->ime." ".$red->prezime;
                                                $_SESSION['status']=$red->status;
                                                Log::upisi("../logs/".date("Y-m-d")."_logovanja.log", "Uspešna prijava za korisnika '{$_SESSION['podaci']}'");
                                                if(isset($_POST['zapamti']))
                                                {
                                                    setcookie("id", $_SESSION['id'], time()+3600, "/");
                                                    setcookie("podaci", $_SESSION['podaci'], time()+3600, "/");
                                                    setcookie("status", $_SESSION['status'], time()+3600, "/");
                                                    
                                                }
                                                //echo "uspesno prijavljen";
                                                header("Location: ../index.php");
                                            }
                                            else
                                            {
                                                $poruka = Poruka::info("Podaci su ispravni, ali je korisnik neaktivan!<br>{$red->komentar}");
                                                Log::upisi("../logs/".date("Y-m-d")."_logovanja.log", "Podaci su ispravni, ali je korisnik '{$red->email}' neaktivan!");
                                            }
                                                
                                        }
                                        else
                                        {
                                            $poruka = Poruka::greska("Pogrešna lozinka za korisnika '{$email}'!" );
                                            Log::upisi("../logs/".date("Y-m-d")."_logovanja.log", "Pogrešna lozinka za korisnika '{$email}'");
                                        }
                                            
                                    }
                                    else
                                    {
                                        $poruka = Poruka::greska("Korisnik sa email adresom '{$email}' nije registrovan!" );
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
                        $poruka = Poruka::info("Dobrodošli na stranicu za prijavu");
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