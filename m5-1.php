<?php
    $dsn = 'mysql:dbname=tb230176db;host=localhost';
    $user = 'tb-230176';
    $password = 'Dt4b7gYD7L';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
     $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "password char(20)"
    .");";
    $stmt = $pdo->query($sql);
    $sql = 'SHOW CREATE TABLE tbtest';
    //名前とコメントを入力した場合//
    if(isset($_POST['comment'],$_POST['name'],$_POST['password'])){
        $name = $_POST['name'];
        $comment = $_POST['comment']; //好きな名前、好きな言葉は自分で決めること
        $password = $_POST['password'];
        $edit_hidden = $_POST['edit_hidden'];
        //hidden番号に番号が入っていない場合//
            if($name != "" && $comment != ""  && $password != "" && $edit_hidden == ""){
                $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, password) VALUES (:name, :comment, :password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql ->bindParam(':password', $password, PDO::PARAM_STR);
                $sql -> execute();
            }
            //hidden番号に番号が入っていた場合//
            elseif($name != "" && $comment != ""  && $password != "" && $edit_hidden != ""){
                    $id = $edit_hidden; //変更する投稿番号
                    $sql = 'UPDATE tbtest SET name=:name,comment=:comment,password=:password WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->execute();
            }
       
    }
    //削除する場合//
    if(isset($_POST['delete'],$_POST['edit_password'])){
        $id = $_POST['delete'];
        $edit_password = $_POST['edit_password'];
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($id == $row['id'] && $edit_password == $row['password']){
                $id = $_POST['delete'];
                $sql = 'delete from tbtest where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
        
    //編集する//
    if(isset($_POST['edit_number'],$_POST['edit_password'])){
        $id = $_POST['edit_number']; //変更する投稿番号
        $edit_password = $_POST['edit_password'];
        //エラーが出ないようにからの物を作っておく//
        $edit_id = "";
        $edit_name = "";
        $edit_comment = "";
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($id == $row['id'] && $edit_password == $row['password']){
                $edit_id = $row['id'];
                $edit_name = $row['name'];
                $edit_comment = $row['comment'];          
            }
        }
    }
    ?>
    
    <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <form  method="post" >
        hidden番号：<input  name="edit_hidden" value ="<?php if(isset($_POST['edit_number'],$_POST['edit_password'])){echo $edit_id;}?>">
        名前：<input type="text" name="name" value="<?php if(isset($_POST['edit_number'])){echo $edit_name;}?>">
        コメント：<input type="text" name="comment" value="<?php if(isset($_POST['edit_number'])){echo $edit_comment;}?>">
        パスワード：<input type="password" name="password">
        <input type="submit" name="submit"　value="送信">
    </form>
    <form  method="post">
        削除番号:<input type="delete" name="delete">
        <input type="password" name="edit_password" placeholder="パスワード">
        <input type="submit" name="submit"　value="送信">
    </form>
      <form method="post">
        編集番号:<input type="number" name="edit_number">
        <input type="password" name="edit_password" placeholder="パスワード">
        <input type="submit" value="送信">
    </form><br>
        <br><br><br>
    </form>
    
    <?php
    //mysqlに保存したデータを表示する//
     $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].'.';
            echo $row['name'].'    ';
            echo $row['comment'].'    ';
            //echo $row['password'].'<br>';
        echo "<hr>";
        }
    ?>
</body>