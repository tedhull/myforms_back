<?php

namespace App\Entity;

use App\Repository\TemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TemplateRepository::class)]
class Template
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['template:read'])]
    private ?int $id = null;

    #[Groups(['template:read'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups(['template:read'])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Groups(['template:read'])]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['template:read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;


    /**
     * @var Collection<int, Field>
     */

    #[Groups(['template:read'])]
    #[ORM\OneToMany(targetEntity: Field::class, mappedBy: 'template', orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $fields;
    #[Groups(['template:read'])]
    #[ORM\ManyToOne(inversedBy: 'Templates')]
    private ?User $creator = null;

    #[Groups(['template:read'])]
    #[ORM\Column(length: 255)]
    private ?string $topic = null;

    #[Groups(['template:read'])]
    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $tags = null;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Field>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(Field $field): static
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setTemplate($this);
        }

        return $this;
    }

    public function removeField(Field $field): static
    {
        if ($this->fields->removeElement($field)) {
            // set the owning side to null (unless already changed)
            if ($field->getTemplate() === $this) {
                $field->setTemplate(null);
            }
        }

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): static
    {
        $this->topic = $topic;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }
}
