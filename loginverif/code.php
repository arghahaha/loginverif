<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Memuat Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($name,$email,$verify_token){
    $mail = new PHPMailer(true);

    $mail->isSMTP();// Membuat mailer menggunakan SMTP
    $mail->CharSet = "utf-8";// Membuat charset menggunakan utf-8
    $mail->SMTPAuth = true;// SMTP authentication
    $mail->SMTPSecure = 'tls';// TLS enkripsi, `ssl` juga dapat diterima

    $mail->Host = 'smtp.gmail.com';// konfigurasi server gmail
    $mail->Port = 587;// TCP port to connect to
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->isHTML(true);//  Mengatur format email sebagai HTML, yang memungkinkan penggunaan HTML dalam konten email.

    // Kredensial untuk masuk ke akun Gmail yang digunakan untuk mengirim email
    $mail->Username = 'sagiriinazuma0411@gmail.com';// SMTP username
    $mail->Password = 'ejfijdtpgpoayxxm';// SMTP password

    // Konfigurasi Pengirim dan Subjek Email
    $mail->setFrom('sagiriinazuma0411@gmail.com', $name);//Nama email 
    $mail->Subject = 'Email Verification from KEYKEEPER';//Subject pesan
    $email_template = "

    <h2>You have registered with KEYKEEPER</h2>
    <h5>Verify your email address to Login with the below given link</h5>
    <br/><br/>
    <a href='http://localhost/loginverif/verify-email.php?token=$verify_token'>Click Me</a>

    ";

    $mail->Body = $email_template; // Body email
    $mail->addAddress($email);// Email target

    $mail->send();
    
}

if(isset($_POST["register_btn"])){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $raw_password = $_POST['password'];

    // Memeriksa apakah password sesuai dengan kriteria
    if(strlen($raw_password) < 8 || !preg_match('/[a-z]/', $raw_password) || !preg_match('/[A-Z]/', $raw_password) || !preg_match('/\d/', $raw_password) || !preg_match('/[^\da-zA-Z]/', $raw_password)){
        $_SESSION['status'] = 'Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, one digit, and one special character.';
        header("Location: register.php");
        exit();
    }

    // Melalukan hashing pada password
    $password = password_hash($raw_password, PASSWORD_DEFAULT);
    $verify_token = md5(rand());

    // Pengecekan apakah nama sudah ada
    $check_name_query = "SELECT name FROM user WHERE name=? LIMIT 1";
    $stmt_check_name = mysqli_prepare($con, $check_name_query);
    mysqli_stmt_bind_param($stmt_check_name, "s", $name);
    mysqli_stmt_execute($stmt_check_name);
    mysqli_stmt_store_result($stmt_check_name);

    if(mysqli_stmt_num_rows($stmt_check_name) > 0){
        $_SESSION['status'] = 'Name is already taken. Please choose a different name.';
        header("Location: register.php");
        exit();
    }

    // Pengecekan apakah email sudah ada
    $check_email_query = "SELECT email FROM user WHERE email=? LIMIT 1";
    $stmt_check_email = mysqli_prepare($con, $check_email_query);
    mysqli_stmt_bind_param($stmt_check_email, "s", $email);
    mysqli_stmt_execute($stmt_check_email);
    mysqli_stmt_store_result($stmt_check_email);

    if(mysqli_stmt_num_rows($stmt_check_email) > 0){
        $_SESSION['status'] = 'Email is already Exists';
        header("Location: register.php");
    }
    else{
        $query = "INSERT INTO user (name, phone, email, password, verify_token) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $name, $phone, $email, $password, $verify_token);
        $query_run = mysqli_stmt_execute($stmt);

        if($query_run){

            sendemail_verify($name, $email, $verify_token);

            $_SESSION['status'] = 'Registration Successful. Please verify your Email!';
            header("Location: register.php");

        }
        else{

            $_SESSION['status'] = 'Registration Failed';
            header("Location: register.php");

        }
    }
}

?>