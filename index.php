<html>
<head>
<title>word finder</title>
</head>
<body>
<form action="./index.php" method="get">
<div id="pattern"><span class="label">pattern:</span><input type="text" name="pattern" value="<?= htmlspecialchars($_REQUEST["pattern"]) ?>">
<div id="uniq"><span class="label">uniq:</span><input type="checkbox" name="uniq" value="1" "<?= $_REQUEST["uniq"]==1 ? "checked" : "" ?>">
<input type="submit" value="find">
</form>
<?php
  var_dump($_REQUEST);
  if (!empty($_REQUEST["pattern"])) {
    $lines = file("./wordsEn.txt");
    var_dump(count($lines));
    $founds = preg_grep("/" . $_REQUEST["pattern"] . "/", $lines);
    var_dump($founds);
?>
    <ul id="results">
      <?php foreach ($founds as $found) { ?>
      <li class="result"><?= htmlspecialchars($found) ?></li>
      <?php } ?>
    </ul>
<?php
  }
?>
</body>
