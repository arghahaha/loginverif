<?php
session_start();
$page_title="Home Page";
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<script>
    function checkLogin() {
        // Memeriksa apakah user sudah login
        var isLoggedIn = <?php echo isset($_SESSION['auth_user']) ? 'true' : 'false'; ?>;

        // Jika sudah Login, akan diarahakan ke dashboard.php
        if (isLoggedIn) {
            window.location.href = "dashboard.php";
        } else {
            // Jika belum, akan memunculkan sebuah notifikasi dan diarhakan ke login.php
            alert("Login dulu sebelum buat memulai!");
            window.location.href = "login.php";
        }
    }
</script>
    
<div class="py-5">
    <div class="container">
        <div class="col-md-12 text-center">
            <h2>KEYKEEPER</h2>
            <br>
            <h4>We Make And Keep Your Key </h4>
        </div>
        <div class="col-md-12 text-center">
            <hr>
            <h4>Click this button to generate your password</h4>
            <button type="submit" name="Start_btn" onclick="checkLogin()" class="btn btn-primary">Start Now</button>
            <hr>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
