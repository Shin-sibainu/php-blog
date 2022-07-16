<?php
// DB接続
$dsn = "mysql:host=localhost;dbname=blog3";
$username = "root";
$password = "root";
$db = new PDO($dsn, $username, $password);

//全記事を抽出
$statement = $db->prepare("SELECT id, (SELECT category FROM category_list WHERE category_id = id) AS category, title, text, date FROM blog ORDER BY date DESC");
$statement->execute();

//記事を出力
while ($row = $statement->fetch()) {
    echo "<article>";
    echo "<h1>" . $row['title'] . "</h1>";
    echo "<p>カテゴリー：" . $row['category'];
    echo "<p>" . $row['text'];
    echo "<p>投稿日時：" . $row['date'];
    echo "</article>";
}

$statement = null;
