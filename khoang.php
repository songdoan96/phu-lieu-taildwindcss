<?php
require_once "init.php";
$page_title = "Khoang";
include_once "header.php";
$containers = DB::table('items')
    ->select('item_container')
    ->distinct()
    ->fetchAll();
?>

<div class="container mx-auto p-4">
    <h2 id="item-title" class="text-center font-bold text-xl md:text-2xl dark:text-muted-200 mb-4 text-teal-600">DANH SÁCH KHOANG</h2>
    <div class="grid grid-cols-3 md:grid-cols-10 gap-3 md:gap-6">
        <?php foreach ($containers as $container) { ?>
            <div class="w-full relative text-center rounded-lg overflow-hidden uppercase max-w-sm bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                <a href="items.php?khoang=<?= $container->item_container ?>" class="block underline p-2 text-base font-bold bg-teal-700 dark:bg-muted-500 text-white dark:text-white"><?= $container->item_container ?></a>
                <ul class="p-1">
                    <?php
                    $styles = DB::table('items')
                        ->select('item_style')
                        ->distinct()
                        ->where('item_container', '=', $container->item_container)
                        ->whereNull('order_id')
                        ->fetchAll();
                    foreach ($styles as $style) { ?>
                        <li class="">
                            <a href="items.php?ma-hang=<?= $style->item_style ?>&khoang=<?= $container->item_container ?>" class="text-center py-2 text-base font-semibold underline underline-offset-1 block text-muted-700 dark:text-muted-200 hover:text-teal-600">#<?= $style->item_style ?></a>
                        </li>
                    <?php }
                    ?>
                </ul>


            </div>
        <?php } ?>
    </div>
</div>


<?php require_once "footer.php"; ?>