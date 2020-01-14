<?php
session_start();
require('function.php');

//フォームが空かどうかチェック
if (!empty($_POST)) {
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }

    //アカウント重複チェック
    if (empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if ($record['cnt'] > 0) {
            $error['email'] = 'duplicate';
        }
    }
    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location: check.php');
        exit();
    }
}
if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
    $_POST = $_SESSION['join'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="content">
        <header>
            <div class="mein-name">
                <h2>ひとこと掲示板Onetweet</h2>
            </div>
        </header>
        <h1>新規登録</h1>
        <p class="description">ニックネーム、メールアドレス、パスワードをご記入ください。</p>
        <form action="" method="post">
            <div class="control">
                <label for="name">ニックネーム<span class="required">必須</span></label>
                <input type="text" name="name" id="name" maxlength="30" placeholder="例）やまちゃん" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES); ?>">
                <?php if ($error['name'] == 'blank') : ?>
                    <p class="required">※ニックネームを入力してください。</p>
                <?php endif; ?>
            </div>
            <div class="control">
                <label for="email">メールアドレス<span class="required">必須</span></label>
                <input type="email" name="email" id="email" maxlength="100" placeholder="例）xxxx@xxxx" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>">
                <?php if ($error['email'] == 'blank') : ?>
                    <p class="required">※メールアドレスを入力してください。</p>
                <?php elseif ($error['email'] == 'duplicate') : ?>
                    <p class="required">※このメールアドレスは既に登録されています。</p>
                <?php endif; ?>
            </div>
            <div class="control">
                <label for="password">パスワード<span class="required">必須</span></label>
                <input type="password" name="password" id="password" maxlength="30" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>">
                <?php if ($error['password'] == 'blank') : ?>
                    <p class="required">※パスワードを入力してください。</p>
                <?php endif; ?>
            </div>
            <div class="button">
                <button type="submit">入力内容を確認する</button>
            </div>
        </form>

        <p class="back-command"><a href="login.php">ログイン画面へ戻る</a></p>
    </div>
    <div class="ex">
        <ul>
            <li>フォームの空チェック</li>
            <li>データベースのメールアドレス重複チェック</li>
        </ul>
    </div>
</body>

</html>
