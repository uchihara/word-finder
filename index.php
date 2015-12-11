<html>
<head>
<title>word finder</title>
</head>
<body>
<form action="./index.php" method="get">
<div id="pattern"><span class="label">pattern:</span><input type="text" name="pattern" value="<?= htmlspecialchars($_REQUEST["pattern"]) ?>"></div>
<div id="uniq"><span class="label">uniq:</span><input type="checkbox" name="uniq" value="1"<?= $_REQUEST["uniq"]==1 ? "checked" : "" ?>></div>
<div id="strings"><span class="label">strings:</span><input type="text" name="strings" value="<?= htmlspecialchars($_REQUEST["strings"]) ?>"></div>
<div id="length"><span class="label">length:</span>
<select name="length">
<?php for ($i=1; $i<=20; $i++) { ?>
<option value="<?= $i ?>"<?= $_REQUEST["length"]==$i ? " selected" : ""?>>
<?php } ?>
</div>
<div id="filters"><span class="label">filters:</span><input type="text" name="filters" value="<?= htmlspecialchars($_REQUEST["filters"]) ?>"></div>
<div id="submit"><input type="submit" value="find"></div>
</form>
<?php
  function is_uniq($s) {
    $s1 = str_split($s);
    sort($s1);
    $s2 = $s1;
    $s2 = array_unique($s2);
    return $s1 == $s2;
  }

  if (!empty($_REQUEST["pattern"])) {
    $lines = file("./words.txt");
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

  function parse_strings($s) {
    $counts = array();
    foreach (str_split($s) as $c) {
      if (!isset($counts[$c])) $counts[$c] = 0;
      $counts[$c] += 1;
    }
    return $counts;
  }

  function is_match($s, $base_counts) {
    $this_counts = parse_strings($s);
    foreach ($this_counts as $c => $cnt) {
      if (!isset($base_counts[$c])) return false;
      if ($base_counts[$c] < $cnt) return false;
    }
    return true;
  }

  function is_filtered($s, $filters) {
    foreach ($filters as $filter) {
      if (strpos($s, $filter) !== false) {
        return true;
      }
    }
    return false;
  }

  if (!empty($_REQUEST["strings"])) {
    $strings = $_REQUEST["strings"];
    $length = intval($_REQUEST["length"]);
    $filters = empty($_REQUEST["filters"]) ? [] : explode(" ", $_REQUEST["filters"]);
    $counts = parse_strings($strings);
    $lines = file("./words.txt", FILE_IGNORE_NEW_LINES);
?>
    <ul id="results">
      <?php foreach ($lines as $line) { ?>
        <?php if (strlen($line)==$length && is_match($line, $counts) && !is_filtered($line, $filters)) { ?>
          <li class="result"><?= htmlspecialchars($line) ?></li>
        <?php } ?>
      <?php } ?>
    </ul>
<?php
  }
?>
</body>
