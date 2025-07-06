<?php

namespace App\Entity;

use App\Repository\FormResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FormResponseRepository::class)]
class FormResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'formResponses')]
    #[Groups('form:read')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Field $question = null;
    #[Groups('form:read')]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $respondent = null;
    #[Groups('form:read')]
    #[ORM\Column(nullable: true)]
    private ?array $value = null;
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Template $template = null;

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

    public function setRespondent(?User $respondent): static
    {
        $this->respondent = $respondent;

        return $this;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(?array $value): static
    {
        $this->value = $value;

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
}
