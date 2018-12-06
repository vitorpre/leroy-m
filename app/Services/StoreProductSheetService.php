<?php
/**
 * Created by PhpStorm.
 * User: AMD
 * Date: 03/12/2018
 * Time: 23:37
 */

namespace App\Services;


use App\Http\Requests\ProductSheet\StoreProductSheetRequest;
use App\Jobs\ProcessProductSheet;
use App\Models\ProductSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class StoreProductSheetService
{
    public function make(StoreProductSheetRequest $request) {

        DB::beginTransaction();


        $productSheet = new ProductSheet();
        $filename = uniqid('xls-') . '.' . $request->file('product_sheet')->extension() ;


        try {

            if(!$this->store($productSheet, $filename)) {
                throw new \Exception('Falha ao salvar Produto');
            }

            if(!$this->saveFile($request, $filename)) {
                throw new \Exception('Falha ao fazer upload do arquivo');
            }

        } catch (\Exception $e) {
            $this->removeFile($filename);
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        Queue::push(new ProcessProductSheet($productSheet));

        return $productSheet;
    }

    private function store($productSheet, $filename) {
        $productSheet->file = $filename;
        return $productSheet->save();
    }

    private function saveFile(Request $request, $filename) {
        return (bool) $request->file('product_sheet')
            ->storeAs(Config::get('constants.PRODUCT_SHEET_PATH'), $filename);
    }

    private function removeFile($filename) {
        $fileExists = Storage::disk('local')
            ->exists(Config::get('constants.PRODUCT_SHEET_PATH') . '/' . $filename);

        if($fileExists) {
            Storage::disk('local')
                ->delete(Config::get('constants.PRODUCT_SHEET_PATH') . '/' . $filename);
        }
    }

}
