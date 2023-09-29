<?php
require_once "init.php";
include_once "header.php";
$headerTitle = "PHỤ LIỆU HẾT";

$_SESSION['page'] = $_SERVER['REQUEST_URI'];

$items = DB::table('items');
if (count($_GET) === 1 && isset($_GET['ma-hang'])) {
    $headerTitle .= "- MÃ HÀNG " . get("ma-hang");
    $items = $items->where('item_style', '=', get('ma-hang'));
}
if (count($_GET) === 2 && isset($_GET['ma-hang'], $_GET['plieu'])) {
    $headerTitle .= "- MÃ HÀNG " . get("ma-hang") . " ( " . get("plieu") . " )";
    $items = $items
        ->where('item_style', '=', get('ma-hang'))
        ->where('item_type', '=', get('plieu'));
}
$items = $items->where('item_sold_out', '=', 1)
    ->whereNull('order_id')
    ->fetchAll();


if (!count($items)) {
    echo "<h2 class='p-4 text-center font-bold text-2xl dark:text-white mb-4'>KHÔNG TÌM THÁY PHỤ LIỆU HẾT</h2>";
}
if (count($items)) { ?>
    <div class="p-4" id="items">
        <h2 class="text-center font-bold text-2xl dark:text-white mb-4"><?= $headerTitle ?></h2>
        <div class="relative overflow-x-auto md:overflow-x-hidden shadow-md ">
            <table class="w-full border-collapse table-auto text-xs">
                <thead>
                    <tr class="bg-gray-300 dark:bg-slate-900/70 dark:text-white text-gray-600 uppercase text-sm leading-normal text-center">
                        <th class="border py-2 px-1 w-24" style="min-width: 90px;">Ngày</th>
                        <th class="border py-2 px-1">Khoang</th>
                        <th class="border py-2 px-1">Khách hàng</th>
                        <th class="border py-2 px-1">Mã hàng</th>
                        <th class="border py-2 px-1">Model</th>
                        <th class="border py-2 px-1 w-36" style="min-width: 140px;">Loại</th>
                        <th class="border py-2 px-1">Item</th>
                        <th class="border py-2 px-1">Màu</th>
                        <th class="border py-2 px-1">Thông số</th>
                        <th class="border py-2 px-1">Size</th>
                        <th class="border py-2 px-1">Đơn vị</th>
                        <th class="border py-2 px-1">PO</th>
                        <th class="border py-2 px-1 w-16">Nhập/Xuất</th>
                        <th class="border py-2 px-1">Ghi chú</th>
                        <th class="border"></th>

                    </tr>
                </thead>
                <tbody>


                    <?php foreach ($items as $item) {
                        $ordersItem = DB::table('items')->where('order_id', '=', $item->item_id)->fetchAll();
                    ?>
                        <tr item-id="<?= $item->item_id ?>" class="text-center text-sm odd:bg-gray-50 even:bg-gray-200 hover:bg-gray-300 dark:bg-gray-600  dark:hover:bg-gray-700 dark:text-white transition">
                            <td class="border px-1 py-2"><?= formatDate($item->item_date) ?></td>
                            <td class="border px-1 py-2"><a href="items.php?khoang=<?= $item->item_container ?>"><?= $item->item_container ?></a>
                            </td>
                            <td class="border px-1 py-2"><?= $item->item_customer ?></td>
                            <td class="border px-1 py-2"><a href="het.php?ma-hang=<?= $item->item_style ?>"><?= $item->item_style ?></a></td>
                            <td class="border px-1 py-2"><?= $item->item_model ?></td>
                            <td class="border px-1 py-2"><a href="het.php?ma-hang=<?= $item->item_style ?>&plieu=<?= $item->item_type ?>"><?= $item->item_type ?></a>
                            </td>
                            <td class="border px-1 py-2"><?= $item->item_item ?></td>
                            <td class="border px-1 py-2"><?= $item->item_color ?></td>
                            <td class="border px-1 py-2"><?= $item->item_params ?></td>
                            <td class="border px-1 py-2"><?= $item->item_size ?></td>
                            <td class="border px-1 py-2"><?= $item->item_unit ?></td>
                            <td class="border px-1 py-2"><?= $item->item_po ?></td>
                            <td class="border bg-green-700 text-green-200 underline-offset-2 py-2 px-1"><a href="nhap-kho.php?id=<?= $item->item_id ?>"><?= formatNumber($item->item_qty) ?></a></td>
                            <td class="border px-1 py-2"><?= $item->item_note ?></td>
                            <td class="border w-16 px-2">
                                <div class="flex gap-1 justify-between items-center">

                                    <button title="Chi tiết xuất" type="button" class="btn-show-order w-5 transform hover:text-blue-500 transition hover:scale-125" data-id="<?= $item->item_id ?>">
                                        <svg class="img-show" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="hidden img-hidden" viewBox="0 0 24 24" fill="none">
                                            <path d="M2.99902 3L20.999 21M9.8433 9.91364C9.32066 10.4536 8.99902 11.1892 8.99902 12C8.99902 13.6569 10.3422 15 11.999 15C12.8215 15 13.5667 14.669 14.1086 14.133M6.49902 6.64715C4.59972 7.90034 3.15305 9.78394 2.45703 12C3.73128 16.0571 7.52159 19 11.9992 19C13.9881 19 15.8414 18.4194 17.3988 17.4184M10.999 5.04939C11.328 5.01673 11.6617 5 11.9992 5C16.4769 5 20.2672 7.94291 21.5414 12C21.2607 12.894 20.8577 13.7338 20.3522 14.5" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <button type="button" title="Xóa phụ liệu" data-id="<?= $item->item_id ?>" class="btn-delete-item w-5 transform hover:text-red-500 transition hover:scale-110">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>

                            </td>
                        </tr>
                        <?php
                        foreach ($ordersItem as $orderItem) { ?>
                            <tr parent-id="<?= $item->item_id ?>" class="hidden text-center text-sm bg-red-500 hover:bg-opacity-80 text-white dark:bg-gray-600 dark:hover:bg-gray-700 dark:text-white transition">
                                <td class="border px-1 py-2"><?= $orderItem->item_date ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_container ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_customer ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_style ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_model ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_type ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_item ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_color ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_params ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_size ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_unit ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_po ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_qty ?></td>
                                <td class="border px-1 py-2"><?= $orderItem->item_note ?></td>
                                <td class="border px-1 py-2">
                                    <div class="flex gap-1 justify-between items-center">
                                        <a href="sua.php?id=<?= $orderItem->item_id ?>" title="Sửa phụ liệu" class="btn-show-order w-5 transform hover:text-green-500 transition hover:scale-125" data-id="<?= $item->item_id ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <button data-id="<?= $orderItem->item_id ?>" class="btn-delete-item w-5 transform hover:text-red-500 transition hover:scale-110">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        <?php }  ?>

                    <?php } ?>

                </tbody>
            </table>
        </div>


    </div>
    <div id="modal-delete" class="fixed hidden top-0 left-0 right-0 z-50 p-4 overflow-hidden md:inset-0 h-screen bg-black/80 justify-center items-center">
        <div class="relative w-full max-w-md max-h-screen mx-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-red-400 w-12 h-12 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Xóa phụ liệu?</h3>
                    <form id="form-delete" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" class="inline-block">
                        <input type="hidden" name="delete-id" id="delete-id">
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Xóa
                        </button>
                    </form>
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