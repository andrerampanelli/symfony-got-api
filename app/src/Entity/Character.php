<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['character']],
)]
#[ApiFilter(SearchFilter::class, properties: [
    'characterName' => 'partial',
    'nickname' => 'partial',
    'actors.characterName' => 'partial',
    'siblings.characterName' => 'partial',
    'parents.characterName' => 'partial',
    'parentOf.characterName' => 'partial',
    'marriedEngaged.characterName' => 'partial',
    'serves.characterName' => 'partial',
    'servedBy.characterName' => 'partial',
    'allies.characterName' => 'partial',
    'killed.characterName' => 'partial',
    'killedBy.characterName' => 'partial',
    'houses.name' => 'partial',
    'guardianOf.characterName' => 'partial',
    'guardedBy.characterName' => 'partial'
])]
#[ApiFilter(BooleanFilter::class, properties: ['kingsguard', 'royal'])]
class Character
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['character'])]
    #[Assert\NotBlank]
    private ?string $characterName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['character'])]
    private ?string $characterImageThumb = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['character'])]
    private ?string $characterImageFull = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['character'])]
    private ?string $nickname = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['character'])]
    private bool $kingsguard = false;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['character'])]
    private bool $royal = false;

    /**
     * @var Collection<int, Actor>
     */
    #[ORM\OneToMany(targetEntity: Actor::class, mappedBy: 'character')]
    #[Groups(['character'])]
    private Collection $actors;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class)]
    #[ORM\JoinTable(name: 'character_siblings')]
    #[Groups(['character'])]
    private Collection $siblings;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'parentOf')]
    #[ORM\JoinTable(name: 'character_parents')]
    #[Groups(['character'])]
    private Collection $parents;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'parents')]
    #[Groups(['character'])]
    private Collection $parentOf;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class)]
    #[ORM\JoinTable(name: 'character_married_engaged')]
    #[Groups(['character'])]
    private Collection $marriedEngaged;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'servedBy')]
    #[ORM\JoinTable(name: 'character_serves')]
    #[Groups(['character'])]
    private Collection $serves;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'serves')]
    #[Groups(['character'])]
    private Collection $servedBy;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class)]
    #[ORM\JoinTable(name: 'character_allies')]
    #[Groups(['character'])]
    private Collection $allies;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'killedBy')]
    #[ORM\JoinTable(name: 'character_killed')]
    #[Groups(['character'])]
    private Collection $killed;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'killed')]
    #[Groups(['character'])]
    private Collection $killedBy;

    /**
     * @var Collection<int, House>
     */
    #[ORM\ManyToMany(targetEntity: House::class, inversedBy: 'characters')]
    #[Groups(['character'])]
    private Collection $houses;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'guardedBy')]
    #[ORM\JoinTable(name: 'character_guardian')]
    #[Groups(['character'])]
    private Collection $guardianOf;

    /**
     * @var Collection<int, self>
     */
    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'guardianOf')]
    #[Groups(['character'])]
    private Collection $guardedBy;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->siblings = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->parentOf = new ArrayCollection();
        $this->marriedEngaged = new ArrayCollection();
        $this->serves = new ArrayCollection();
        $this->servedBy = new ArrayCollection();
        $this->allies = new ArrayCollection();
        $this->killed = new ArrayCollection();
        $this->killedBy = new ArrayCollection();
        $this->houses = new ArrayCollection();
        $this->guardianOf = new ArrayCollection();
        $this->guardedBy = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function isKingsguard(): ?bool
    {
        return $this->kingsguard;
    }

    public function setKingsguard(bool $kingsguard): static
    {
        $this->kingsguard = $kingsguard;

        return $this;
    }

    public function isRoyal(): ?bool
    {
        return $this->royal;
    }

    public function setRoyal(bool $royal): static
    {
        $this->royal = $royal;

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): static
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
            $actor->setCharacter($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): static
    {
        if ($this->actors->removeElement($actor)) {
            // set the owning side to null (unless already changed)
            if ($actor->getCharacter() === $this) {
                $actor->setCharacter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSiblings(): Collection
    {
        return $this->siblings;
    }

    public function addSibling(self $sibling): static
    {
        if (!$this->siblings->contains($sibling)) {
            $this->siblings->add($sibling);
        }

        return $this;
    }

    public function removeSibling(self $sibling): static
    {
        $this->siblings->removeElement($sibling);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function addParent(self $parent): static
    {
        if (!$this->parents->contains($parent)) {
            $this->parents->add($parent);
        }

        return $this;
    }

    public function removeParent(self $parent): static
    {
        $this->parents->removeElement($parent);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getParentOf(): Collection
    {
        return $this->parentOf;
    }

    public function addParentOf(self $parentOf): static
    {
        if (!$this->parentOf->contains($parentOf)) {
            $this->parentOf->add($parentOf);
            $parentOf->addParent($this);
        }

        return $this;
    }

    public function removeParentOf(self $parentOf): static
    {
        if ($this->parentOf->removeElement($parentOf)) {
            $parentOf->removeParent($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getMarriedEngaged(): Collection
    {
        return $this->marriedEngaged;
    }

    public function addMarriedEngaged(self $marriedEngaged): static
    {
        if (!$this->marriedEngaged->contains($marriedEngaged)) {
            $this->marriedEngaged->add($marriedEngaged);
        }

        return $this;
    }

    public function removeMarriedEngaged(self $marriedEngaged): static
    {
        $this->marriedEngaged->removeElement($marriedEngaged);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getServes(): Collection
    {
        return $this->serves;
    }

    public function addSerf(self $serf): static
    {
        if (!$this->serves->contains($serf)) {
            $this->serves->add($serf);
        }

        return $this;
    }

    public function removeSerf(self $serf): static
    {
        $this->serves->removeElement($serf);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getServedBy(): Collection
    {
        return $this->servedBy;
    }

    public function addServedBy(self $servedBy): static
    {
        if (!$this->servedBy->contains($servedBy)) {
            $this->servedBy->add($servedBy);
            $servedBy->addSerf($this);
        }

        return $this;
    }

    public function removeServedBy(self $servedBy): static
    {
        if ($this->servedBy->removeElement($servedBy)) {
            $servedBy->removeSerf($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAllies(): Collection
    {
        return $this->allies;
    }

    public function addAlly(self $ally): static
    {
        if (!$this->allies->contains($ally)) {
            $this->allies->add($ally);
        }

        return $this;
    }

    public function removeAlly(self $ally): static
    {
        $this->allies->removeElement($ally);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getKilled(): Collection
    {
        return $this->killed;
    }

    public function addKilled(self $killed): static
    {
        if (!$this->killed->contains($killed)) {
            $this->killed->add($killed);
        }

        return $this;
    }

    public function removeKilled(self $killed): static
    {
        $this->killed->removeElement($killed);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getKilledBy(): Collection
    {
        return $this->killedBy;
    }

    public function addKilledBy(self $killedBy): static
    {
        if (!$this->killedBy->contains($killedBy)) {
            $this->killedBy->add($killedBy);
            $killedBy->addKilled($this);
        }

        return $this;
    }

    public function removeKilledBy(self $killedBy): static
    {
        if ($this->killedBy->removeElement($killedBy)) {
            $killedBy->removeKilled($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, House>
     */
    public function getHouses(): Collection
    {
        return $this->houses;
    }

    public function addHouse(House $house): static
    {
        if (!$this->houses->contains($house)) {
            $this->houses->add($house);
        }

        return $this;
    }

    public function removeHouse(House $house): static
    {
        $this->houses->removeElement($house);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getGuardianOf(): Collection
    {
        return $this->guardianOf;
    }

    public function addGuardianOf(self $guardianOf): static
    {
        if (!$this->guardianOf->contains($guardianOf)) {
            $this->guardianOf->add($guardianOf);
        }

        return $this;
    }

    public function removeGuardianOf(self $guardianOf): static
    {
        $this->guardianOf->removeElement($guardianOf);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getGuardedBy(): Collection
    {
        return $this->guardedBy;
    }

    public function addGuardedBy(self $guardedBy): static
    {
        if (!$this->guardedBy->contains($guardedBy)) {
            $this->guardedBy->add($guardedBy);
            $guardedBy->addGuardianOf($this);
        }

        return $this;
    }

    public function removeGuardedBy(self $guardedBy): static
    {
        if ($this->guardedBy->removeElement($guardedBy)) {
            $guardedBy->removeGuardianOf($this);
        }

        return $this;
    }

    public function getCharacterName(): ?string
    {
        return $this->characterName;
    }

    public function setCharacterName(string $characterName): static
    {
        $this->characterName = $characterName;

        return $this;
    }

    public function getCharacterImageThumb(): ?string
    {
        return $this->characterImageThumb;
    }

    public function setCharacterImageThumb(?string $characterImageThumb): static
    {
        $this->characterImageThumb = $characterImageThumb;

        return $this;
    }

    public function getCharacterImageFull(): ?string
    {
        return $this->characterImageFull;
    }

    public function setCharacterImageFull(?string $characterImageFull): static
    {
        $this->characterImageFull = $characterImageFull;

        return $this;
    }
}
