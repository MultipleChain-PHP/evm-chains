<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests\Assets;

use MultipleChain\EvmChains\Assets\Token;
use MultipleChain\EvmChains\Models\Transaction;
use MultipleChain\EvmChains\Tests\BaseTest;
use MultipleChain\Utils\Math;

class TokenTest extends BaseTest
{
    /**
     * @var Token
     */
    private Token $token;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->token = new Token($this->data->tokenTestAddress);
    }

    /**
     * @return void
     */
    public function testName(): void
    {
        $this->assertEquals('MyToken', $this->token->getName());
    }

    /**
     * @return void
     */
    public function testSymbol(): void
    {
        $this->assertEquals('MTK', $this->token->getSymbol());
    }

    /**
     * @return void
     */
    public function testDecimals(): void
    {
        $this->assertEquals(18, $this->token->getDecimals());
    }

    /**
     * @return void
     */
    public function testBalance(): void
    {
        $this->assertEquals(
            $this->data->tokenBalanceTestAmount,
            $this->token->getBalance($this->data->balanceTestAddress)->toFloat()
        );
    }

    /**
     * @return void
     */
    public function testTotalSupply(): void
    {
        $this->assertEquals(
            1000000,
            $this->token->getTotalSupply()->toFloat()
        );
    }

    /**
     * @return void
     */
    public function testTransfer(): void
    {
        $signer = $this->token->transfer(
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress,
            $this->data->tokenTransferTestAmount
        );

        if (!$this->data->tokenTransferTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        $beforeBalance = $this->token->getBalance($this->data->receiverTestAddress);

        (new Transaction($signer->sign($this->data->senderPrivateKey)->send()))->wait();

        $afterBalance = $this->token->getBalance($this->data->receiverTestAddress);

        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function testApproveAndAllowance(): void
    {
        $signer = $this->token->approve(
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress,
            $this->data->tokenApproveTestAmount
        );

        if (!$this->data->tokenApproveTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        (new Transaction($signer->sign($this->data->senderPrivateKey)->send()))->wait();

        $allowance = $this->token->getAllowance(
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress
        );

        $this->assertEquals(
            $this->data->tokenTransferTestAmount,
            $allowance
        );
    }

    /**
     * @return void
     */
    public function testTransferFrom(): void
    {
        $signer = $this->token->transferFrom(
            $this->data->receiverTestAddress,
            $this->data->senderTestAddress,
            $this->data->receiverTestAddress,
            2
        );

        if (!$this->data->tokenTransferFromTestIsActive) {
            $this->assertTrue(true);
            return;
        }

        $beforeBalance = $this->token->getBalance($this->data->receiverTestAddress);

        (new Transaction($signer->sign($this->data->receiverPrivateKey)->send()))->wait();

        $afterBalance = $this->token->getBalance($this->data->receiverTestAddress);

        $this->assertTrue(true);
    }
}
