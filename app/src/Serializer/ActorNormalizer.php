<?php

namespace App\Serializer;

use App\Entity\Actor;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ActorNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'ACTOR_NORMALIZER_ALREADY_CALLED';

    public function normalize($actor, string $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        /** @var Actor $actor */

        $context[self::ALREADY_CALLED] = true;
        $context[ObjectNormalizer::SKIP_NULL_VALUES] = true;
        $context[ObjectNormalizer::SKIP_UNINITIALIZED_VALUES] = true;

        $data = $this->normalizer->normalize($actor, $format, $context);

        $data['actorLink'] = $data['@id'];
        unset($data['@id']);
        unset($data['@type']);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Actor;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            '*'
        ];
    }
}