<?php
require_once "init.php";
require_once "header.php";

if (isset($_GET['id'])) {
    $item = DB::table('items')
        ->where('item_id', '=', get('id'))
        ->fetch();
    if (!$item) {
        $_SESSION['error'] = "Không tìm thấy phụ liệu.";
        header("Location: index.php");
        exit();
    }
    if ($item->order_id) {
        echo "111111111111";
        $totalOrder = DB::table('items')
            ->where('order_id', '=', $item->order_id)
            ->where('item_id', '!=', $item->item_id)
            ->sum('item_qty');
        $inventory =  DB::table('items')->where('item_id', '=', $item->order_id)->fetch()->item_qty - $totalOrder;
    }
}
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['edit-item'], $_POST['edit_id'])) {
    DB::table('items')
        ->where('item_id', '=', post('edit_id'))
        ->update([
            'item_customer' => postToUpper('customer'),
            'item_style' => postToUpper('style'),
            'item_type' => postToUpper('type'),
            'item_style' => postToUpper('style'),
            'item_container' => postToUpper('container'),
            'item_model' => postToUpper('model'),
            'item_item' => postToUpper('item'),
            'item_color' => postToUpper('color'),
            'item_params' => postToUpper('params'),
            'item_size' => postToUpper('size'),
            'item_po' => postToUpper('po'),
            'item_unit' => postToUpper('unit'),
            'item_qty' =>  post('qty'),
            'item_note' => post('note'),
            'item_date' => post('date'),
        ]);
    $_SESSION['success'] = "Chỉnh sửa thành công.";

    if (isset($_SESSION['page'])) {
        header('Location: ' . $_SESSION['page']);
    } else {
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }
    exit();
}

?>

<div class="container mx-auto p-4">
    <form id="form-add-customer" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="bg-white max-w-xl mx-auto p-4 shadow-lg rounded-md">
        <h2 class="text-second-600 text-center text-2xl font-bold uppercase mb-5">Chỉnh sửa phụ liệu</h2>
        <div class="flex flex-wrap items-center mb-2">
            <label for="customer" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Khách
                hàng:</label>
            <input type="text" id="customer" name="customer" value="<?= $item->item_customer  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="style" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Mã
                hàng:</label>
            <input type="text" id="style" name="style" value="<?= $item->item_style  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="type" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Loại:</label>
            <input type="text" id="type" name="type" value="<?= $item->item_type  ?>" class="uppercase w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="container" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Khoang:</label>
            <input required type="text" id="container" name="container" value="<?= $item->item_container  ?>" class="uppercase w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>

        <div class="flex flex-wrap items-center mb-2">
            <label for="model" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Model:</label>
            <input type="text" id="model" name="model" value="<?= $item->item_model  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="item" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Item:</label>
            <input type="text" id="item" name="item" value="<?= $item->item_item  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="color" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Màu:</label>
            <input type="text" id="color" name="color" value="<?= $item->item_color  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="params" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Thông
                số:</label>
            <input type="text" id="params" name="params" value="<?= $item->item_params  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="size" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Size:</label>
            <input type="text" id="size" name="size" value="<?= $item->item_size  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="po" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">PO:</label>
            <input type="text" id="po" name="po" value="<?= $item->item_po  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="unit" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Đơn
                vị:</label>
            <input type="text" id="unit" name="unit" value="<?= $item->item_unit  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">

            <?php
            if (is_null($item->order_id)) { ?>
                <label for="qty" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Số
                    lượng:</label>
                <input disabled type="number" id="qty" value="<?= $item->item_qty  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                <input type="hidden" name="qty" value="<?= $item->item_qty  ?>">
            <?php } else { ?>
                <label for="qty" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Số
                    lượng ( <span class="font-normal text-red-400"><?= 'Tối đa ' . formatNumber($inventory) . " " . $item->item_unit ?></span> ) : </label>
                <input require type="number" id="qty" name="qty" placeholder="Tối đa <?= $inventory . " " . $item->item_unit ?> " value="<?= $item->item_qty  ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <?php } ?>
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="date" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Ngày
                xuất:</label>
            <input required type="date" id="date" name="date" value="<?= $item->item_date ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <div class="flex flex-wrap items-center mb-2">
            <label for="note" class="w-full mb-2 md:mb-0 md:w-2/4 block font-medium text-gray-900 dark:text-black">Ghi
                chú:</label>
            <input type="text" id="note" name="note" value="<?= $item->item_note ?>" class="w-full md:w-2/4 rounded bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-primary-600 focus:border-primary-600 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
        </div>
        <input type="hidden" name="edit_id" value="<?= $item->item_id ?>">
        <div class="flex gap-2 justify-end mt-4">
            <button type="submit" name="edit-item" class="flex  px-5 py-2.5 text-sm font-medium text-center text-white bg-second-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-second-800">
                Cập nhật
            </button>
        </div>

    </form>
</div>
<?php require_once "footer.php";