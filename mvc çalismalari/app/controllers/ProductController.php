<?php
class ProductController extends Controller
{
    public function index()
    {
        $productModel = $this->model('Product');
        $products = $productModel->getAll();
        $this->view('product-list', ['products' => $products]);
    }
}
