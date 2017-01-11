<?php
/**
 * Created by PhpStorm.
 * User: MamiSugiura
 * Date: 2016/12/20
 * Time: 18:28
 */
/*エスケープ処理　クロスサイトスクリプティング用　for XSS*/
function escape($str){
    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>簡易掲示板</title>
</head>
<body>
<h1>掲示板</h1>
<form action="" method="post" style="margin: 15px">
    名前:<input type="text" name="name" placeholder="32文字以内" style="margin-bottom: 5px"><!--32--><br>
    投稿内容:<br>
    <textarea cols="50" rows="4" name="contents"　placeholder="300字以内"　style="margin-top: 5px"></textarea><!--300--><br>
    <input type="submit" value="送信">
</form>

<?php
/*データベース接続*/
require_once 'db.php';

if(empty($_POST['name']) || empty($_POST['contents'])) {//フォームに何もないとき
    print "Please input your name or contents";
}else{
    //XSS　エスケープ処理
    $name = escape($_POST['name']);
    $contents = escape($_POST['contents']);
    /* 投稿内容をデータベースに保存 */
    try {
        $db = getDb();//データベースへの接続を確立
        //INSERT命令の準備
        $stt = $db->prepare('INSERT INTO post(name, contents) VALUES(:name, :contents)');
        $stt->bindValue(':name', $name);//ユーザー名 set
        $stt->bindValue(':contents', $contents);//投稿内容 set
        $stt->execute();//INSERT命令実行
        $db = NULL;
    } catch (PDOException $error) {
        die('エラーメッセージ' . $error->getMessage());//接続失敗時の出力文
    }
}
?>
<hr>
<?php
    /*「ユーザー名」「本文」データ表示(投稿内容表示)*/
    try{
        $db = getDb();//データベースへの接続を確立
        //SELECT命令の実行
        $stt = $db->prepare('SELECT * FROM post ORdER BY id DESC');
        $stt->execute();
        while($row = $stt->fetch(PDO::FETCH_ASSOC)){//現在格納されているものすべてを
?>
            <div style="margin-left: 30px;margin-bottom: 10px;padding: 5px;" >
            投稿者名:<b><?php print $row['name']; ?></b><br><!--名前を表示-->
            <?php print $row['contents']; ?><br><!--投稿内容を表示-->
            <br>
            </div>
<?php
        }
        $db = NULL;
    } catch (PDOException $error) {
        die('エラーメッセージ' . $error->getMessage());//接続失敗時の出力文
    }

?>
</body>
</html>