<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function resend_email_verify($name,$email,$verify_token){
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
    $mail->Subject = 'Resend-Email Verification from KEYKEEPER';//Subject pesan
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

if(isset($_POST["resend_email_verify_btn"])){
    if(!empty(trim($_POST['email']))){
        $email = mysqli_real_escape_string($con, $_POST['email']);

        $checkemail_query = "SELECT * FROM user WHERE email='$email' LIMIT 1";
        $checkemail_query_run = mysqli_query($con, $checkemail_query);

        if(mysqli_num_rows($checkemail_query_run) > 0){
            $row = mysqli_fetch_array($checkemail_query_run);
            if($row['verify_status'] == "0"){
                $name = $row['name'];
                $email = $row['email'];
                $verify_token = $row['verify_token'];

                resend_email_verify($name,$email,$verify_token);

                $_SESSION['status'] = "Verification Email Link has been sent to your email address !";
                header('Location: login.php');
                exit(0);
            }
            else{
                $_SESSION['status'] = "Email already verified. Please Log in !";
                header('Location: resend-email-verification.php');
                exit(0);
            }
        }
        else{
            $_SESSION['status'] = "Email does not registered. Please register now !";
            header('Location: register.php');
            exit(0);
        }
    }
    else{
        $_SESSION['status'] = "Please enter the email field !";
        header('Location: resend-email-verification.php');
        exit(0);
    }
}

?>