<?php
//データベース接続
$dsn = "mysql:host=localhost;dbname=blog3";
$username = "root";
$password = "root";
$db = new PDO($dsn, $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement = $db->prepare("SELECT id, (SELECT category FROM category_list WHERE category_id = id) AS category, title, date FROM blog ORDER BY date ASC");

$statement->execute();

//記事一覧をHTMLに出力
echo "<table>";
echo "<tr><th>記事ID</th><th>カテゴリー</th><th>タイトル</th><th>投稿日時</th></tr>";
while ($row = $statement->fetch()) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['category'] . "</td>";
    echo "<td><a href=\"article_modify.php?id=" . $row['id'] . "\">" . $row['title'] . "</a></td>";
    echo "<td>" . $row['date'] . "</td>";
    echo "</tr>";
}
echo "</table>";

$statement = null;
