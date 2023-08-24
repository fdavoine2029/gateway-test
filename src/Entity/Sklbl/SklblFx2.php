<?php

namespace App\Entity\Sklbl;

use App\Repository\Sklbl\SklblFx2Repository;
use Doctrine\DBAL\Types\BinaryType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use FontLib\BinaryStream;

#[ORM\Entity(repositoryClass: SklblFx2Repository::class)]
class SklblFx2
{
    #[ORM\Id]
    #[ORM\Column(type: "binary")]
    private $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uniqueId = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deals_on = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $gen_scalabel_on = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFx2s')]
    private ?SklblFx $SklblFx = null;

    #[ORM\Column(length: 255)]
    private ?string $sklblFilename = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFx2s')]
    private ?SklblFiles $sklblFile = null;

    #[ORM\ManyToOne(inversedBy: 'sklblFx2s')]
    private ?SklblOf $sklblOf = null;

    #[ORM\ManyToOne(inversedBy: 'sklblCustFx2s')]
    private ?SklblFiles $sklblCustfile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data4 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data5 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data6 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data7 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data8 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data9 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data10 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data11 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data12 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data13 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data14 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data15 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data16 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data17 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data18 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data19 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $data20 = null;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    public function setUniqueId(string $uniqueId): static
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDealsOn(): ?\DateTimeImmutable
    {
        return $this->deals_on;
    }

    public function setDealsOn(?\DateTimeImmutable $deals_on): static
    {
        $this->deals_on = $deals_on;

        return $this;
    }

    public function getGenScalabelOn(): ?\DateTimeImmutable
    {
        return $this->gen_scalabel_on;
    }

    public function setGenScalabelOn(\DateTimeImmutable $gen_scalabel_on): static
    {
        $this->gen_scalabel_on = $gen_scalabel_on;

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

    public function getSklblFx(): ?SklblFx
    {
        return $this->SklblFx;
    }

    public function setSklblFx(?SklblFx $SklblFx): static
    {
        $this->SklblFx = $SklblFx;

        return $this;
    }

    public function getSklblFilename(): ?string
    {
        return $this->sklblFilename;
    }

    public function setSklblFilename(string $sklblFilename): static
    {
        $this->sklblFilename = $sklblFilename;

        return $this;
    }

    public function getSklblFile(): ?SklblFiles
    {
        return $this->sklblFile;
    }

    public function setSklblFile(?SklblFiles $sklblFile): static
    {
        $this->sklblFile = $sklblFile;

        return $this;
    }

    public function getSklblOf(): ?SklblOf
    {
        return $this->sklblOf;
    }

    public function setSklblOf(?SklblOf $sklblOf): static
    {
        $this->sklblOf = $sklblOf;

        return $this;
    }

    public function getSklblCustfile(): ?SklblFiles
    {
        return $this->sklblCustfile;
    }

    public function setSklblCustfile(?SklblFiles $sklblCustfile): static
    {
        $this->sklblCustfile = $sklblCustfile;

        return $this;
    }

    public function setDataColumn(string $colonne, ?string $data): static
    {

        switch ($colonne) {
            case 'data1':
                $this->data1 = $data;
                break;
            case 'data2':
                $this->data2 = $data;
                break;
            case 'data3':
                $this->data3 = $data;
                break;
            case 'data4':
                $this->data4 = $data;
                break;
            case 'data5':
                $this->data5 = $data;
                break;    
            case 'data6':
                $this->data6 = $data;
                break;
            case 'data7':
                $this->data7 = $data;
                break;
            case 'data8':
                $this->data8 = $data;
                break;
            case 'data9':
                $this->data9 = $data;
                break;
            case 'data10':
                $this->data10 = $data;
                break; 
            case 'data11':
                $this->data11 = $data;
                break;
            case 'data12':
                $this->data12 = $data;
                break;
            case 'data13':
                $this->data13 = $data;
                break;
            case 'data14':
                $this->data14 = $data;
                break;
            case 'data15':
                $this->data15 = $data;
                break;    
            case 'data16':
                $this->data16 = $data;
                break;
            case 'data17':
                $this->data17 = $data;
                break;
            case 'data18':
                $this->data18 = $data;
                break;
            case 'data19':
                $this->data19 = $data;
                break;
            case 'data20':
                $this->data20 = $data;
                break;
        }
        

        return $this;
    }

    public function getDataColumn(string $colonne): ?string
    {
        
        switch ($colonne) {
            case 'data1':
                return $this->data1;
                break;
            case 'data2':
                return $this->data2;
                break;
            case 'data3':
                return $this->data3;
                break;
            case 'data4':
                return $this->data4;
                break;
            case 'data5':
                return $this->data5;
                break;    
            case 'data6':
                return $this->data6;
                break;
            case 'data7':
                return $this->data7;
                break;
            case 'data8':
                return $this->data8;
                break;
            case 'data9':
                return $this->data9;
                break;
            case 'data10':
                return $this->data10;
                break; 
            case 'data11':
                return $this->data11;
                break;
            case 'data12':
                return $this->data12;
                break;
            case 'data13':
                return $this->data13;
                break;
            case 'data14':
                return $this->data14;
                break;
            case 'data15':
                return $this->data15;
                break;    
            case 'data16':
                return $this->data16;
                break;
            case 'data17':
                return $this->data17;
                break;
            case 'data18':
                return $this->data18;
                break;
            case 'data19':
                return $this->data19;
                break;
            case 'data20':
                return $this->data20;
                break;
        }
    }

    public function getData1(): ?string
    {
        return $this->data1;
    }

    public function setData1(?string $data1): static
    {
        $this->data1 = $data1;

        return $this;
    }

    public function getData2(): ?string
    {
        return $this->data2;
    }

    public function setData2(?string $data2): static
    {
        $this->data2 = $data2;

        return $this;
    }

    public function getData3(): ?string
    {
        return $this->data3;
    }

    public function setData3(?string $data3): static
    {
        $this->data3 = $data3;

        return $this;
    }

    public function getData4(): ?string
    {
        return $this->data4;
    }

    public function setData4(?string $data4): static
    {
        $this->data4 = $data4;

        return $this;
    }

    public function getData5(): ?string
    {
        return $this->data5;
    }

    public function setData5(?string $data5): static
    {
        $this->data5 = $data5;

        return $this;
    }

    public function getData6(): ?string
    {
        return $this->data6;
    }

    public function setData6(?string $data6): static
    {
        $this->data6 = $data6;

        return $this;
    }

    public function getData7(): ?string
    {
        return $this->data7;
    }

    public function setData7(?string $data7): static
    {
        $this->data7 = $data7;

        return $this;
    }

    public function getData8(): ?string
    {
        return $this->data8;
    }

    public function setData8(?string $data8): static
    {
        $this->data8 = $data8;

        return $this;
    }

    public function getData9(): ?string
    {
        return $this->data9;
    }

    public function setData9(?string $data9): static
    {
        $this->data9 = $data9;

        return $this;
    }

    public function getData10(): ?string
    {
        return $this->data10;
    }

    public function setData10(?string $data10): static
    {
        $this->data10 = $data10;

        return $this;
    }

    public function getData11(): ?string
    {
        return $this->data11;
    }

    public function setData11(?string $data11): static
    {
        $this->data11 = $data11;

        return $this;
    }

    public function getData12(): ?string
    {
        return $this->data12;
    }

    public function setData12(?string $data12): static
    {
        $this->data12 = $data12;

        return $this;
    }

    public function getData13(): ?string
    {
        return $this->data13;
    }

    public function setData13(?string $data13): static
    {
        $this->data13 = $data13;

        return $this;
    }

    public function getData14(): ?string
    {
        return $this->data14;
    }

    public function setData14(?string $data14): static
    {
        $this->data14 = $data14;

        return $this;
    }

    public function getData15(): ?string
    {
        return $this->data15;
    }

    public function setData15(?string $data15): static
    {
        $this->data15 = $data15;

        return $this;
    }

    public function getData16(): ?string
    {
        return $this->data16;
    }

    public function setData16(?string $data16): static
    {
        $this->data16 = $data16;

        return $this;
    }

    public function getData17(): ?string
    {
        return $this->data17;
    }

    public function setData17(?string $data17): static
    {
        $this->data17 = $data17;

        return $this;
    }

    public function getData18(): ?string
    {
        return $this->data18;
    }

    public function setData18(?string $data18): static
    {
        $this->data18 = $data18;

        return $this;
    }

    public function getData19(): ?string
    {
        return $this->data19;
    }

    public function setData19(?string $data19): static
    {
        $this->data19 = $data19;

        return $this;
    }

    public function getData20(): ?string
    {
        return $this->data20;
    }

    public function setData20(?string $data20): static
    {
        $this->data20 = $data20;

        return $this;
    }

    

}
