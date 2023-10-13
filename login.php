<?php
ob_start();
session_start();
$page_title = "Đăng nhập";
require_once "DB.php";
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?? "Phụ liệu" ?></title>
    <link rel="stylesheet" href="assets/css/main.css?v=<?= time() ?>">
</head>

<body class="bg-gray-50 dark:bg-gray-700">

    <?php
    if (isset($_SESSION['success']) || isset($_SESSION['error'])) {
        $toastMessage = $_SESSION['success'] ?? $_SESSION['error'];
        $toastType = isset($_SESSION['success']) ? "green" : "red";
    }
    if (isset($toastMessage)) { ?>
        <div id="toast" class="fixed flex items-center bottom-4 right-4 z-50">
            <!-- <a href="" class="bg-green-100 border-green-400 text-green-900 bg-green-50"></a> -->
            <!-- <a href="" class="bg-red-100 border-red-400 text-red-900 bg-red-50"></a> -->
            <div id="toast-wrap" class="border-l-4 flex items-center w-full max-w-xs p-4 bg-<?= $toastType ?>-100 border-<?= $toastType ?>-400 text-<?= $toastType ?>-900 rounded-lg shadow-md" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-<?= $toastType ?>-100 bg-<?= $toastType ?>-50 rounded-lg ">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="mx-3 text-sm font-normal"><?= $toastMessage ?></div>
                <button id="close-toast-btn" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:hover:text-white dark:bg-green-200 dark:hover:bg-green-300">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    <?php
        unset($_SESSION['success'], $_SESSION['error']);
    }
    ?>


    <div class="min-h-screen bg-gray-100 dark:bg-muted-800 text-gray-900 flex justify-center">
        <div class="max-w-screen-xl m-0 sm:m-10 bg-white shadow sm:rounded-lg overflow-hidden flex justify-center flex-1">
            <div class="w-full lg:w-1/2 xl:w-5/12 p-6 sm:p-12 order-2 dark:bg-muted-700">
                <div>
                    <img src="assets/img/logo-1.png" class="w-32 mx-auto" />
                </div>
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-2xl font-extrabold uppercase dark:text-muted-200">
                        Đăng nhập
                    </h1>
                    <div class="w-full flex-1 mt-8">
                        <form class="mx-auto max-w-xs" action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="mb-5">
                                <label for="username" class="block mb-1 font-medium text-gray-900 dark:text-muted-200">Tài khoản</label>
                                <input type="username" id="username" name="username" value="admin" class="bg-muted-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 dark:bg-muted-500 dark:border-gray-600 dark:placeholder-gray-400 dark:text-muted-200 dark:focus:ring-teal-500 dark:focus:border-teal-500" required="">
                            </div>
                            <div class="">
                                <label for="password" class="block mb-1 font-medium text-gray-900 dark:text-muted-200">Mật khẩu</label>
                                <input type="password" id="password" name="password" value="admin" class="bg-muted-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 dark:bg-muted-500 dark:border-gray-600 dark:placeholder-gray-400 dark:text-muted-200 dark:focus:ring-teal-500 dark:focus:border-teal-500" required="">
                            </div>
                            <button name="login-form" type="submit" class="mt-5 tracking-wide font-semibold w-full py-4 rounded-lg bg-teal-500 hover:bg-teal-600 text-muted-700 dark:bg-muted-500 dark:hover:bg-muted-600 dark:text-muted-200 transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                <svg class="w-6 h-6 -ml-2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                    <circle cx="8.5" cy="7" r="4" />
                                    <path d="M20 8v6M23 11h-6" />
                                </svg>
                                <span class="ml-3">
                                    Đăng nhập
                                </span>
                            </button>

                        </form>
                    </div>
                </div>
            </div>
            <div class="flex-1 bg-indigo-100 text-center hidden lg:flex">
                <img src="assets/img/bg.jpg" class="bg-no-repeat bg-cover bg-center" alt="background">
                <!-- <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat" style="background-image: url('https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg');">
                </div> -->
            </div>
        </div>
    </div>

    <script src="assets/js/main.js?v=<?= time() ?>"></script>
</body>

</html>


<?php

if (isset($_SESSION["user"]) && $_SESSION["user"]) {
    $_SESSION['success'] = "Bạn đã đăng nhập";
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login-form'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = DB::table('users')->where('username', '=', $username)->fetch();
    if ($user) {
        if (password_verify($password, $user->password)) {
            $_SESSION["user"] = time();
            $_SESSION["username"] = $user->username;
            $_SESSION["is_admin"] = $user->is_admin;
            $_SESSION["success"] = "Đăng nhập thành công";
            header('Location: index.php');
        } else {
            $_SESSION["error"] = "Mật khẩu chưa đúng.";
            header('Location: login.php');
        }
    } else {
        $_SESSION["error"] = "Tài khoản không tồn tại.";
        header('Location: login.php');
    }
}
?>