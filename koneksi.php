<?php
    $user = 'root';
    $host = 'localhost';
    $db = 'laravel';

    $con = mysqli_connect($host,$user,"",$db);

    if(!$con){
        echo 'Koneksi DB Gagal';
    }

?>