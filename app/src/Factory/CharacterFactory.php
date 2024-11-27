<?php

namespace App\Factory;

use App\Entity\Character;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Character>
 */
final class CharacterFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Character::class;
    }
    protected function defaults(): array|callable
    {
        return [
            'kingsguard' => self::faker()->boolean(),
            'characterName' => self::faker()->name(),
            'royal' => self::faker()->boolean(),
            'characterImageThumb' => self::faker()->url(),
            'characterImageFull' => self::faker()->url(),
            'nickname' => self::faker()->name(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Character $character): void {})
        ;
    }
}
