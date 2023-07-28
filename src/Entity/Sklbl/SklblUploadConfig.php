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

    #[ORM\Column(length: 50)]
    private ?string $categorie = null;

    #[ORM\Column(length: 50)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $format = null;

    #[ORM\Column(nullable: true)]
    private ?int $orderNum = null;

    #[ORM\Column]
    private ?bool $customer = null;

    #[ORM\Column]
    private ?bool $f1 = null;

    #[ORM\Column]
    private ?bool $f2 = null;

    #[ORM\Column]
    private ?bool $f3 = null;

    #[ORM\Column]
    private ?bool $f4 = null;

    #[ORM\Column]
    private ?bool $f5 = null;

    #[ORM\Column]
    private ?bool $lisage = null;

    #[ORM\Column]
    private ?bool $uniqueValue = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $customerCsv = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $f1Csv = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $f2Csv = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $f3Csv = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $f4Csv = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $f5Csv = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $lisageCsv = null;

    #[ORM\ManyToOne(inversedBy: 'sklblUploadConfigs')]
    private ?SklblStructure $sklblStructure = null;




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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getOrderNum(): ?int
    {
        return $this->orderNum;
    }

    public function setOrderNum(?int $orderNum): static
    {
        $this->orderNum = $orderNum;

        return $this;
    }

    public function getCustomer(): ?bool
    {
        return $this->customer;
    }

    public function setCustomer(bool $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getF1(): ?bool
    {
        return $this->f1;
    }

    public function setF1(bool $f1): static
    {
        $this->f1 = $f1;

        return $this;
    }

    public function getF2(): ?bool
    {
        return $this->f2;
    }

    public function setF2(bool $f2): static
    {
        $this->f2 = $f2;

        return $this;
    }

    public function getF3(): ?bool
    {
        return $this->f3;
    }

    public function setF3(bool $f3): static
    {
        $this->f3 = $f3;

        return $this;
    }

    public function getF4(): ?bool
    {
        return $this->f4;
    }

    public function setF4(bool $f4): static
    {
        $this->f4 = $f4;

        return $this;
    }

    public function getF5(): ?bool
    {
        return $this->f5;
    }

    public function setF5(bool $f5): static
    {
        $this->f5 = $f5;

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

    public function getUniqueValue(): ?bool
    {
        return $this->uniqueValue;
    }

    public function setUniqueValue(bool $uniqueValue): static
    {
        $this->uniqueValue = $uniqueValue;

        return $this;
    }

    public function getCustomerCsv(): ?string
    {
        return $this->customerCsv;
    }

    public function setCustomerCsv(?string $customerCsv): static
    {
        $this->customerCsv = $customerCsv;

        return $this;
    }

    public function getF1Csv(): ?string
    {
        return $this->f1Csv;
    }

    public function setF1Csv(?string $f1Csv): static
    {
        $this->f1Csv = $f1Csv;

        return $this;
    }

    public function getF2Csv(): ?string
    {
        return $this->f2Csv;
    }

    public function setF2Csv(?string $f2Csv): static
    {
        $this->f2Csv = $f2Csv;

        return $this;
    }

    public function getF3Csv(): ?string
    {
        return $this->f3Csv;
    }

    public function setF3Csv(?string $f3Csv): static
    {
        $this->f3Csv = $f3Csv;

        return $this;
    }

    public function getF4Csv(): ?string
    {
        return $this->f4Csv;
    }

    public function setF4Csv(?string $f4Csv): static
    {
        $this->f4Csv = $f4Csv;

        return $this;
    }

    public function getF5Csv(): ?string
    {
        return $this->f5Csv;
    }

    public function setF5Csv(?string $f5Csv): static
    {
        $this->f5Csv = $f5Csv;

        return $this;
    }

    public function getLisageCsv(): ?string
    {
        return $this->lisageCsv;
    }

    public function setLisageCsv(?string $lisageCsv): static
    {
        $this->lisageCsv = $lisageCsv;

        return $this;
    }

    public function getSklblStructure(): ?SklblStructure
    {
        return $this->sklblStructure;
    }

    public function setSklblStructure(?SklblStructure $sklblStructure): static
    {
        $this->sklblStructure = $sklblStructure;

        return $this;
    }


    


}
