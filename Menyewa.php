<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rental_mobil";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

$Id_Menyewa      = "";
$Id_Client       = "";
$Id_Mobil        = "";
$Id_Client1       = "";
$Id_Mobil1        = "";
$Tanggal_Sewa    = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $Id_Menyewa    = $_GET['id'];
    $sql1          = "delete from Menyewa where Id_Menyewa = '$Id_Menyewa'";
    $q1            = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error  = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $Id_Menyewa     = $_GET['id'];
    $sql1           = "select * from Menyewa where Id_Menyewa = '$Id_Menyewa'";
    $q1             = mysqli_query($koneksi, $sql1);
    $r1             = mysqli_fetch_array($q1);
    $Id_Client      = $r1['Id_Client'];
    $Id_Mobil       = $r1['Id_Mobil'];
    $Tanggal_Sewa    = $r1['Tanggal_Sewa'];

    if ($Tanggal_Sewa == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $Id_Client          = $_POST['id_client'];
    $Id_Mobil           = $_POST['id_mobil'];
    $Tanggal_Sewa       = $_POST['tanggal_sewa'];

    if ($Id_Client && $Id_Mobil && $Tanggal_Sewa) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update Menyewa set Id_Client='$Id_Client', Id_Mobil = '$Id_Mobil', Tanggal_Sewa = '$Tanggal_Sewa' where Id_Menyewa = '$Id_Menyewa'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1   = "insert into Menyewa(Id_Mobil, Id_Client,  Tanggal_Sewa) values('$Id_Mobil', '$Id_Client', '$Tanggal_Sewa')";
            $q1     = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses     = "Berhasil memasukkan data baru";
            } else {
                $error      = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Menyewa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <br>
        <a href="http://127.0.0.1:5500/LandingPage.html"><button class="btn btn-primary">❮ Kembali ke halaman utama</button></a>

        <div class="card-body">
            <?php
            if ($error) {
            ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error ?>
                </div>
            <?php
                header("refresh:3;url=Menyewa.php"); //3 : detik
            }
            ?>
            <?php
            if ($sukses) {
            ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses ?>
                </div>
            <?php
                header("refresh:3;url=Menyewa.php");
            }
            ?>
            <div class="card">
                <div class="card-header text-white" style="background-color: #cc9c75;">
                    Data Mobil
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nama Mobil</th>
                                <th scope="col">Jenis</th>
                                <th scope="col">Plat Nomor</th>
                                <th scope="col">Harga Sewa(Hari)</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2   = "select * from Mobil";
                            $q2     = mysqli_query($koneksi, $sql2);
                            $urut   = 1;
                            while ($r2 = mysqli_fetch_array($q2)) {
                                $Id_Mobil1           = $r2['Id_Mobil'];
                                $Nama               = $r2['Nama'];
                                $Jenis              = $r2['Jenis'];
                                $Plat_Nomor         = $r2['Plat_Nomor'];
                                $Harga_Sewa         = $r2['Harga_Sewa'];
                                $Status             = $r2['Status'];

                            ?>
                                <tr>
                                    <th scope="row"><?php echo $Id_Mobil1 ?></th>
                                    <td scope="row"><?php echo $Nama ?></td>
                                    <td scope="row"><?php echo $Jenis ?></td>
                                    <td scope="row"><?php echo $Plat_Nomor ?></td>
                                    <td scope="row"><?php echo $Harga_Sewa ?></td>
                                    <td scope="row"><?php echo $Status ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header text-white" style="background-color: #cc9c75;">
                    Data Client
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">No Telp</th>
                                <th scope="col">Jaminan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2   = "select * from Client";
                            $q2     = mysqli_query($koneksi, $sql2);
                            $urut   = 1;
                            while ($r2 = mysqli_fetch_array($q2)) {
                                $Id_Client1          = $r2['Id_Client'];
                                $Nama               = $r2['Nama'];
                                $Alamat             = $r2['Alamat'];
                                $No_Telp            = $r2['No_Telp'];
                                $Jaminan            = $r2['Jaminan'];

                            ?>
                                <tr>
                                    <th scope="row"><?php echo $Id_Client1 ?></th>
                                    <td scope="row"><?php echo $Nama ?></td>
                                    <td scope="row"><?php echo $Alamat ?></td>
                                    <td scope="row"><?php echo $No_Telp ?></td>
                                    <td scope="row"><?php echo $Jaminan ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>

            <br>
            <div class="card">
                <div class="card-header text-white bg-secondary">
                    Create / Edit Menyewa
                </div><br>
                <div class="card-body">
                    <form action="" method="POST">

                        <div class="mb-3 row">
                            <label for="id_mobil" class="col-sm-2 col-form-label">Id Mobil</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="id_mobil" name="id_mobil" value="<?php echo $Id_Mobil ?>">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="id_client" class="col-sm-2 col-form-label">Id Client</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="id_client" name="id_client" value="<?php echo $Id_Client ?>">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="tanggal_sewa" class="col-sm-2 col-form-label">Tanggal_Sewa</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="tanggal_sewa" name="tanggal_sewa" value="<?php echo $Tanggal_Sewa ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                        </div>
                    </form>
                </div>

            </div>
            <!-- untuk mengeluarkan data -->
            <div class="card">
                <div class="card-header text-white bg-secondary">
                    Data Menyewa
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Id Mobil</th>
                                <th scope="col">Id Client</th>
                                <th scope="col">Tanggal Sewa</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql2   = "select * from Menyewa";
                            $q2     = mysqli_query($koneksi, $sql2);
                            $urut   = 1;
                            while ($r2 = mysqli_fetch_array($q2)) {
                                $Id_Menyewa             = $r2['Id_Menyewa'];
                                $Id_Mobil               = $r2['Id_Mobil'];
                                $Id_Client              = $r2['Id_Client'];
                                $Tanggal_Sewa           = $r2['Tanggal_Sewa'];

                            ?>
                                <tr>
                                    <th scope="row"><?php echo $Id_Menyewa ?></th>
                                    <td scope="row"><?php echo $Id_Mobil ?></td>
                                    <td scope="row"><?php echo $Id_Client ?></td>
                                    <td scope="row"><?php echo $Tanggal_Sewa ?></td>
                                    <td scope="row">
                                        <a href="Menyewa.php?op=edit&id=<?php echo $Id_Menyewa ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                        <a href="Menyewa.php?op=delete&id=<?php echo $Id_Menyewa ?>" onclick="return confirm('Yakin?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                    </table>
                    <br>
                </div>
            </div>
        </div>
</body>
</html>