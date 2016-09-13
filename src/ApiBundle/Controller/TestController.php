<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\DBAL\LockMode;
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
        $em = $this->get("doctrine")->getEntityManager();
        $c = $em->getConnection();
        $c->beginTransaction();
        try {
            $wallet = $em->find('AppBundle:Wallet', 1, LockMode::PESSIMISTIC_WRITE);
            $wallet->addAmount(1);
            $this->persist($wallet);

            $tx = new Transaction();
            $tx->setWallet($wallet);
            $tx->setAmount(1);
            $this->persist($tx);
            $this->flush();

            $c->commit();
        } catch(Exception $e) {
            // should retry if deadlock, but ORM manager locks down in failed state
            $c->rollback();
            throw $e;
        }

        return new SingleResource($wallet);
    }
}
