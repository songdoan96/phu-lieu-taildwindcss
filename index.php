<?php
require_once "init.php";
include_once "header.php";
?>

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
                            <li><a href='items.php?ma-hang=<?= $style->item_style ?>' class='flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-second-100 hover:bg-second-200 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white'><span class='flex-1 ml-3 whitespace-nowrap'>#<?= $style->item_style ?></span></a></li>
                        <?php }  ?>
                    </ul>
                <?php }
                ?>



            </div>
        <?php } ?>
    </div>
</div>


<?php
include_once "footer.php";
