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
        $wallets = [1, 2, 3, 4];

        $credit = mt_rand(1, 4);
        $debit = array_values(array_diff($wallets, [$credit]))[mt_rand(0, 2)];

        $em = $this->get("doctrine")->getEntityManager();
        $c = $em->getConnection();
        $c->beginTransaction();
        try {
            $c->executeUpdate("update wallets set amount = amount + ? where id = ?",
                [1, $credit]
            );
            $c->executeUpdate("update wallets set amount = amount - ? where id = ?",
                [1, $debit]
            );
            $credit = $em->find('AppBundle:Wallet', $credit);
            $debit = $em->find('AppBundle:Wallet', $debit);

            // $credit->addAmount(1);
            // $this->persist($credit);

            $tx = new Transaction();
            $tx->setWallet($credit);
            $tx->setAmount(1);
            $this->persist($tx);

            // $debit->addAmount(-1);
            // $this->persist($debit);

            $tx = new Transaction();
            $tx->setWallet($debit);
            $tx->setAmount(-1);
            $this->persist($tx);

            $this->flush();

            $c->commit();
        } catch(Exception $e) {
            // should retry if deadlock, but ORM manager locks down in failed state
            $c->rollback();
            throw $e;
        }

        return new SingleResource($credit);
    }
}
