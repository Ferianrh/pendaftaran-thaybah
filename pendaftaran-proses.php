<?php
include('koneksi.php');
include('fungsi.php');


    if(isset($_REQUEST['simpan'])){
        $username = $_REQUEST['username'];
        $no_ktp = $_REQUEST['no_ktp'];
        $nama_lengkap = $_REQUEST['nama_lengkap'];
        $nama_panggilan = $_REQUEST['nama_panggilan'];
        $nama_kunyah = $_REQUEST['nama_kunyah'];
        $tempat_lahir = $_REQUEST['tempat_lahir'];
        $tanggal_lahir = date('Y-m-d', strtotime($_REQUEST['tanggal_lahir']));
        $kota_asal = $_REQUEST['kota_asal'];
        $alamat_asal = $_REQUEST['alamat_asal'];
        $no_hp = $_REQUEST['no_hp'];
        $email = $_REQUEST['email'];
        $nama_kampus = $_REQUEST['nama_kampus'];
        $jurusan = $_REQUEST['jurusan'];
        $strata = $_REQUEST['strata'];
        $tahun_angkatan = $_REQUEST['tahun_angkatan'];
        $date = date('Y-m-d');
        $generate = substr($nama_lengkap,0,3).substr($tanggal_lahir,-2);
        $options = [
            'rounds' => 10,
        ];
        $password = password_hash($generate,PASSWORD_BCRYPT,$options);
        $admin_url = 'http://localhost:8000/';


        //keluarga calon santri
        $nama_ayah = $_REQUEST['nama_ayah'];
        $alamat_ayah = $_REQUEST['alamat_ayah'];
        $pekerjaan_ayah = $_REQUEST['pekerjaan_ayah'];
        $no_hp_ayah = $_REQUEST['no_hp_ayah'];
        $nama_ibu = $_REQUEST['nama_ibu'];
        $alamat_ibu = $_REQUEST['alamat_ibu'];
        $pekerjaan_ibu = $_REQUEST['pekerjaan_ibu'];
        $no_hp_ibu = $_REQUEST['no_hp_ibu'];
        $nama_wali = $_REQUEST['nama_wali'];
        $alamat_wali = $_REQUEST['alamat_wali'];
        $pekerjaan_wali = $_REQUEST['pekerjaan_wali'];
        $no_hp_wali = $_REQUEST['no_hp_wali'];


        //get id Periode
        $idPeriode = 'select id_periode from periode 
                        order by id_periode desc LIMIT 1';
        $dataId = mysqli_fetch_assoc(mysqli_query($con,$idPeriode));

        if ((($_FILES["image"]["type"] == "image/jpg") || ($_FILES["image"]["type"] == "image/jpeg"))
        && ($_FILES["image"]["size"] < 2000000)
        // && in_array($extension, $allowedExts)
        ) {
            if ($_FILES["image"]["error"] > 0) {
                echo "Return Code: " . $_FILES["image"]["error"] . "<br>";
            } else {
                // echo '<div class="plus">';
                // echo "Uploaded Successully";
                // echo '</div>';
                // echo "<br/><b><u>Image Details</u></b><br/>";
                // echo "Name: " . $_FILES["image"]["name"] . "<br/>";
                // echo "Type: " . $_FILES["image"]["type"] . "<br/>";
                // echo "Size: " . ceil(($_FILES["image"]["size"] / 1024)) . " KB";
                if (file_exists("img/" . $_FILES["image"]["name"])) {
                    unlink("img/" . $_FILES["image"]["name"]);
                } else {
                    $pic = $_FILES["image"]["name"];
                    $conv = explode(".", $pic);
                    $ext = $conv[1];
                    move_uploaded_file($admin_url, "images/users/" . $username . "." . $ext);
                    $url = $username . "." . $ext;
                    $insertUser = "INSERT INTO users (`id_role`, `username`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) values(2,'$username','$password',null,'$date','$date',null)";
                    $storeUser = mysqli_query($con,$insertUser) or die(mysqli_error($con));
                    $last_id = mysqli_insert_id($con);
                    $insertSantri = "INSERT INTO CALON_SANTRI (`id_user`, `id_periode`, `no_ktp`, `nama_lengkap`, `nama_panggilan`, `nama_kunyah`, `tempat_lahir`, `tanggal_lahir`, `kota_asal`, `alamat_asal`, `no_hp`, `email`, `nama_kampus`, `jurusan`, `strata`, `tahun_angkatan`, `foto_profil`, `penerimaan_santri`, `created_at`, `updated_at`, `deleted_at`) values(
                        $last_id,
                        '".$dataId['id_periode']."',
                        '$no_ktp',
                        '$nama_lengkap',
                        '$nama_panggilan',
                        '$nama_kunyah',
                        '$tempat_lahir',
                        '$tanggal_lahir',
                        '$kota_asal',
                        '$alamat_asal',
                        '$no_hp',
                        '$email',
                        '$nama_kampus',
                        '$jurusan',
                        '$strata',
                        '$tahun_angkatan',
                        '$url',
                        'PENDING',
                        '$date',
                        '$date',
                        null
                    )";
                    $storeSantri = mysqli_query($con,$insertSantri) or die(mysqli_error($con));
                    $last_id = mysqli_insert_id($con);
                    $insertKeluarga = "INSERT INTO KELUARGA_SANTRIS (`id_calonsantri`, `nama_ayah`, `alamat_ayah`, `pekerjaan_ayah`, `no_hp_ayah`, `nama_ibu`, `alamat_ibu`, `pekerjaan_ibu`, `no_hp_ibu`, `nama_wali`, `alamat_wali`, `pekerjaan_wali`, `no_hp_wali`, `created_at`, `updated_at`, `deleted_at`) values(
                        '$last_id',
                        '$nama_ayah',
                        '$alamat_ayah',
                        '$pekerjaan_ayah',
                        '$no_hp_ayah',
                        '$nama_ibu',
                        '$alamat_ibu',
                        '$pekerjaan_ibu',
                        '$no_hp_ibu',
                        '$nama_wali',
                        '$alamat_wali',
                        '$pekerjaan_wali',
                        '$no_hp_wali',
                        '$date',
                        '$date',
                        null
                    )";
                    $storeKeluarga = mysqli_query($con, $insertKeluarga) or die(mysqli_error($con));
                    if ($storeUser && $storeSantri && $storeKeluarga) {
                        echo "<script type='text/javascript'>alert('Pendaftaran Berhasil');window.location.href='index.html';</script>";
                    }else{
                        echo mysqli_error($storeUser);
                        
                    }
                }
            }
        } else {
            echo "File Size Limit Crossed 200 KB Use Picture Size less than 200 KB";
        }
    }

?>
