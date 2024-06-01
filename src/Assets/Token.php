<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Assets;

use MultipleChain\Interfaces\Assets\TokenInterface;
use MultipleChain\EvmChains\Services\TransactionSigner;

class Token extends Contract implements TokenInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Token';
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return 'TOKEN';
    }

    /**
     * @return int
     */
    public function getDecimals(): int
    {
        return 18;
    }

    /**
     * @param string $owner
     * @return float
     */
    public function getBalance(string $owner): float
    {
        return 0.0;
    }

    /**
     * @return float
     */
    public function getTotalSupply(): float
    {
        return 0.0;
    }

    /**
     * @param string $owner
     * @param string $spender
     * @return float
     */
    public function getAllowance(string $owner, string $spender): float
    {
        return 0.0;
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param float $amount
     * @return TransactionSigner
     */
    public function transfer(string $sender, string $receiver, float $amount): TransactionSigner
    {
        return new TransactionSigner('example');
    }

    /**
     * @param string $spender
     * @param string $owner
     * @param string $receiver
     * @param float $amount
     * @return TransactionSigner
     */
    public function transferFrom(
        string $spender,
        string $owner,
        string $receiver,
        float $amount
    ): TransactionSigner {
        return new TransactionSigner('example');
    }

    /**
     * @param string $owner
     * @param string $spender
     * @param float $amount
     * @return TransactionSigner
     */
    public function approve(string $owner, string $spender, float $amount): TransactionSigner
    {
        return new TransactionSigner('example');
    }
}
