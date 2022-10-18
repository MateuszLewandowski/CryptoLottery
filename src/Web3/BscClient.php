<?php 

namespace App\Web3;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @see https://docs.bscscan.com/v/bscscan-testnet/api-endpoints/accounts
 */
final class BscClient
{
    private const URL = 'https://api-testnet.bscscan.com/api';
    private const API_KEY = 'AETXUYUD8M7JNIXVXR86YETWUQIBSP4GDP';
    public const LOTTERY_WALLET = '0x919d0bD4D33a24805f3BaC9DF9c886Eb83126F8E'; # mozilla
    private const TEST_WALLET = '0x7641559b0D6C5D28D139A02Ff25A0d8449cC449b'; # chrome

    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function test() {
        $this->getLogs();
        // $this->getContractABI(self::TEST_WALLET);
    }

    public function getNormalTransactionListByAddress(string $address) {
        $response = $this->client->request(
            method: 'GET',
            url: SELF::URL . '?module=account&action=txlist&startblock=0&endblock=99999999&page=1&offset=10&sort=ascaddress=' . $address . '&apikey=' . SELF::API_KEY
        );
        dd($response->toArray());
    }

    public function getBalance(string $address) {
        $response = $this->client->request(
            method: 'GET',
            url: SELF::URL . '?module=account&action=balancemulti&address=' . $address . '&apikey=' . SELF::API_KEY
        );
        dd($response->toArray());
    }

    public function getLogs() {
        $response = $this->client->request(
            method: 'GET',
            url: 'https://api-testnet.bscscan.com/api?module=account&action=txlist&address='.self::LOTTERY_WALLET.'&startblock=0&endblock=99999999&page=1&offset=100&sort=asc&apikey=' . SELF::API_KEY
        );
        return $response->toArray();
    }

    public function getTransactionReceiptStatus(string $address) {
        $response = $this->client->request(
            method: 'GET',
            url: SELF::URL . '?module=account&action=gettxreceiptstatus&txhash=' . $address . '&apikey=' . SELF::API_KEY
        );
        dd($response->toArray());
    }

    public function getContractABI(string $address) {
        $response = $this->client->request(
            method: 'GET',
            url: SELF::URL . '?module=contract&action=getabi&address=' . $address . '&apikey=' . SELF::API_KEY
        );
        dd($response->toArray());
    }

    public function getSourceCodeForVerifiedContractSource(string $address) {
        $response = $this->client->request(
            method: 'GET',
            url: SELF::URL . '?module=contract&action=getsourcecode&address=' . $address . '&apikey=' . SELF::API_KEY
        );
        dd($response->toArray());
    }
}