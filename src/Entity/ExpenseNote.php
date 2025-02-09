<?php

namespace App\Entity;

use App\Enum\ExpenseType;
use App\Repository\ExpenseNoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExpenseNoteRepository::class)]
class ExpenseNote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $noteDate = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $submissionDate = null;

    #[ORM\ManyToOne(inversedBy: 'expenseNotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\Column(type: 'string', enumType: ExpenseType::class)]
    private ?ExpenseType $type = null;

    #[ORM\ManyToOne(inversedBy: 'expenseNotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoteDate(): ?\DateTimeInterface
    {
        return $this->noteDate;
    }

    public function setNoteDate(\DateTimeInterface $noteDate): static
    {
        $this->noteDate = $noteDate;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getSubmissionDate(): ?\DateTimeInterface
    {
        return $this->submissionDate;
    }

    public function setSubmissionDate(\DateTimeInterface $submissionDate): static
    {
        $this->submissionDate = $submissionDate;

        return $this;
    }

    public function getType(): ?ExpenseType
    {
        return $this->type;
    }

    public function setType(ExpenseType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
