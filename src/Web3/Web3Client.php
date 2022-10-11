<?php 

namespace App\Web3;

use Web3\ValueObjects\Transaction;
use Web3\ValueObjects\Wei;
use Web3\Web3;

final class Web3Client
{
    private Web3 $web3;

    public function __construct(
    ) {  
        $this->web3 = new Web3('https://data-seed-prebsc-1-s1.binance.org:8545');
    } 
    
    public function getBalance(string $address): float 
    {
        return $this->web3->eth()->getBalance($address)->toEth();
    }

    public function contract(string $from, string $to, float $eth): bool 
    {
        return $this->web3->eth()->sendTransaction(
            Transaction::between(
                from: $from,
                to: $to
            )->withValue(
                wei: Wei::fromEth(
                    eth: $eth
                )
            )
        );
    }
}