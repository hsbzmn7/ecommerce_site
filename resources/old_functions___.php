<?php
require_once("config.php");


require '../vendor/autoload.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;


?>


<?php 


  if(isset($_GET['add'])) {


    $query = query("SELECT * FROM products WHERE product_id=" . escape_string($_GET['add']). " ");
    confirm($query);

    while($row = fetch_array($query)) {


      if($row['product_quantity'] != $_SESSION['product_' . $_GET['add']]) {

        $_SESSION['product_' . $_GET['add']]+=1;
        redirect("../public/checkout.php");


      } else {


        set_message("We only have " . $row['product_quantity'] . " " . "{$row['product_title']}" . " available");
        redirect("../public/checkout.php");



      }






    }



  // $_SESSION['product_' . $_GET['add']] +=1;

  // redirect("index.php");


  }


  if(isset($_GET['remove'])) {

    $_SESSION['product_' . $_GET['remove']]--;

    if($_SESSION['product_' . $_GET['remove']] < 1) {

      unset($_SESSION['item_total']);
      unset($_SESSION['item_quantity']);
      redirect("../public/checkout.php");

    } else {

      redirect("../public/checkout.php");

     }


  }


 if(isset($_GET['delete'])) { 

  $_SESSION['product_' . $_GET['delete']] = '0';
  unset($_SESSION['item_total']);
  unset($_SESSION['item_quantity']);

  redirect("../public/checkout.php");


 }




function cart() {


//
//    foreach ($_SESSION as $name => $value) {
//
//        echo "<pre>";
//
//       var_dump($_SESSION);
//
//        echo "</pre>";
//
//
//    }




$total = 0;
$item_quantity = 0;
$item_name = 1;
$item_number =1;
$amount = 1;
$quantity =1;
foreach ($_SESSION as $name => $value) {










if($value > 0 ) {

if(substr($name, 0, 8 ) == "product_") {


$length = strlen($name);

$id = substr($name, 8 , $length);


$query = query("SELECT * FROM products WHERE product_id = " . escape_string($id). " ");
confirm($query);

while($row = fetch_array($query)) {

$sub = $row['product_price']*$value;
$item_quantity +=$value;

$product_image = display_image($row['product_image']);

$product = <<<DELIMETER

<tr>
  <td>{$row['product_title']}<br>

  <img width='100' src='../resources/{$product_image}'>

  </td>
  <td>&#36;{$row['product_price']}</td>
  <td>{$value}</td>
  <td>&#36;{$sub}</td>
  <td><a class='btn btn-warning' href="../resources/cart.php?remove={$row['product_id']}"><span class='glyphicon glyphicon-minus'></span></a>   <a class='btn btn-success' href="../resources/cart.php?add={$row['product_id']}"><span class='glyphicon glyphicon-plus'></span></a>
<a class='btn btn-danger' href="../resources/cart.php?delete={$row['product_id']}"><span class='glyphicon glyphicon-remove'></span></a></td>
  </tr>

<input type="hidden" name="item_name_{$item_name}" value="{$row['product_title']}">
<input type="hidden" name="item_number_{$item_number}" value="{$row['product_id']}">
<input type="hidden" name="amount_{$amount}" value="{$row['product_price']}">
<input type="hidden" name="quantity_{$quantity}" value="{$value}">


DELIMETER;

echo $product;

$item_name++;
$item_number++;
$amount++;
$quantity++;



}


$_SESSION['item_total'] = $total += $sub;
$_SESSION['item_quantity'] = $item_quantity;


           }

      }

    }



}


function show_paypal() {


if(isset($_SESSION['item_quantity']) && $_SESSION['item_quantity'] >= 1) {


$paypal_button = <<<DELIMETER

    <input type="image" name="upload" border="0"
src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
alt="PayPal - The safer, easier way to pay online">


DELIMETER;

return $paypal_button;

  }


}



function process_transaction() {


if(isset($_GET['tx'])) {



    $paypal = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential('AZSWpJ0-AfdlcRm_uIqIERYX_umPkvbFxobn3cDZ_zpNufBxyqcyKuaBLXBDcw08kiNFsgkmxESDC_a4','ELeLHbWlgTQbxLhnynCJHfHNDP7j1H-uiIcA5o9deIgJDtSwjr3joBu1Qq-LP8SX3GZIaOHFSq-0G3Qd'));


$amount = $_GET['amt'];
$currency = $_GET['cc'];
$transaction = $_GET['tx'];
$status = $_GET['st'];
$total = 0;
$item_quantity = 0;

foreach ($_SESSION as $name => $value) {




    if($value > 0 ) {

if(substr($name, 0, 8 ) == "product_") {

$length = strlen($name - 8);
$id = substr($name, 8 , $length);


$send_order = query("INSERT INTO orders (order_amount, order_transaction, order_currency, order_status ) VALUES('{$amount}', '{$transaction}','{$currency}','{$status}')");
$last_id =last_id();
confirm($send_order);



$query = query("SELECT * FROM products WHERE product_id = " . escape_string($id). " ");
confirm($query);

while($row = fetch_array($query)) {
$product_price = $row['product_price'];
$product_title = $row['product_title'];
$sub = $row['product_price']*$value;
$item_quantity +=$value;


$insert_report = query("INSERT INTO reports (product_id, order_id, product_title, product_price, product_quantity) VALUES('{$id}','{$last_id}','{$product_title}','{$product_price}','{$value}')");
confirm($insert_report);



    $total += $sub;
    echo $item_quantity;



    $payer = new Payer();

    $payer->setPaymentMethod('paypal');


    $item = new Item();

    $item->setName($name)->setCurrency($currency)->setQuantity($item_quantity)->setPrice($product_price);

    $itemList = new ItemList();
    $itemList->setItems([$item]);



    $details = new Details();
    $details->setShipping(23)->setSubtotal($sub);


    $amount = new Amount();
    $amount->setCurrency($currency)->setTotal($total)->setDetails($details);

    $transaction = new Transaction();
    $transaction->setAmount($amount)->setItemList($itemList)->setDescription('Payment for something')->setInvoiceNumber(uniqid());


    $redirectUrls = new redirectUrls();
    $redirectUrls->setReturnUrl('http://localhost:8888/ecom/public')->setCancelUrl('http://localhost:8888/ecom/public/pay.php?success=false');

    $payment = new Payment();
    $payment->setIntent('sale')->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions([$transaction]);








}




           }

      }












 
    }

session_destroy();
  } else {


redirect("index.php");


}



}




















 ?>