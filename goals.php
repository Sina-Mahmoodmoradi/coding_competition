<?php


$arr=preg_split("/\s+/",readline());
$n=$arr[0];
$a=$arr[1];
$b=$arr[2];
$goals=preg_split("/\s+/",readline());
foreach($goals as $i=>$min){
    if($i===$n-1)break;
    if($min>$goals[$i+1])
        if ($min > 45 + $a) die('NO');
}

if($goals[$n-1] > 90+$b)die('NO');
die('YES');