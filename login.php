<?php
// jalankan dulu sessionnya untuk memulai $_SESSION
session_start();
include('functions.php');

// cek cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // ambil username berdasarkan id nya
    $result = mysqli_query($conn, "SELECT username FROM users WHERE id=$id");
    $row = mysqli_fetch_assoc($result);

    // cek cookie dan username
    if ($key === hash('sha256', $row['username'])) {
        $_SESSION['login'] = true;
    }

    // if($_COOKIE["login"]=='true'){ // jika true, maka setcookie akan berjalan sesuai dengan perintah yg dibuat tadi
    //     $_SESSION['login']=true; // jika true, maka code yg dibawah akan dijalankan, selama cookie nya masih ada/belum expire
    // }
}
// jika sudah login, maka biarkan user di halaman index
if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}
// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["login"])) {

    $email = $_POST["Email"];
    $password = $_POST["Password"];

    // hasil dari database tabel users fieldnya email
    $result = mysqli_query($conn, "SELECT * FROM user where Email='$email'");

    // cek email
    if (mysqli_num_rows($result) === 1) { // untuk ngitung ada brp baris yg dikembalikan dari fungsi Select diatas, jadi kalo ada nilainya/emailnya pasti 1, kalo gk ada berarti 0
        // cek passwordnya
        $row = mysqli_fetch_assoc($result); // menyimpan data user yg login
        // untuk mengecek sebuah password, apakah string dari ini sama dengan password_hash yg ada, jika ada dan sama, berarti passwordnya bener
        // jika passwordnya bener, arahkan ke halaman index.php 
        if (password_verify($password, $row["Password"])) {
            // set session
            $_SESSION["login"] = true;
            $_SESSION["user"] = $row["Email"];
            $_SESSION["id"] = $row["UserID"];

            // cek remember me
            if (isset($_POST["remember"])) {
                // buat cookie

                setcookie('id', $row['id'], time() + 60);
                setcookie('key', hash('sha256', $row['Email']), time() + 60);
                // fungsi hash bisa menggunakan algoritma yg bermacam macam, bisa cek di php.net tentang hash
                // fungsi hash ini punya 2 parameter, yg pertama algoritmanya, yg kedua stringnya/valuenya
                //  code diatas ngambil data string dari database email ketika user login, jadi emailnya di
                // acak gitu jadi karakter yg gk berurutan
            }

            header("Location: index.php");
            exit; // ini akan memberhentikan code yg ada dibawahnya, jadi programnya berhenti disini, tidak masuk ke error
        }
    }

    // error ini akan berjalan ketika code diatas ada kesalahan, jika ada kesalahan
    // tampilkan pesan error
    $error = true;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" action="" method="post">
                                        <div class="form-group">
                                            <input type="email" name="Email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="Password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                                        </div>
                                        <button type="submit" name="login" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                        <hr>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>