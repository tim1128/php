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

//取得所有使用者留言板
$messages = get_all_user_message();
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
                <th>主題</th>
                <th>內容</th>
                <th>回復內容</th>
                <th>回復時間</th>
              </tr>
              <?php if($messages):?>
                <?php foreach($messages as $message):?>
                  <tr>
					<td><?php echo $message['username'];?></td>
                    <td><?php echo $message['theme'];?></td>
                    <td><?php echo $message['content'];?></td>
                    <td>
						<?php if($message['verifyreply'] == 0):?>
						<form method="get" action="reply_message.php">
							<input type="submit" value="回復">
							<input type="hidden" name="username" value="<?php echo $message['username'];?>">
							<input type="hidden" name="theme" value="<?php echo $message['theme'];?>">
							<input type="hidden" name="content" value="<?php echo $message['content'];?>">
							<input type="hidden" name="id" value="<?php echo $message['id'];?>">
						</form>
						<?php elseif($message['verifyreply'] == 1):?>
							<?php echo $message['replymessage'];?>
						<?php endif;?>
					</td>
                    <td><?php echo ($message['verifyreply'])?$message['replyTime']:"";?></td>
					
                  </tr>
                <?php endforeach;?>
              <?php else:?>
                <tr>
                  <td colspan="5">無資料</td>
                </tr>
              <?php endif;?>
            </table>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
