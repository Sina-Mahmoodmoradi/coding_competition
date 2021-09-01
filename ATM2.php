<?php

function time_stamp($time)
{
    $time=explode('-',$time);
    return strtotime($time[0].' '.$time[1]);
}

class ATM
{
    static private $one        = 10;
    static private $five       = 10;
    static private $ten        = 10;
    static private $fifty      = 10;
    static private $onehundred = 10;

    static public function WITHDRAW($amount)
    {
        $one=self::$one;
        $five=self::$five;
        $ten=self::$ten;
        $fifty=self::$fifty;
        $onehundred=self::$onehundred;
        while($amount>0){
            if($amount>=100 AND self::$onehundred>0){
                $amount-=100;
                self::$onehundred--;
            }elseif($amount>=50 AND self::$fifty>0){
                $amount-=50;
                self::$fifty--;
            }elseif($amount>=10 AND self::$ten>0){
                $amount-=10;
                self::$ten--;
            }elseif($amount>=5 AND self::$five>0){
                $amount-=5;
                self::$five--;
            }elseif($amount>=1 AND self::$one>0){
                $amount-=1;
                self::$one--;
            }else{
                self::$one=$one;
                self::$five=$five;
                self::$ten=$ten;
                self::$fifty=$fifty;
                self::$onehundred=$onehundred;
                return false;
            }
        }
        return true;
    }

    public static function ADD_BANKNOTE($value, $amount)
    {
        switch($value) {
            case 1   : self::$one        += $amount; break;
            case 5   : self::$five       += $amount; break;
            case 10  : self::$ten        += $amount; break;
            case 50  : self::$fifty      += $amount; break;
            case 100 : self::$onehundred += $amount; break;
        }
    }

    public static function getAll()
    {
        return (self::$one)+(self::$five*5)+(self::$ten*10)+(self::$fifty*50)+(self::$onehundred*100);
    }
}

class accounts
{
    private $username;
    private $balance;

    public function __CONSTRUCT($user)
    {
        $this->username = $user;
        $this->balance = 100;
    }


    public function getUser()
    {
        return $this->username;
    }

    public function deposit($amount)
    {
        $this->balance += $amount;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function WITHDRAW($amount)
    {
        $this->balance -= $amount;
    }
}

class Bank
{
    private $accounts;

    public function userExists($user)
    {
        return isset($this->accounts[$user]);
    }

    public function addAccount($user)
    {
        $this->accounts[$user] = new accounts($user);
    }

    public function getAccount($user)
    {
        return $this->accounts[$user];
    }
}

$bank = new Bank;
$n=readline();
$orders = array();
$checks=array();

for($i=0;$i<$n;$i++) {
    $line=readline();
    $line = preg_split('/\s+/', $line);
    $line['timestamp']= time_stamp($line[array_key_last($line)]);
    $line['order']=$i;
    $orders[]=$line;
    if($line[0] == 'TRANSFER'){
        $order=['DEPOSIT',$line[2],$line[3]];
        $order['timestamp']=$line['timestamp']+3600;
        $checks[$order['timestamp']]=false;
        $orders[]=$order;
    }
}

usort($orders, function ($a, $b) {
    return $a['timestamp'] - $b['timestamp'];
});

foreach($orders as $index=>&$order){

    if($order[0] === 'REGISTER'){
        if($bank->userExists($order[1])){
            $order['message']='Duplicate User!';
        }else{
            $bank->addAccount($order[1]);
            $order['message']='Registered Successfully';
        }
    }elseif($order[0] === 'DEPOSIT'){
        if(isset($checks[$order['timestamp']])){
            if($checks[$order['timestamp']]){
                $bank->getAccount($order[1])->deposit($order[2]);
            }
            unset($orders[$index]);
        }elseif(!$bank->userExists($order[1])){
            $order['message']='No Such User Found!';
        }else{
            $bank->getAccount($order[1])->deposit($order[2]);
            $order['message']=$bank->getAccount($order[1])->getBalance();
        }
    }elseif($order[0] === 'WITHDRAW'){
        if(!$bank->userExists($order[1])){
            $order['message']='No Such User Found!';
        }elseif($order[2]>200){
            $order['message']='Maximum Amount Exceeded!';
        }elseif($bank->getAccount($order[1])->getBalance()<$order[2]){
            $order['message']='Not Enough Fund!';
        }else{
            $check=ATM::WITHDRAW($order[2]);
            if($check){
                $bank->getAccount($order[1])->WITHDRAW($order[2]);
                $order['message']=$bank->getAccount($order[1])->getBalance();
            }else{
                $order['message']='Not Enough Banknotes!';
            }
        }
    }elseif($order[0] === 'TRANSFER'){
        if(!$bank->userExists($order[1]) OR !$bank->userExists($order[2])){
            $order['message']='No Such User Found!';
        }elseif($order[3]>3000){
            $order['message']='Maximum Amount Exceeded!';
        }elseif($bank->getAccount($order[1])->getBalance()<$order[3]){
            $order['message']='Not Enough Fund!';
        }else{
            $bank->getAccount($order[1])->WITHDRAW($order[3]);
            $order['message']=$bank->getAccount($order[1])->getBalance();
            $checks[$order['timestamp']+3600]=true;
        }
    }elseif($order[0] === 'GET_BALANCE'){
        if(!$bank->userExists($order[1])){
            $order['message']='No Such User Found!';
        }elseif($bank->getAccount($order[1])->getBalance()<10){
            $order['message']='Not Enough Fund!';
        }else{
            $bank->getAccount($order[1])->WITHDRAW(10);
            $order['message']=$bank->getAccount($order[1])->getBalance();
        }
    }elseif($order[0] === 'ADD_BANKNOTE'){
        ATM::ADD_BANKNOTE($order[1],$order[2]);
        $order['message']=ATM::getAll();
    }
}

usort($orders, function ($a, $b) {
    return $a['order'] - $b['order'];
});

foreach($orders as &$order){
    echo $order['message']."\n";
}