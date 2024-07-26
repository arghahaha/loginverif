<?php

include('dbcon.php');
include('authentication.php');
$page_title="Cari Page";
include('includes/header.php'); 
include('includes/navbar.php');

// Memeriksa apakah tombol cari telah ditekan
$isSearchClicked = isset($_GET['kategori1']);
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Cari Password</h4>
                    </div>
                    <div class="card-body">
                    <h4>Your Account :</h4>
                    <hr>
                    <h5>Username: <?= $_SESSION['auth_user']['username']; ?></h5>
                    <h5>Email: <?= $_SESSION['auth_user']['email']; ?></h5>

                    <hr>
                    <br>
                    
                    
                    <form action="cari.php" method="get">
                        <table>
                            <tr>
                                <td>Kategori</td>
                                <td>:</td>
                                <td>
                                <select name="kategori1">
                                    <option value="Pribadi">--Pilih Kategori--</option> 
                                    <option value="Pribadi">Pribadi</option> 
                                    <option value="Media Sosial">Media Sosial</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><input type="submit" value="Cari password" /></td>
                            </tr>
                        </table>
                    </form>

                    <br>
                    
                    <?php 
                    // Tabel akan muncul ketika tombol cari ditekan
                    if ($isSearchClicked) { ?>


                        <table cellpadding="5" border="1">
                        <tr>
                        <th>Nomor</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Password</th>
                        </tr>

                        <?php
                                // Mengambil nama pengguna yang sedang terautentikasi (login)
                                $namaPengguna = $_SESSION['auth_user']['username'];

                                // Query untuk mengambil data dari tabel password_gen berdasarkan kategori dan nama pengguna
                                $query = "SELECT * FROM password_gen WHERE kategori LIKE '%" . $_GET['kategori1'] . "%' AND name = '$namaPengguna'";

                                $result = mysqli_query($con, $query);

                                if (!$result) {
                                    die("Query Error: " . mysqli_errno($con) . " - " . mysqli_error($con));
                                }

                                // Membuat nomor pada baris tabel
                                $no = 1;

                                // Membuat perulangan untuk menampilkan isi tabel
                                while ($d = mysqli_fetch_array($result)) {
                                ?>

                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $d['name']; ?></td>
                            <td><?php echo $d['kategori']; ?></td>
                            <td><?php echo $d['keterangan']; ?></td>
                            <td><?php echo $d['password']; ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <?php } ?>

                    <br>

                    <h5><a href="dashboard.php">Kembali ke menu</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>


