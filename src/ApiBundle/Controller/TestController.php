<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Controller\DoctrineController;
use AppBundle\Entity\Transaction;
use ApiBundle\Resource\Wallet\SingleResource;

/**
 * @Route("/test")
 */
class TestController extends Controller
{
    use DoctrineController;

    /**
     * @Route
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $wallet = $this->repo('AppBundle:Wallet')->findOneById(1);
        $wallet->addAmount(1);
        $this->persist($wallet);

        $tx = new Transaction();
        $tx->setWallet($wallet);
        $tx->setAmount(1);
        $this->persist($tx);
        $this->flush();

        return new SingleResource($wallet);
    }
}
