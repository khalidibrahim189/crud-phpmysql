<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "akademik";

$koneksi = mysqli_connect($host, $user, $password, $db);

if (!$koneksi) {
    //cek koneksi 
    die("Tidak terhubung");
} else {
    // echo "Berhasil Terhubung";
}

$nim = "";
$nama = "";
$jenis_kelamin = "";
$jurusan = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') { //hapus data
    $id = $_GET['id'];
    $sql1 = "delete from mahasiswa where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);

    if ($q1) {
        $sukses = "Data Berhasil dihapus";
    } else {
        $error = "Cie gagal";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "select * from mahasiswa where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);

    $r1 = mysqli_fetch_array($q1);
    $nim = $r1['nim'];
    $nama = $r1['nama'];
    $jenis_kelamin = $r1['jenis_kelamin'];
    $jurusan = $r1['jurusan'];

    if ($nim == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $jurusan = $_POST['jurusan'];

    if ($nim && $nama && $jenis_kelamin && $jurusan) {
        if ($op == 'edit') { //untuk update
            $sql1 = "update mahasiswa set nim = '$nim', nama = '$nama', jenis_kelamin = '$jenis_kelamin', jurusan = '$jurusan' where id = '$id'";
            $q1 = mysqli_query($koneksi, $sql1);

            if ($q1) {
                $sukses = "Data Anda Berhasil diedit";
            } else {
                $error = "Gagal diupdate";
            }
        } else { //untuk insert
            $sql1 = "insert into mahasiswa(nim,nama,jenis_kelamin,jurusan) values ('$nim','$nama','$jenis_kelamin','$jurusan') ";
            $q1   = mysqli_query($koneksi, $sql1);

            if ($q1) {
                $sukses =  "Berhasil menambah data baru";
            } else {
                $error = "Gagal menambah data";
            }
        }
    } else {
        $error = "Masukan kembali data anda!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendataan Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="mx-auto mt-4">
            <!-- untuk memasukan sebuah data -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Create / Edit Data
                </div>
                <div class="card-body">
                    <?php
                    if ($error) {
                    ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error ?>
                        </div>
                    <?php

                        header("refresh:5;url=index.php"); // 5 detik
                        
                    }
                    ?>

                    <?php
                    if ($sukses) {
                    ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $sukses ?>
                        </div>
                    <?php

                        header("refresh:5;url=index.php"); // 5 detik

                    }
                    ?>

                    <form action="" method="POST">
                        <div class="mb-3 row">
                            <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                            <div class="col-sm-10">
                                <input type="text" name="nim" id="nim" class="form-control" value="<?php echo $nim ?>">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $nama ?>">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="jenis_kelamin" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-10">
                                <input type="text" name="jenis_kelamin" id="jenis_kelamin" class="form-control" value="<?php echo $jenis_kelamin ?>">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="jurusan" class="col-sm-2 col-form-label">Jurusan</label>
                            <div class="col-sm-10">
                                <input type="text" name="jurusan" id="jurusan" class="form-control" value="<?php echo $jurusan ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <input type="submit" name="simpan" class="btn btn-danger mt-3" value="Simpan Data">
                        </div>
                    </form>
                </div>
            </div>

            <!-- menampilkan data -->
            <div class="card mt-4">
                <div class="card-header bg-danger text-white">
                    Data Mahasiswa
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">NIM</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jenis Kelamin</th>
                                <th scope="col">Jurusan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $sql2 = "select * from mahasiswa order by id desc";
                            $q2 = mysqli_query($koneksi, $sql2);
                            while ($r2 = mysqli_fetch_array($q2)) {
                                $id = $r2['id'];
                                $nim = $r2['nim'];
                                $nama = $r2['nama'];
                                $jenis_kelamin = $r2['jenis_kelamin'];
                                $jurusan = $r2['jurusan'];

                            ?>
                                <tr>
                                    <th scope="row"><?php echo $id++ ?></th>
                                    <td scope="row"><?php echo $nim ?></td>
                                    <td scope="row"><?php echo $nama ?></td>
                                    <td scope="row"><?php echo $jenis_kelamin ?></td>
                                    <td scope="row"><?php echo $jurusan ?></td>
                                    <td scope="row">
                                        <a href="index.php?op=edit&id=<?php echo $id ?>"><button class="btn btn-danger">Edit</button></a>
                                        <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau hapus data?')"><button class="btn btn-warning">Hapus</button></a>

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
    </div>

</body>

</html>