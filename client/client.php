<?php

session_start();
$conn = mysqli_connect("localhost", "root","3333", "eatery");
mysqli_set_charset($conn, 'utf8');

  //name 속성이 add_to_cart 인 form태그에 submit 버튼을 눌렀을때
  if(isset($_POST["add_to_cart"])){

    //쇼핑카트 세션 배열이 존재한다면
    if(isset($_SESSION["shopping_cart"])){
      //참고:https://www.w3schools.com/php/func_array_column.asp
      //값이 배열로 이루어진 배열에서 key 값이 item_id인 값을 찾아서 배열로 리턴
      $item_array_id = array_column($_SESSION["shopping_cart"],"item_id");

      //클릭한 상품의 id가 $item_array_id 배열에 존재 하지 않으면
      if(!in_array($_POST["id"], $item_array_id)){
        //shopping_cart 세션 배열에 들어있는 배열의 수
        $count = count($_SESSION["shopping_cart"]);
        //클릭한 상품의 데이터를 배열에 넣는다.
        $item_array = array(
        'item_id' => $_POST["id"],
        'item_name' => $_POST["hidden_name"],
        'item_price' => $_POST["hidden_price"],
        'item_quantity' => $_POST["quantity"]
        );

        //shopping_cart 세션 배열에서 그 다음 방부터 차례로 넣는다.
        $_SESSION["shopping_cart"][$count] = $item_array;
        
        echo '<script>alert("장바구니에 담았습니다.")</script>';
        echo '<script>window.location="client.php?setmenu='.$_GET['setmenu'].'&table='.$_GET['table'].'"</script>';

        }
        else{
          //클릭한 상품의 id가 $item_array_id 배열에 존재한다면
          echo '<script>alert("같은 상품이 존재합니다. 삭제 후 다시 추가해 주세요.")</script>';
          echo '<script>window.location="client.php?setmenu='.$_GET['setmenu'].'&table='.$_GET['table'].'"</script>';
        }


        //쇼핑카트 세션 배열이 존재하지 않는다면(즉, 제일 처음 카트 버튼을 눌렀을 때)
      }
      else {
        $item_array = array(
        'item_id' => $_POST["id"],
        'item_name' => $_POST["hidden_name"],
        'item_price' => $_POST["hidden_price"],
        'item_quantity' => $_POST["quantity"]
        );

        //key 값이 shopping_cart 인 배열 0번 방에 상품 배열을 넣었다.
        $_SESSION["shopping_cart"][0] = $item_array;
        echo '<script>alert("장바구니에 담았습니다.")</script>';
        echo '<script>window.location="client.php?setmenu='.$_GET['setmenu'].'&table='.$_GET['table'].'"</script>';
       }

     }

  if(isset($_POST["del_to_cart"])){

     if(isset($_GET["action"])){
       if($_GET["action"]=="delete"){
         //shopping_cart 세션 배열에 존재하는 배열들을 $values 에 넣는다.
         foreach($_SESSION["shopping_cart"] as $keys => $values){
           //배열의 item_id 값이 클릭한 id 값과 같으면
           if($values["item_id"] == $_POST["del_id"]){
             //세션에서 제거한다.
             unset($_SESSION["shopping_cart"][$keys]);
             // 삭제후 배열 재구성
             $_SESSION["shopping_cart"] = array_values($_SESSION["shopping_cart"]);
             echo '<script>alert("삭제 되었습니다")</script>';
             echo '<script>window.location="client.php?setmenu='.$_GET['setmenu'].'&table='.$_GET['table'].'"</script>';
           }
         }
        }
      }
  }
//주문요청
  if(isset($_POST["order_data_save"])){
    if(isset($_GET["action"])){
      if($_GET["action"]=="order"){
        $order_text = '';
        $table = $_GET['table'];
        $total_pay = $_POST["total_pay"];
        foreach($_SESSION["shopping_cart"] as $keys => $values){
          $order_text = $order_text.($values["item_name"]." ".$values["item_quantity"]."   ");
        }
        mysqli_query($conn,"
        INSERT INTO order_menu(time, table_num, order_menu, total_price)
        VALUES (now(), '$table', '$order_text', '$total_pay');
        ");
        echo '<script>alert("주문이 완료 되었습니다.")</script>';
        echo '<script>window.location="client.php?setmenu=order_finish&table='.$_GET['table'].'"</script>';
      }
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>한경분식점</title>
  </head>
  <body>
    <header class=top>
      <div id="menu">
       <a href="client.php?table=<?php echo $_GET['table']; ?>"><h1>한경분식점</h1></a>
       <div>
        <ul id="list">
          <?php
          echo "<a href=\"client.php?setmenu=main_menu&table=".$_GET['table']."\"><li>메인 메뉴<img src=\"./data/pic/main.png\" /></li></a>";
          echo "<a href=\"client.php?setmenu=side_menu&table=".$_GET['table']."\"><li>사이드 메뉴<img src=\"./data/pic/side.png\" /></li></a>";
          ?>
        </ul>         
       </div>
      </div>      
    </header>
    
    <div id="main_menu">
      
     <?php // 메인메뉴 페이지
      
      if(($_GET['setmenu']) == "main_menu") {
        $sql_main_menu = "SELECT * FROM main_menu";
        $result_main = mysqli_query($conn, $sql_main_menu);
      while($row_main = mysqli_fetch_array($result_main)){
      ?>  
       
      <div id="one_menu">
        <form method="post" action="client.php?setmenu=<?php echo $_GET['setmenu']; ?>&table=<?php echo $_GET['table']; ?>&action=add">
          <div>
            <img src = './data/pic/<?php echo $row_main['name'];  ?>.png'>
            <div id="menu_table">
              <h4 class="text-name">  <?php echo $row_main['name'];  ?> </h4>
              <h5 class="text-info">  <?php echo $row_main['s_info'];  ?> </h5>
              <h4 class="text-price"> <?php echo $row_main['price']; ?> </h4>
              <input type="number" name="quantity" class="form-control" value="1" />
              <input type="hidden" name="id" value="<?php echo $row_main['id'];?>"/>
              <input type="hidden" name="hidden_name" value="<?php echo $row_main['name']; ?>" />
              <input type="hidden" name="hidden_price" value="<?php echo $row_main['price']; ?>" />
              <input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="담기"/>
              <br>
            </div>
          </div>
        </form>
      </div> 
      <?php
       }
      }//메인메뉴 끝
      mysqli_close($conn);
      ?>
      
      <?php //사이드 메뉴 페이지
      $conn = mysqli_connect("localhost", "root","3333", "eatery");
      mysqli_set_charset($conn, 'utf8');
      if (mysqli_connect_errno()){
      echo "연결실패<br>이유 : " . mysqli_connect_error();
      }
      
      if(($_GET['setmenu']) == "side_menu") {
        $sql_side_menu = "SELECT * FROM side_menu";
        $result_side = mysqli_query($conn, $sql_side_menu);
      while($row_side = mysqli_fetch_array($result_side)){
      ?>  
       
      <div id="one_menu">
        <form method="post" action="client.php?setmenu=<?php echo $_GET['setmenu']; ?>&table=<?php echo $_GET['table']; ?>&action=add">
          <div>
            <img src = './data/pic/<?php echo $row_side['name'];  ?>.png'>
            <div id="menu_table">
              <h4 class="text-name">  <?php echo $row_side['name'];  ?> </h4>
              <h5 class="text-info">  <?php echo $row_side['s_info'];  ?> </h5>
              <h4 class="text-price"> <?php echo $row_side['price']; ?> </h4>
              <input type="number" name="quantity" class="form-control" value="1" />
              <input type="hidden" name="id" value="<?php echo $row_side['id'];?>"/>
              <input type="hidden" name="hidden_name" value="<?php echo $row_side['name']; ?>" />
              <input type="hidden" name="hidden_price" value="<?php echo $row_side['price']; ?>" />
              <input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="담기"/>
            </div>
           <br>
          </div>
        </form>
      </div> 
<?php
        }
      }//사이드메뉴 끝
      mysqli_close($conn);
?>

<!--        장바구니 정보 -->
      <?php
      if(($_GET['setmenu']) == "inventory") {
      ?>
        <h3>주문내역</h3>
        <div id="table-responsive">
          <table class="table-bordered">
            <tr>
              <th width="40%">주문하신 음식</th>
              <th width="10%">수량</th>
              <th width="20%">가격</th>
              <th width="15%">총금액</th>
              <th width="5%">옵션</th>
            </tr>

      <?php
        //장바구니에 물건이 존재하면
        if(!empty($_SESSION["shopping_cart"])){
          $total = 0;
          foreach($_SESSION["shopping_cart"] as $keys => $values){
      ?>
            <tr>
              <td><?php echo $values["item_name"]; ?></td>
              <td><?php echo $values["item_quantity"]; ?></td>
              <td><?php echo $values["item_price"]; ?></td>
              <td><?php echo number_format($values["item_quantity"] * $values["item_price"],0);?></td>
              <td>
                <form method="post" action="client.php?setmenu=<?php echo $_GET['setmenu']; ?>&table=<?php echo $_GET['table']; ?>&action=delete">
                  <input type="hidden" name="del_id" value="<?php echo $values["item_id"];?>"/>
                  <input type="submit" name="del_to_cart" style="margin-top:5px;" class="btn btn-success" value="삭제"/>
                </form>
            </tr>
            
            <?php
            // 총가격 : 개수 * 가격
              $total = $total + ($values["item_quantity"] * $values["item_price"]);
              } //foreach 끝
             ?>

             <tr>
              <td colspan="3" align="right">총금액</td>
              <td align="right"><?php echo number_format($total,0);?> 원</td>
              <td></td>
             </tr>
        <?php
        } //if 끝
        ?>
          </table>
<!--           결제버튼 -->
          <div>
            <form method="post" action="client.php?setmenu=<?php echo $_GET['setmenu']; ?>&table=<?php echo $_GET['table']; ?>&action=order">
              <input type="hidden" name="total_pay" value="<?php echo $total ?>"/>
              <input type="submit" name="order_data_save" style="margin:5px;" value="결제하기"/>
            </form>
          </div>
      </div>
      <?php
      }//장바구니 끝
      mysqli_close($conn);
      ?>
      
      <?php
      // 주문 완료 결과표
      if(($_GET['setmenu']) == "order_finish") {
      ?>
        <h3>주문 완료 내역</h3>
        <div id="table-responsive">
          <table class="table-bordered">
            <tr>
              <th width="40%">주문하신 음식</th>
              <th width="10%">수량</th>
              <th width="20%">가격</th>
              <th width="20%">합</th>
            </tr>

      <?php
        //장바구니에 물건이 존재하면
        if(!empty($_SESSION["shopping_cart"])){
          $total = 0;
          foreach($_SESSION["shopping_cart"] as $keys => $values){
      ?>
            <tr>
              <td><?php echo $values["item_name"]; ?></td>
              <td><?php echo $values["item_quantity"]; ?></td>
              <td><?php echo $values["item_price"]; ?></td>
              <td><?php echo number_format($values["item_quantity"] * $values["item_price"],0);?></td>
            </tr>
            
            <?php
            // 총가격 : 개수 * 가격
              $total = $total + ($values["item_quantity"] * $values["item_price"]);
              } //foreach 끝
             ?>

             <tr>
              <td colspan="3" align="right">총금액</td>
              <td align="right"><?php echo number_format($total,0);?> 원</td>
              <td></td>
             </tr>
        <?php
        }//if 끝
        ?>
        </table>
         <?php
        //세션 없애기
        session_destroy();
        mysqli_close($conn);
      }//결과표 끝
        ?>
          
      <?php
        // 기본화면
      if(!isset($_GET['setmenu'])){
        echo "<div id='welcome'>";
        echo "안녕하세요 한경분식점 입니다.";
        echo "<br>";
        echo "귀하의 테이블은 ".$_GET['table']."번테이블 입니다.";
        echo "</div>";
      }
      mysqli_close($conn);
      ?>
      
      </div>
    </div>
    
    
    <footer>
      <?php 
          echo "<a href=\"client.php?setmenu=inventory&table=".$_GET['table']."\"><div id=\"tale\">장바구니<img src=\"./data/pic/basket.png\" /></div></a>";
      ?>
    </footer>
  </body>
</html>
