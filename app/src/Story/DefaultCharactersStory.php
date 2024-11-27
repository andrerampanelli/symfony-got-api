<?php

namespace App\Story;

use App\Factory\CharacterFactory;
use Zenstruck\Foundry\Story;

final class DefaultCharactersStory extends Story
{
    public function build(): void
    {
        CharacterFactory::createMany(20);
    }
}
