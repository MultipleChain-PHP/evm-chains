<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Models;

use MultipleChain\Utils\Number;
use MultipleChain\EvmChains\Provider;
use MultipleChain\Enums\AssetDirection;
use MultipleChain\EvmChains\Assets\Token;
use MultipleChain\Enums\TransactionStatus;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Models\TokenTransactionInterface;

class TokenTransaction extends ContractTransaction implements TokenTransactionInterface
{
    /**
     * @param string $address
     * @param Provider|null $provider
     * @param array<object>|null $abi
     */
    public function __construct(string $address, ?ProviderInterface $provider = null, ?array $abi = null)
    {
        parent::__construct(
            $address,
            $provider,
            $abi ? $abi : json_decode(file_get_contents(dirname(__DIR__, 2) . '/resources/ERC20.json') ?: '')
        );
    }

    /**
     * @return string
     */
    public function getReceiver(): string
    {
        $decoded = $this->decodeData();

        if (is_null($decoded)) {
            return '';
        }

        if ('transferFrom' === $decoded->name) {
            return $decoded->args[1]->value;
        }

        return $decoded->args[0]->value;
    }

    /**
     * @return string
     */
    public function getSender(): string
    {
        $decoded = $this->decodeData();

        if (is_null($decoded)) {
            return '';
        }

        if ('transferFrom' === $decoded->name) {
            return $decoded->args[0]->value;
        }

        return $this->getSigner();
    }

    /**
     * @return Number
     */
    public function getAmount(): Number
    {
        $decoded = $this->decodeData();

        if (is_null($decoded)) {
            return new Number(0);
        }

        $token = new Token($this->getAddress());

        if ('transferFrom' === $decoded->name) {
            return new Number($decoded->args[2]->value, $token->getDecimals());
        }

        return new Number($decoded->args[1]->value, $token->getDecimals());
    }

    /**
     * @param AssetDirection $direction
     * @param string $address
     * @param float $amount
     * @return TransactionStatus
     */
    public function verifyTransfer(AssetDirection $direction, string $address, float $amount): TransactionStatus
    {
        $status = $this->getStatus();

        if (TransactionStatus::PENDING === $status) {
            return TransactionStatus::PENDING;
        }

        if ($this->getAmount()->toString() != (string) $amount) {
            return TransactionStatus::FAILED;
        }

        if (AssetDirection::INCOMING === $direction) {
            if (strtolower($this->getReceiver()) !== strtolower($address)) {
                return TransactionStatus::FAILED;
            }
        } else {
            if (strtolower($this->getSender()) !== strtolower($address)) {
                return TransactionStatus::FAILED;
            }
        }

        return TransactionStatus::CONFIRMED;
    }
}
