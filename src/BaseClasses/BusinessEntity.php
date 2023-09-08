<?php

namespace App\BaseClasses;

use Medoo\Medoo;

class BusinessEntity
{
    public function __construct(Medoo $db)
    {
        $this->db = $db;
    }

}