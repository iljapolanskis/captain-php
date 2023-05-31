<?php

namespace App\Model\Data\User;

use App\Api\Data\User\ImageInterface;
use App\Api\Data\UserInterface;
use App\Model\Data\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'user_images')]
class Image implements ImageInterface
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $url;

    #[Column(options: ['unsigned' => true])]
    private int $width;

    #[Column(options: ['unsigned' => true])]
    private int $height;

    #[Column(name: 'size_in_kb', options: ['unsigned' => true])]
    private int $size_in_kb;

    #[ManyToOne(inversedBy: 'images')]
    private User $user;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): ImageInterface
    {
        $this->user = $user;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): ImageInterface
    {
        $this->url = $url;

        // Get image dimensions
        [$this->width, $this->height] = getimagesize($url);

        // Get image size in KB
        $this->size_in_kb = round(filesize($url) / 1024);

        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getSizeInKb(): int
    {
        return $this->size_in_kb;
    }
}