<?php

namespace App\Factory;

use App\Entity\Actor;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Actor>
 */
final class ActorFactory extends PersistentProxyObjectFactory
{

    public function __construct()
    {
    }

    public static function class(): string
    {
        return Actor::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'actorName' => self::faker()->name(),
            'seasonsActive' => self::faker()->randomElements([1, 2, 3, 4, 5, 6, 7, 8], self::faker()->numberBetween(1, 8)),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Actor $actor): void {})
        ;
    }
}
