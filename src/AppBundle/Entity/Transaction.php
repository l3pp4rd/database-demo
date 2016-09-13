<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="transactions")
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Wallet")
     */
    private $wallet;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    public function getId()
    {
        return $this->id;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setWallet(Wallet $wallet)
    {
        $this->wallet = $wallet;
        return $this;
    }

    public function getWallet()
    {
        return $wallet;
    }
}
