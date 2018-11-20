<?php
//載入 db.php 檔案，透過它連接資料庫
require_once '../../php/db.php';
require_once '../../php/functions.php';
//print_r($_SESSION); //查看目前session內容

//如過沒有 $_SESSION['is_login'] 這個值，或者 $_SESSION['is_login'] 為 false 都代表沒登入
if(!isset($_SESSION['is_login']) || !$_SESSION['is_login'])
{
	//直接轉跳到 login.php
	header("Location: ../login.php");
}

//取得所有商品資訊
$product_cart = get_all_product_cart();
?>
<!DOCTYPE html>
<html lang="zh-TW">
  <head>
    <title>會員專區</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <!-- 給行動裝置或平板顯示用，根據裝置寬度而定，初始放大比例 1 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 載入 bootstrap 的 css 方便我們快速設計網站-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
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
          <br></br>
          <div class="col-xs-12">
            <!-- 資料列表 -->
            <table class="table table-hover">
              <tr>
                <th>會員帳號</th>
                <th>商品編號</th>
				<th>商品數量</th>
				<th>會員地址</th>
				<th>訂單狀態</th>
              </tr>
              <?php if($product_cart):?>
                <?php foreach($product_cart as $shopping_cart):?>
					<?php if($shopping_cart['status'] == 1):?>
					  <tr>
						<td><?php echo $shopping_cart['username'];?></td>
						<td><?php echo $shopping_cart['product_number'];?></td>
						<td><?php echo $shopping_cart['quantity'];?></td>
						<td><?php echo root_get_userdata($shopping_cart['username']);?></td>
						<td>
							<?php echo "待確認訂單";?>
							<form method="get" action="../../php/check_product.php">
								<input type="submit" value="確認寄出">
								<input type="hidden" name="id" value="<?php echo $shopping_cart['id'];?>">
							</form>
							<form method="get" action="../../php/check_product.php">
								<input type="hidden" name="del" value="1">
								<input type="hidden" name="id" value="<?php echo $shopping_cart['id'];?>">
								<input type="submit" value="刪除訂單">
							</form>
						</td>
					  </tr>
					<?php elseif($shopping_cart['status'] == 2):?>
					  <tr>
						<td><?php echo $shopping_cart['username'];?></td>
						<td><?php echo $shopping_cart['product_number'];?></td>
						<td><?php echo $shopping_cart['quantity'];?></td>
						<td><?php echo root_get_userdata($shopping_cart['username']);?></td>
						<td>
							<?php echo "已確認寄出";?>
						</td>
					  </tr>
					<?php endif;?>
                <?php endforeach;?>
              <?php else:?>

              <?php endif;?>
            </table>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
