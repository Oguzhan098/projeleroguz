<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Flight;

final class FlightsController extends Controller
{
    public function index(): void {
        $model = new Flight();
        $flights = $model->all();
        $this->view('flights/index', compact('flights'));
    }

    public function create(): void {
        $data = [
            'departure_airport_id' => (string)self::input('departure_airport_id', ''),
            'arrival_airport_id'   => (string)self::input('arrival_airport_id',   ''),
            'plane_id'             => (string)self::input('plane_id',             ''),
            'departure_ts'         => (string)self::input('departure_ts',         ''),
            'arrival_ts'           => (string)self::input('arrival_ts',           ''),
        ];
        $this->view('flights/create', compact('data'));
    }

    public function store(): void {
        $data = [
            'departure_airport_id' => (int)self::input('departure_airport_id'),
            'arrival_airport_id'   => (int)self::input('arrival_airport_id'),
            'plane_id'             => (int)self::input('plane_id'),
            'departure_ts'         => (string)self::input('departure_ts'),
            'arrival_ts'           => (string)self::input('arrival_ts'),
        ];
        try {
            $id = (new Flight())->create($data);
            $this->redirect('/flights/' . $id . '/edit');
        } catch (\Throwable $e) {
            $error = $e->getMessage();
            $this->view('flights/create', compact('error','data'));
        }
    }

    public function edit(string $id): void {
        $model  = new Flight();
        $flight = $model->find((int)$id);
        if (!$flight) { $this->redirect('/flights'); return; }
        $this->view('flights/edit', compact('flight'));
    }

    public function update(string $id): void {
        $data = [
            'departure_airport_id' => (int)self::input('departure_airport_id'),
            'arrival_airport_id'   => (int)self::input('arrival_airport_id'),
            'plane_id'             => (int)self::input('plane_id'),
            'departure_ts'         => (string)self::input('departure_ts'),
            'arrival_ts'           => (string)self::input('arrival_ts'),
        ];
        try {
            (new Flight())->update((int)$id, $data);
            $this->redirect('/flights');
        } catch (\Throwable $e) {
            $error  = $e->getMessage();
            $flight = array_merge(['id'=>(int)$id], $data);
            $this->view('flights/edit', compact('error','flight'));
        }
    }
}
