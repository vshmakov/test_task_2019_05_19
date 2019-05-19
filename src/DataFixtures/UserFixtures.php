<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Object\ObjectAccessor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture
{
    private const  ADMIN = [
        'username' => 'admin',
        'password' => '123',
        'roles' => ['ROLE_ADMIN'],
    ];

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $userData = self::ADMIN;
        /** @var User $user */
        $user = ObjectAccessor::initialize(User::class, $userData);
        $userData['password'] = $this->passwordEncoder->encodePassword($user, $userData['password']);
        $user->setPassword($userData['password']);

        $manager->persist($user);
        $manager->flush();
    }
}
