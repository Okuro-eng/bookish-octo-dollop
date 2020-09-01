<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>mission_5-2</title>
    <style>
      form {
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>
      
<?php
   //手順1
  $dsn = 'データベース名';
  $user = 'ユーザー名';
  $password = 'パスワード'; 
  $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
	
	//手順２
   $sql = "CREATE TABLE IF NOT EXISTS mission5c"
   ." ("
   . "id INT AUTO_INCREMENT PRIMARY KEY,"
   . "name char(32),"
   . "comment TEXT," 
   . "postedAt char(32),"
   . "pass char(32)"
   .");";
   $stmt = $pdo->query($sql);
	
	//削除機能
if (!empty($_POST['dnum']) && $_POST['delepass'] =="pass") 
{
          //入力データの受け取りを変数に代入
 $delete = $_POST['dnum'];
 $delepass=$_POST['delepass'];
          
	    //変数から削除番号を抽出
  $sql = 'SELECT * FROM mission5c where id=:id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
  $stmt->execute();
	    
   $results = $stmt->fetchAll();
   foreach ($results as $row)
   {$pass=$row['pass'];
   }
  
	    //パスワードがあってたら削除削除実行
 if($pass == $delepass)
  {
  $sql = 'delete from mission5c where id=:id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
  $stmt->execute();
  }
}

//編集①
 $edit = $_POST['edit'];
 $edipass=$_POST['edipass'];


if (!empty($_POST['edit']) && $edipass=$_POST['edipass'] == "pass") 
{
 //入力データの受け取りを変数に代入
 $edit = $_POST['edit'];
 $edipass=$_POST['edipass'];
  // 番号、名前、コメント抽出
  $id = $edit;
  $sql = 'SELECT * FROM mission5c where id=:id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
	    
   $results = $stmt->fetchAll();
   foreach ($results as $row)
   {$pass=$row['pass'];
   }
	    
    if($pass == $edipass)
    {
    $editnumber = $row['id'];
    $editname = $row['name'];
    $editcomment = $row['comment'];
    }
}

  
  
//新規新規投稿
if (!empty($_POST['newname']) && !empty($_POST['newcomment']) && $_POST['newpass'] == "pass") 
{
  $newname = $_POST['newname'];
  $newcomment = $_POST['newcomment'];
  $newpass = $_POST['newpass'];
  $newpostedAt = date("Y年m月d日 H:i:s");
    
    
    //データベースに入れ替え
   $sql = $pdo -> 
   prepare("INSERT INTO mission5c (name, comment, postedAt, pass)
   VALUES(:name, :comment, :postedAt, :pass)");
	
	$sql -> bindParam(':name',$newname,PDO::PARAM_STR);
	$sql -> bindParam(':comment',$newcomment,PDO::PARAM_STR);
	$sql -> bindParam(':postedAt',$newpostedAt,PDO::PARAM_STR);
	$sql -> bindParam(':pass',$newpass,PDO::PARAM_STR);
	 
	 $sql -> execute();
	
}
	
	 if (!empty($_POST['newname']) && !empty($_POST['newcomment']) && !empty($_POST['editNO'])
     && $_POST['newpass'] == "pass")
     {
    
      $editNO = $_POST['editNO'];
       
       $id = $editNO;
        
        $sql = 'SELECT * FROM mission5c where id=:id';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	    $stmt->execute();    
        $results = $stmt->fetchAll();
	    foreach ($results as $row)
	    {
	        $pass=$row['pass'];
	    }
	    //パスワードがあってたら編集実行
	     if($pass == $newpass)
	     {
            $sql = 'UPDATE mission5c SET name=:name,comment=:comment,postedAt=:postedAt,pass=:pass WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt -> bindParam(':id', $id, PDO::PARAM_STR);
	        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt -> bindParam(':postedAt', $postedAt, PDO::PARAM_STR);
	        $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
	   
	         $stmt -> execute();
    	  }
      }
      
?>  
<form action="mission_5-1.php" method="post">
      <input type="text" name="newname" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"><br>
      <input type="text" name="newcomment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"><br>
      <input type="hidden" name="editNO" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>">
      <input type="text" name="newpass" placeholder="パスワード">
    <input type="submit" name="submit" value="送信">
      
    </form>

    <form action="mission_5-1.php" method="post">
      <input type="text" name="dnum" placeholder="削除対象番号"><br>
      <input type="text" name="delepass" placeholder="パスワード">
      <input type="submit" name="delete" value="削除">
    </form>

    <form action="mission_5-2.php" method="post">
      <input type="text" name="edit" placeholder="編集対象番号"><br>
      <input type="text" name="edipass" placeholder="パスワード">
      <input type="submit" name="edit" value="編集">
    </form>
<?php    
    //手順5
$sql = 'SELECT * FROM mission5c';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row)
	{
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['postedAt'].',';
		echo $row['pass'].',<br>';
	    echo "<hr>";
	}
?>
</body>
</html>