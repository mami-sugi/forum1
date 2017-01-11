<?php
function getDb(){/*データベース接続*/
    $hostname = "localhost";//データベースのホスト名
    //$hostname = "127.0.0.1";//データベースのホスト名
    $dbname = "forum1";//データベースの名前
    $username = "root";//データベースのユーザー名
    $password = "";//ユーザーのパスワード

    $dsn = "mysql:host=$hostname;dbname=$dbname;charset=utf8";//DNS文(データベースの接続に使用)の生成（文字コードはUTF-8）

    try {
        $db = new PDO($dsn, $username, $password);//データベースに接続
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//エラーモード設定
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $error) {
        die('データベースへの接続に失敗しました。' . $error->getMessage());//接続失敗時の出力文
    }
    return $db;
}