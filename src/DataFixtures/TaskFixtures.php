<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Task;
use App\Object\ObjectAccessor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class TaskFixtures extends Fixture
{
    private const  TASK = [
        'username' => 'Geremy',
        'email' => 'geremy@gmail.com',
        'title' => 'This is my task #%s',
    ];

    public function load(ObjectManager $manager)
    {
        for ($number = 1; $number <= 10; ++$number) {
            $taskData = self::TASK;
            $taskData['title'] = sprintf($taskData['title'], $number);

            $task = ObjectAccessor::initialize(Task::class, $taskData);
            $manager->persist($task);
        }

        $manager->flush();
    }
}
