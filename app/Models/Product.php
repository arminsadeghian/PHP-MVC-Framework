<?php

namespace App\Models;

use App\Models\Contracts\MysqlBaseModel;

class Product extends MysqlBaseModel
{
    protected string $table = 'products';
}