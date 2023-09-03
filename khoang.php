<?php
require_once "init.php";
include_once "header.php";
$containers = DB::table('items')
    ->select('item_container')
    ->distinct()
    ->fetchAll();
?>

<div class="container mx-auto p-4">
    <div class="grid grid-cols-2 md:grid-cols-8 gap-3 md:gap-6">
        <?php foreach ($containers as $container) { ?>
            <div class="w-full relative text-center rounded-lg overflow-hidden uppercase max-w-sm bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                <a href="items.php?khoang=<?= $container->item_container ?>" class="block underline p-2 text-base font-bold bg-main-500 text-white dark:text-white"><?= $container->item_container ?></a>
                <ul class=" space-y-3 p-4">
                    <?php
                    $styles = DB::table('items')
                        ->select('item_style')
                        ->distinct()
                        ->where('item_container', '=', $container->item_container)
                        ->whereNull('order_id')
                        ->fetchAll();
                    foreach ($styles as $style) { ?>
                        <li><a href="items.php?ma-hang=<?= $style->item_style ?>&khoang=<?= $container->item_container ?>" class="flex items-center p-3 text-base font-bold text-gray-900 bg-second-100 hover:bg-second-200 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white"><span class="flex-1 ml-3 whitespace-nowrap"><?= $style->item_style ?></span></a></li>
                    <?php }
                    ?>
                </ul>



            </div>
        <?php } ?>
    </div>
</div>


<?php require_once "footer.php"; ?>