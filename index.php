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
    <h2 class="text-center font-bold text-2xl dark:text-white mb-4"><?= $headerTitle ?></h2>
    <div class="relative overflow-x-auto md:overflow-x-hidden bg-white shadow-lg rounded ">
        <table class="w-full border-collapse table-auto text-xs">
            <thead>
                <tr class="bg-gray-300 dark:bg-slate-900/70 dark:text-white text-gray-600 uppercase text-sm leading-normal text-center">
                    <th class="py-2 border px-1 w-32" style="min-width: 100px;">Ngày</th>
                    <th class="py-2 border px-1">Khoang</th>
                    <th class="py-2 border px-1">Khách hàng</th>
                    <th class="py-2 border px-1">Mã hàng</th>
                    <th class="py-2 border px-1">Model</th>
                    <th class="py-2 border px-1 w-36" style="min-width: 140px;">Loại</th>
                    <th class="py-2 border px-1">Item</th>
                    <th class="py-2 border px-1" style="min-width: 100px;">Màu</th>
                    <th class="py-2 border px-1" style="min-width: 60px;">Thông số</th>
                    <th class="py-2 border px-1" style="min-width: 100px;">Size</th>
                    <th class="py-2 border px-1">Đơn vị</th>
                    <th class="py-2 border px-1" style="min-width: 150px;">PO</th>
                    <th class="py-2 border px-1 w-20" style="min-width: 70px;">Nhập</th>
                    <th class="py-2 border px-1 w-20" style="min-width: 70px;">Xuất</th>
                    <th class="py-2 border px-1 w-36">Ghi chú</th>
                    <th class="py-2 border px-1 w-20" style="min-width: 40px;"></th>

                </tr>
            </thead>
            <tbody>


                <?php foreach ($items as $item) { ?>
                    <tr class="text-center text-sm odd:bg-gray-50 even:bg-gray-200 hover:bg-gray-300 dark:bg-gray-600  dark:hover:bg-gray-700 dark:text-white transition">
                        <td class="border py-2 px-1 w-32"><?= formatDate($item->item_date) ?></td>
                        <td class="border py-2 px-1"><a href="items.php?khoang=<?= $item->item_container ?>"><?= $item->item_container ?></a>
                        </td>
                        <td class="border py-2 px-1"><?= $item->item_customer ?></td>
                        <td class="border py-2 px-1"><a href="items.php?ma-hang=<?= $item->item_style ?>"><?= $item->item_style ?></a></td>
                        <td class="border py-2 px-1"><?= $item->item_model ?></td>
                        <td class="border py-2 px-1"><a href="items.php?ma-hang=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>"><?= $item->item_type ?></a>
                        </td>
                        <td class="border py-2 px-1"><?= $item->item_item ?></td>
                        <td class="border py-2 px-1"><?= $item->item_color ?></td>
                        <td class="border py-2 px-1"><?= $item->item_params ?></td>
                        <td class="border py-2 px-1"><?= $item->item_size ?></td>
                        <td class="border py-2 px-1"><?= $item->item_unit ?></td>
                        <td class="border py-2 px-1"><?= $item->item_po ?></td>
                        <td class="border">
                            <?php
                            if (!$item->order_id) { ?>
                                <a class="bg-green-700 text-green-200 underline-offset-2 block py-2 px-1" href="nhap-kho.php?id=<?= $item->item_id ?>"><?= formatNumber($item->item_qty) ?></a>
                            <?php } else { ?>
                                <a class="no-underline flex justify-center text-green-600 dark:text-green-300 transition hover:scale-125" href="nhap-kho.php?id=<?= $item->item_id ?>">
                                    <svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="16">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>


                            <?php } ?>
                        </td>
                        <td class="border">
                            <?php
                            if ($item->order_id) { ?>
                                <a class="bg-red-700 text-red-200 underline-offset-2 block py-2 px-1" href="xuat-kho.php?id=<?= $item->order_id ?>"><?= formatNumber($item->item_qty) ?></a>
                            <?php } else { ?>
                                <a class="no-underline flex justify-center text-red-600 dark:text-red-300 transition hover:scale-125" href="xuat-kho.php?id=<?= $item->item_id ?>">
                                    <svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="16">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                            <?php } ?>
                        </td>
                        <td class="border py-2 px-1"><?= $item->item_note ?></td>
                        <td title="<?= $item->item_id ?>" class="border w-16">
                            <div class="flex gap-1 justify-center items-center">
                                <button data-id="<?= $item->item_id ?>" class="btn-delete-item w-5 transform hover:text-red-500 transition hover:scale-110">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>

                            </div>

                        </td>
                    </tr>

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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const btnDeleteItem = document.querySelectorAll(".btn-delete-item");
        btnDeleteItem.forEach(el => {
            el.addEventListener("click", function() {
                document.querySelector("#modal-delete").classList.replace("hidden", "flex");
                document.querySelector("#form-delete #delete-id").value = this.dataset.id;
            })
        });
        document.querySelector("#btn-close-modal")?.addEventListener("click", function() {
            document.querySelector("#modal-delete").classList.replace("flex", "hidden");
        });
    })
</script>
<?php require_once "footer.php"; ?>