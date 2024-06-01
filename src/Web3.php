<?php

declare(strict_types=1);

namespace MultipleChain\EvmChains;

use Web3\Net;
use Web3\Eth;
use Web3\Web3 as Web3Base;
use phpseclib\Math\BigInteger;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint

class Web3 extends Web3Base
{
    /**
     * @var Net
     */
    public $net;

    /**
     * @var Eth
     */
    public $eth;

    /**
     * @var int
     */
    private $defaultGasPrice = 10000000000;

    /**
     * @var int
     */
    private $defaultNonce = 1;

    /**
     * @param string $rpcUrl
     */
    public function __construct(string $rpcUrl)
    {
        parent::__construct($rpcUrl, 60);
        $this->net = new Net($rpcUrl, 60);
        $this->eth = new Eth($rpcUrl, 60);
    }

    /**
     * @param int $value
     * @return string
     */
    private function hex(int $value): string
    {
        return '0x' . dechex($value);
    }

    /**
     * @return integer
     */
    public function getChainId(): int
    {
        try {
            $chainId = null;
            $this->net->version(function ($err, $res) use (&$chainId): void {
                if ($err) {
                    throw new \Exception($err->getMessage(), $err->getCode());
                } else {
                    $chainId = $res;
                }
            });

            if (is_string($chainId)) {
                return intval($chainId);
            } else {
                return 0;
            }
        } catch (\Throwable $th) {
            return 0;
        }
    }

    /**
     * @return integer
     */
    public function getBlockNumber(): int
    {
        $number = null;
        $this->eth->blockNumber(function ($err, $res) use (&$number): void {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $number = $res;
            }
        });

        if (is_object($number) && $number instanceof BigInteger) {
            return intval($number->toString());
        } else {
            throw new \Exception("There was a problem retrieving the block number!", 14001);
        }
    }

    /**
     * @param array<mixed> $data
     * @return string
     */
    public function getEstimateGas(array $data): string
    {
        $result = null;
        $this->eth->estimateGas($data, function ($err, $res) use (&$result): void {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        });

        if ($result instanceof BigInteger) {
            return '0x' . $result->toHex();
        } else {
            return $this->hex($this->defaultGas);
        }
    }

    /**
     * @return string
     */
    public function getGasPrice(): string
    {
        $result = null;
        $this->eth->gasPrice(function ($err, $res) use (&$result): void {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        });

        if ($result instanceof BigInteger) {
            return '0x' . $result->toHex();
        } else {
            return $this->hex($this->defaultGasPrice);
        }
    }

    /**
     * @param string $from
     * @return string
     */
    public function getNonce(string $from): string
    {
        $result = null;
        $this->eth->getTransactionCount($from, 'pending', function ($err, $res) use (&$result): void {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        });

        if ($result instanceof BigInteger) {
            return '0x' . $result->toHex();
        } else {
            return $this->hex($this->defaultNonce);
        }
    }

    /**
     * @param string $hash
     * @return array<mixed>|null
     */
    public function getTransaction(string $hash): ?array
    {
        $result = null;
        $this->eth->getTransactionByHash($hash, function ($err, $res) use (&$result): void {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = (array) $res;
            }
        });

        if (is_array($result)) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * @param string $hash
     * @return array<mixed>|null
     */
    public function getTransactionReceipt(string $hash): ?array
    {
        $result = null;
        $this->eth->getTransactionReceipt($hash, function ($err, $res) use (&$result): void {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = (array) $res;
            }
        });

        if (is_array($result)) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * @param string $owner
     * @param integer $decimals
     * @return string
     */
    public function getBalance(string $owner): string
    {
        $result = null;
        $this->eth->getBalance($owner, 'latest', function ($err, $res) use (&$result): void {
            if ($err) {
                throw new \Exception($err->getMessage(), $err->getCode());
            } else {
                $result = $res;
            }
        });

        if ($result instanceof BigInteger) {
            return '0x' . $result->toHex();
        } else {
            return '0x0';
        }
    }
}
