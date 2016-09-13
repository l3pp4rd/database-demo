<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="wallets")
 */
class Wallet
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount = 0;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    public function getId()
    {
        return $this->id;
    }

    public function addAmount($amount)
    {
        $this->amount += $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $user;
    }
}
