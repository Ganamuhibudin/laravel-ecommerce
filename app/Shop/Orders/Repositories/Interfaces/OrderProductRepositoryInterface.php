<?php

namespace App\Shop\Orders\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Orders\OrderProduct;
use Illuminate\Support\Collection;

interface OrderProductRepositoryInterface extends BaseRepositoryInterface
{
    public function listTopOrderedProducts() : Collection;
}
