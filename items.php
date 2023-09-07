<?php
require_once "init.php";
include_once "header.php";
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
    DB::table('items')->where('item_id', '=', post('sold-out'))->update([
        "item_sold_out" => 1
    ]);
    $_SESSION['success'] = "Phụ liệu đã hết.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

if (count($_GET) == 1 && isset($_GET['ma-hang'])) {
    $items = DB::table('items')
        ->where('item_style', '=', get('ma-hang'))
        ->whereNull('order_id')
        ->where('item_sold_out', "=", 0)
        ->groupBy('item_type, item_color, item_size, item_params')
        ->fetchAll();
}
if (count($_GET) == 1 && isset($_GET['khoang'])) {
    $items = DB::table('items')
        ->where('item_container', '=', get('khoang'))
        ->whereNull('order_id')
        ->where('item_sold_out', "=", 0)
        ->fetchAll();
}
if (count($_GET) == 1 && isset($_GET['qlxn'])) {
    $items = DB::table('items')
        ->orderBy('created_at', 'desc')
        ->limit(50)
        ->fetchAll();
}
if (count($_GET) == 1 && isset($_GET['het'])) {
    $items = DB::table('items')
        // ->orderBy('created_at', 'desc')
        ->where('item_sold_out', '=', 1)
        ->whereNull('order_id')
        ->fetchAll();
}
if (count($_GET) == 2 && isset($_GET['search-type'], $_GET['search-value'])) {
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
if (count($_GET) == 2 && isset($_GET['ma-hang'], $_GET['khoang'])) {
    $items = DB::table('items')
        ->where('item_style', '=', get('ma-hang'))
        ->where('item_container', '=', get('khoang'))
        ->whereNull('order_id')
        ->where('item_sold_out', "=", 0)
        ->orderBy('item_type')
        ->fetchAll();
}
if (count($_GET) == 5 && isset($_GET['ma-hang'], $_GET['type'])) {
    $items = DB::table('items')
        ->where('item_style', '=', get('ma-hang'))
        ->where('item_type', '=', get('type'))
        ->where('item_color', '=', get('color'))
        ->where('item_size', '=', get('size'))
        ->where('item_params', '=', get('params'))
        ->whereNull('order_id')
        ->where('item_sold_out', "=", 0)
        ->fetchAll();
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
$_SESSION['page'] = $_SERVER['REQUEST_URI'];
if (!count($items)) {
    echo "<h2 class='p-4 text-center font-bold text-2xl dark:text-white mb-4'>PHỤ LIỆU TRỐNG</h2>";
    exit();
}
?>

<div class="p-4" id="items">

    <h2 class="text-center font-bold text-2xl dark:text-white mb-4">QUẢN LÝ XUẤT NHẬP TỒN PHỤ LIỆU</h2>
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
                    <th class="border py-1 px-2 w-20">Tồn</th>
                    <th class="border py-1 px-2 w-20">Ghi chú</th>
                    <th class="border w-16"></th>

                </tr>
            </thead>
            <tbody>


                <?php foreach ($items as $index => $item) {
                    $class = $item->order_id ? "bg-danger-500 text-white" : "bg-success-500 text-black";
                    $totalOrder = DB::table('items')->where('order_id', '=', $item->item_id)->sum('item_qty');
                    $inventory = $item->item_qty - $totalOrder;
                    $index++;
                ?>
                    <tr item-id="<?= $item->item_id ?>" class="<?= $class ?> dark:odd:bg-gray-500 dark:even:bg-gray-600 text-black dark:text-white  dark:border-gray-700 hover:contrast-75 transition">
                        <td class="border px-1 py-2"><?= formatDate($item->item_date) ?></td>
                        <td class="border px-1 py-2"><?= $item->item_container ?></td>
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
                        <?php
                        if ($item->order_id) { ?>
                            <td class="border px-1 py-2"></td>
                            <td class="border px-1 py-2 dark:bg-red-600 "><?= formatNumber($item->item_qty) ?>

                            </td>
                            <td class="border px-1 py-2"></td>
                        <?php  } else { ?>
                            <td class="border px-1 py-2 dark:bg-green-600 "><a href="nhap-kho.php?id=<?= $item->item_id ?>"><?= formatNumber($item->item_qty) ?></a></td>
                            <td class="border px-1 py-2">
                                <?php
                                if ($inventory != 0) { ?>
                                    <a href="xuat-kho.php?id=<?= $item->item_id ?>"><?= formatNumber($totalOrder) ?></a>
                                <?php  } else { ?>
                                    <?= formatNumber($totalOrder) ?>
                                <?php } ?>
                            </td>
                            <td class="border px-1 py-2"><?= $inventory != 0 ? formatNumber($inventory) : "" ?>
                                <?php
                                if ($inventory == 0) { ?>
                                    <form class="inline-block ml-1" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                                        <input type="hidden" value="<?= $item->item_id ?>" name="sold-out">
                                        <button class="bg-danger-500 text-white p-1 rounded" type="submit">HẾT</button>
                                    </form>
                                <?php } ?>
                            </td>

                        <?php } ?>
                        <td class="border px-1 py-2"><?= $item->item_note ?></td>
                        <td class="border w-16">
                            <div class="flex gap-1 justify-center items-center">

                                <?php
                                if (!$item->order_id && $totalOrder != 0) { ?>
                                    <button title="Chi tiết xuất" type="button" class="btn-show-order inline-block" data-id="<?= $item->item_id ?>">
                                        <svg class="block" width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g id="Vector">
                                                <path d="M3.5868 13.7788C5.36623 15.5478 8.46953 17.9999 12.0002 17.9999C15.5308 17.9999 18.6335 15.5478 20.413 13.7788C20.8823 13.3123 21.1177 13.0782 21.2671 12.6201C21.3738 12.2933 21.3738 11.7067 21.2671 11.3799C21.1177 10.9218 20.8823 10.6877 20.413 10.2211C18.6335 8.45208 15.5308 6 12.0002 6C8.46953 6 5.36623 8.45208 3.5868 10.2211C3.11714 10.688 2.88229 10.9216 2.7328 11.3799C2.62618 11.7067 2.62618 12.2933 2.7328 12.6201C2.88229 13.0784 3.11714 13.3119 3.5868 13.7788Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M10 12C10 13.1046 10.8954 14 12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </g>
                                        </svg>
                                    </button>
                                <?php }
                                ?>
                                <form class="flex" onsubmit="event.preventDefault();if (confirm('Có chắc muốn xóa?')) this.submit()" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                                    <input type="hidden" name="delete-id" value="<?= $item->item_id ?>">

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
                <?php
                if (isset($total)) { ?>
                    <tr class="text-sm font-bold overflow-hidden text-gray-700 uppercase bg-gray-50 dark:bg-gray-900 dark:text-gray-400">
                        <td colspan="12">Tổng</td>
                        <td class="border-x px-0 py-1 text-success-500"><?= formatNumber($total) ?></td>
                        <td class="border-x px-0 py-1 text-danger-500"><?= formatNumber($totalSumOrder) ?></td>
                        <td class="border-x px-0 py-1 text-warning-500"><?= formatNumber($totalSumInventory) ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>


</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Sold out
        const btnSoldOut = document.querySelectorAll(".btn-sold-out");
        btnSoldOut.forEach(el => {
            el.addEventListener("click", async function(e) {
                const id = e.target.dataset.id;
                console.log(id);
            })
        })


        // Order
        const btnShowOrder = document.querySelectorAll(".btn-show-order");
        btnShowOrder.forEach(el => {
            el.addEventListener("click", async function(e) {
                const id = e.target.dataset.id;
                const response = await fetch('services.php?get-order=' + id);
                const data = await response.json();
                if (data.itemsOrder.length) {
                    el.disabled = true;
                    let html = "";
                    data.itemsOrder.forEach(item => {
                        html += `<tr class="bg-danger-500 text-white dark:bg-danger-800 dark:border-gray-700 hover:contrast-75 transition dark:hover:bg-gray-600">
                        <td class="border px-1 py-2">${item.item_date}</td>
                        <td class="border px-1 py-2">${item.item_container}</td>
                        <td class="border px-1 py-2">${item.item_customer}</td>
                        <td class="border px-1 py-2">${item.item_style}</td>
                        <td class="border px-1 py-2">${item.item_model}</td>
                        <td class="border px-1 py-2">${item.item_type}</td>
                        <td class="border px-1 py-2">${item.item_item}</td>
                        <td class="border px-1 py-2">${item.item_color}</td>
                        <td class="border px-1 py-2">${item.item_params}</td>
                        <td class="border px-1 py-2">${item.item_size}</td>
                        <td class="border px-1 py-2">${item.item_unit}</td>
                        <td class="border px-1 py-2">${item.item_po}</td>
                        <td class="border px-1 py-2"></td>
                        <td class="border px-1 py-2">${item.item_qty}</td>
                        <td class="border px-1 py-2"></td>
                        <td class="border px-1 py-2">${item.item_note}</td>
                        <td class="border px-1 py-2">
                        <form onsubmit="event.preventDefault();if (confirm('Có chắc muốn xóa?')) this.submit()" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                                <input type="hidden" name="delete-id" value="${item.item_id}">
                                <?php
                                if (isset($_GET['het'])) { ?>
                                        <input type="hidden" name="redo-sold-out" value="${item.order_id}">
                                    <?php }
                                    ?>
                                <button type="submit"><svg x="0px" y="0px" width="20" height="20" viewBox="0,0,256,256">
<g fill="#fff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(8.53333,8.53333)"><path d="M14.98438,2.48633c-0.55152,0.00862 -0.99193,0.46214 -0.98437,1.01367v0.5h-5.5c-0.26757,-0.00363 -0.52543,0.10012 -0.71593,0.28805c-0.1905,0.18793 -0.29774,0.44436 -0.29774,0.71195h-1.48633c-0.36064,-0.0051 -0.69608,0.18438 -0.87789,0.49587c-0.18181,0.3115 -0.18181,0.69676 0,1.00825c0.18181,0.3115 0.51725,0.50097 0.87789,0.49587h18c0.36064,0.0051 0.69608,-0.18438 0.87789,-0.49587c0.18181,-0.3115 0.18181,-0.69676 0,-1.00825c-0.18181,-0.3115 -0.51725,-0.50097 -0.87789,-0.49587h-1.48633c0,-0.26759 -0.10724,-0.52403 -0.29774,-0.71195c-0.1905,-0.18793 -0.44836,-0.29168 -0.71593,-0.28805h-5.5v-0.5c0.0037,-0.2703 -0.10218,-0.53059 -0.29351,-0.72155c-0.19133,-0.19097 -0.45182,-0.29634 -0.72212,-0.29212zM6,9l1.79297,15.23438c0.118,1.007 0.97037,1.76563 1.98438,1.76563h10.44531c1.014,0 1.86538,-0.75862 1.98438,-1.76562l1.79297,-15.23437z"></path></g></g>
</svg></button>
                            </form>
                        </td>
                    </tr>`;
                    })
                    const existingElement = el.closest("tr");

                    existingElement.insertAdjacentHTML('afterend', html);
                }
            })
        });

    })
</script>

<?php
require_once "footer.php";
?>