<?php
require_once "init.php";
include_once "header.php";
$headerTitle = "QUẢN LÝ XUẤT NHẬP TỒN";
$items = DB::table('items')
    ->orderBy('created_at', 'desc')
    ->limit(50)
    ->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete-id'])) {
    $item = DB::table('items')->where('item_id', '=', post('delete-id'));
    if ($item->fetch()->order_id) {
        DB::table('items')
            ->where('item_id', '=', $item->fetch()->order_id)
            ->update([
                'item_sold_out' => 0
            ]);
    }
    $item->delete();
    $_SESSION['success'] = "Xóa thành công.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
$_SESSION['page'] = $_SERVER['REQUEST_URI'];

?>
<div class="p-4" id="items">
    <h2 id="item-title" class="text-center font-bold text-xl md:text-2xl dark:text-muted-200 mb-4 text-orange-500"><?= $headerTitle ?></h2>
    <div id="table" class="w-full">
        <div class="border-muted-200 dark:border-muted-700 border rounded-md overflow-hidden shadow-lg">
            <div class="inline-block min-w-full align-middle">
                <table class="divide-muted-200 dark:divide-muted-700 min-w-full table-fixed divide-y text-center">
                    <thead>
                        <tr>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Ngày</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Khoang</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Khách hàng</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Mã hàng</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Model</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4" style="min-width: 140px;">Loại</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Item</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Màu</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Thông số</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Size</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Đơn vị</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">PO</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Nhập</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Xuất</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Ghi chú</th>
                            <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-muted-200 dark:divide-muted-700 dark:bg-muted-800 divide-y bg-muted-50">

                        <?php foreach ($items as $item) { ?>
                            <tr class="hover:bg-muted-100 dark:hover:bg-muted-900 transition-colors duration-200">



                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= formatDate($item->item_date) ?></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><a href="items.php?khoang=<?= $item->item_container ?>"><?= $item->item_container ?></a></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= $item->item_customer ?></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><a href="items.php?ma-hang=<?= $item->item_style ?>"><?= $item->item_style ?></a></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= $item->item_model ?></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><a href="items.php?ma-hang=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>"><?= $item->item_type ?></a></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= $item->item_item ?></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= $item->item_color ?></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= $item->item_params ?></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= $item->item_size ?></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= $item->item_unit ?></td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= $item->item_po ?></td>



                                <?php
                                if (!$item->order_id) { ?>
                                    <td class="text-sm border-r dark:border-muted-700  dark:text-white bg-green-700 text-green-200">
                                        <a href="nhap-kho.php?id=<?= $item->item_id ?>"><?= formatNumber($item->item_qty) ?></a>
                                    </td>
                                <?php } else { ?>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white">
                                        <a class="no-underline flex justify-center text-green-600 dark:text-green-300 transition hover:scale-125" href="nhap-kho.php?id=<?= $item->item_id ?>">
                                            <svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="16">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    </td>

                                <?php } ?>
                                <?php
                                if ($item->order_id) { ?>
                                    <td class="text-sm border-r dark:border-muted-700  dark:text-white bg-red-700 text-red-200">
                                        <a href="xuat-kho.php?id=<?= $item->order_id ?>"><?= formatNumber($item->item_qty) ?></a>
                                    </td>
                                <?php } else { ?>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white">
                                        <a class="no-underline flex justify-center text-red-600 dark:text-red-300 transition hover:scale-125" href="xuat-kho.php?id=<?= $item->item_id ?>">
                                            <svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="16">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                    </td>
                                <?php } ?>
                                </td>
                                <td class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1"><?= $item->item_note ?></td>
                                <td title="<?= $item->item_id ?>" class="text-sm border-r dark:border-muted-700 text-muted-500 dark:text-white py-2 px-1">
                                    <div class="flex gap-1 justify-center items-center px-2">
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
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<div id="modal" class="fixed hidden top-0 left-0 right-0 z-50 p-4 overflow-hidden md:inset-0 h-screen bg-black/80 justify-center items-center">
    <div class="relative w-full max-w-md max-h-screen mx-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
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

<?php require_once "footer.php"; ?>