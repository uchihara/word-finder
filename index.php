<?php
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
    div.container {
      margin: 0.5em;
    }
    .filters {
      border-width: 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <form class="finder-form" action="./index.php" method="get">
      <div class="strings"><label for="strings">strings:</label><input type="text" name="strings" value="<?= htmlspecialchars($_REQUEST["strings"]) ?>" data-clear-btn="true" data-mini="true"></div>
      <div class="length"><label for="length">length:</label>
        <select name="length" data-mini="true">
          <?php for ($i=1; $i<=20; $i++) { ?>
            <option value="<?= $i ?>"<?= $_REQUEST["length"]==$i ? " selected" : ""?>><?= $i ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="filters ui-input-text ui-input-has-clear"><label for="filters">filters:</label><textarea class="filter-input" name="filters" data-clear-btn="true" data-mini="true"><?= htmlspecialchars($_REQUEST["filters"]) ?></textarea><a href="#" tabindex="-1" aria-hidden="true" class="clear-filters ui-input-clear ui-btn ui-icon-delete ui-btn-icon-notext ui-corner-all" title="Clear text">Clear text</a></div>
      <div class="submit"><input type="submit" value="find" data-mini="true"></div>
    </form>
    <ul class="results" data-role="listview" data-inset="true" data-autodividers="true">
      <?php foreach ($results as $result) { ?>
        <li class="result" data-word="<?= htmlspecialchars($result) ?>"><a href="https://www.google.co.jp/search?q=define+<?= urlencode($result) ?>&cad=h" target=_blank><?= htmlspecialchars($result) ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <script language="javascript">
    $(function(){

      var filter_results = function(self) {
        var filters = $(self).val().split(" ").filter(function(v){return v!==""});
        var is_filtered = function(s, filters) {
          return s != undefined && filters.some(function(filter) {
            return s.indexOf(filter) != -1;
          });
        };
        $.each($(".ui-page-active .results li.result"), function(idx, val) {
          if (filters.length>=0 && is_filtered($(this).data("word").toString(), filters)) {
            $(this).hide();
          } else {
            $(this).show();
          }
        });
      };

      filter_results($(".ui-page-active .filter-input"));
      $(document).on("pagechange", function() {
        filter_results($(".ui-page-active .filter-input"));
      })
      $(document).on("keyup", ".ui-page-active .filter-input", function(ev) {
        filter_results(this);
      });
      $(document).on("click", ".ui-page-active .clear-filters", function(ev) {
        $(".ui-page-active .filter-input").val("");
        filter_results($(".ui-page-active .filter-input"));
      });
    });
  </script>
</body>
