<?php

namespace App\Http\Controllers\Front;

use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Orders\Repositories\Interfaces\OrderProductRepositoryInterface;

class HomeController
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * HomeController constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     * @param OrderProductRepositoryInterface $opRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, OrderProductRepositoryInterface $opRepository)
    {
        $this->categoryRepo = $categoryRepository;
        $this->orderProduct = $opRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cat1 = $this->categoryRepo->findCategoryById(2);
        $cat2 = $this->categoryRepo->findCategoryById(3);
        $bestSeller = $this->orderProduct->listTopOrderedProducts();

        return view('front.index', compact('cat1', 'cat2', 'bestSeller'));
    }
}
