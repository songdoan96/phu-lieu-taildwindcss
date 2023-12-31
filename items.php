<?php
require_once "init.php";
include_once "header.php";
$headerTitle = "QUẢN LÝ XUẤT NHẬP TỒN";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete-id'])) {
    DB::table('items')->where('item_id', '=', post('delete-id'))->delete();
    if (isset($_POST['redo-sold-out'])) {
        DB::table('items')
            ->where('item_id', '=', post('redo-sold-out'))
            ->update([
                'item_sold_out' => 0
            ]);
    }
    $_SESSION['success'] = "Xóa thành công.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['sold-out'])) {
    DB::table('items')
        ->where('item_id', '=', post('sold-out'))
        ->update([
            "item_sold_out" => 1
        ]);
    $_SESSION['danger'] = "Phụ liệu đã hết.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

if (count($_GET) === 1 && isset($_GET['ma-hang'])) {
    $headerTitle = "QUẢN LÝ XUẤT NHẬP TỒN MÃ HÀNG " . $_GET['ma-hang'];

    $items = DB::table('items')
        ->where('item_style', '=', get('ma-hang'))
        ->whereNull('order_id')
        ->where('item_sold_out', "=", 0)
        ->groupBy('item_type, item_color, item_size, item_params')
        ->fetchAll();
}
if (count($_GET) === 1 && isset($_GET['khoang'])) {
    $headerTitle = "QUẢN LÝ XUẤT NHẬP TỒN KHOANG " . $_GET['khoang'];

    $items = DB::table('items')
        ->where('item_container', '=', get('khoang'))
        ->whereNull('order_id')
        ->where('item_sold_out', "=", 0)
        ->fetchAll();
}
// if (count($_GET) == 1 && isset($_GET['qlxn'])) {
//     $items = DB::table('items')
//         ->orderBy('created_at', 'desc')
//         ->limit(50)
//         ->fetchAll();
// }
// if (count($_GET) === 1 && isset($_GET['het'])) {
//     $headerTitle = "PHỤ LIỆU HẾT";

//     $items = DB::table('items')
//         // ->orderBy('created_at', 'desc')
//         ->where('item_sold_out', '=', 1)
//         ->whereNull('order_id')
//         ->fetchAll();
// }
if (count($_GET) === 2 && isset($_GET['search-type'], $_GET['search-value'])) {
    if (get('search-type') === "ma-hang") {
        $searchType = "item_style";
    } else if (get('search-type') === "po") {
        $searchType = "item_po";
    } else {
        $searchType = "item_type";
    }
    $items = DB::table('items')
        ->where($searchType, 'LIKE', "%" . get('search-value') . "%")
        ->where('item_sold_out', '=', 0)
        ->whereNull('order_id')
        ->groupBy('item_type, item_color, item_size, item_params')
        ->fetchAll();
}
if (count($_GET) === 2 && isset($_GET['ma-hang'], $_GET['khoang'])) {
    $headerTitle = "MÃ HÀNG " . $_GET['ma-hang'] . " - KHOANG " . $_GET['khoang'];

    $items = DB::table('items')
        ->where('item_style', '=', get('ma-hang'))
        ->where('item_container', '=', get('khoang'))
        ->whereNull('order_id')
        ->where('item_sold_out', "=", 0)
        ->orderBy('item_type')
        ->fetchAll();
}
if (count($_GET) === 5 && isset($_GET['ma-hang'], $_GET['type'])) {
    $headerTitle = "QL XUẤT NHẬP MÃ HÀNG " . $_GET['ma-hang'] . " - " . $_GET['type'];

    $items = DB::table('items')
        ->where('item_style', '=', get('ma-hang'))
        ->where('item_type', '=', get('type'))
        ->where('item_color', '=', get('color'))
        ->where('item_size', '=', get('size'))
        ->where('item_params', '=', get('params'))
        ->whereNull('order_id')
        ->where('item_sold_out', "=", 0)
        ->fetchAll();
    if ($items) {
        $total = 0;
        $totalSumOrder = 0;
        $totalSumInventory = 0;
        foreach ($items as $item) {
            $total += $item->item_qty;
            $totalOrders = DB::table('items')->where('order_id', '=', $item->item_id)->sum('item_qty');
            $totalSumOrder += $totalOrders;
            $totalSumInventory += $item->item_qty - $totalOrders;
        }
    }
}
$_SESSION['page'] = $_SERVER['REQUEST_URI'];

if (!count($items)) {
    echo "<h2 class='p-4 text-center font-bold text-2xl dark:text-white mb-4 text-red-500'>PHỤ LIỆU ĐÃ HẾT</h2>";
}
if (count($items)) { ?>
    <div class="px-2 py-4 md:p-4" id="items">
        <h2 id="item-title" class="text-center font-bold text-xl md:text-2xl dark:text-muted-200 mb-4 text-teal-600"><?= $headerTitle ?></h2>
        <div id="table" class="w-full">
            <div class="border-muted-200 dark:border-muted-700 border rounded-md overflow-hidden shadow-lg">
                <div class="min-w-full align-middle overflow-x-auto">
                    <table class="divide-muted-200 dark:divide-muted-700 min-w-full table-fixed divide-y text-center">
                        <thead>
                            <tr>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">NGÀY</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">KHOANG</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">KHÁCH HÀNG</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">MÃ HÀNG</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">MODEL</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">LOẠI</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">ITEM</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">MÀU</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">THÔNG SỐ</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">SIZE</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">ĐƠN VỊ</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">PO</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">NHẬP</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">XUẤT</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">TỒN</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">GHI CHÚ</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-muted-200 dark:divide-muted-700 dark:bg-muted-800 divide-y bg-muted-50">
                            <?php foreach ($items as $item) {
                                $ordersItem = DB::table('items')->where('order_id', '=', $item->item_id)->fetchAll();
                                $totalOrder = DB::table('items')
                                    ->where('order_id', '=', $item->item_id)
                                    ->sum('item_qty');
                                $inventory = $item->item_qty - $totalOrder;

                            ?>
                                <tr item-id="<?= $item->item_id ?>" class="hover:bg-muted-100 dark:hover:bg-muted-900 transition-colors duration-200">

                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= formatDate($item->item_date) ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><a href="items.php?khoang=<?= $item->item_container ?>"><?= $item->item_container ?></a></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_customer ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><a href="items.php?ma-hang=<?= $item->item_style ?>"><?= $item->item_style ?></a></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_model ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><a title="<?= $item->item_id ?>" href="items.php?ma-hang=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>"><?= $item->item_type ?></a></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_item ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_color ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_params ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_size ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_unit ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_po ?></td>

                                    <td class="text-sm border-r dark:border-muted-700 text-muted-200 bg-emerald-700 py-2 px-1 whitespace-nowrap"><a href="nhap-kho.php?id=<?= $item->item_id ?>"><?= formatNumber($item->item_qty) ?></a>
                                    </td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-200 bg-rose-700 py-2 px-1 whitespace-nowrap">
                                        <?php
                                        if ($inventory != 0) { ?>
                                            <a href="xuat-kho.php?id=<?= $item->item_id ?>"><?= formatNumber($totalOrder) ?></a>
                                        <?php } else { ?>
                                            <?= formatNumber($totalOrder) ?>
                                        <?php } ?>
                                    </td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-200 bg-sky-700 py-2 px-1 whitespace-nowrap">
                                        <?php
                                        if ($inventory == 0) { ?>
                                            <form class="inline-block ml-1" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                                                <input type="hidden" value="<?= $item->item_id ?>" name="sold-out">
                                                <button class="bg-yellow-300 text-red-700 font-bold p-1 rounded uppercase" type="submit">
                                                    Hết
                                                </button>
                                            </form>
                                        <?php } else {
                                            echo formatNumber($inventory);
                                        } ?>
                                    </td>

                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1"><?= $item->item_note ?></td>
                                    <td class="text-sm text-muted-700 dark:text-muted-200 py-2 px-1">
                                        <div class="flex gap-1 justify-end items-center">
                                            <?php
                                            if (!$item->order_id && $totalOrder != 0) { ?>
                                                <button title="Chi tiết xuất" type="button" class="btn-show-order w-5 transform hover:text-blue-500 transition hover:scale-125" data-id="<?= $item->item_id ?>">
                                                    <svg class="img-show" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <svg class="hidden img-hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                        <path d="M2.99902 3L20.999 21M9.8433 9.91364C9.32066 10.4536 8.99902 11.1892 8.99902 12C8.99902 13.6569 10.3422 15 11.999 15C12.8215 15 13.5667 14.669 14.1086 14.133M6.49902 6.64715C4.59972 7.90034 3.15305 9.78394 2.45703 12C3.73128 16.0571 7.52159 19 11.9992 19C13.9881 19 15.8414 18.4194 17.3988 17.4184M10.999 5.04939C11.328 5.01673 11.6617 5 11.9992 5C16.4769 5 20.2672 7.94291 21.5414 12C21.2607 12.894 20.8577 13.7338 20.3522 14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            <?php }
                                            ?>
                                            <a href="sua.php?id=<?= $item->item_id ?>" title="Sửa phụ liệu" class="btn-show-order w-5 transform hover:text-green-500 transition hover:scale-125" data-id="<?= $item->item_id ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                            <form id="form-delete" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="inline-block">
                                                <input type="hidden" name="delete-id" id="delete-id" value="<?= $item->item_id ?>">
                                                <button type="button" title="Xóa phụ liệu" data-id="<?= $item->item_id ?>" class="btn-show-modal w-5 transform hover:text-red-500 transition hover:scale-110">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>

                                    </td>
                                </tr>
                                <?php
                                foreach ($ordersItem as $orderItem) { ?>
                                    <tr item-id="<?= $orderItem->item_id ?>" parent-id="<?= $item->item_id ?>" class="hidden text-center bg-red-300 hover:bg-red-300/80 transition-colors duration-200">
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_date ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_container ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_customer ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_style ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_model ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1" title="<?= $orderItem->item_id ?>"><?= $orderItem->item_type ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_item ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_color ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_params ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_size ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_unit ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_po ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= formatNumber($orderItem->item_qty) ?></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"></td>
                                        <td class="text-sm border-r dark:border-muted-700 text-muted-700 py-2 px-1"><?= $orderItem->item_note ?></td>
                                        <td class="text-sm text-muted-700 py-2 px-1">
                                            <div class="flex gap-1 justify-end items-center">
                                                <a href="sua.php?id=<?= $orderItem->item_id ?>" title="Sửa phụ liệu" class="btn-show-order w-5 transform hover:text-green-500 transition hover:scale-125" data-id="<?= $item->item_id ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                                <form id="form-delete" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="inline-block">
                                                    <input type="hidden" name="delete-id" id="delete-id" value="<?= $orderItem->item_id ?>">
                                                    <input type="hidden" name="redo-sold-out" value="<?= $item->item_id ?>">
                                                    <button type="button" data-id="<?= $orderItem->item_id ?>" class="btn-show-modal w-5 transform hover:text-red-500 transition hover:scale-110">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }  ?>

                                </tr>
                            <?php } ?>
                            <?php
                            if (isset($total)) { ?>
                                <tr class="">
                                    <td colspan="12" class="whitespace-nowrap p-2 pr-12 text-muted-500 dark:text-muted-200 uppercase font-bold text-lg text-right">Tổng :</td>
                                    <td><span class="whitespace-nowrap bg-emerald-100 text-emerald-700 p-1 px-2 text-sm font-bold rounded-full"><?= formatNumber($total) ?></span></td>
                                    <td><span class="whitespace-nowrap bg-rose-100 text-rose-700 p-1 px-2 text-sm font-bold rounded-full"><?= formatNumber($totalSumOrder) ?></span></td>
                                    <td><span class="whitespace-nowrap bg-sky-100 text-sky-700 p-1 px-2 text-sm font-bold rounded-full"><?= formatNumber($totalSumInventory) ?></span></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="modal" class="fixed hidden top-0 left-0 right-0 z-50 p-4 overflow-hidden md:inset-0 h-screen bg-black/80 justify-center items-center">
        <div class="relative w-full max-w-md max-h-screen mx-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-muted-700">
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-red-400 w-12 h-12 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Xóa phụ liệu?</h3>

                    <button type="button" id="btn-confirm-delete" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Xóa
                    </button>

                    <button type="button" id="btn-close-modal" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Hủy</button>
                </div>
            </div>
        </div>
    </div>
<?php }
?>



<?php
require_once "footer.php";
?>