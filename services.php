<?php

require_once "helper.php";
require_once "DB.php";
// if (isset($_GET['get-style'])) {
//     $customer_id = $_GET['get-style'];
//     $styles =  DB::table('styles')->where('customer_id', '=', $customer_id)->fetchAll();
//     echo json_encode(['styles' => $styles]);
//     return;
// }
// if (isset($_GET['check-exist-customer'])) {
//     $customer = $_GET['check-exist-customer'];
//     $checkCustomer =  DB::table('customers')->where('customer_name', '=', $customer)->fetch();
//     if ($checkCustomer) {
//         echo json_encode(['checkCustomerExist' => true]);
//     } else {
//         echo json_encode(['checkCustomerExist' => false]);
//     }
//     return;
// }
// if (isset($_GET['check-exist-style'])) {
//     // ok
//     $style_code = $_GET['check-exist-style'];
//     $customer_id = $_GET['customer-id'];
//     $checkStyleExist =  DB::table('styles')
//         ->where('customer_id', '=', $customer_id)
//         ->where('style_code', '=', $style_code)
//         ->fetch();
//     if ($checkStyleExist) {
//         echo json_encode(['checkStyleExist' => true]);
//     } else {
//         echo json_encode(['checkStyleExist' => false]);
//     }
//     return;
// }
// if (isset($_GET['get-types'])) {
//     $style_id = get('get-types');
//     $styleTypes = DB::table('style_type')
//         ->where('style_id', '=', $style_id)
//         ->join('types', 'types.type_id', '=', 'style_type.type_id')
//         ->fetchAll();
//     echo json_encode(['styleTypes' => $styleTypes]);
//     return;
// }
if (isset($_GET['get-order'])) {
    $id = get('get-order');
    $itemsOrder = DB::table('items')
        ->where('order_id', '=', $id)
        ->fetchAll();
    echo json_encode(['itemsOrder' => $itemsOrder]);
    return;
}
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new-style-type'])) {
//     echo json_encode(['styleTypeId' => $_POST['new-style-type'], 'style_id' => $_POST['style_id']]);
//     return;
// }
