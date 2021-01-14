<?php require_once("../resources/config.php"); ?>
<?php include(TEMPLATE_FRONT . DS . "header.php") ?>


<?php



require '../vendor/autoload.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;




class product{


    public $title;
    public $price;
    public $quantity;
    public $currency;
    public $subTotal;



}




if(isset($_POST['submit'])){


    $paypal = new \PayPal\Rest\ApiContext( new \PayPal\Auth\OAuthTokenCredential('AZSWpJ0-AfdlcRm_uIqIERYX_umPkvbFxobn3cDZ_zpNufBxyqcyKuaBLXBDcw08kiNFsgkmxESDC_a4','ELeLHbWlgTQbxLhnynCJHfHNDP7j1H-uiIcA5o9deIgJDtSwjr3joBu1Qq-LP8SX3GZIaOHFSq-0G3Qd'));
    $payer = new Payer();
    $payer->setPaymentMethod('paypal');

//    $item = new Item();

    $itemList = new ItemList();

    $item = new Item();

    $details = new Details();

    $amount = new Amount();

    $transaction = new Transaction();

    $redirectUrls = new redirectUrls();

    $payment = new Payment();


    $x = 0;
    $total =0;




 if(isset($_POST)){


         $products =[];


            for($i=0; $i < count($_POST['product_title']); $i++) {


                $product[$i] = new Product;

                $product[$i]->title = $_POST['product_title'][$i];
                $product[$i]->price = $_POST['product_price'][$i];
                $product[$i]->quantity = $_POST['product_quantity'][$i];
                $product[$i]->currency = $_POST['currency_code'];


                $product[$i]->subTotal = $product[$i]->price * $product[$i]->quantity;

                echo "<pre>";


                $item->setName($product[$i]->title);
                $item->setPrice($product[$i]->price);
                $item->setQuantity($product[$i]->quantity);
                $item->setCurrency($product[$i]->currency);
                $item->setSku(uniqid());

                print_r($product[$i]);


                $products[] = $product[$i];


                $itemList->setItems($products);



                echo "</pre>";




//                $details->setShipping(23)->setSubtotal($sub);
//
//
//                $amount->setCurrency($currency)->setTotal($total)->setDetails($details);
//
//
//                $transaction->setAmount($amount)->setItemList($itemList)->setDescription('Payment for something')->setInvoiceNumber(uniqid());
//
//
//                $redirectUrls->setReturnUrl('http://localhost:8888/ecom/public')->setCancelUrl('http://localhost:8888/ecom/public/pay.php?success=false');
//
//
//                $payment->setIntent('sale')->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions([$transaction]);

//




            }















 }





//
//
//    $items = array();
//    $arr_length = count($value);
//    for ($i = 0; $i < $arr_length; $i++) {
//        $item[$i] = new Item();
//        $item[$i]->setName($data[$i]['productName'])
//            ->setCurrency($data[$i]['currency'])
//            ->setQuantity($data[$i]['quantity'])
//            ->setPrice($data[$i]['price'])
//            ->setSku(uniqid());
//
//        $items[] = $item[$i];
//    }
//
//    $itemList = new ItemList();
//    $itemList->setItems($items);



//
//
//        echo "<pre>";
//
//        var_dump($itemList);
//
//        echo "</pre>";







}







?>





    <!-- Page Content -->
    <div class="container">


<!-- /.row --> 

<div class="row">
      <h4 class="text-center bg-danger"><?php display_message(); ?></h4>
      <h1>Checkout</h1>

<form action="" method="post">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="business" value="edwindiaz123-facilitator@gmail.com">
<input type="hidden" name="currency_code" value="US">
    <table class="table table-striped">
        <thead>
          <tr>
           <th>Product</th>
           <th>Price</th>
           <th>Quantity</th>
           <th>Sub-total</th>
     
          </tr>
        </thead>
        <tbody>


          <?php cart(); ?>



        </tbody>
    </table>


    <input type="submit" name="submit">

</form>



<!--  ***********CART TOTALS*************-->
            
<div class="col-xs-4 pull-right ">
<h2>Cart Totals</h2>

<table class="table table-bordered" cellspacing="0">

<tr class="cart-subtotal">
<th>Items:</th>
<td><span class="amount"><?php 
echo isset($_SESSION['item_quantity']) ? $_SESSION['item_quantity'] : $_SESSION['item_quantity'] = "0";?></span></td>
</tr>
<tr class="shipping">
<th>Shipping and Handling</th>
<td>Free Shipping</td>
</tr>

<tr class="order-total">
<th>Order Total</th>
<td><strong><span class="amount">&#36;<?php 
echo isset($_SESSION['item_total']) ? $_SESSION['item_total'] : $_SESSION['item_total'] = "0";?>



</span></strong> </td>
</tr>


</tbody>

</table>

</div><!-- CART TOTALS-->


 </div><!--Main Content-->


    </div>
    <!-- /.container -->



<?php include(TEMPLATE_FRONT . DS . "footer.php") ?>
