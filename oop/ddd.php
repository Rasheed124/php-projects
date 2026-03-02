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




// ==============================================================


<?php

header('Content-Type: text/plain');

class BankAccount
{

    public float $balance;

    public function __construct(
        public string $trid,
        public string $holder,
        float $balance
    ) {
        $this->balance = max(0, $balance);
    }

    public function printBalance()
    {
        echo "The balance of account #{$this->trid} is {$this->balance}.\n";
    }

    public function transfer(BankAccount $to, $amount = 0)
    {
        if ($this->balance >= $amount) {
            $this->balance = $this->balance - $amount;
            $to->balance   = $to->balance + $amount;

            return true;
        } else {
            return false;
        }

    }


}

$account1 = new BankAccount('2349785623478', 'Olivia Mason', 1250.00);

$account2 = new BankAccount('2349785623478', 'Adeola Adam', 500.00);
// $account2->printBalance();

$account1->transfer($account2, 800);

$account1->printBalance();
$account2->printBalance();






