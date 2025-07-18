<?php

class CustomerController
{
    public function index()
    {
        echo "Tüm müşteriler listelendi.";
    }

    public function show($id)
    {
        echo "Müşteri detayları: ID = " . $id;
    }

    public function store()
    {
        echo "Yeni müşteri kaydedildi.";
    }
}
