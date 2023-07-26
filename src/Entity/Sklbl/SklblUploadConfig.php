<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\SklblUploadConfigRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SklblUploadConfigRepository::class)]
class SklblUploadConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sklblUploadConfigs')]
    private ?SklblOrders $sklblOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $columnName = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $ColumnCsv = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $columnLabel = null;

    #[ORM\Column]
    private ?bool $lisage = null;

    #[ORM\Column]
    private ?int $num = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSklblOrder(): ?SklblOrders
    {
        return $this->sklblOrder;
    }

    public function setSklblOrder(?SklblOrders $sklblOrder): static
    {
        $this->sklblOrder = $sklblOrder;

        return $this;
    }

    public function getColumnName(): ?string
    {
        return $this->columnName;
    }

    public function setColumnName(string $columnName): static
    {
        $this->columnName = $columnName;

        return $this;
    }

    public function getColumnCsv(): ?string
    {
        return $this->ColumnCsv;
    }

    public function setColumnCsv(?string $ColumnCsv): static
    {
        $this->ColumnCsv = $ColumnCsv;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getColumnLabel(): ?string
    {
        return $this->columnLabel;
    }

    public function setColumnLabel(?string $columnLabel): static
    {
        $this->columnLabel = $columnLabel;

        return $this;
    }

    public function getLisage(): ?bool
    {
        return $this->lisage;
    }

    public function setLisage(bool $lisage): static
    {
        $this->lisage = $lisage;

        return $this;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): static
    {
        $this->num = $num;

        return $this;
    }

}
