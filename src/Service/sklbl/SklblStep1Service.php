<?php

namespace App\Service\sklbl;

class SklblStep1Service
{
    private $orderId;
    private $fileName;
    private $path;
    private $vendorColumn;
    private $idColumn;
    private $skuColumn;
    private $skuTisseColumn;
    private $qteColumn;
    private $deleteSku;
    private $ligne;

    public function __construct(
        $orderId,
        $fileName,
        $path,
        $vendorColumn,
        $idColumn,
        $skuColumn,
        $skuTisseColumn,
        $qteColumn, 
        $deleteSku, 
        $ligne

    )
    {
        $this->orderId = $orderId;
        $this->fileName = $fileName;
        $this->path = $path;
        $this->vendorColumn = $vendorColumn; 
        $this->idColumn =  $idColumn;
        $this->skuColumn =  $skuColumn;
        $this->skuTisseColumn =  $skuTisseColumn;
        $this->qteColumn = $qteColumn; 
        $this->deleteSku = $deleteSku; 
        $this->ligne = $ligne;
        
    }

    public function getOrderId()
    {
        return $this->orderId;
    }
    public function getFileName()
    {
        return $this->fileName;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function getVendorColumn()
    {
        return $this->vendorColumn;
    }
    public function getIdColumn()
    {
        return $this->idColumn;
    }
    public function getSkuColumn()
    {
        return $this->skuColumn;
    }
    public function getSkuTisseColumn()
    {
        return $this->skuTisseColumn;
    }
    public function getQteColumn()
    {
        return $this->qteColumn;
    }
    public function getDeleteSku()
    {
        return $this->deleteSku;
    }
    public function getLigne()
    {
        return $this->ligne;
    }

}
