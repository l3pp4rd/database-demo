<?php

namespace AppBundle\Fixture\Users;

use AppBundle\Entity\User;
use AppBundle\Entity\Wallet;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DevUsers implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10; // may need some groups or over related stuff created before
    }

    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        if (!in_array($this->container->getParameter('kernel.environment'), ['dev'])) {
            return; // only for dev environment
        }

        $faker = Factory::create();
        $users = [
            'yoda' => ['ROLE_ADMIN'],
            'luke' => ['ROLE_USER'],
        ];
        foreach ($users as $username => $roles) {
            $user = new User();
            $user->setFirstname($faker->firstname);
            $user->setLastname($faker->lastname);
            $user->setEmail($username . '@datadog.lt');
            $user->setRoles($roles);

            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $user->setPassword($encoder->encodePassword('S3cretpassword', $user->getSalt()));

            $em->persist($user);

            $wallet = new Wallet();
            $wallet->setUser($user);

            $em->persist($wallet);
        }
        $em->flush();
    }
}
