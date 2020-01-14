<?php
session_start();
require('function.php');

if ($_COOKIE['email'] !== '') {
    $email = $_COOKIE['email'];
}

//DBに登録されているか確認、フォームが空でないか確認
if (!empty($_POST)) {
    $email = $_POST['email'];
    if ($_POST['email'] !== '' && $_POST['password'] !== '') {
        $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
        $login->execute(array(
            $_POST['email'],
            sha1($_POST['password'])
        ));
        $member = $login->fetch();

        if ($member) {
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();

            if ($_POST['save'] === 'on') {
                setcookie('email', $_POST['email'], time() + 60 * 60 * 24 * 14);
            }

            header('Location: index.php');
            exit();
        } else {
            $error['login'] = 'failed';
        }
    } elseif ($_POST['email'] == '') {
        $error['email'] = 'blank';
        if ($_POST['password'] == '') {
            $error['password'] = 'blank';
        }
    } elseif ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="content">
        <header>
            <div class="mein-name">
                <h2>ひとこと掲示板Onetweet</h2>
            </div>
        </header>
        <h1>ログイン</h1>



        <form action="" method="post">
            <div class="control">
                <label for="mymail">メールアドレス<span class="required">必須</span></label>
                <input id="mymail" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"></label>
                <?php if ($error['email'] == 'blank') : ?>
                    <p class="required">※メールアドレスを入力してください。</p>
                <?php endif; ?>
            </div>
            <div class="control">
                <label for="pass">パスワード<span class="required">必須</span></label>
                <input id="pass" type="password" name="password" value="<?php echo htmlspecialchars($_POST['password']); ?>">
                <?php if ($error['password'] == 'blank') : ?>
                    <p class="required">※パスワードを入力してください。</p>
                <?php endif; ?>
                <?php if ($error['login'] == 'failed') : ?>
                    <p class="required">※ログインできませんでした、記入内容をご確認下さい。</p>
                <?php endif; ?>
            </div>
            <div class="form-check">
                <label for="save" style="display:inline">次回ログイン時メールアドレスを自動入力する</label>
                <input id="save" type="checkbox" name="save" value="on">
            </div>
            <div class="button">
                <button type="submit">ログイン</button>
            </div>
        </form>
        <p class="back-command"><a href="createaccount.php">登録されていない方はこちらから新規登録してください</a></p>
    </div>
    <div class="ex">
        <ul>
            <li>PHPとMySQL上での、セッション・クッキーを使用したログイン機能・書き込み機能</li>
            <li>HTML/CSSを使用して、簡易的なサイトを構成しています。</li>
        </ul>
        <p>登録しない場合下記の物をお使い下さい。</p>
        <ul>
            <li>メールアドレス：1@1</li>
        <li>パスワード：1</li>
        </ul>
    </div>
</body>

</html>
