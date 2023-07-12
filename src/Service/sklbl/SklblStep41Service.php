<?php

namespace App\Service\sklbl;

use App\Entity\Sklbl\SklblFiles;
use App\Entity\Sklbl\SklblOf;

class SklblStep41Service
{
    private int $id_file;

    public function __construct($id_file)
    {
        $this->id_file = $id_file;
    }

    public function getIdFile()
    {
        return $this->id_file;
    }

}
