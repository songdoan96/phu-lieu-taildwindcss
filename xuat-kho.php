<?php
require_once "init.php";
require_once "header.php";
if (isset($_GET['id'])) {
    $item = DB::table('items')->where('item_id', '=', get('id'))->fetch();
    $totalOrder = DB::table('items')
        ->where('order_id', '=', get('id'))
        ->sum('item_qty');
    $inventory = $item->item_qty - $totalOrder;
    if ($inventory == 0) {
        $_SESSION['error'] = "Phụ liệu đã hết.";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['order-item'])) {
    $item = DB::table('items')->where('item_id', '=', post('id'))->fetch();

    DB::table('items')->insert([
        'item_customer' => $item->item_customer,
        'item_style' => $item->item_style,
        'item_type' => $item->item_type,
        'item_style' => $item->item_style,
        'item_container' => postToUpper('container'),
        'item_model' => $item->item_model,
        'item_item' => $item->item_item,
        'item_color' => $item->item_color,
        'item_params' => $item->item_params,
        'item_size' => $item->item_size,
        'item_po' => $item->item_po,
        'item_unit' => $item->item_unit,
        'item_qty' => post('qty'),
        'item_note' => post('note'),
        'item_date' => post('date'),
        'number_group' => post('number_group'),
        'order_id' => $item->item_id,
        'user_id' => post('user_id'),
    ]);

    $_SESSION['success'] = "Xuất kho thành công.";

    if (isset($_SESSION['page'])) {
        header('Location: ' . $_SESSION['page']);
    } else {
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }
    exit();
}
?>

<?php
if (isset($item)) { ?>
    <div class="container mx-auto p-4">
        <form id="form-add-customer" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="bg-muted-200 dark:bg-muted-500 max-w-xl mx-auto p-4 shadow-lg rounded-md">
            <h2 class="text-muted-700 dark:text-muted-200 text-center text-2xl font-bold uppercase mb-5">Xuất kho</h2>
            <div class="flex flex-wrap items-center mb-2">
                <label for="customer" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Khách
                    hàng:</label>
                <input disabled type="text" id="customer" name="customer" value="<?= $item ? $item->item_customer : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="style" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Mã
                    hàng:</label>
                <input disabled type="text" id="style" name="style" value="<?= $item ? $item->item_style : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="type" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Loại:</label>
                <input disabled type="text" id="type" name="type" value="<?= $item ? $item->item_type : "" ?>" class="uppercase w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>


            <div class="flex flex-wrap items-center mb-2">
                <label for="model" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Model:</label>
                <input disabled type="text" id="model" name="model" value="<?= $item ? $item->item_model : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="item" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Item:</label>
                <input disabled type="text" id="item" name="item" value="<?= $item ? $item->item_item : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="color" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Màu:</label>
                <input disabled type="text" id="color" name="color" value="<?= $item ? $item->item_color : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="size" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Size:</label>
                <input disabled type="text" id="size" name="size" value="<?= $item ? $item->item_size : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="po" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">PO:</label>
                <input disabled type="text" id="po" name="po" value="<?= $item ? $item->item_po : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="unit" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Đơn
                    vị:</label>
                <input disabled type="text" id="unit" name="unit" value="<?= $item ? $item->item_unit : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="container" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Khoang:</label>
                <input required type="text" id="container" name="container" value="<?= $item ? $item->item_container : "" ?>" class="uppercase w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="qty" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Số
                    lượng <span class="text-red-400"><?= '( Tối đa ' . formatNumber($inventory) . " " . $item->item_unit . " )" ?></span> : </label>

                <input required type="number" step="0.01" min="0.01" max="<?= $inventory ?>" id="qty" name="qty" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="date" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Ngày
                    xuất:</label>
                <input required type="date" id="date" name="date" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="number_group" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Tổ:</label>
                <input required type="number" min="0" id="number_group" name="number_group" value="<?= $item ? $item->number_group : "" ?>" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>
            <div class="flex flex-wrap items-center mb-2">
                <label for="user_id" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Người nhận</label>
                <select id="user_id" name="user_id" required class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
                    <option value="">Người nhận</option>
                    <?php
                    $users = DB::table('users')->where('is_admin', '=', 0)->fetchAll();
                    foreach ($users as $user) { ?>
                        <option value="<?= $user->id ?>"><?= $user->name ?></option>
                    <?php }  ?>
                </select>

            </div>
            <?php

            ?>
            <div class="flex flex-wrap items-center mb-2">
                <label for="note" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-muted-700 dark:text-muted-300">Ghi
                    chú:</label>
                <input type="text" id="note" name="note" class="w-full md:w-2/4 rounded bg-gray-50 focus:outline-muted-400 dark:focus:outline-muted-700 text-gray-900 text-sm block p-2 dark:bg-muted-700 dark:text-muted-300">
            </div>

            <input required type="hidden" value=<?= $item->item_id ?> id="id" name="id" class="hidden">
            <div class="flex gap-2 justify-end mt-4">
                <button type="submit" name="order-item" class="flex uppercase px-5 py-2.5 text-sm font-medium text-center text-muted-50 bg-teal-600 hover:bg-teal-700 dark:bg-muted-700 dark:hover:bg-muted-800 dark:text-muted-200 rounded-lg focus:ring-4">
                    XUẤT
                </button>
            </div>
        </form>
    </div>
<?php } ?>

<?php require_once "footer.php";
