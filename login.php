<?php
require_once '../php/db.php';

if(isset($_SESSION['is_login']) && $_SESSION['is_login'])
{
  //判斷使用者權限是否為管理員(5是管理員， >=4是一般會員)
  if($_SESSION['login_user_permission'] == 5)
  {
    header("Location: root/index.php");
  }else
  {
	header("Location: index.php");
  }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
  <head>
    <title>會員登入</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- 給行動裝置或平板顯示用，根據裝置寬度而定，初始放大比例 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 載入 bootstrap 的 css 方便我們快速設計網站-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/style.css"/>
    <link rel="shortcut icon" href="../images/favicon.ico">
  </head>

  <body>

    <!-- 網站內容 -->
    <div class="content">
      <div class="container">
        <!-- 建立第一個 row 空間，裡面準備放格線系統 -->
        <div class="row">
          <!-- 在 xs 尺寸，佔12格，可參考 http://getbootstrap.com/css/#grid 說明-->
          <div class="col-xs-12 col-sm-4 col-sm-offset-4">
          	<h1>會員登入</h1>
            <form class="login">
              <div class="form-group">
                <label for="username">帳號</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="請輸入帳號" required>
              </div>
              <div class="form-group">
                <label for="password">密碼</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="請輸入密碼" required>
              </div>
              <button type="submit" class="btn btn-default">
                登入
              </button>
			  
            </form>
			<a href="../index.html" class="body-System">回首頁</a>&nbsp;&nbsp;|&nbsp;
			<a href="../register.php" class="body-System">新會員註冊</a>
          </div>
        </div>
      </div>
    </div>
    
    <!-- 在表單送出前，檢查確認密碼是否輸入一樣 -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script>
      //當文件準備好時
      $(document).on("ready", function() {
				//當表單 sumbit 出去的時候
				$("form.login").on("submit", function(){
				  //使用 ajax 送出 帳密給 verify_user.php
					$.ajax({
						type : "POST",
						url : "../php/verify_user.php", //因為此 login.php 是放在 admin 資料夾內，若要前往 php，就要回上一層 ../ 找到 php 才能進入 verify_user.php
						data : {
						  un : $("#username").val(), //使用者帳號
						  pw : $("#password").val() //使用者密碼
						},
						dataType : 'html' //設定該網頁回應的會是 html 格式
				  }).done(function(data) {
						//成功的時候
						console.log(data);
						//一般成功是會員登入
						if(data == "yes")
						{
						  //註冊新增成功，轉跳到會員登入頁面。
						  window.location.href = "index.php";
						}
						//檢查權限後為管理員，則登入管理員頁面
						else if(data == "yesRo")
						{
						  window.location.href = "root/index.php";
						}
						//失敗提示
						else
						{
						  alert("登入失敗，請確認帳號密碼");
						}
					
				  }).fail(function(jqXHR, textStatus, errorThrown) {
						//失敗的時候
						alert("請看 console log錯誤");
						console.log(jqXHR.responseText);
				  });
				  
				  //回傳 false 為了要阻止 from 繼續送出去。由上方ajax處理即可
				  return false;
				});
      });
    </script>

  </body>
</html>
