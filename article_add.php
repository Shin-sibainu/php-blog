<?php if (isset($_POST['title']) && isset($_POST['text']) && isset($_POST['category'])) {
    $title = $_POST['title'];
    $text = $_POST['text'];
    $category = $_POST['category'];

    //実際はここで入力値の検証を実施する。

    //db接続
    $dsn = "mysql:host=localhost;dbname=blog3";
    $username = "root";
    $password = "root";
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //category_listテーブルに同名のカテゴリーが存在しなければカテゴリーを登録
    try {
        $res = $db->query("SELECT COUNT(*) FROM category_list WHERE category = '" . $category . "'");
        if ($res->fetchColumn() == 0) {
            $statement = $db->prepare("INSERT INTO category_list (category) VALUES (:category)");
            $statement->bindValue(":category", $category, PDO::PARAM_STR);
            $statement->execute();
            $statement = null;
        } else {
            //カテゴリーに登録しない＝何もしない。
        }
    } catch (PDOException $e) {
        echo "カテゴリー登録失敗:" . $e->getMessage();
    }

    //記事と登録
    try {
        $statement = $db->prepare("INSERT INTO blog (title, text, date, category_id) VALUES (:title, :text, NOW(), (SELECT id FROM category_list WHERE category = :category))");
        $statement->bindValue(":title", $title, PDO::PARAM_STR);
        $statement->bindValue(":text", $text, PDO::PARAM_STR);
        $statement->bindValue(":category", $category, PDO::PARAM_STR);
        $statement->execute();
        $statement = null;
        echo "記事を追加しました。";
    } catch (PDOException $e) {
        echo "記事の登録失敗：" . $e->getMessage();
    }
};
?>


<!-- 記事の登録フォーム -->
<form name="article_add" action="article_add.php" method="post">
    <p>カテゴリ:<input name="category" type="text"></p>
    <p>タイトル:<input name="title" type="text"></p>
    <p>本文:<br><textarea name="text"></textarea></p>
    <p><input type="submit" value="登録"></p>
</form>