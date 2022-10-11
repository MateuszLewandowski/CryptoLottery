<?php 

namespace App\Web3;

use Web3\Net;
use Web3\Web3;
/**
 * eth()
  1 => "accounts"
  2 => "chainId"
  3 => "gasPrice"
  4 => "getBalance"
  5 => "getBlockTransactionCountByHash"
  6 => "getBlockTransactionCountByNumber"
  7 => "getTransactionByHash"
  8 => "getTransactionReceipt"
  9 => "getUncleCountByBlockHash"
  10 => "hashrate"
  11 => "isMining"
  12 => "blockNumber"
  13 => "coinbase"
  14 => "sendTransaction"
  15 => "submitWork"
 */
final class RunContract
{
    private Web3 $web3;

    public function __construct(
    ) {  
        $this->web3 = new Web3('https://data-seed-prebsc-1-s1.binance.org:8545');
    } 
    /**
     * getBalance
        0 => "__construct"
        1 => "fromHex"
        2 => "fromEth"
        3 => "value"
        4 => "toWei"
        5 => "toKwei"
        6 => "toMwei"
        7 => "toGwei"
        8 => "toMicroether"
        9 => "toMilliether"
        10 => "toEther"
        11 => "toEth"
        12 => "toString"
     */
    public function fire() 
    {
        dd(
            // get_class_methods($this->web3->eth()->getBalance('0x919d0bD4D33a24805f3BaC9DF9c886Eb83126F8E'))
            ['pure' => $this->web3->eth()->getBalance('0x919d0bD4D33a24805f3BaC9DF9c886Eb83126F8E')->value()],
            ['eth' => $this->web3->eth()->getBalance('0x919d0bD4D33a24805f3BaC9DF9c886Eb83126F8E')->toEth()],
        );
    }
}