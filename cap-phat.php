<?php
require_once "init.php";
$page_title = "Cấp phát";
require_once "header.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create-note'])) {
    $input_data = [
        'style' => post('style'),
        'type' => post('type'),
        'user' => post('user'),
    ];
    // $_SESSION['input_data'] = $input_data;
    DB::table('notes')->insert([
        'date' => post('date'),
        'style' => post('style'),
        'type' => post('type'),
        'size' => post('size'),
        'color' => post('color'),
        'qty' => post('qty'),
        'user' => post('user'),
        'note' => post('note'),
    ]);
    $_SESSION['success'] = "Thêm thành công.";
    header("Location: cap-phat.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['done'])) {
    DB::table('notes')->where('id', '=', post('done'))->update([
        'status' => 1
    ]);
    $_SESSION['success'] = "Thành công.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['undo'])) {
    DB::table('notes')->where('id', '=', post('undo'))->update([
        'status' => 0
    ]);
    $_SESSION['success'] = "Hoàn tác thành công.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
    DB::table('notes')->where('id', '=', post('delete'))->delete();
    $_SESSION['success'] = "Xóa thành công.";
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

if (!empty($_GET['to_date'])) {
    $to_date = $_GET['to_date'];
} else {
    $to_date = date("Y-m-d");
}
$notes = DB::table('notes');
if (!empty($_GET['style']) && $_GET['style'] != "all") {
    $notes = $notes->where('style', '=', $_GET['style']);
}
if (!empty($_GET['from_date'])) {
    $notes = $notes
        ->where('date', '>=', $_GET['from_date'])
        ->where('date', '<=', $to_date);
} else {
    $notes = $notes->where('date', '=', date('Y-m-d'));
}
$notes = $notes->orderBy('date', 'desc')->fetchAll();
?>
<div id="items" class="p-4">
    <h2 id="item-title" class="text-center font-bold text-xl md:text-2xl dark:text-muted-200 mb-4 text-orange-500">THEO DÕI CẤP PHÁT THEO NGÀY</h2>
    <div id="filter" class="flex justify-center">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
            <div class="flex gap-4">
                <div class="form-control border rounded">
                    <label for="style" class="bg-gray-200 dark:bg-gray-500 p-2 inline-block font-medium text-gray-900 dark:text-black">Mã hàng</label>
                    <select name="style" id="style" class="bg-gray-50 text-gray-900  font-medium focus:ring-blue-500 focus:border-blue-500 p-2 dark:bg-gray-700  dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 ">
                        <option value="all">Tất cả</option>
                        <?php
                        $styles = DB::table("items")
                            ->select('item_style')
                            ->distinct()
                            ->fetchAll();
                        foreach ($styles as $style) { ?>
                            <option value="<?= $style->item_style ?>"><?= $style->item_style ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-control border rounded">
                    <label for="from_date" class="bg-gray-200 dark:bg-gray-500 p-2 inline-block font-medium text-gray-900 dark:text-black">Từ ngày</label>
                    <input type="date" class="form-control" name="from_date" id="from_date" required>

                </div>
                <div class="form-control border rounded">
                    <label for="to_date" class="bg-gray-200 dark:bg-gray-500 p-2 inline-block font-medium text-gray-900 dark:text-black">Từ ngày</label>
                    <input type="date" class="form-control" name="to_date" id="to_date">

                </div>
                <button type="submit" class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Lọc</button>
            </div>
        </form>
    </div>
    <?php
    if (count($notes)) { ?>
        <div id="notes" class="mt-4">

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-center  dark:text-gray-400 shadow-lg ">
                    <thead class="text-xs uppercase bg-gray-300 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-2 py-3 border border-black">
                                NGÀY
                            </th>
                            <th scope="col" class="px-2 py-3 border border-black">
                                MÃ HÀNG
                            </th>
                            <th scope="col" class="px-2 py-3 border border-black">
                                LOẠI
                            </th>
                            <th scope="col" class="px-2 py-3 border border-black">
                                SIZE
                            </th>
                            <th scope="col" class="px-2 py-3 border border-black">
                                MÀU
                            </th>
                            <th scope="col" class="px-2 py-3 border border-black">
                                SÓ LƯỢNG
                            </th>
                            <th scope="col" class="px-2 py-3 border border-black">
                                NGƯỜI NHẬN
                            </th>
                            <th scope="col" class="px-2 py-3 border border-black">
                                GHI CHÚ
                            </th>
                            <th scope="col" class="px-2 py-3 border border-black">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($notes as $note) { ?>
                            <tr class="<?= $note->status == 1 ? "bg-green-200" : "bg-white" ?>">
                                <td class="p-2 border border-black font-bold">
                                    <?= formatDate($note->date) ?>
                                </td>
                                <td class="p-2 border border-black">
                                    <?= $note->style ?>
                                </td>
                                <td class="p-2 border border-black">
                                    <?= $note->type ?>
                                </td>
                                <td class="p-2 border border-black">
                                    <?= $note->size ?>
                                </td>
                                <td class="p-2 border border-black">
                                    <?= $note->color ?>
                                </td>
                                <td class="p-2 border border-black">
                                    <?= $note->qty ?>
                                </td>
                                <td class="p-2 border border-black">
                                    <?= $note->user ?>
                                </td>
                                <td class="p-2 border border-black">
                                    <?= $note->note ?>
                                </td>
                                <td class="p-2 border border-black">
                                    <div class="flex justify-center gap-2 items-center">

                                        <?php
                                        if (!$note->status) { ?>
                                            <form class="inline-block" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
                                                <button type="submit" name="done" value="<?= $note->id ?>">
                                                    <svg class="w-8" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" fill-rule="nonzero">
                                                        <g transform="translate(8.96,8.96) scale(0.93,0.93)">
                                                            <g fill="#369e3a" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                                                                <path d="M-9.63441,265.63441v-275.26882h275.26882v275.26882z" id="bgRectangle"></path>
                                                            </g>
                                                            <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                                                                <g transform="scale(5.33333,5.33333)">
                                                                    <path d="M40.6,12.1l-23.6,23.6l-9.6,-9.6l-2.8,2.9l12.4,12.3l26.4,-26.4z"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form class="inline-block" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
                                                <button type="submit" name="delete" value="<?= $note->id ?>">
                                                    <svg class="w-8" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" fill-rule="nonzero">
                                                        <g fill="#ff0000" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                                                            <path d="M0,256v-256h256v256z" id="bgRectangle"></path>
                                                        </g>
                                                        <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                                                            <g transform="scale(5.33333,5.33333)">
                                                                <path transform="translate(24.00059,-9.94113) rotate(45.001)" d="M21.5,4.5h5.001v39h-5.001z"></path>
                                                                <path transform="translate(57.94113,24.00474) rotate(135.008)" d="M21.5,4.5h5v39.001h-5z"></path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php } else { ?>

                                            <form class="block" action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
                                                <button type="submit" name="undo" value="<?= $note->id ?>">
                                                    <svg class="w-8" viewBox="-102.4 -102.4 1228.80 1228.80" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                                        <g stroke-width="0">
                                                            <rect x="-102.4" y="-102.4" width="1228.80" height="1228.80" rx="73.728" fill="#38afd6" strokewidth="0" />
                                                        </g>
                                                        <g stroke-linecap="round" stroke-linejoin="round" />
                                                        <g>
                                                            <path d="M106.666667 384L405.333333 134.4v499.2z" fill="#ffffff" />
                                                            <path d="M597.333333 298.666667H341.333333v170.666666h256c59.733333 0 106.666667 46.933333 106.666667 106.666667s-46.933333 106.666667-106.666667 106.666667h-64v170.666666h64c153.6 0 277.333333-123.733333 277.333334-277.333333s-123.733333-277.333333-277.333334-277.333333z" fill="#ffffff" />
                                                        </g>
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php } ?>
                                    </div>

                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>

        </div>
    <?php } ?>

</div>
<div class="fixed bottom-2 left-1/2 -translate-x-1/2 max-w-xs text-center z-40">
    <button type="button" title="Thêm" id="btn-add-note" class="btn-show-modal hover:translate-y-1 w-12 h-12 bg-blue-700 hover:bg-blue-800 p-2 rounded-full flex items-center justify-center">
        <svg viewBox="0 0 20 20" enable-background="new 0 0 20 20" class="w-6 h-6 inline-block">
            <path fill="#FFFFFF" d="M16,10c0,0.553-0.048,1-0.601,1H11v4.399C11,15.951,10.553,16,10,16c-0.553,0-1-0.049-1-0.601V11H4.601
            C4.049,11,4,10.553,4,10c0-0.553,0.049-1,0.601-1H9V4.601C9,4.048,9.447,4,10,4c0.553,0,1,0.048,1,0.601V9h4.399
            C15.952,9,16,9.447,16,10z" />
        </svg>
    </button>
</div>
<div id="modal" class="modal fixed hidden top-0 left-0 right-0 z-50 p-4 overflow-hidden md:inset-0 h-screen bg-black/80 justify-center items-center">
    <div class="relative w-full max-w-xl max-h-screen mx-auto">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="p-6">
                <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-wrap gap-2">
                            <label for="date" class="block w-full">Ngày xuất</label>
                            <input type="date" value="2023-10-01" class="block border rounded w-full p-2" name="date" id="date" required>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <label for="style" class="block w-full">Mã hàng</label>
                            <!-- <input type="text" class="hidden block border rounded w-full p-2" name="style" id="style" value="" required> -->
                            <select required name="style" id="style" class="block border rounded w-full p-2">
                                <option value="">Chọn mã hàng</option>
                                <?php
                                foreach ($styles as $style) { ?>
                                    <option value="<?= $style->item_style ?>"><?= $style->item_style ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <label for="type" class="block w-full">Loại phụ liệu</label>
                            <input type="text" class="block border rounded w-full p-2" name="type" id="type" value="" required>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <label for="size" class="block w-full">Size</label>
                            <input type="text" class="block border rounded w-full p-2" name="size" id="size">
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <label for="color" class="block w-full">Màu</label>
                            <input type="text" class="block border rounded w-full p-2" name="color" id="color">
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <label for="qty" class="block w-full">Số lượng</label>
                            <input type="text" class="block border rounded w-full p-2" name="qty" id="qty" required>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <label for="user" class="block w-full">Người nhận</label>
                            <select required name="user" id="user" class="block border rounded w-full p-2">
                                <option value="">Người nhận</option>
                                <?php
                                $users = DB::table('users')->where('is_admin', '=', 0)->fetchAll();
                                foreach ($users as $user) { ?>
                                    <option value="<?= $user->name ?>"><?= $user->name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <label for="note" class="block w-full">Ghi chú</label>
                            <input type="text" class="block border rounded w-full p-2" name="note" id="note">
                        </div>

                    </div>
                    <div class="actions mt-2 text-right">
                        <button type="button" id="btn-close-modal" class="text-black bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Hủy</button>
                        <button type="submit" name="create-note" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Thêm
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once "footer.php";
