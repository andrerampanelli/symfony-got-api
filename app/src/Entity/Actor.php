<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ActorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[ApiResource]
class Actor
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['character'])]
    #[Assert\NotBlank]
    private ?string $actorName = null;

    #[ApiProperty(openapiContext: ['type' => 'array', 'items' => ['type' => 'integer']])]
    #[Groups(['character'])]
    #[Assert\NotNull]
    #[Assert\Count(min: 1)]
    #[ORM\Column(nullable: true)]
    private ?array $seasonsActive = null;

    #[ORM\ManyToOne(inversedBy: 'actors')]
    private ?Character $character = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getCharacter(): ?Character
    {
        return $this->character;
    }

    public function setCharacter(?Character $character): static
    {
        $this->character = $character;

        return $this;
    }

    public function getActorName(): ?string
    {
        return $this->actorName;
    }

    public function setActorName(string $actorName): static
    {
        $this->actorName = $actorName;

        return $this;
    }

    public function getSeasonsActive(): ?array
    {
        return $this->seasonsActive;
    }

    public function setSeasonsActive(?array $seasonsActive): static
    {
        $this->seasonsActive = $seasonsActive;

        return $this;
    }
}
