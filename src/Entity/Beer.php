<?php

namespace App\Entity;

use App\Repository\BeerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BeerRepository::class)]
class Beer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $firstBrewed = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tips = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tagLine = null;

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

    public function getFirstBrewed(): ?string
    {
        return $this->firstBrewed;
    }

    public function setFirstBrewed(string $firstBrewed): static
    {
        $this->firstBrewed = $firstBrewed;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getTips(): ?string
    {
        return $this->tips;
    }

    public function setTips(?string $tips): static
    {
        $this->tips = $tips;

        return $this;
    }

    public function getTagLine(): ?string
    {
        return $this->tagLine;
    }

    public function setTagLine(?string $tagLine): static
    {
        $this->tagLine = $tagLine;

        return $this;
    }
}
