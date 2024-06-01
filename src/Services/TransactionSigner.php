<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Services;

use MultipleChain\EvmChains\Provider;
use MultipleChain\EvmChains\TransactionData;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Services\TransactionSignerInterface;

class TransactionSigner implements TransactionSignerInterface
{
    /**
     * @var TransactionData
     */
    private TransactionData $rawData;

    /**
     * @var string
     */
    private string $signedData;

    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @param mixed $rawData
     * @param Provider|null $provider
     * @return void
     */
    public function __construct(mixed $rawData, ?ProviderInterface $provider = null)
    {
        if (null === $rawData) {
            throw new \RuntimeException('Invalid transaction data');
        }

        if (!is_array($rawData)) {
            throw new \RuntimeException('Invalid transaction data');
        }

        // @phpstan-ignore-next-line
        if (!($rawData instanceof TransactionData)) {
            $rawData = new TransactionData($rawData);
        }

        $this->rawData = $rawData;
        $this->provider = $provider ?? Provider::instance();
    }

    /**
     * @param string $privateKey
     * @return TransactionSignerInterface
     */
    public function sign(string $privateKey): TransactionSignerInterface
    {
        // example implementation
        $this->provider->isTestnet(); // just for phpstan
        $this->signedData = 'signedData';
        return $this;
    }

    /**
     * @return string Transaction id
     */
    public function send(): string
    {
        // example implementation
        return 'transactionId';
    }

    /**
     * @return TransactionData
     */
    public function getRawData(): mixed
    {
        return $this->rawData;
    }

    /**
     * @return string
     */
    public function getSignedData(): string
    {
        return $this->signedData;
    }
}
