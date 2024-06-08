<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders->map(function ($order) {
            return [
                $order->user->name,
                $order->rec_address,
                $order->phone,
                $order->orderProducts->pluck('product.title')->implode(', '),
                $order->total_payment,
                $order->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Alamat',
            'Telepon',
            'Nama Barang',
            'Total Harga',
            'Status',
        ];
    }
}

