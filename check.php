<?php
session_start();
require('function.php');

if (!isset($_SESSION['join'])) {
    header('Location: login.php');
    exit();
}

//セッション情報のDB書き込み
if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, created=NOW()');
    $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password'])
    ));
    setcookie('email', $_SESSION['join']['email'], time() + 60);
    unset($_SESSION['join']);
    header('Location: thanks.php');
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="content">
        <header>
            <div class="mein-name">
                <h2>ひとこと掲示板Onetweet</h2>
            </div>
        </header>
        <h1>内容確認</h1>
        <p class="description">内容を確認して、問題が無ければ「登録する」ボタンを押してください。</p>
        <form action="" method="post">
            <input type="hidden" name="action" value="submit">
            <dl>
                <dt>ニックネーム</dt>
                <dd><?php echo $_SESSION['join']['name']; ?></dd>
                <dt>メールアドレス</dt>
                <dd><?php echo $_SESSION['join']['email']; ?></dd>
                <dt>パスワード</dt>
                <dd><?php echo '【表示されません】'; ?></dd>
            </dl>

            <button type="submit">登録する</button>
        </form>
        <p class="back-command"><a href="createaccount.php?action=rewrite">&lt;修正へ戻る</a></p>
    </div>
<div class="ex">
    <ul>
        <li>データベースへの書き込み</li>
        <li>セッション情報の処理</li>
    </ul>
</div>
</body>

</html>
