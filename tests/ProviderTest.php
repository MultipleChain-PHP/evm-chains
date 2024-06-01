<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains\Tests;

class ProviderTest extends BaseTest
{
    /**
     * @return void
     */
    public function testChainId(): void
    {
        $this->assertEquals(11155111, $this->provider->network->getId());
    }
}
