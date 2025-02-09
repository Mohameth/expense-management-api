<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, expenseNote>
     */
    #[ORM\OneToMany(targetEntity: expenseNote::class, mappedBy: 'company')]
    private Collection $expenseNotes;

    public function __construct(string $name)
    {
        $this->setName($name);
        
        $this->expenseNotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, expenseNote>
     */
    public function getExpenseNotes(): Collection
    {
        return $this->expenseNotes;
    }

    public function addExpenseNote(expenseNote $expenseNote): static
    {
        if (!$this->expenseNotes->contains($expenseNote)) {
            $this->expenseNotes->add($expenseNote);
            $expenseNote->setCompany($this);
        }

        return $this;
    }

    public function removeExpenseNote(expenseNote $expenseNote): static
    {
        if ($this->expenseNotes->removeElement($expenseNote)) {
            // set the owning side to null (unless already changed)
            if ($expenseNote->getCompany() === $this) {
                $expenseNote->setCompany(null);
            }
        }

        return $this;
    }
}
