<html>
<head>
<title>word finder</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<style type="text/css">
  div#container {
    margin: 0.5em;
  }
</style>
</head>
<body>
<div id="container">
<form action="./index.php" method="get">
<div id="pattern"><label for="pattern">pattern:</label><input type="text" name="pattern" value="<?= htmlspecialchars($_REQUEST["pattern"]) ?>"></div>
<div id="uniq"><label for="uniq">uniq:</label><input type="checkbox" name="uniq" value="1"<?= $_REQUEST["uniq"]==1 ? "checked" : "" ?>></div>
<div id="strings"><label for="strings">strings:</label><input type="text" name="strings" value="<?= htmlspecialchars($_REQUEST["strings"]) ?>"></div>
<div id="length"><label for="length">length:</label>
<select name="length">
<?php for ($i=1; $i<=20; $i++) { ?>
<option value="<?= $i ?>"<?= $_REQUEST["length"]==$i ? " selected" : ""?>><?= $i ?>
<?php } ?>
</select>
</div>
<div id="filters"><label for="filters">filters:</label><input type="text" name="filters" value="<?= htmlspecialchars($_REQUEST["filters"]) ?>"></div>
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
    <ul id="results" data-role="listview" data-insert="true" data-filter="true">
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
    <ul id="results" data-role="listview" data-insert="true">
      <?php foreach ($lines as $line) { ?>
        <?php if (strlen($line)==$length && is_match($line, $counts) && !is_filtered($line, $filters)) { ?>
          <li class="result"><?= htmlspecialchars($line) ?></li>
        <?php } ?>
      <?php } ?>
    </ul>
<?php
  }
?>
</div>
</body>
