<?php
include('authentication.php');
$page_title="Dashboard Page";
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <?php

                if(isset($_SESSION['status'])){
                    ?>
                    <div class="alert alert-success">
                        <h5><?= $_SESSION['status']; ?></h5>
                    </div>
                    <?php
                    unset($_SESSION['status']);
                }

                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>Dashboard</h4>
                    </div>
                    <div class="card-body">
                    <h4>Your Account :</h4>
                    <hr>
                    <h5>Username: <?= $_SESSION['auth_user']['username']; ?></h5>
                    <h5>Email: <?= $_SESSION['auth_user']['email']; ?></h5>
                    </div>
                    <table cellpadding="10">
                        <tr>
                            <td><a href="cari.php"><button name="cari_btn" class="btn btn-primary">Cari</button></a></td>
                            <td><a href="passgen.php"><button name="passgen_btn" class="btn btn-primary">Buat</button></a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>