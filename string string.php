<?php

$line= preg_split("/\s+/",readline());
$n=$line[0];
$k=$line[1];
$words = [];
for($i = 0 ; $i < $n ; $i++){
    $words[]=readline();
}

for($i = 0 ; $i < $k ; $i++){
    $line=readline();
    $counter=0;
    foreach($words as $word){
        if(strlen($word)==strlen($line)){
            if(strtolower($word)===strtolower($line)){
                $counter++;
                continue;
            }
            $check=0;
            $a=str_split($word);
            $b=str_split($line);
            foreach($a as $e=>$l){
                if($l!==$b[$e]){
                    $check++;
                }
            }
            if($check<=1){
                $counter++;
            }
        }elseif(strlen($word)+1==strlen($line) OR strlen($word)==strlen($line)+1){
            if(strlen($word)>strlen($line)){
            $a=str_split($word);
            $b=str_split($line);
            }else{
                $b=str_split($word);
                $a=str_split($line);
            }
            $check=false;
            $check2=true;
            foreach($a as $e=>$l){
                $check2=true;
                if($check){
                    if ($l !== $b[$e-1]) {
                        $check2=false;
                    }
                }else {
                    if(!isset($b[$e])){
                        continue;
                    }
                    if ($l !== $b[$e]) {
                        $check=true;
                    }
                }
            }
            if($check2)$counter++;
        }
    }
    echo $counter."\n";
}