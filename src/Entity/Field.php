<?php

namespace App\Entity;

use App\Repository\FieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FieldRepository::class)]
class Field
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['template:read'])]
    private ?int $id = null;
    #[Groups(['template:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;
    #[ORM\ManyToOne(targetEntity: Template::class, inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Template $template = null;
    #[Groups(['template:read'])]
    #[ORM\Column(length: 255)]
    private ?string $type = null;
    #[Groups(['template:read'])]
    #[ORM\Column(nullable: true)]
    private ?bool $isRequired = null;
    #[Groups(['template:read'])]
    #[ORM\Column]
    private ?int $position = null;

    #[Groups(['template:read'])]
    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $options = null;


    #[Groups(['template:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[Groups(['template:read'])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Groups(['template:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $questionType = null;

    #[Groups(['template:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $key = null;

    #[Groups(['template:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $caption = null;

    #[Groups(['template:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preview = null;

    /**
     * @var Collection<int, FormResponse>
     */
    #[ORM\OneToMany(targetEntity: FormResponse::class, mappedBy: 'question', orphanRemoval: true)]
    private Collection $formResponses;

    public function __construct()
    {
        $this->formResponses = new ArrayCollection();
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

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): static
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

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

    public function getQuestionType(): ?string
    {
        return $this->questionType;
    }

    public function setQuestionType(?string $questionType): static
    {
        $this->questionType = $questionType;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): static
    {
        $this->caption = $caption;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): static
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * @return Collection<int, FormResponse>
     */
    public function getFormResponses(): Collection
    {
        return $this->formResponses;
    }

    public function addFormResponse(FormResponse $formResponse): static
    {
        if (!$this->formResponses->contains($formResponse)) {
            $this->formResponses->add($formResponse);
            $formResponse->setQuestion($this);
        }

        return $this;
    }

    public function removeFormResponse(FormResponse $formResponse): static
    {
        if ($this->formResponses->removeElement($formResponse)) {
            // set the owning side to null (unless already changed)
            if ($formResponse->getQuestion() === $this) {
                $formResponse->setQuestion(null);
            }
        }

        return $this;
    }

}
