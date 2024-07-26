<?php
include('dbcon.php');
include('authentication.php');
$page_title = "PassGen Page";
include('includes/header.php');
include('includes/navbar.php');

if (isset($_SESSION['status'])) {
    ?>
    <div class="alert alert-success">
        <h5><?= $_SESSION['status']; ?></h5>
    </div>
    <?php
    unset($_SESSION['status']);
}

function randomPassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@#$%^&*()_';
    $pass = array(); // remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; // put the length -1 in cache
    for ($i = 0; $i < 12; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); // turn the array into a string
}

if (isset($_POST['generate'])) {
    $name = $_SESSION['auth_user']['username'];
    $kategori = $_POST["kategori"];
    $keterangan = isset($_POST["keterangan1"]) ? $_POST["keterangan1"] : '';
    $code = randomPassword();

    $sql = "SELECT * FROM password_gen WHERE password='$code'";
    $result = mysqli_query($con, $sql);
    if (!$result->num_rows > 0) {
        $sql = "INSERT INTO password_gen (name, kategori, keterangan, password) VALUES('$name','$kategori', '$keterangan', '$code')";
        $result = mysqli_query($con, $sql);
        echo "<script>alert('Hai $name, ini kode kamu : $code')</script>";
    } else {
        echo "<script>alert('Eitss, username sudah terdaftar !!')</script>";
    }
} elseif (isset($_POST['buat_password'])) {
    $name = $_SESSION['auth_user']['username'];
    $kategori = $_POST["kategori"];
    $keterangan = $_POST["keterangan"];
    $code = $_POST["password"];

    $sql = "SELECT * FROM password_gen WHERE password='$code'";
    $result = mysqli_query($con, $sql);
    if (!$result->num_rows > 0) {
        $sql = "INSERT INTO password_gen (name, kategori, keterangan, password) VALUES('$name','$kategori', '$keterangan', '$code')";
        $result = mysqli_query($con, $sql);
        echo "<script>alert('Hai $name, ini kode kamu : $code')</script>";
    } else {
        echo "<script>alert('Eitss, username sudah terdaftar !!')</script>";
    }
}
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Code Generator</h4>
                    </div>
                    <div class="card-body">
                    <h4>Your Account :</h4>
                    <hr>
                    <h5>Username: <?= $_SESSION['auth_user']['username']; ?></h5>
                    <h5>Email: <?= $_SESSION['auth_user']['email']; ?></h5>
                    
                    <form method="POST">
                        <div class="form-group mb-3">
                            <label for="metode_pembuatan">Pilih Metode Pembuatan Password</label><br>
                            <select id="metode_pembuatan" name="metode_pembuatan">
                                <option value="#">-- Metode --</option>
                                <option value="otomatis">Otomatis</option>
                                <option value="manual">Manual</option>
                            </select>
                        </div>

                        <!-- Kategori Section -->
                        <div id="otomatis_section">
                            <table>
                                <tr>
                                    <td>Kategori</td>
                                    <td>:</td>
                                    <td>
                                        <select name="kategori" id="kategori">
                                            <option value="Pribadi">Pribadi</option>
                                            <option value="Media Sosial">Media Sosial</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr><br></tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td><input type="text" name="keterangan1" id="keterangan1" class="form-control"/></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><input class="btn btn-primary" type="submit" name="generate" value="Generate Code" /></td>
                                </tr>
                            </table>
                        </div>

                        <div id="manual_section">
                            <div class="form-group mb-3">
                                <label for="">Kategori</label><br>
                                <select id="" name="">
                                    <option value="#">-- Pilih Kategori --</option>
                                    <option value="Pribadi">Pribadi</option>
                                    <option value="Media Sosial">Media Sosial</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="text" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                            <input class="btn btn-primary" type="submit" name="buat_password" value="Buat Password" />
                            </div>
                        </div>
                    </form>

                    <br>
                    <h5><a href="dashboard.php">Kembali ke menu</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to show/hide Kategori section based on the selected option
    function toggleKategoriSection() {
        var selectedOption = document.getElementById('metode_pembuatan').value;
        var kategoriSection = document.getElementById('otomatis_section');
        var manualSection = document.getElementById('manual_section');

        if (selectedOption === 'otomatis') {
            kategoriSection.style.display = 'block';
            manualSection.style.display = 'none'; // Hide manual section when otomatis is selected
        } else if (selectedOption === 'manual') {
            kategoriSection.style.display = 'none'; // Hide kategori section when manual is selected
            manualSection.style.display = 'block';
        } else {
            kategoriSection.style.display = 'none';
            manualSection.style.display = 'none';
        }
    }

    // Adding an event listener to call the function when the selection changes
    document.getElementById('metode_pembuatan').addEventListener('change', toggleKategoriSection);

    // Calling the function initially to set the initial state
    toggleKategoriSection();
</script>

<?php include('includes/footer.php'); ?>