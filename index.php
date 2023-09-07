<?php
require_once "init.php";
include_once "header.php";
$headerTitle = "QUẢN LÝ XUẤT NHẬP TỒN";
$items = DB::table('items')
    ->orderBy('created_at', 'desc')
    ->limit(50)
    ->fetchAll();
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
$_SESSION['page'] = $_SERVER['REQUEST_URI'];


?>
<div class="p-4" id="items">
    <h2 class="text-center font-bold text-2xl dark:text-white mb-4"><?= $headerTitle ?></h2>
    <div class="relative overflow-x-auto md:overflow-x-hidden shadow-md ">
        <table class="w-full border-collapse border border-slate-400 text-sm text-center text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-900 dark:text-gray-400">
                <tr>
                    <th class="border py-1 px-2 w-24">Ngày</th>
                    <th class="border py-1 px-2 w-14">Khoang</th>
                    <th class="border py-1 px-2 w-14">Khách hàng</th>
                    <th class="border py-1 px-2">Mã hàng</th>
                    <th class="border py-1 px-2">Model</th>
                    <th class="border py-1 px-2">Loại</th>
                    <th class="border py-1 px-2">Item</th>
                    <th class="border py-1 px-2">Màu</th>
                    <th class="border py-1 px-2">Thông số</th>
                    <th class="border py-1 px-2 w-20">Size</th>
                    <th class="border py-1 px-2">Đơn vị</th>
                    <th class="border py-1 px-2">PO</th>
                    <th class="border py-1 px-2 w-20">Nhập</th>
                    <th class="border py-1 px-2 w-20">Xuất</th>
                    <th class="border py-1 px-2 w-20">Ghi chú</th>
                    <th class="border w-16"></th>

                </tr>
            </thead>
            <tbody>


                <?php foreach ($items as $item) {
                    $class = $item->order_id ? "bg-danger-500 text-white" : "bg-success-500 text-black";

                ?>
                    <tr class="<?= $class ?> dark:odd:bg-gray-500 dark:even:bg-gray-600 text-black dark:text-white  dark:border-gray-700 hover:contrast-75 transition">
                        <td class="border px-1 py-2"><?= formatDate($item->item_date) ?></td>
                        <td class="border px-1 py-2"><a href="items.php?khoang=<?= $item->item_container ?>"><?= $item->item_container ?></a></td>
                        <td class="border px-1 py-2"><?= $item->item_customer ?></td>
                        <td class="border px-1 py-2"><a href="items.php?ma-hang=<?= $item->item_style ?>"><?= $item->item_style ?></a></td>
                        <td class="border px-1 py-2"><?= $item->item_model ?></td>
                        <td class="border px-1 py-2"><a href="items.php?ma-hang=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>"><?= $item->item_type ?></a></td>
                        <td class="border px-1 py-2"><?= $item->item_item ?></td>
                        <td class="border px-1 py-2"><?= $item->item_color ?></td>
                        <td class="border px-1 py-2"><?= $item->item_params ?></td>
                        <td class="border px-1 py-2"><?= $item->item_size ?></td>
                        <td class="border px-1 py-2"><?= $item->item_unit ?></td>
                        <td class="border px-1 py-2"><?= $item->item_po ?></td>
                        <td class="border px-1 py-2">
                            <?php
                            if (!$item->order_id) { ?>
                                <a href="nhap-kho.php?id=<?= $item->item_id ?>"><?= formatNumber($item->item_qty) ?></a>
                            <?php } else { ?>
                                <a class="no-underline flex justify-center" href="nhap-kho.php?id=<?= $item->item_id ?>"><svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="16">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg></a>


                            <?php } ?>
                        </td>
                        <td class="border px-1 py-2">
                            <?php
                            if ($item->order_id) { ?>
                                <a href="xuat-kho.php?id=<?= $item->order_id ?>"><?= formatNumber($item->item_qty) ?></a>
                            <?php } else { ?>
                                <a class="no-underline flex justify-center" href="xuat-kho.php?id=<?= $item->item_id ?>"><svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="16">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg></a>
                            <?php } ?>
                        </td>
                        <td class="border px-1 py-2"><?= $item->item_note ?></td>
                        <td title="<?= $item->item_id ?>" class="border w-16">
                            <div class="flex gap-1 justify-center items-center">
                                <form class="flex" onsubmit="event.preventDefault();if (confirm('Có chắc muốn xóa?')) this.submit()" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                                    <input type="hidden" name="delete-id" value="<?= $item->item_id ?>">
                                    <?php if ($item->order_id) { ?>
                                        <input type="hidden" name="redo-sold-out" value="<?= $item->order_id ?>">
                                    <?php }
                                    ?>
                                    <button type="submit"><svg x="0px" y="0px" width="20" height="20" viewBox="0,0,256,256">
                                            <g fill="#fff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                                                <g transform="scale(8.53333,8.53333)">
                                                    <path d="M14.98438,2.48633c-0.55152,0.00862 -0.99193,0.46214 -0.98437,1.01367v0.5h-5.5c-0.26757,-0.00363 -0.52543,0.10012 -0.71593,0.28805c-0.1905,0.18793 -0.29774,0.44436 -0.29774,0.71195h-1.48633c-0.36064,-0.0051 -0.69608,0.18438 -0.87789,0.49587c-0.18181,0.3115 -0.18181,0.69676 0,1.00825c0.18181,0.3115 0.51725,0.50097 0.87789,0.49587h18c0.36064,0.0051 0.69608,-0.18438 0.87789,-0.49587c0.18181,-0.3115 0.18181,-0.69676 0,-1.00825c-0.18181,-0.3115 -0.51725,-0.50097 -0.87789,-0.49587h-1.48633c0,-0.26759 -0.10724,-0.52403 -0.29774,-0.71195c-0.1905,-0.18793 -0.44836,-0.29168 -0.71593,-0.28805h-5.5v-0.5c0.0037,-0.2703 -0.10218,-0.53059 -0.29351,-0.72155c-0.19133,-0.19097 -0.45182,-0.29634 -0.72212,-0.29212zM6,9l1.79297,15.23438c0.118,1.007 0.97037,1.76563 1.98438,1.76563h10.44531c1.014,0 1.86538,-0.75862 1.98438,-1.76562l1.79297,-15.23437z"></path>
                                                </g>
                                            </g>
                                        </svg></button>
                                </form>
                            </div>

                        </td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>
    </div>


</div>