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

if(isset($_SESSION['is_login']) && $_SESSION['is_login'])
{
	//del_product_cart可在functions.php找到
	$del_product = del_product_cart($_GET['product_id']);

	if($del_product)
	{
		//若為true 代表新增成功，印出yes
		echo 'yes';
	}
	else
	{
		//若為 null 或者 false 代表失敗
		echo 'no';	
	}
	
}
else
{
	//若為 null 或者 false 代表失敗
	echo 'no';	
}

echo "<script>alert('加入成功'); location.href = 'index.php';</script>";

?>