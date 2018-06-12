<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>한경분식점</title>
    <link rel="stylesheet" href="serverCSS.css">
    <link rel="stylesheet" type="text/css" href="chat.css" />
    <script type="text/javascript" src="chat.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
      function finish(obj) {
        $('div').remove(obj);
      }
      function update_transition() {
          $('.new').attr('class','new_end');
      }
      var intervals = window.setInterval(update_transition, 3000);
    </script>
  </head>
  <body>
    <div class="main">
      <div id="a">
        <h1>한경분식점</h1>
      </div>
      <div id="b">
        <p>주문시간 / 테이블 번호 / 음식 , 개수  </p>
        <p>더블클릭 : 삭제</p>
      </div>
    </div>
    <div class="menu1">

      <div id="no1">
        <div style="border-bottom:1px solid gray;">주문순서</div>
        <div id="order">
        </div>
      </div>
    </div>
  </body>
</html>
