<?php

namespace App\Serializer;

use App\Entity\Actor;
use App\Entity\Character;
use App\Entity\House;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CharacterNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'CHARACTER_NORMALIZER_ALREADY_CALLED';

    public function normalize($object, string $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        /** @var Character $object */

        $context[self::ALREADY_CALLED] = true;
        $context[ObjectNormalizer::SKIP_NULL_VALUES] = true;
        $context[ObjectNormalizer::SKIP_UNINITIALIZED_VALUES] = true;
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['characterLink'] = $data['@id'];
        unset($data['@id']);
        unset($data['@type']);

        // Remove boolean values if they are false
        if (!$object->isRoyal()) {
            unset($data['royal']);
        }
        if (!$object->isKingsguard()) {
            unset($data['kingsguard']);
        }

        // Map Actors collections
        $this->handleActors($data, $object->getActors(), $format, $context);

        // Map Houses collections
        $this->handleHouses($data, $object->getHouses(), $format, $context);

        // Map Killed collections
        $this->handleKilled($data, $object->getKilled(), $format, $context);

        // Map KilledBy collections
        $this->handleKilledBy($data, $object->getKilledBy(), $format, $context);

        // Map GuardianOf collections
        $this->handleGuardianOf($data, $object->getGuardianOf(), $format, $context);

        // Map GuardedBy collections
        $this->handleGuardedBy($data, $object->getGuardedBy(), $format, $context);

        // Map Allies collections
        $this->handleAllies($data, $object->getAllies(), $format, $context);

        // Map Serves collections
        $this->handleServes($data, $object->getServes(), $format, $context);

        // Map ServedBy collections
        $this->handleServedBy($data, $object->getServedBy(), $format, $context);

        // Map Parents collections
        $this->handleParents($data, $object->getParents(), $format, $context);

        // Map ParentOf collections
        $this->handleParentOf($data, $object->getParentOf(), $format, $context);

        // Map Siblings collections
        $this->handleSiblings($data, $object->getSiblings(), $format, $context);

        // Map MarriedEngaged collections
        $this->handleMarriedEngaged($data, $object->getMarriedEngaged(), $format, $context);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Character;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            '*'
        ];
    }

    private function handleStringCollection(array &$character, Collection $collection, string $key, string $format = null, array $context = []): void
    {
        if ($collection->isEmpty()) {
            unset($character[$key]);
            return;
        }

        $character[$key] = $collection->map(fn(House | Character $item) => $item->getName())->toArray();
    }

    private function handleActors(array &$character, ?Collection $actors, string $format = null, array $context = []): void
    {
        if (!$actors || $actors->isEmpty()) {
            unset($character['actors']);
            return;
        }

        if ($actors->count() === 1) {
            unset($character['actors']);
            $actor = $actors->first();
            $character['actorName'] = $actor->getName();
            $character['actorLink'] = '/api/actors/' . $actor->getId();
            return;
        }

        $character['actors'] = $actors->map(function (Actor $actor) {
            $data = [];
            $data['actorName'] = $actor->getName();
            $data['actorLink'] = '/api/actors/' . $actor->getId();
            if (empty($actor->getSeasons())) {
                return $data;
            }
            return [
                'actorName' => $actor->getName(),
                'actorLink' => '/api/actors/' . $actor->getId(),
                'seasonsActive' => $actor->getSeasons()
            ];
        })->toArray();
    }

    private function handleHouses(array &$character, Collection $houses, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $houses, 'houses', $format, $context);
    }

    private function handleKilled(array &$character, Collection $killed, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $killed, 'killed', $format, $context);
    }

    private function handleKilledBy(array &$character, Collection $killedBy, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $killedBy, 'killedBy', $format, $context);
    }

    private function handleGuardianOf(array &$character, Collection $guardianOf, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $guardianOf, 'guardianOf', $format, $context);
    }

    private function handleGuardedBy(array &$character, Collection $guardedBy, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $guardedBy, 'guardedBy', $format, $context);
    }

    private function handleAllies(array &$character, Collection $allies, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $allies, 'allies', $format, $context);
    }

    private function handleServes(array &$character, Collection $serves, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $serves, 'serves', $format, $context);
    }

    private function handleServedBy(array &$character, Collection $servedBy, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $servedBy, 'servedBy', $format, $context);
    }

    private function handleParents(array &$character, Collection $parents, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $parents, 'parents', $format, $context);
    }

    private function handleParentOf(array &$character, Collection $parentOf, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $parentOf, 'parentOf', $format, $context);
    }

    private function handleSiblings(array &$character, Collection $siblings, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $siblings, 'siblings', $format, $context);
    }

    private function handleMarriedEngaged(array &$character, Collection $marriedEngaged, string $format = null, array $context = []): void
    {
        $this->handleStringCollection($character, $marriedEngaged, 'marriedEngaged', $format, $context);
    }
}