<?php

namespace App\Model\Data;

use App\Api\Data\CategoryInterface;
use App\Api\Data\PostInterface;
use App\Api\Data\UserInterface;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'posts')]
#[HasLifecycleCallbacks]
class Post implements PostInterface
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column(unique: true)]
    private string $slug;

    #[Column]
    private string $title;

    #[Column(type: Types::TEXT)]
    private string $content;

    #[Column(name: 'created_at')]
    private DateTime $createdAt;

    #[Column(name: 'updated_at')]
    private DateTime $updatedAt;

    #[ManyToOne(inversedBy: 'posts')]
    private User $user;

    #[ManyToMany(targetEntity: Category::class, inversedBy: 'posts')]
    #[JoinTable(name: 'posts_categories')]
    private Collection $categories;

    /** @inheritDoc */
    public function getId(): int
    {
        return $this->id;
    }

    /** @inheritDoc */
    public function setId(int $id): PostInterface
    {
        $this->id = $id;
        return $this;
    }

    /** @inheritDoc */
    public function getTitle(): string
    {
        return $this->title;
    }

    /** @inheritDoc */
    public function setTitle(string $title): PostInterface
    {
        $this->title = $title;
        return $this;
    }

    /** @inheritDoc */
    public function getContent(): string
    {
        return $this->content;
    }

    /** @inheritDoc */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /** @inheritDoc */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /** @inheritDoc */
    public function setUpdatedAt(?DateTime $updatedAt = null): self
    {
        if ($updatedAt === null) {
            $this->updatedAt = new DateTime();
        }
        return $this;
    }

    /** @inheritDoc */
    public function getAuthor(): User
    {
        return $this->user;
    }

    /** @inheritDoc */
    public function setAuthor(UserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }

    /** @inheritDoc */
    public function getCategories(): array
    {
        return $this->categories->toArray();
    }

    /** @inheritDoc */
    public function addCategory(CategoryInterface $category): self
    {
        $this->categories->add($category);
        return $this;
    }

    #[PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTime();
    }

    #[PrePersist]
    #[PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new DateTime();
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return '/post/view/' . $this->getSlug();
    }

    /** @inheritDoc */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /*
     * This is a Doctrine lifecycle callback methods.
     */

    /** @inheritDoc */
    public function setSlug(string $slug): PostInterface
    {
        $this->slug = $slug;
        return $this;
    }

    public function getHumanReadableDate(): string
    {
        return (new Carbon($this->getCreatedAt()))->diffForHumans();
    }

    /** @inheritDoc */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /** @inheritDoc */
    public function setCreatedAt(?DateTime $createdAt = null): self
    {
        if ($createdAt === null) {
            $this->createdAt = new DateTime();
        }
        return $this;
    }
}
