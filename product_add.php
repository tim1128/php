<?php
//載入 db.php 檔案，透過它連接資料庫
require_once '../../php/db.php';
//print_r($_SESSION); //查看目前session內容

//如過沒有 $_SESSION['is_login'] 這個值，或者 $_SESSION['is_login'] 為 false 都代表沒登入
if(!isset($_SESSION['is_login']) || !$_SESSION['is_login'])
{
	//直接轉跳到 login.php
	header("Location: ../login.php");
}

?>
<!DOCTYPE html>
<html lang="zh-TW">
  <head>
    <title>管理模式</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- 給行動裝置或平板顯示用，根據裝置寬度而定，初始放大比例 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 載入 bootstrap 的 css 方便我們快速設計網站-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/style.css"/>
  </head>

  <body>
    <!-- 頁首 -->
		<?php include_once 'menu.php'; ?>
		
    <!-- 網站內容 -->
    <div class="content">
      <div class="container">
        <!-- 建立第一個 row 空間，裡面準備放格線系統 -->
        <div class="row">
          <!-- 在 xs 尺寸，佔12格，可參考 http://getbootstrap.com/css/#grid 說明-->
          <div class="col-xs-12">
            <form id="add_article_form">
              <div class="form-group">
                <label for="product_name">商品名稱</label>
                <input type="text" class="form-control" id="product_name" name="product_name" placeholder="請輸入商品名稱" required>
              </div>
			  <div class="form-group">
                <label for="price">售價</label>
                <input type="text" class="form-control" id="price" name="price" placeholder="請輸入售價" required>
              </div>
			  <div class="form-group">
                <label for="category">圖片上傳</label>
                <input type="file" name="image_path" accept="image/gif, image/jpeg, image/png">
                <input type="hidden" id="image_path" value="">
                <div class="image"></div>
                <a href='javascript:void(0);' class="del_image btn btn-default">刪除照片</a>
              </div>

              <button type="submit" class="btn btn-default">送出</button>
              <div class="loading text-center"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
		
    
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script>
    	$(document).on("ready", function(){
			
			//上傳圖片的input更動的時候
			$("input[name='image_path']").on("change", function() {
			  //產生 FormData 物件
			  var file_data = new FormData(),
				  file_name = $(this)[0].files[0]['name'],
				  save_path = "picture/";

			  //在圖片區塊，顯示loading
			  $("div.image").html('<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>');

			  //FormData 新增剛剛選擇的檔案
			  file_data.append("file", $(this)[0].files[0]);
			  file_data.append("save_path", save_path);
			  //透過ajax傳資料
			  $.ajax({
				type : 'POST',
				url : '../../php/upload_file.php',
				data : file_data,
				cache : false, 			//只有上傳檔案，所以不用暫存
				processData : false, 	//只有上傳檔案，所以不用處理表單資訊
				contentType : false, 	//送過去的內容，由 FormData 產生了，所以設定false
				dataType : 'html'
			  }).done(function(data) {
				console.log(data);
				//上傳成功
				if (data == "yes") {
				  //將檔案插入
				  $("div.image").html("<img src='../../../" + save_path + file_name + "'>");
				  console.log(save_path);
				  //給予 #image_path 值，等等存檔時會用
				  $("#image_path").val(save_path + file_name);
				} else {
				  //警告回傳的訊息
				  alert(data);
				}

			  }).fail(function(data) {
				//失敗的時候
				alert("有錯誤產生，請看 console log");
				console.log(jqXHR.responseText);
			  });
			});
			
			
			//刪除照片
			$("a.del_image").on("click", function() {
			  //如果有圖片路徑，就刪除該檔案
			  if ($("#image_path").val() != '') {
				//透過ajax刪除
				$.ajax({
				  type : 'POST',
				  url : '../../php/del_file.php',
				  data : {
					"file" : $("#image_path").val()
				  },
				  dataType : 'html'
				}).done(function(data) {
				  console.log(data);
				  //上傳成功
				  if (data == "yes") {
					//將圖片標籤移除，並清空目前設定路徑
					$("div.image").html("");
					//給予 #image_path 值，等等存檔時會用
					$("#image_path").val('');
					$("input[name='image_path']").val('');
				  } else {
					//警告回傳的訊息
					alert(data);
				  }

				}).fail(function(data) {
				  //失敗的時候
				  alert("有錯誤產生，請看 console log");
				  console.log(jqXHR.responseText);
				});
			  } else {
				alert("無檔案可以刪除");
			  }
			});
			
			
    		//表單送出
    		$("#add_article_form").on("submit", function(){
				
    			//加入loading icon
    			$("div.loading").html('<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>');
    			
    			if($("#price").val() == '' || $("#product_name").val() == ''){
    				alert("請填入標題或內文");
    				
    				//清掉 loading icon
    				$("div.loading").html('');
    			}else{
					  //使用 ajax 送出至 add_product.php
					$.ajax({
						type : "POST",
						url : "../../php/add_product.php",
						data : {
						  price : $("#price").val(), 				//商品價格
						  product_name : $("#product_name").val(), 	//商品名稱
						  image_path : $("#image_path").val()		//照片位置
						},
						dataType : 'html' //設定該網頁回應的會是 html 格式
					}).done(function(data) {
						//成功的時候
						console.log(data);
						if(data == "yes")
						{
						  //註冊新增成功，轉跳到登入頁面。
						  alert("新增商品成功");
						  window.location.href = "index.php";
						}
						else
						{
						  alert("新增錯誤");
						}
						
					}).fail(function(jqXHR, textStatus, errorThrown) {
						//失敗的時候
						alert("有錯誤產生，請看 console log");
						console.log(jqXHR.responseText);
					});
    			}
    			return false;
    		});
    	});
    </script>
  </body>
</html>
