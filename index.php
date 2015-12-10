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
  function is_uniq($s) {
    $s1 = str_split($s);
    $s1 = sort($s1);
    $s2 = $s2;
    $s2 = array_unique($s2);
    return $s1 == $s2;
  }

  if (!empty($_REQUEST["pattern"])) {
    $lines = file("./wordsEn.txt");
    $founds = preg_grep("/" . $_REQUEST["pattern"] . "/", $lines);
?>
    <ul id="results">
      <?php foreach ($founds as $found) { ?>
        <?php if ($_REQUEST["uniq"]==false || is_uniq($found)) { ?>
          <li class="result"><?= htmlspecialchars($found) ?></li>
        <?php } ?>
      <?php } ?>
    </ul>
<?php
  }
?>
</body>
