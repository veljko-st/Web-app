        <?php
        $upit = "SELECT * FROM kategorije";
        $rez = mysqli_query($db, $upit);
        while ($red = mysqli_fetch_assoc($rez)) {
            echo "<li class='category-item'><a href='index.php?kategorija={$red['id']}'>{$red['naziv']}</a></li> ";
        } 
        ?>
