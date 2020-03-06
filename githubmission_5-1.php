<html>
	
	<head>
		<title>mission5-1</title>
		<meta charset= "utf-8">
	</head>

	<body>

 <?php

  //データベースに接続   
    $dsn = 'データベース名';
    $user = 'ユーザー名';
	$password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); 

    $sql = "CREATE TABLE IF NOT EXISTS tbtext1"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."pass char(32)"
    .");";
    $stmt = $pdo->query($sql);


    //編集番号のデータを読み込み
    if(!empty($_POST["editNo"]) and !empty($_POST["editpass"]))
    {
        $id = $_POST["editNo"];
        $editpass = $_POST["editpass"];
        //番号が一致するパスワードを呼び出し
        $sql = 'SELECT * FROM tbtext1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row)
        {
            if($row['id'] == $id)
            {
                $editname = $row['name'];
                $editcomment = $row['comment'];
                $pass = $row['pass'];
                $editcount = $row['id'];
            }
        }
        //パスワードが違うときは$passを初期化
        if($editpass !== $pass)
        {
            $pass = null;
        }
    }

?>  
			<form method = "POST" action = mission_5-1.php>
			<input type = "text"  name = "name"  value =  "<?php if(!empty($pass)){echo $editname;}?>"  placeholder = "<?php if(empty($pass)){echo "名前";}?>"><br>
			<input type = "text"  name = "comment"  value = "<?php if(!empty($pass)){echo $editcomment;}?>"  placeholder = "<?php if(empty($pass)){echo "コメント";}?>"><br>		
			<input type = "text" name = "password" value ="<?php if(!empty($pass)){echo $pass;}?>"  placeholder = "<?php if(empty($pass)){echo "パスワード";}?>">
        
		    <input type = "hidden"  name = "editnum" value = "<?php if(!empty($pass)){echo $editcount;}?>" ><br>
			<input type = "submit"  name = "btn"value = "送信"><br><br>
	
			<input type = "text" name = "deleteNo"  placeholder = "削除対象番号"><br>
			<input type = "text" name = "delpass"  placeholder = "パスワード"><br>
			<input type = "submit"  name = "delete" value = "削除"><br><br>
	
			<input type = "text" name = "editNo" placeholder = "編集番号"><br>
			<input type = "text" name = "editpass"  placeholder = "パスワード"><br>
			<input type =  "submit" name = "edit" value = "編集"><br><br> 

<?php
// 　　　　　　編集内容に書き換える

if(!empty($_POST["editnum"]) and empty($_POST["editNo"]))
{
    $id = $_POST["editnum"]; //変更する投稿番号
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $password = $_POST["password"]; 
        
    //変更したいこと
    $sql = 'update tbtext1 set name=:name,comment=:comment,pass=:pass where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
}

// 　　　　　　新規投稿機能

//名前とコメントとパスワードが入っていれば新規投稿に
if(!empty($_POST["name"]) and !empty($_POST["comment"]) and !empty($_POST["password"]) and empty($_POST["editnum"]))
{
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $password = $_POST["password"];

    //データをセットする準備する
    $sql = $pdo -> prepare("INSERT INTO tbtext1 (name, comment, pass) VALUES(:name, :comment, :pass)");
   
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);          //変数をバインドする
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $password, PDO::PARAM_STR);
    $sql -> execute();              //実行する
}

//　　　　　　 削除機能
if(!empty($_POST["deleteNo"]) and !empty($_POST["delpass"]))
{
    $id = $_POST["deleteNo"];
    $delpass = $_POST["delpass"];
    $pass ;

    //テーブル内にある指定した番号のパスワードを取り出す
    $sql = 'SELECT * FROM tbtext1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach($results as $row)
    {
        if($row['id'] == $id)
        {
            $pass = $row['pass'];
        }

    }
    //パスワードが一致したとき削除
    if($pass == $delpass)
    {
        $sql = 'delete from tbtext1 where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

//              表示機能

    $sql = 'SELECT * FROM tbtext1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row)
    {
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].'<br>';
        echo "<hr>";
    }



?>

