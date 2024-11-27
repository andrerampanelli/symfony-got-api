<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Character;
use App\Factory\CharacterFactory;
use App\Factory\HouseFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CharactersTest extends ApiTestCase
{
    // This trait provided by Foundry will take care of refreshing the database content to a known state before each test
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        CharacterFactory::createMany(10);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', '/api/characters');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/Character',
            '@id' => '/api/characters',
            '@type' => 'Collection',
            'totalItems' => 10,
        ]);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Character::class);
    }

    public function testGetCollectionWithFilter(): void
    {
        CharacterFactory::createMany(10);
        CharacterFactory::createOne(['characterName' => 'UNIQUENAME_123']);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', '/api/characters?characterName=UNIQ');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/Character',
            '@id' => '/api/characters',
            '@type' => 'Collection',
            'totalItems' => 1,
        ]);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Character::class);
    }

    public function testGetCollectionWithNestedFilter(): void
    {
        CharacterFactory::createMany(10);
        CharacterFactory::createOne([
            'characterName' => 'NAME_123',
            'houses' => [HouseFactory::createOne(['name' => 'HOUSE'])]
        ]);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', '/api/characters?houses.name=HOUSE');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/Character',
            '@id' => '/api/characters',
            '@type' => 'Collection',
            'totalItems' => 1,
        ]);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Character::class);
    }

    public function testCreateCharacter(): void
    {
        $response = static::createClient()->request('POST', '/api/characters', [
            'json' => [
                "characterName" => "Super name"
            ]
        ]);

        $responseIri = $response->toArray()['@id'];

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Character',
            '@type' => 'Character',
            'characterName' => 'Super name',
        ]);

        static::createClient()->request('GET', $responseIri);

        $this->assertMatchesResourceItemJsonSchema(Character::class);
    }

    public function testUpdateCharacter(): void
    {
        CharacterFactory::createOne(['characterName' => 'Super name']);

        $client = static::createClient();
        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        $iri = $this->findIriBy(Character::class, ['characterName' => 'Super name']);

        // Use the PATCH method here to do a partial update
        $client->request('PATCH', $iri, [
            'json' => [
                'characterName' => 'New name',
            ],
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'characterName' => 'New name'
        ]);
        $this->assertJsonContains([
            '@id' => $iri
        ]);
    }

    public function testDeleteCharacter(): void
    {
        CharacterFactory::createOne(['characterName' => 'Super name']);

        $client = static::createClient();
        $iri = $this->findIriBy(Character::class, ['characterName' => 'Super name']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);

        static::createClient()->request('GET', $iri);
        $this->assertResponseStatusCodeSame(404);

    }
}