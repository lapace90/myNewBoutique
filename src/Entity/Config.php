<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]
class Config
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $script = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScript(): ?string
    {
        return $this->script;
    }

    public function setScript(?string $script): static
    {
        $this->script = $script;

        return $this;
    }
}
