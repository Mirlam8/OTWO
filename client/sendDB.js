  <?php 
$order_text = '';
foreach($_SESSION["shopping_cart"] as $keys => $values){
  $order_text = $order_text.($values["item_name"]." ".$values["item_quantity"]."   ");
}
$Sconn = mysqli_connect("localhost", "root", "3333", "eatery");
mysqli_query($Sconn,"
INSERT INTO order(time, table_num, order_menu, total_price)
VALUE (now(), '$_GET[\'table\']', '$order_text','$total')
");
?>