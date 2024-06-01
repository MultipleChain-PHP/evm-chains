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
}
