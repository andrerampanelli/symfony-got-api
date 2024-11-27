<?php

namespace App\DataFixtures;

use App\Story\DefaultActorsStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DefaultActorsStory::load();
        DefaultActorsStory::load();
        DefaultActorsStory::load();

        $manager->flush();
    }
}
