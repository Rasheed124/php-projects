<?php


header('Content-Type: text/plain');

class BankAccount {
    public string $trid;
    public string $name;
    public float $balance;

    function printBalance(){
        echo  "The Account balance of {$this->name}, with transaction ID {$this->trid} is {$this->balance}";
    }
}


$account1 = new BankAccount();


$account1->trid = '200222034';
$account1->name = 'Adeola';
$account1->balance = 400.00;

$account1->printBalance();


// var_dump($account1);





