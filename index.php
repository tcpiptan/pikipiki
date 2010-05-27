<?php

$key = '(＃＾ω＾)ﾋﾟｷﾋﾟｷ度';
$url = 'http://ptan.info/pikipiki/';
$urlPath = '/pikipiki/';
$bitlyUrl = 'http://ptan.info/bitly/?url=';

$ua = $_SERVER['HTTP_USER_AGENT'];
$id = trim($_REQUEST['id']);
$date = date("Ymd");
$todayYmd = date("Ymd");
$dateOk = TRUE;
if (preg_match('/^\d{8}$/', $_REQUEST['date'])) {
    if ($date < $_REQUEST['date']) {
        $dateOk = FALSE;
    }
    $date = $_REQUEST['date'];
}
elseif (!empty($_REQUEST['date'])) {
    $dateOk = FALSE;
}
if (preg_match('/^[A-Za-z0-9_]+$/', $id)) {
    $digest = unpack("C*", sha1("{$date}{$id}{$key}", TRUE));
    $percent = $digest[1] % 101;
    $bitly = trim(file_get_contents("{$bitlyUrl}{$url}%3fid={$id}%26date={$date}"));
    if (preg_match('/^ERROR:/', $bitly)) {
        $bitly = "{$url}%3fid={$id}%26date={$date}";
    }
    $tweetStr = urlencode("今日の {$id} さんの{$key}は {$percent}% です。 {$bitly} #pikipiki");
}
else {
    $id = NULL;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo($key); ?>判定</title>
<style type="text/css">
body {
    background-image: url(bg.gif);
}
div#form {
    width: 100%;
    padding: 64px 0 0 0;
    border: 0px solid #000;
}
div#form div {
    width: 720px;;
    border: 0px solid #000;
    margin: 0 auto;
    padding: 64px;
    text-align: center;
    background-color: #fff;
    opacity: 0.75;
}
span#result {
    font-weight: bold;
    font-size: medium;
}
span#twitterid {
    font-size: x-large;
}
span#percent {
    font-size: x-large;
}
</style>
</head>
<body>
<div id="form">
<div>
<h1><?php echo($key); ?>判定</h1><br />
<form method="POST" action="<?php echo($urlPath); ?>">
<input type="hidden" name="submit" value="1">
<input type="hidden" name="date" value="<?php echo($todayYmd); ?>">
twitterID：<input type="text" name="id">
<input type="submit" value="判定する！"><br />
<br />
</form>
<?php if ($dateOk): ?>
<?php if (!is_null($id)): ?>
<span id="result">今日の <a href="http://twitter.com/<?php echo($id); ?>" onClick="javascript: window.open('http://twitter.com/<?php echo($id); ?>');return false;"><span id="twitterid">@<?php echo($id); ?></span></a> さんの<?php echo($key); ?>は <span id="percent"><?php echo($percent); ?>%</span> です。</span><br />
<br />
<?php if ($_POST['submit'] == 1): ?>
<a href="http://twitter.com/home?status=<?php echo($tweetStr); ?>"><img src="twitter_icon.jpg" border="0"> twitterでつぶやく</a><br />
<?php endif; ?>
<?php elseif(!empty($_REQUEST['id'])): ?>
twitterIDが不正です！<br />
<?php endif; ?>
<?php else: ?>
日付が不正です！<br />
<?php endif; ?>
<br />
<hr />
&copy; 2010 <a href="http://twitter.com/tcpiptan" onClick="javascript: window.open('http://twitter.com/tcpiptan');return false;">@tcpiptan</a><br />
</div>
</div>
</body>
</html>
