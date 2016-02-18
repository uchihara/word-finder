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

  function any($array, callable $callback) {
    $array = array_map($callback, $array);
    return array_reduce($array, function ($a, $b) { return $a || $b; }, false);
  }

  function is_filtered($s, $filters) {
    return any($filters, function($filter) use ($s) {
      return strpos($s, $filter) !== false;
    });
  }

  function filter_results($filters, $src) {
    $dst = array();
    foreach($src as $result) {
      if (count($filters)>=0 && is_filtered($result, $filters)) {
      } else {
        $dst[] = $result;
      }
    }
    return $dst;
  }

  function split_combination_filters($cfilters) {
    if (!isset($cfilters)) return [];
    $arr = preg_split("/\\s+/", $cfilters);
    $filters = [];
    foreach ($arr as $s) {
      $first_char = substr($s, 0, 1);
      for ($i = 1; $i < strlen($s); $i++) {
        $c = substr($s, $i, 1);
        $filters[] = $first_char . $c;
        $filters[] = $c . $first_char;
      }
    }
    return $filters;
  }

  if (!empty($_REQUEST["strings"])) {
    $strings = $_REQUEST["strings"];
    $lengths = $_REQUEST["lengths"];
    $filters = empty($_REQUEST["filters"]) ? [] : explode(" ", $_REQUEST["filters"]);
    $combination_filters = split_combination_filters($_REQUEST["combination_filters"]);
    $filters = array_merge($filters, $combination_filters);
    $counts = parse_strings($strings);
    $results = [];
    foreach ($lengths as $length) {
      $lines = file("./data/words-$length.txt", FILE_IGNORE_NEW_LINES);
      foreach ($lines as $line) {
        if (strlen($line)==$length && is_match($line, $counts)) {
          $results[] = $line;
        }
      }
    }
    $results = filter_results($filters, $results);
    sort($results);
    $combined_filters = $filters;
    sort($combined_filters);
  }
?>
<html>
<head>
  <title>word finder</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
  <link rel="stylesheet" href="//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
  <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
  <style type="text/css">
    div.container {
      margin: 0.5em;
    }
    .filters, .combination-filters {
      border-width: 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <form class="finder-form" action="./index.php" method="get">
      <div class="strings"><label for="strings">strings:</label><input type="text" name="strings" value="<?= htmlspecialchars($_REQUEST["strings"]) ?>" data-clear-btn="true" data-mini="true"></div>
      <div class="lengths"><label for="lengths[]">length:</label>
        <select name="lengths[]" data-mini="false" data-native-menu="false" multiple>
          <?php for ($i=1; $i<=8; $i++) { ?>
            <option value="<?= $i ?>"<?= in_array($i, $_REQUEST["lengths"]) ? " selected" : ""?>><?= $i ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="filters ui-input-text ui-input-has-clear"><label for="filters">filters:</label><textarea class="filter-input" name="filters" data-clear-btn="true" data-mini="true"><?= htmlspecialchars($_REQUEST["filters"]) ?></textarea><a href="#" tabindex="-1" aria-hidden="true" class="clear-filters ui-input-clear ui-btn ui-icon-delete ui-btn-icon-notext ui-corner-all" title="Clear text">Clear text</a></div>
      <div class="combination-filters ui-input-text ui-input-has-clear"><label for="combination-filters">combination filters:</label><textarea class="combination-filter-input" name="combination_filters" data-clear-btn="true" data-mini="true"><?= htmlspecialchars($_REQUEST["combination_filters"]) ?></textarea><a href="#" tabindex="-1" aria-hidden="true" class="clear-combination-filters ui-input-clear ui-btn ui-icon-delete ui-btn-icon-notext ui-corner-all" title="Clear text">Clear text</a></div>
      <div class="combined-filters"><label>combined filters:</label><textarea data-mini="true" readonly="readonly"><?= htmlspecialchars(implode(" ", $combined_filters)) ?></textarea></div>
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
      $(document).on("click", ".ui-page-active .clear-filters", function(ev) {
        $(".ui-page-active .filter-input").val("");
      });
      $(document).on("click", ".ui-page-active .clear-combination-filters", function(ev) {
        $(".ui-page-active .combination-filter-input").val("");
      });
    });
  </script>
</body>
