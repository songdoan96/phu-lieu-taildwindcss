<?php
require_once "init.php";
include_once "header.php";

if (!isset($_GET['ma-hang'])) { ?>
    <div class="container mx-auto p-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
            <?php
            $items = DB::table('items')->select("item_customer")->distinct()->fetchAll();
            foreach ($items as $item) { ?>
                <div class="w-full relative text-center rounded-tl-3xl rounded-br-3xl overflow-hidden uppercase max-w-sm bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="p-4 text-base font-bold bg-main-500 dark:bg-gray-500 text-white md:text-2xl dark:text-white">
                        <?= $item->item_customer ?>
                    </h5>
                    <?php
                    $styles = DB::table('items')->select('item_style')->distinct()->where('item_customer', '=', $item->item_customer)->fetchAll();
                    if ($styles) { ?>
                        <ul class=" space-y-3 p-4">
                            <?php foreach ($styles as $style) { ?>
                                <li><a href='can-doi.php?ma-hang=<?= $style->item_style ?>' class='flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-second-100 hover:bg-second-200 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white'><span class='flex-1 ml-3 whitespace-nowrap'>#<?= $style->item_style ?></span></a></li>
                            <?php }  ?>
                        </ul>
                    <?php }
                    ?>



                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>


<?php
if (isset($_GET['ma-hang'])) { ?>
    <table class="w-full border-collapse border border-slate-400 text-sm text-center text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-50 uppercase bg-success-500 dark:bg-gray-700 dark:text-white">
            <tr>
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
                ->where('item_style', '=', get('ma-hang'))
                ->whereNull('order_id')
                // ->where('item_sold_out', "=", 0)
                ->groupBy('item_type, item_color, item_size, item_params')
                ->fetchAll();
            foreach ($items as $item) { ?>
                <tr item-id="<?= $item->item_id ?>" class="even:bg-gray-400 odd:bg-gray-200 dark:odd:bg-gray-500 dark:even:bg-gray-600 text-black dark:text-white  dark:border-gray-700 hover:contrast-75 transition">

                    <td class="border px-1 py-2"><?= $item->item_customer ?></td>
                    <td class="border px-1 py-2"><a href="items.php?ma-hang=<?= $item->item_style ?>"><?= $item->item_style ?></a></td>
                    <td class="border px-1 py-2"><a href="items.php?ma-hang=<?= $item->item_style ?>&type=<?= $item->item_type ?>&color=<?= $item->item_color ?>&size=<?= $item->item_size ?>&params=<?= $item->item_params ?>"><?= $item->item_type ?></a></td>
                    <td class="border px-1 py-2"><?= $item->item_color ?></td>
                    <td class="border px-1 py-2"><?= $item->item_params ?></td>
                    <td class="border px-1 py-2"><?= $item->item_size ?></td>
                    <td class="border px-1 py-2"><?= $item->item_unit ?></td>
                    <td class="border px-1 py-2"></td>
                    <td class="border px-1 py-2"></td>
                    <td class="border px-1 py-2"></td>
                    <td class="border px-1 py-2"></td>
                    <td class="border px-1 py-2"></td>
                    <td class="border px-1 py-2"></td>
                    <td class="border px-1 py-2"></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>



    <!-- Main modal -->
    <div id="authentication-modal" class="fixed top-0 left-0 right-0 bottom-0 h-screen z-50 bg-black bg-opacity-70 p-4 overflow-hidden hidden">
        <div class="relative w-full max-w-md mx-auto mt-16">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Sign in to our platform</h3>
                    <form class="space-y-6" action="#">
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                            <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div class="flex justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required>
                                </div>
                                <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
                            </div>
                            <a href="#" class="text-sm text-blue-700 hover:underline dark:text-blue-500">Lost Password?</a>
                        </div>
                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login to your account</button>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                            Not registered? <a href="#" class="text-blue-700 hover:underline dark:text-blue-500">Create account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php } ?>


<?php
include_once "footer.php";
