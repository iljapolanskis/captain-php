<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[Entity, Table(name: 'users')]
#[HasLifecycleCallbacks]
class User
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column(options: ['unique' => true])]
    private string $email;

    #[Column]
    private string $name;

    #[Column]
    private string $password;

    #[Column(name: 'created_at', type: 'datetime')]
    private \DateTime $createdAt;

    #[Column(name: 'updated_at', type: 'datetime')]
    private \DateTime $updatedAt;

    #[OneToMany(mappedBy: 'user', targetEntity: Category::class)]
    private Collection $categories;

    #[OneToMany(mappedBy: 'user', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    #[PrePersist, PreUpdate]
    public function updateTimeStamps(LifecycleEventArgs $args)
    {
        if (! isset($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        $this->updatedAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param \App\Entity\Category $category
     * @return User
     */
    public function addCategory(Category $category): User
    {
        $this->categories->add($category);
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    /**
     * @param \App\Entity\Transaction $transaction
     * @return User
     */
    public function addTransaction(Transaction $transaction): User
    {
        $this->transactions->add($transaction);
        return $this;
    }
}
