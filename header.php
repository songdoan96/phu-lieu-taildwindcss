<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $page_title ?? "Phụ liệu" ?></title>
    <link rel="stylesheet" href="assets/css/main.css?v=<?= time() ?>">

    <!-- <script src="assets/js/jquery.min.js"></script> -->

</head>

<body class="bg-gray-100 dark:bg-gray-600">
    <nav class="shadow bg-white border-gray-200 dark:bg-gray-900">
        <div class="container flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="index.php" class="text-main-600 dark:text-white flex items-center uppercase text-2xl font-extrabold">Hòa Thọ</a>
            <div class="flex md:order-2">

                <?php if (isset($_SESSION['user'])) { ?>
                    <form method="get" action="items.php" class="hidden md:block relative overflow-hidden">
                        <select name="search-type" id="form-search-list" class="border border-second-500 rounded p-2">
                            <option value="type">PHỤ LIỆU</option>
                            <option value="ma-hang">MÃ HÀNG</option>
                            <option value="po">PO</option>
                        </select>
                        <input type="text" name="search-value" id="search-input" class="h-full rounded-r-lg border p-2" placeholder="Tìm kiếm...">
                        <button type="submit" class="absolute right-0 bg-blue-600 text-white h-full px-2 rounded-r-lg">
                            <svg class="w-4 h-4" aria-hidden="true" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"></path>
                            </svg>
                            <span class="sr-only">Search</span>
                        </button>
                    </form>
                    <button type="button" id="mobile-btn" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-search" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>

                <?php } ?>

                <?php
                if (!isset($_SESSION['user'])) { ?>
                    <a class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" href="login.php">Đăng nhập</a>
                <?php } else { ?>
                    <a class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" href="logout.php">Đăng xuất</a>
                <?php                }
                ?>
                <div class="flex items-center ml-2 md:ml-6 dark:border-slate-800">
                    <button type="button" id="btn-dark">
                        <span class="dark:hidden">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                                <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" class="fill-sky-400/20 stroke-sky-500"></path>
                                <path d="M12 4v1M17.66 6.344l-.828.828M20.005 12.004h-1M17.66 17.664l-.828-.828M12 20.01V19M6.34 17.664l.835-.836M3.995 12.004h1.01M6 6l.835.836" class="stroke-sky-500"></path>
                            </svg>
                        </span>
                        <span class="hidden dark:inline">
                            <svg viewBox="0 0 24 24" fill="none" class="w-6 h-6">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17.715 15.15A6.5 6.5 0 0 1 9 6.035C6.106 6.922 4 9.645 4 12.867c0 3.94 3.153 7.136 7.042 7.136 3.101 0 5.734-2.032 6.673-4.853Z" class="fill-sky-400/20"></path>
                                <path d="m17.715 15.15.95.316a1 1 0 0 0-1.445-1.185l.495.869ZM9 6.035l.846.534a1 1 0 0 0-1.14-1.49L9 6.035Zm8.221 8.246a5.47 5.47 0 0 1-2.72.718v2a7.47 7.47 0 0 0 3.71-.98l-.99-1.738Zm-2.72.718A5.5 5.5 0 0 1 9 9.5H7a7.5 7.5 0 0 0 7.5 7.5v-2ZM9 9.5c0-1.079.31-2.082.845-2.93L8.153 5.5A7.47 7.47 0 0 0 7 9.5h2Zm-4 3.368C5 10.089 6.815 7.75 9.292 6.99L8.706 5.08C5.397 6.094 3 9.201 3 12.867h2Zm6.042 6.136C7.718 19.003 5 16.268 5 12.867H3c0 4.48 3.588 8.136 8.042 8.136v-2Zm5.725-4.17c-.81 2.433-3.074 4.17-5.725 4.17v2c3.552 0 6.553-2.327 7.622-5.537l-1.897-.632Z" class="fill-sky-500"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M17 3a1 1 0 0 1 1 1 2 2 0 0 0 2 2 1 1 0 1 1 0 2 2 2 0 0 0-2 2 1 1 0 1 1-2 0 2 2 0 0 0-2-2 1 1 0 1 1 0-2 2 2 0 0 0 2-2 1 1 0 0 1 1-1Z" class="fill-sky-500"></path>
                            </svg>
                        </span>
                    </button>

                </div>
            </div>
            <?php if (isset($_SESSION['user'])) { ?>
                <div class="items-center justify-between w-full md:flex md:w-auto md:order-1" id="navbar-search">
                    <div id="mobile-search" class="relative mt-3 hidden md:hidden">

                        <form method="get" action="items.php" class="flex items-center md:hidden overflow-hidden max-w-xl">
                            <select name="search-type" id="form-mobile-search-list" class="border border-second-500 rounded p-2">
                                <option value="type">PHỤ LIỆU</option>
                                <option value="ma-hang">MÃ HÀNG</option>
                                <option value="po">PO</option>
                            </select>
                            <input type="text" name="search-value" id="search-mobile-input" class="h-full w-full rounded-r-lg border p-2" placeholder="Tìm kiếm...">
                            <button type="submit" class="absolute right-0 bg-blue-600 text-white h-full px-2 rounded-r-lg">
                                <svg class="w-4 h-4" aria-hidden="true" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"></path>
                                </svg>
                                <span class="sr-only">Search</span>
                            </button>
                        </form>
                    </div>
                    <ul id="main-nav" class="hidden uppercase md:flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li>
                            <a href="khoang.php" class="block py-2 pl-3 pr-4 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500" aria-current="page">Khoang</a>
                        </li>

                        <!-- <li>
                            <a href="items.php?qlxn" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">QL xuất nhập</a>
                        </li> -->
                        <li>
                            <a href="items.php?het" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">pl hết</a>
                        </li>
                        <li>
                            <a href="can-doi.php" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">cân đối</a>
                        </li>

                    </ul>

                </div>
            <?php } ?>

        </div>

    </nav>


    <!-- Toast -->
    <?php
    if (isset($_SESSION['success']) || isset($_SESSION['error'])) {
        $toastMessage = $_SESSION['success'] ?? $_SESSION['error'];
        $toastType = isset($_SESSION['success']) ? "success" : "danger";
    }
    if (isset($toastMessage)) { ?>
        <div id="toast" class="fixed flex items-center bottom-4 right-4 z-50">
            <!-- <a href="" class="bg-success-100 border-success-400 text-success-900 bg-success-50"></a> -->
            <!-- <a href="" class="bg-danger-100 border-danger-400 text-danger-900 bg-danger-50"></a> -->
            <div x-if="showToast" id="toast-wrap" class="border-l-4 flex items-center w-full max-w-xs p-4 bg-<?= $toastType ?>-100 border-<?= $toastType ?>-400 text-<?= $toastType ?>-900 rounded-lg shadow-md" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-<?= $toastType ?>-100 bg-<?= $toastType ?>-50 rounded-lg ">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div class="mx-3 text-sm font-normal"><?= $toastMessage ?></div>
                <button id="close-toast-btn" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
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