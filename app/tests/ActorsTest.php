<?php


use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Actor;
use App\Factory\ActorFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ActorsTest extends ApiTestCase
{
    // This trait provided by Foundry will take care of refreshing the database content to a known state before each test
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        ActorFactory::createMany(10);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', '/api/actors');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/Actor',
            '@id' => '/api/actors',
            '@type' => 'Collection',
            'totalItems' => 10,
        ]);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(Actor::class);
    }

    public function testCreateActor(): void
    {
        $response = static::createClient()->request('POST', '/api/actors', [
            'json' => [
                "actorName" => "Super name",
                "seasonsActive" => [1, 2, 3]
            ]
        ]);

        $responseIri = $response->toArray()['@id'];

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            'actorName' => 'Super name',
            'seasonsActive' => [1, 2, 3]
        ]);

        static::createClient()->request('GET', $responseIri);

        $this->assertMatchesResourceItemJsonSchema(Actor::class);
    }

    public function testUpdateActor(): void
    {
        ActorFactory::createOne(['actorName' => 'Super name']);

        $client = static::createClient();
        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        $iri = $this->findIriBy(Actor::class, ['actorName' => 'Super name']);

        // Use the PATCH method here to do a partial update
        $client->request('PATCH', $iri, [
            'json' => [
                'actorName' => 'New name',
            ],
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'actorName' => 'New name'
        ]);
        $this->assertJsonContains([
            '@id' => $iri
        ]);
    }

    public function testDeleteActor(): void
    {
        ActorFactory::createOne(['actorName' => 'Super name']);

        $client = static::createClient();
        $iri = $this->findIriBy(Actor::class, ['actorName' => 'Super name']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);

        static::createClient()->request('GET', $iri);
        $this->assertResponseStatusCodeSame(404);

    }
}