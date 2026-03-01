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

        $this->balance = $this->balance - $amount;
        $to->balance   = $to->balance + $amount;

        // var_dump($this);
        // var_dump($to);
        // var_dump($amount);
    }
}

$account1 = new BankAccount('2349785623478', 'Olivia Mason', 1250.00);

$account2 = new BankAccount('2349785623478', 'Adeola Adam', 500.00);
// $account2->printBalance();

$account1->transfer($account2, 600);

$account1->printBalance();
