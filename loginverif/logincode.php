<?php
session_start();

include('dbcon.php');

// Fungsi untuk mengatur waktu blokir setelah 3 kali gagal login
function setBlockedTime() {
    $_SESSION['blocked_time'] = time() + 15; // Waktu blokir 15 detik
}

// Fungsi untuk menghitung waktu tersisa sebelum dapat mencoba login lagi
function getRemainingTime() {
    return $_SESSION['blocked_time'] - time();
}

// Fungsi untuk mengecek apakah pengguna sedang dalam waktu blokir
function isBlocked() {
    return isset($_SESSION['blocked_time']) && time() < $_SESSION['blocked_time'];
}

if (isset($_POST['login_now_btn'])) {
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
        $ip = $_SERVER['REMOTE_ADDR'];

        // Periksa apakah IP diblokir
        if (isBlocked()) {
            $_SESSION['status'] = "Your account has been blocked. Please try again after " . getRemainingTime() . " seconds.";
            header("Location: login.php");
            // $msg = "Your account has been blocked. Please try again after " . getRemainingTime() . " seconds.";
        } else {
            $login_time = time() - 15;
            $login_attempts = mysqli_query($con, "SELECT count(*) AS total_count FROM ip_details WHERE ip='$ip' and login_time > '$login_time'");
            $res = mysqli_fetch_assoc($login_attempts);
            $count = $res['total_count'];

            if ($count == 3) {
                // Jika sudah 3 kali percobaan, atur waktu blokir
                setBlockedTime();
                $_SESSION['status'] = "Your account has been blocked. Please try again after 15 seconds.";
                header("Location: login.php");
                // $msg = "Your account has been blocked. Please try again after 15 seconds.";
            } else {
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $password = mysqli_real_escape_string($con, $_POST['password']);

                $login_query = "SELECT * FROM user WHERE email=? LIMIT 1";
                $stmt = mysqli_prepare($con, $login_query);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);

                    if (password_verify($password, $row['password'])) { // Check hashed password
                        if ($row['verify_status'] == "1") {
                            $delete_query = mysqli_query($con, "DELETE FROM ip_details WHERE ip='$ip'");
                            $_SESSION['authenticated'] = TRUE;
                            $_SESSION['auth_user'] = [
                                'username' => $row['name'],
                                'phone' => $row['phone'],
                                'email' => $row['email']
                            ];
                            $_SESSION['status'] = "You are logged in successfully !";
                            header("Location: dashboard.php");
                            exit(0);
                        } else {
                            $_SESSION['status'] = "Please verify your email address to login !";
                            header("Location: login.php");
                            exit(0);
                        }
                    } else {
                        $count++;
                        $remaining_attempts = 3 - $count;

                        if ($remaining_attempts == 0) {
                            // Jika sudah 3 kali percobaan, atur waktu blokir
                            setBlockedTime();
                            $_SESSION['status'] = "Your account has been blocked. Please try again after 15 seconds.";
                            header("Location: login.php");
                            // $msg = "Your account has been blocked. Please try again after 15 seconds.";
                        } else {
                            $ip = $_SERVER['REMOTE_ADDR'];
                            $login_time = time();
                            $ip_query = "INSERT INTO ip_details (ip,login_time) VALUES ('$ip','$login_time')";
                            $insert_query = mysqli_query($con, $ip_query);
                            $_SESSION['status'] = "Invalid Email or Password ! $remaining_attempts attempts remaining.";
                            // $msg = "Invalid Email or Password ! $remaining_attempts attempts remaining.";
                            header("Location: login.php");
                        }
                    }
                } else {
                    $_SESSION['status'] = "Invalid Email or Password !";
                    header("Location: login.php");
                    exit(0);
                }
            }
        }
    } else {
        $_SESSION['status'] = "All fields are Mandatory !";
        header("Location: login.php");
        exit(0);
    }
}



?>