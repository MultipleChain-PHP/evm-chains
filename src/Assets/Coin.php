<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Assets;

use MultipleChain\Utils;
use MultipleChain\Enums\ErrorType;
use MultipleChain\EvmChains\Provider;
use MultipleChain\EvmChains\TransactionData;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Assets\CoinInterface;
use MultipleChain\EvmChains\Services\TransactionSigner;

class Coin implements CoinInterface
{
    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @var array<string,mixed>
     */
    private array $currency;

    /**
     * @param Provider|null $provider
     */
    public function __construct(?ProviderInterface $provider = null)
    {
        $this->provider = $provider ?? Provider::instance();
        $this->currency = $this->provider->network->getNativeCurrency();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        $name = $this->currency['name'] ?? $this->currency['symbol'];
        return is_string($name) ? $name : throw new \RuntimeException('Invalid currency name');
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        $symbol = $this->currency['symbol'];
        return is_string($symbol) ? $symbol : throw new \RuntimeException('Invalid currency symbol');
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        $decimals = $this->currency['decimals'];
        return is_int($decimals) ? $decimals : throw new \RuntimeException('Invalid currency decimals');
    }

    /**
     * @param string $owner
     * @return float
     */
    public function getBalance(string $owner): float
    {
        $balance = $this->provider->web3->getBalance($owner);
        return Utils::hexToNumber($balance, $this->getDecimals());
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param float $amount
     * @return TransactionSigner
     */
    public function transfer(string $sender, string $receiver, float $amount): TransactionSigner
    {
        if ($amount < 0) {
            throw new \RuntimeException(ErrorType::INVALID_AMOUNT->value);
        }

        if ($amount > $this->getBalance($sender)) {
            throw new \RuntimeException(ErrorType::INSUFFICIENT_BALANCE->value);
        }

        $amount = Utils::numberToHex($amount, $this->getDecimals());

        $txData = (new TransactionData())
            ->setFrom($sender)
            ->setTo($receiver)
            ->setValue($amount)
            ->setChainId($this->provider->network->getId())
            ->setGasPrice($this->provider->web3->getGasPrice())
            ->setNonce($this->provider->web3->getNonce($sender));

        $txData->setGasLimit($this->provider->web3->getEstimateGas($txData->toArray()));

        return new TransactionSigner($txData);
    }
}
