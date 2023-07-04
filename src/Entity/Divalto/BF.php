<?php

namespace App\Entity\Divalto;

use App\Repository\Divalto\BFRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BFRepository::class)]
class BF
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
