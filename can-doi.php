<?php
require_once "init.php";
$page_title = "Cân đối";
include_once "header.php";
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $items = DB::table('items')
        ->where('item_customer', '=', post('customer'))
        ->where('item_style', '=', post('style'))
        ->where('item_type', '=', post('type'))
        ->where('item_size', '=', post('size'))
        ->where('item_color', '=', post('color'))
        ->where('item_params', '=', post('params'))
        ->whereNull('order_id')
        ->fetchAll();
    foreach ($items as $item) {
        DB::table('items')->where('item_id', '=', $item->item_id)->update([
            "item_plan" => post('plan'),
            "item_norm" => post('norm')
        ]);
    }
    $_SESSION["success"] = "Thêm KHSX thành công";
    header("Location: can-doi.php?customer=" . post('customer') . "&style=" . post('style'));
    return;
}
if (!isset($_GET['style'])) { ?>
    <div class="container mx-auto p-4">
        <h2 id="item-title" class="text-center font-bold text-xl md:text-2xl dark:text-muted-200 mb-4 text-teal-600">CÂN ĐỐI PHỤ LIỆU</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
            <?php
            $items = DB::table('items')->select("item_customer")->distinct()->fetchAll();
            foreach ($items as $item) { ?>
                <div class="w-full relative text-center rounded-tl-3xl rounded-br-3xl overflow-hidden uppercase max-w-sm bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="p-4 text-base font-bold bg-teal-600 dark:bg-gray-500 text-white md:text-2xl dark:text-white">
                        <?= $item->item_customer ?>
                    </h5>
                    <?php
                    $styles = DB::table('items')
                        ->select('item_style')
                        ->distinct()
                        ->where('item_customer', '=', $item->item_customer)->fetchAll();
                    if ($styles) { ?>
                        <ul class=" space-y-3 p-4">
                            <?php foreach ($styles as $style) { ?>
                                <li>
                                    <a href='can-doi.php?customer=<?= $item->item_customer ?>&style=<?= $style->item_style ?>' class='flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-second-100 hover:bg-second-200 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white'><span class='flex-1 ml-3 whitespace-nowrap'>#<?= $style->item_style ?></span></a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php }
                    ?>


                </div>
            <?php } ?>
        </div>

    </div>
<?php } ?>


<?php
if (isset($_GET['style'], $_GET['customer'], $_GET['type'])) { ?>
    <div class="container mx-auto p-4">
        <form id="form-add-plan" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="bg-muted-200 dark:bg-muted-500 max-w-xl mx-auto p-4 shadow-lg rounded-md">
            <h2 class="text-muted-700 dark:text-muted-200 text-center text-2xl font-bold uppercase mb-5">Cân đối</h2>
            <div class="flex flex-wrap items-center mb-2">
                <label for="customer" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Khách
                    hàng:</label>
                <input readonly type="text" id="customer" name="customer" value="<?= get('customer') ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="style" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Mã
                    hàng:</label>
                <input readonly type="text" id="style" name="style" value="<?= get('style') ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="type" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Mã
                    hàng:</label>
                <input readonly type="text" id="type" name="type" value="<?= get('type') ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="color" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Màu:</label>
                <input readonly type="text" id="color" name="color" value="<?= get('color') ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="size" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Màu:</label>
                <input readonly type="text" id="size" name="size" value="<?= get('size') ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="params" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Màu:</label>
                <input readonly type="text" id="params" name="params" value="<?= get('params') ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="plan" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">KHSX:</label>
                <input required type="number" step="0.01" id="plan" name="plan" value="<?= !empty("plan") ? get('plan') : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="norm" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Định
                    mức:</label>
                <input required type="number" step="0.01" id="norm" name="norm" value="<?= !empty("norm") ? get('norm') : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>


            <div class="flex gap-2 justify-end mt-4">
                <a href="index.php" class="flex  px-5 py-2.5 text-sm font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800">
                    Hủy
                </a>
                <button type="submit" class="flex uppercase px-5 py-2.5 text-sm font-medium text-center text-muted-50 bg-teal-600 hover:bg-teal-700 dark:bg-muted-700 dark:hover:bg-muted-800 dark:text-muted-200 rounded-lg">
                    Thêm
                </button>
            </div>
        </form>
    </div>
<?php return;
} ?>
<?php
if (isset($_GET['style'], $_GET['customer'])) { ?>
    <div class="p-4">
        <h2 class="text-center font-bold text-2xl dark:text-muted-200 mb-4 uppercase text-teal-600">cân đối mã
            hàng <?= get('style') ?></h2>

        <div id="table" class="w-full">
            <div class="border-muted-200 dark:border-muted-700 border rounded-md overflow-hidden shadow-lg">
                <div class="min-w-full align-middle overflow-x-auto">
                    <table class="divide-muted-200 dark:divide-muted-700 min-w-full table-fixed divide-y text-center">
                        <thead>
                            <tr>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Khách hàng</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Mã hàng</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Loại</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Màu</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Thông số</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Size</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Đơn vị</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">KHSX</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">ĐỊNH MỨC</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">SL CẦN</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">SL NHẬP</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">LỆCH</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">SL XUẤT</th>
                                <th class="text-muted-700 dark:text-muted-400 font-bold text-xs uppercase border-muted-200 dark:border-muted-700 last:border-e-none dark:bg-muted-800 border-r bg-white px-1 py-4">Tồn</th>
                            </tr>
                        </thead>
                        <tbody class="divide-muted-200 dark:divide-muted-700 dark:bg-muted-800 divide-y bg-muted-50">

                            <?php
                            $items = DB::table('items')
                                ->where('item_style', '=', get('style'))
                                ->whereNull('order_id')
                                ->groupBy('item_type, item_color, item_size, item_params')
                                ->fetchAll();
                            foreach ($items as $item) { ?>
                                <tr itemId="<?= $item->item_id ?>" class="hover:bg-muted-100 dark:hover:bg-muted-900 transition-colors duration-200">



                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_customer ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><a class="underline" href="items.php?ma-hang=<?= $item->item_style ?>"><?= $item->item_style ?></a>
                                    </td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><a class="underline" href="items.php?ma-hang=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>"><?= $item->item_type ?></a>
                                    </td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_color ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_params ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_size ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_unit ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap">
                                        <?php
                                        if ($item->item_plan) { ?>
                                            <a class="underline" href="can-doi.php?customer=<?= $item->item_customer ?>&style=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>&plan=<?= $item->item_plan ?>&norm=<?= $item->item_norm ?>"><?= $item->item_plan ?></a>
                                        <?php } else { ?>
                                            <a class="no-underline flex justify-center transform hover:text-blue-500 transition hover:scale-110" href="can-doi.php?customer=<?= $item->item_customer ?>&style=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>&plan=<?= $item->item_plan ?>&norm=<?= $item->item_norm ?>">
                                                <svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="16">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                </svg>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $item->item_norm ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?php $need_qty = $item->item_plan * $item->item_norm;
                                                                                                                                                        echo $need_qty ?: ""; ?></td>
                                    <?php

                                    $getItems = DB::table('items')
                                        ->where('item_customer', '=', $item->item_customer)
                                        ->where('item_style', '=', $item->item_style)
                                        ->where('item_type', '=', $item->item_type)
                                        ->where('item_color', '=', $item->item_color)
                                        ->where('item_size', '=', $item->item_size)
                                        ->where('item_params', '=', $item->item_params)
                                        ->whereNull('order_id')
                                        ->fetchAll();
                                    $total = 0;
                                    $totalSumOrder = 0;
                                    $totalSumInventory = 0;
                                    foreach ($getItems as $getItem) {
                                        $total += $getItem->item_qty;
                                        $totalOrders = DB::table('items')->where('order_id', '=', $getItem->item_id)->sum('item_qty');
                                        $totalSumOrder += $totalOrders;
                                        $totalSumInventory += $getItem->item_qty - $totalOrders;
                                    }

                                    ?>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $total ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $total - $need_qty ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $totalSumOrder ?></td>
                                    <td class="text-sm border-r dark:border-muted-700 text-muted-700 dark:text-muted-200 py-2 px-1 whitespace-nowrap"><?= $totalSumInventory ?></td>

                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>




        <div class="overflow-x-auto md:overflow-x-hidden shadow-md hidden">

            <table class="w-full border-collapse table-auto text-xs">
                <thead>
                    <tr class="bg-gray-300 dark:bg-slate-900/70 dark:text-white text-gray-600 uppercase text-sm leading-normal text-center">
                        <th class="border py-1 px-2 w-14">Khách hàng</th>
                        <th class="border py-1 px-2">Mã hàng</th>
                        <th class="border py-1 px-2">Loại</th>
                        <th class="border py-1 px-2">Màu</th>
                        <th class="border py-1 px-2">Thông số</th>
                        <th class="border py-1 px-2">Size</th>
                        <th class="border py-1 px-2">Đơn vị</th>
                        <th class="border py-1 px-2">KHSX</th>
                        <th class="border py-1 px-2">ĐỊNH MỨC</th>
                        <th class="border py-1 px-2">SL CẦN</th>
                        <th class="border py-1 px-2">SL NHẬP</th>
                        <th class="border py-1 px-2">LỆCH</th>
                        <th class="border py-1 px-2">SL XUẤT</th>
                        <th class="border py-1 px-2">Tồn</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $items = DB::table('items')
                        ->where('item_style', '=', get('style'))
                        ->whereNull('order_id')
                        ->groupBy('item_type, item_color, item_size, item_params')
                        ->fetchAll();
                    foreach ($items as $item) { ?>
                        <tr itemId="<?= $item->item_id ?>" class="text-center text-sm odd:bg-gray-50 even:bg-gray-200 hover:bg-gray-300 dark:bg-gray-600  dark:hover:bg-gray-700 dark:text-white transition">

                            <td class="border px-1 py-2"><?= $item->item_customer ?></td>
                            <td class="border px-1 py-2"><a class="underline" href="items.php?ma-hang=<?= $item->item_style ?>"><?= $item->item_style ?></a>
                            </td>
                            <td class="border px-1 py-2"><a class="underline" href="items.php?ma-hang=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>"><?= $item->item_type ?></a>
                            </td>
                            <td class="border px-1 py-2"><?= $item->item_color ?></td>
                            <td class="border px-1 py-2"><?= $item->item_params ?></td>
                            <td class="border px-1 py-2"><?= $item->item_size ?></td>
                            <td class="border px-1 py-2"><?= $item->item_unit ?></td>
                            <td class="border px-1 py-2">
                                <?php
                                if ($item->item_plan) { ?>
                                    <a class="underline" href="can-doi.php?customer=<?= $item->item_customer ?>&style=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>&plan=<?= $item->item_plan ?>&norm=<?= $item->item_norm ?>"><?= $item->item_plan ?></a>
                                <?php } else { ?>
                                    <a class="no-underline flex justify-center transform hover:text-blue-500 transition hover:scale-110" href="can-doi.php?customer=<?= $item->item_customer ?>&style=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>&plan=<?= $item->item_plan ?>&norm=<?= $item->item_norm ?>">
                                        <svg fill="none" height="16" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="16">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </a>
                                <?php } ?>
                            </td>
                            <td class="border px-1 py-2"><?= $item->item_norm ?></td>
                            <td class="border px-1 py-2"><?php $need_qty = $item->item_plan * $item->item_norm;
                                                            echo $need_qty ?: ""; ?></td>
                            <?php

                            $getItems = DB::table('items')
                                ->where('item_customer', '=', $item->item_customer)
                                ->where('item_style', '=', $item->item_style)
                                ->where('item_type', '=', $item->item_type)
                                ->where('item_color', '=', $item->item_color)
                                ->where('item_size', '=', $item->item_size)
                                ->where('item_params', '=', $item->item_params)
                                ->whereNull('order_id')
                                ->fetchAll();
                            $total = 0;
                            $totalSumOrder = 0;
                            $totalSumInventory = 0;
                            foreach ($getItems as $getItem) {
                                $total += $getItem->item_qty;
                                $totalOrders = DB::table('items')->where('order_id', '=', $getItem->item_id)->sum('item_qty');
                                $totalSumOrder += $totalOrders;
                                $totalSumInventory += $getItem->item_qty - $totalOrders;
                            }

                            ?>
                            <td class="border px-1 py-2"><?= $total ?></td>
                            <td class="border px-1 py-2"><?= $total - $need_qty ?></td>
                            <td class="border px-1 py-2"><?= $totalSumOrder ?></td>
                            <td class="border px-1 py-2"><?= $totalSumInventory ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>


    </div>

<?php } ?>


<?php
include_once "footer.php";
