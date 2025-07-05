<?php

namespace App\Entity;

use App\Repository\FormResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormResponseRepository::class)]
class FormResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'formResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Field $question = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $textResponse = null;

    #[ORM\Column(nullable: true)]
    private ?int $numericResponse = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $pickedOptions = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $respondent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Field
    {
        return $this->question;
    }

    public function setQuestion(?Field $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getTextResponse(): ?string
    {
        return $this->textResponse;
    }

    public function setTextResponse(?string $textResponse): static
    {
        $this->textResponse = $textResponse;

        return $this;
    }

    public function getNumericResponse(): ?int
    {
        return $this->numericResponse;
    }

    public function setNumericResponse(?int $numericResponse): static
    {
        $this->numericResponse = $numericResponse;
        return $this;
    }

    public function getPickedOptions(): ?array
    {
        return $this->pickedOptions;
    }

    public function setPickedOptions(?array $pickedOptions): static
    {
        $this->pickedOptions = $pickedOptions;

        return $this;
    }

    public function getRespondent(): ?User
    {
        return $this->respondent;
    }

    public function setRespondent(?User $respondent): static
    {
        $this->respondent = $respondent;

        return $this;
    }
}
