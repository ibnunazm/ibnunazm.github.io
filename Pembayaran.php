<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rental_mobil";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

$Id_Trasaksi    = "";
$Id_Client      = "";
$Id_Pegawai     = "";
$Id_Client1      = "";
$Id_Pegawai1     = "";
$Total_Harga    = "";
$Lama_Peminjaman = "";
$Harga_Sewa = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $Id_Transaksi  = $_GET['id'];
    $sql1          = "delete from pembayaran where Id_Transaksi = '$Id_Transaksi'";
    $q1             = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error  = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $Id_Transaksi  = $_GET['id'];
    $sql1           = "select * from pembayaran where Id_Transaksi = '$Id_Transaksi'";
    $q1             = mysqli_query($koneksi, $sql1);
    $r1             = mysqli_fetch_array($q1);
    $Id_Client      = $r1['Id_Client'];
    $Id_Pegawai     = $r1['Id_Pegawai'];
    $Lama_Peminjaman = $r1['Lama_Peminjaman'];

    if ($Id_Transaksi == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $Id_Client       = $_POST['id_client'];
    $Id_Pegawai     = $_POST['id_pegawai'];
    $Lama_Peminjaman    = $_POST['lama_peminjaman'];
    
    $sql2   = "select Mobil.harga_sewa from menyewa join mobil on menyewa.id_mobil = mobil.id_mobil where mobil.id_mobil in (select id_mobil from menyewa where id_client in ( select id_client from client where id_client = '$Id_Client'));";
    $q2     = mysqli_query($koneksi, $sql2);
    $r2     = mysqli_fetch_array($q2);
    $harga_sewa = $r2['harga_sewa'];
    $sql4   = "select T_Harga('$Lama_Peminjaman', '$harga_sewa');";
    $q4     = mysqli_query($koneksi, $sql4);
    $r4     = mysqli_fetch_array($q4);
    $Total_Harga = $r4["T_Harga('$Lama_Peminjaman', '$harga_sewa')"];

    if ($Id_Client && $Id_Pegawai && $Lama_Peminjaman) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update Pembayaran set Id_Client='$Id_Client', Id_Pegawai = '$Id_Pegawai', Total_Harga = '$Total_Harga', Lama_Peminjaman = '$Lama_Peminjaman' where Id_Transaksi = '$Id_Transaksi'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1   = "insert into Pembayaran(Id_Pegawai, Id_Client,  Lama_Peminjaman, Total_Harga) values('$Id_Pegawai', '$Id_Client', '$Lama_Peminjaman', '$Total_Harga')";
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
    <title>Data Transaksi</title>
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
        <a href="https://ibnunazm.github.io/"><button class="btn btn-primary">❮ Kembali ke halaman utama</button></a>
        <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:3;url=pembayaran.php"); //3 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:3;url=pembayaran.php");
                }
                ?>
        <div class="card">
            <div class="card-header text-white" style="background-color: #cc9c75;">
                Data Pegawai
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No Telp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "select * from Pegawai";
                        $q2     = mysqli_query($koneksi, $sql2);
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $Id_Pegawai1         = $r2['Id_Pegawai'];
                            $Nama               = $r2['Nama'];
                            $Alamat             = $r2['Alamat'];
                            $No_Telp             = $r2['No_Telp'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $Id_Pegawai1 ?></th>
                                <td scope="row"><?php echo $Nama ?></td>
                                <td scope="row"><?php echo $Alamat ?></td>
                                <td scope="row"><?php echo $No_Telp ?></td>
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
                            <th scope="col">Harga Sewa/hari</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql7   = "select Menyewa.Id_Menyewa, Menyewa.Id_Mobil, Menyewa.Id_Client, Menyewa.Tanggal_Sewa, Mobil.harga_sewa from menyewa join mobil on menyewa.id_mobil = mobil.id_mobil where mobil.id_mobil in ( select id_mobil from menyewa where id_client in ( select id_client from client where id_client = id_client));";
                        $q7     = mysqli_query($koneksi, $sql7);
                        $urut   = 1;
                        while ($r7 = mysqli_fetch_array($q7)) {
                            $Id_Menyewa                 = $r7['Id_Menyewa'];
                            $Id_Mobil                   = $r7['Id_Mobil'];
                            $Id_Client1                 = $r7['Id_Client'];
                            $Tanggal_Sewa               = $r7['Tanggal_Sewa'];
                            $Harga_Sewa                 = $r7['harga_sewa'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $Id_Menyewa ?></th>
                                <td scope="row"><?php echo $Id_Mobil ?></td>
                                <td scope="row"><?php echo $Id_Client1 ?></td>
                                <td scope="row"><?php echo $Tanggal_Sewa ?></td>
                                <td scope="row"><?php echo $Harga_Sewa ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header text-white bg-secondary">
                Create / Edit Pembayaran
            </div>
            <div class="card-body">
                
                <form action="" method="POST">

                    <div class="mb-3 row">
                        <label for="id_pegawai" class="col-sm-2 col-form-label">Id Pegawai</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_pegawai" name="id_pegawai" value="<?php echo $Id_Pegawai ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="id_client" class="col-sm-2 col-form-label">Id Client</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_client" name="id_client" value="<?php echo $Id_Client ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="lama_peminjaman" class="col-sm-2 col-form-label">Lama Peminjaman (Hari)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="lama_peminjaman" name="lama_peminjaman" value="<?php echo $Lama_Peminjaman ?>">
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
                Data Pembayaran
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Id Pegawai</th>
                            <th scope="col">Id Client</th>
                            <th scope="col">lama Peminjaman (Hari)</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql3   = "select * from Pembayaran";
                        $q3     = mysqli_query($koneksi, $sql3);
                        $urut   = 1;
                        while ($r3 = mysqli_fetch_array($q3)) {
                            $Id_Transaksi           = $r3['Id_Transaksi'];
                            $Id_Pegawai             = $r3['Id_Pegawai'];
                            $Id_Client              = $r3['Id_Client'];
                            $Lama_Peminjaman        = $r3['Lama_Peminjaman'];
                            $Total_Harga            = $r3['Total_Harga'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $Id_Transaksi ?></th>
                                <td scope="row"><?php echo $Id_Pegawai ?></td>
                                <td scope="row"><?php echo $Id_Client ?></td>
                                <td scope="row"><?php echo $Lama_Peminjaman ?></td>
                                <td scope="row"><?php echo $Total_Harga ?></td>
                                <td scope="row">
                                    <a href="pembayaran.php?op=edit&id=<?php echo $Id_Transaksi ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="pembayaran.php?op=delete&id=<?php echo $Id_Transaksi ?>" onclick="return confirm('Yakin?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</body>

</html>
