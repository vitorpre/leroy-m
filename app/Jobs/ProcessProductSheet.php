<?php

namespace App\Jobs;

use App\Models\ProductSheet;
use App\Models\Sheet\ProductsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class ProcessProductSheet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productSheet;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ProductSheet $productSheet)
    {
        $this->productSheet = $productSheet;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            Excel::import(new ProductsImport(), Config::get('constants.PRODUCT_SHEET_PATH') . '/' . $this->productSheet->file);
            $this->productSheet->success = true;

        } catch (\Exception $e) {
            $this->productSheet->success = false;
            $this->productSheet->message = $e->getMessage();
        }

        $this->productSheet->processed = true;
        $this->productSheet->save();

    }
}
