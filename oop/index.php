<?php

header('Content-Type: text/plain');

class BankAccount
{

    public function __construct(
        private string $trid,
        private string $holder,
        private float $balance
    ) {
    }

    public function printBalance()
    {
        echo "The balance of account #{$this->trid} is {$this->balance} owned by {$this->holder}.\n";
    }

    public function getBalance(): float
    {
        return $this->balance;
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

var_dump($account1->getBalance());

$account1->printBalance();
// $account2->printBalance();
