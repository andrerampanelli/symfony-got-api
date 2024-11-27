<?php

namespace App\Story;

use App\Factory\HouseFactory;
use Zenstruck\Foundry\Story;

final class DefaultHousesStory extends Story
{
    public function build(): void
    {
        HouseFactory::createMany(7);
    }
}
