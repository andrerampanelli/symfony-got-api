<?php

namespace App\Story;

use App\Factory\ActorFactory;
use Zenstruck\Foundry\Story;

final class DefaultActorsStory extends Story
{
    public function build(): void
    {
        ActorFactory::createMany(25);
    }
}
