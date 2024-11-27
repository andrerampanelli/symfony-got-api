<?php

namespace App\Serializer;

use App\Entity\House;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HouseNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'CHARACTER_NORMALIZER_ALREADY_CALLED';

    public function normalize($house, string $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        /** @var House $house */

        $context[self::ALREADY_CALLED] = true;
        $context[ObjectNormalizer::SKIP_NULL_VALUES] = true;
        $context[ObjectNormalizer::SKIP_UNINITIALIZED_VALUES] = true;

        $data = $this->normalizer->normalize($house, $format, $context);

        $data['houseLink'] = $data['@id'];
        unset($data['@id']);
        unset($data['@type']);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof House;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            '*'
        ];
    }
}