<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!--json arrejaus decode ir atprintinimas html'e su PHP(selector, option)-->

<?php

//JSON get from link
$url = "https://gist.githubusercontent.com/Goles/3196253/raw/9ca4e7e62ea5ad935bb3580dc0a07d9df033b451/CountryCodes.json";
$json = file_get_contents($url);
$decode = json_decode($json, true);
//var_dump($decode);
?>

<form action="index.php" method="POST" >
    <select name="list">
        <?php foreach ($decode as $key => $value): ?>
            <option value="<?php print $key; ?>"> <?php print $value['name']; ?> </option>
        <?php endforeach; ?>
    </select><br>
    <?php if(isset($_POST['send'])): ?>
        <input type="text" value="<?php print $decode[$_POST['list']]['dial_code']; ?>">
    <?php endif; ?>
    <button type="submit" name="send">Siusti</button>
</form>
</body>
</html>