<?php


$name = readline();

$n= substr_count($name,'a')+
    substr_count($name,'i')+
    substr_count($name,'e')+
    substr_count($name,'u')+
    substr_count($name,'o');
/*$n=0;
foreach(explode('',$name) as $l){
    if($l='a' OR $l='i' OR $l='e' OR $l='u' OR $l='o')
        $n++;
}*/
echo 2 ** $n;