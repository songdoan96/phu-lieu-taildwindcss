<?php
ob_start();
session_start();
$page_title = "Đăng nhập";
require_once "DB.php";
require_once "header.php";

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
    <div class="max-w-screen-sm mx-auto p-4">
        <form class="rounded bg-white dark:bg-gray-600 shadow shadow-black/30 p-4 mt-8"
              action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
            <h2 class="mb-6 uppercase text-xl font-bold dark:text-white">Đăng nhập</h2>
            <div class="mb-6">
                <label for="username" class="block mb-2 font-medium text-gray-900 dark:text-white">Tài khoản</label>
                <input type="username" id="username" name="username"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       required>
            </div>
            <div class="mb-6">
                <label for="password" class="block mb-2 font-medium text-gray-900 dark:text-white">Mật khẩu</label>
                <input type="password" id="password" name="password"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-500 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       required>
            </div>

            <button type="submit" name="login-form"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-800">
                Đăng nhập
            </button>
        </form>
    </div>


<?php require_once "footer.php"; ?>