<?php
  if (!empty($_REQUEST["pattern"])) {
    $lines = file("./data/words.txt");
    $founds = preg_grep("/" . $_REQUEST["pattern"] . "/", $lines);
    $results = [];
    foreach ($founds as $found) {
      $results[] = $result;
    }
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

  if (!empty($_REQUEST["strings"])) {
    $strings = $_REQUEST["strings"];
    $length = intval($_REQUEST["length"]);
    $filters = empty($_REQUEST["filters"]) ? [] : explode(" ", $_REQUEST["filters"]);
    $counts = parse_strings($strings);
    $lines = file("./data/words-$length.txt", FILE_IGNORE_NEW_LINES);
    $results = [];
    foreach ($lines as $line) {
      if (strlen($line)==$length && is_match($line, $counts)) {
        $results[] = $line;
      }
    }
  }
?>
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
    <form id="finder-form" action="./index.php" method="get">
      <div id="pattern"><label for="pattern">pattern:</label><input type="text" name="pattern" value="<?= htmlspecialchars($_REQUEST["pattern"]) ?>" data-clear-btn="true" data-mini="true"></div>
      <div id="strings"><label for="strings">strings:</label><input type="text" name="strings" value="<?= htmlspecialchars($_REQUEST["strings"]) ?>" data-clear-btn="true" data-mini="true"></div>
      <div id="length"><label for="length">length:</label>
        <select name="length" data-mini="true">
          <?php for ($i=1; $i<=20; $i++) { ?>
            <option value="<?= $i ?>"<?= $_REQUEST["length"]==$i ? " selected" : ""?>><?= $i ?></option>
          <?php } ?>
        </select>
      </div>
      <div id="filters"><label for="filters">filters:</label><input id="filter-input" type="text" name="filters" value="<?= htmlspecialchars($_REQUEST["filters"]) ?>" data-clear-btn="true" data-mini="true"></div>
      <div id="reset"><input id="clear-form" type="button" value="reset" data-mini="true"></div>
      <div id="submit"><input type="submit" value="find" data-mini="true"></div>
    </form>
    <ul id="results" data-role="listview" data-insert="true" data-autodividers="true">
      <?php foreach ($results as $result) { ?>
        <li class="result"><a href="https://www.google.co.jp/search?q=define+<?= urlencode($result) ?>&cad=h" target=_blank><?= htmlspecialchars($result) ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <script language="javascript">
    $(function(){
      $("#clear-form").on("click", function(ev) {
        $("#finder-form").find("textarea, :text, select").val("").end().find(":checked").prop("checked", false);
        $.each($("#results li"), function() {$(this).show();});
      });
      $("#filter-input").on("keyup", function(ev) {
        var filters = $(this).val().split(" ").filter(function(v){return v!==""});
        var is_filtered = function(s, filters) {
          var found = false;
          $.each(filters, function(idx, filter) {
            if (s.indexOf(filter) != -1) {
              found = true;
            }
          });
          return found;
        };
        $.each($("#results li"), function(idx, val) {
          if (filters.length>=0 && is_filtered($(this).contents()[0].textContent, filters)) {
            $(this).hide();
          } else {
            $(this).show();
          }
        });
      });
    });
  </script>
</body>
