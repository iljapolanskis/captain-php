<?php

namespace App\Model\Data;

use App\Api\Data\PostInterface;
use App\Api\Data\User\ImageInterface;
use App\Api\Data\UserInterface;
use App\Model\Data\User\Image;
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
class User implements UserInterface
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

    #[OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private Collection $posts;

    #[OneToMany(mappedBy: 'user', targetEntity: Image::class)]
    private Collection $images;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    #[PrePersist, PreUpdate]
    public function updateTimeStamps(LifecycleEventArgs $args)
    {
        if (!isset($this->createdAt)) {
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
     * @return PostInterface[]
     */
    public function getPosts(): array
    {
        return $this->posts->toArray();
    }

    /**
     * @param \App\Api\Data\PostInterface $post
     * @return User
     */
    public function addPost(PostInterface $post): User
    {
        $this->posts->add($post);
        return $this;
    }

    /**
     * @return ImageInterface[]
     */
    public function getImages(): array
    {
        return $this->images->toArray();
    }

    /**
     * @param \App\Api\Data\User\ImageInterface $image
     * @return User
     */
    public function addImage(ImageInterface $image): User
    {
        $this->images->add($image);
        return $this;
    }
}
