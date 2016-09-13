<?php

namespace ApiBundle\Resource\Wallet;

use AppBundle\Entity\Wallet;
use ApiBundle\ResourceInterface;

class SingleResource implements ResourceInterface
{
    protected $wallet;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->wallet->getId(),
            'amount' => $this->wallet->getAmount(),
        ];
    }
}
