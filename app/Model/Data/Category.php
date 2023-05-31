<?php

namespace App\Model\Data;

use App\Api\Data\CategoryInterface;
use App\Api\Data\PostInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

/**
 *
 */
#[Entity, Table(name: 'categories')]
class Category implements CategoryInterface
{
    /** @var int */
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    /** @var string */
    #[Column]
    private string $name;

    /** @var Collection */
    #[ManyToMany(targetEntity: Post::class, mappedBy: 'categories')]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param PostInterface $post
     * @return $this
     */
    public function addPost(PostInterface $post): Category
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return '/post/' . $this->getName();
    }
}
