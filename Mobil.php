<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "rental_mobil";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

$Id_Mobil   = "";
$Nama       = "";
$Jenis      = "";
$Plat_Nomor = "";
$Harga_Sewa = "";
$Status     = "";

$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $Id_Mobil  = $_GET['id'];
    $sql1       = "delete from Mobil where Id_Mobil = '$Id_Mobil'";
    $q1         = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error  = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $Id_Mobil  = $_GET['id'];
    $sql1       = "select * from Mobil where Id_Mobil = '$Id_Mobil'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $Nama       = $r1['Nama'];
    $Jenis      = $r1['Jenis'];
    $Plat_Nomor = $r1['Plat_Nomor'];
    $Harga_Sewa = $r1['Harga_Sewa'];
    $Status     = $r1['Status'];

    if ($Nama == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $Nama          = $_POST['nama'];
    $Jenis         = $_POST['jenis'];
    $Plat_Nomor    = $_POST['plat_nomor'];
    $Harga_Sewa    = $_POST['harga_sewa'];
    $Status        = $_POST['status'];

    if ($Nama && $Jenis && $Plat_Nomor && $Harga_Sewa && $Status) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update Mobil set Nama='$Nama', Jenis = '$Jenis', Plat_Nomor = '$Plat_Nomor', Harga_Sewa = '$Harga_Sewa', Status = '$Status' where Id_Mobil = '$Id_Mobil'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1   = "insert into Mobil(Nama, Jenis, Plat_Nomor, Harga_Sewa, Status) values ('$Nama', '$Jenis', '$Plat_Nomor', '$Harga_Sewa', '$Status')";
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
    <title>Data Mobil</title>
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
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Create / Edit Mobil
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:3;url=Mobil.php"); //3 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:3;url=Mobil.php");
                }
                ?>
                <form action="" method="POST">

                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama Mobil</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $Nama ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="jenis" class="col-sm-2 col-form-label">Jenis</label>
                        <div class="col-sm-10">
                            <select name="jenis" class="form-control" id="Jenis_Mobil">
                                <option value="">-Pilih Jenis Mobil</option>
                                <option value="SUV (Sport Utility Vehicle)" <?php if ($Jenis == "SUV (Sport Utility Vehicle)") echo "selected" ?>>SUV (Sport Utility Vehicle)</option>
                                <option value="MPV (Multi Purpose Vehicle)" <?php if ($Jenis == "MPV (Multi Purpose Vehicle)") echo "selected" ?>>MPV (Multi Purpose Vehicle)</option>
                                <option value="Hatchback" <?php if ($Jenis == "Hatchback") echo "selected" ?>>Hatchback</option>
                                <option value="Sedan" <?php if ($Jenis == "Sedan") echo "selected" ?>>Sedan</option>
                                <option value="City Car" <?php if ($Jenis == "City Car") echo "selected" ?>>City Car</option>
                                <option value="LCGC (Low Cost Green Car)" <?php if ($Jenis == "LCGC (Low Cost Green Car)") echo "selected" ?>>LCGC (Low Cost Green Car)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="plat_nomor" class="col-sm-2 col-form-label">Plat Nomor</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" value="<?php echo $Plat_Nomor ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="harga_sewa" class="col-sm-2 col-form-label">Harga Sewa(Hari)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="harga_sewa" name="harga_sewa" value="<?php echo $Harga_Sewa ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="Status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-control" id="status">
                                <option value="">-Pilih Status Mobil</option>
                                <option value="Tersedia" <?php if ($Status == "Tersedia") echo "selected" ?>>Tersedia </option>
                                <option value="Dalam Penyewaan" <?php if ($Status == "Dalam Penyewaan") echo "selected" ?>>Dalam Penyewaan </option>
                            </select>
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
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "select * from Mobil";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $Id_Mobil           = $r2['Id_Mobil'];
                            $Nama               = $r2['Nama'];
                            $Jenis              = $r2['Jenis'];
                            $Plat_Nomor         = $r2['Plat_Nomor'];
                            $Harga_Sewa         = $r2['Harga_Sewa'];
                            $Status             = $r2['Status'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $Id_Mobil ?></th>
                                <td scope="row"><?php echo $Nama ?></td>
                                <td scope="row"><?php echo $Jenis ?></td>
                                <td scope="row"><?php echo $Plat_Nomor ?></td>
                                <td scope="row"><?php echo $Harga_Sewa ?></td>
                                <td scope="row"><?php echo $Status ?></td>
                                <td scope="row">
                                    <a href="Mobil.php?op=edit&id=<?php echo $Id_Mobil ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="Mobil.php?op=delete&id=<?php echo $Id_Mobil ?>" onclick="return confirm('Yakin?')"><button type="button" class="btn btn-danger">Delete</button></a>
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