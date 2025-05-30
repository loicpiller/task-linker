<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $role = null;

    #[ORM\Column(length: 50)]
    private ?string $contractType = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $hireDate = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'employee')]
    private Collection $tasks;

    /**
     * @var Collection<int, Timeslot>
     */
    #[ORM\OneToMany(targetEntity: Timeslot::class, mappedBy: 'employee', orphanRemoval: true)]
    private Collection $timeslots;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->timeslots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(string $contractType): static
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getHireDate(): ?\DateTime
    {
        return $this->hireDate;
    }

    public function setHireDate(\DateTime $hireDate): static
    {
        $this->hireDate = $hireDate;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setEmployee($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getEmployee() === $this) {
                $task->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Timeslot>
     */
    public function getTimeslots(): Collection
    {
        return $this->timeslots;
    }

    public function addTimeslot(Timeslot $timeslot): static
    {
        if (!$this->timeslots->contains($timeslot)) {
            $this->timeslots->add($timeslot);
            $timeslot->setEmployee($this);
        }

        return $this;
    }

    public function removeTimeslot(Timeslot $timeslot): static
    {
        if ($this->timeslots->removeElement($timeslot)) {
            // set the owning side to null (unless already changed)
            if ($timeslot->getEmployee() === $this) {
                $timeslot->setEmployee(null);
            }
        }

        return $this;
    }
}
