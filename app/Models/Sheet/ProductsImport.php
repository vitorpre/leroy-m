<?php

namespace App\Models\Sheet;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;

class ProductsImport implements ToModel, WithHeadingRow, WithMultipleSheets, WithEvents
{
    use RegistersEventListeners;

    public static function beforeImport(BeforeImport $event)
    {
        $worksheet = $event->reader->getActiveSheet();
        $highestRow = $worksheet->getHighestRow(); // e.g. 10

        if ($highestRow < 2) {
            $error = \Illuminate\Validation\ValidationException::withMessages([]);
            $failure = new Failure(1, 'rows', [0 => 'Now enough rows!']);
            $failures = [0 => $failure];
            throw new ValidationException($error, $failures);
        }
    }

    public function model(array $row)
    {
        return new Product([
            'lm' => $row['lm'],
            'name' => $row['name'],
            'free_shipping' => $row['free_shipping'],
            'description' => $row['description'],
            'price' => $row['price'],
        ]);
    }

    public function sheets(): array
    {
        return [
            // Select by sheet index
            0 => new ProductsImport(),
        ];
    }

    public function headingRow(): int
    {
        return 3;
    }
}
