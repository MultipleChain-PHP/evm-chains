<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Assets;

use MultipleChain\Utils\Number;
use MultipleChain\EvmChains\Provider;
use MultipleChain\Interfaces\ProviderInterface;
use MultipleChain\Interfaces\Assets\NftInterface;
use MultipleChain\EvmChains\Services\TransactionSigner;

class NFT extends Contract implements NftInterface
{
    /**
     * @param string $address
     * @param Provider|null $provider
     * @param array<string,object>|null $abi
     */
    public function __construct(string $address, ?ProviderInterface $provider = null, ?array $abi = null)
    {
        $dir = dirname(__DIR__, 2) . '/resources/ERC721.json';
        $erc721Abi = json_decode(file_get_contents($dir) ?: '', true);
        parent::__construct($address, $provider, $abi ?? $erc721Abi);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'NFT';
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return 'NFT';
    }

    /**
     * @param string $owner
     * @return Number
     */
    public function getBalance(string $owner): Number
    {
        return new Number(0);
    }

    /**
     * @param int|string $tokenId
     * @return string
     */
    public function getOwner(int|string $tokenId): string
    {
        return '0x';
    }

    /**
     * @param int|string $tokenId
     * @return string
     */
    public function getTokenURI(int|string $tokenId): string
    {
        return 'https://example.com';
    }

    /**
     * @param int|string $tokenId
     * @return string|null
     */
    public function getApproved(int|string $tokenId): ?string
    {
        return '0x';
    }

    /**
     * @param string $sender
     * @param string $receiver
     * @param int|string $tokenId
     * @return TransactionSigner
     */
    public function transfer(string $sender, string $receiver, int|string $tokenId): TransactionSigner
    {
        return new TransactionSigner('example');
    }

    /**
     * @param string $spender
     * @param string $owner
     * @param string $receiver
     * @param int|string $tokenId
     * @return TransactionSigner
     */
    public function transferFrom(
        string $spender,
        string $owner,
        string $receiver,
        int|string $tokenId
    ): TransactionSigner {
        return new TransactionSigner('example');
    }

    /**
     * @param string $owner
     * @param string $spender
     * @param int|string $tokenId
     * @return TransactionSigner
     */
    public function approve(string $owner, string $spender, int|string $tokenId): TransactionSigner
    {
        return new TransactionSigner('example');
    }
}
