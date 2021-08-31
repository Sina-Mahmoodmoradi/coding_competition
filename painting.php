<?php


$line = readline();
$line = preg_split("/\s+/", $line);
$n=$line[0];
$m=$line[1];
$k=$line[2];

$table = array();

for ($i = 0; $i < $k; $i++){
    $line=readline();
    $line = preg_split("/\s+/", $line);
    $r=$line[0];
    $c=$line[1];
    $l=$line[2];
    for($e=$r;$e<$l+$r;$e++){
        for($j=$c;$j<$l+$c;$j++){
            $table[$e][$j][]=$i;
        }
    }
}

$colors=array();

foreach($table as $row){
    foreach($row as $cell){
        sort($cell);
        $colors[]=$cell;
    }
}

echo count(array_unique($colors,SORT_REGULAR));