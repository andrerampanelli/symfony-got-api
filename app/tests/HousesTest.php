<?php


use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\House;
use App\Factory\HouseFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class HousesTest extends ApiTestCase
{
    // This trait provided by Foundry will take care of refreshing the database content to a known state before each test
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        HouseFactory::createMany(10);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        static::createClient()->request('GET', '/api/houses');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/House',
            '@id' => '/api/houses',
            '@type' => 'Collection',
        ]);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(House::class);
    }

    public function testCreateHouse(): void
    {
        $response = static::createClient()->request('POST', '/api/houses', [
            'json' => [
                "name" => "Super name"
            ]
        ]);

        $responseIri = $response->toArray()['@id'];

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/House',
            '@type' => 'House',
            'name' => 'Super name',
        ]);

        static::createClient()->request('GET', $responseIri);

        $this->assertMatchesResourceItemJsonSchema(House::class);
    }

    public function testUpdateHouse(): void
    {
        HouseFactory::createOne(['name' => 'Super name']);

        $client = static::createClient();
        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        $iri = $this->findIriBy(House::class, ['name' => 'Super name']);

        // Use the PATCH method here to do a partial update
        $client->request('PATCH', $iri, [
            'json' => [
                'name' => 'New name',
            ],
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'New name'
        ]);
        $this->assertJsonContains([
            '@id' => $iri
        ]);
    }

    public function testDeleteHouse(): void
    {
        HouseFactory::createOne(['name' => 'Super name']);

        $client = static::createClient();
        $iri = $this->findIriBy(House::class, ['name' => 'Super name']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);

        static::createClient()->request('GET', $iri);
        $this->assertResponseStatusCodeSame(404);

    }
}