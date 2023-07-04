<?php

namespace App\Entity\Divalto;

use App\Repository\Divalto\SklblRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SklblRepository::class)]
class Sklbl
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
