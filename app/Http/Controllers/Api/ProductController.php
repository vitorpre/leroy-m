<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductSheet\StoreProductSheetRequest;
use App\Models\ProductSheet;
use App\Models\Sheet\ProductsImport;
use App\Services\StoreProductSheetService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    private $storeProductSheetService;

    public function __construct(StoreProductSheetService $storeProductSheetService)
    {
        $this->storeProductSheetService = $storeProductSheetService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductSheetRequest $request)
    {
        try {
            $productSheet = $this->storeProductSheetService->make($request);
        } catch (\Exception $e) {
            return Response::json([
                'errors' => [
                    'status' => 400,
                    'message' => $e->getMessage()
                ]
            ]);
        }

        return Response::json([
            'data' => [
                [
                    'type' => 'ProductSheet',
                    'id' => $productSheet->id
                ]
            ]
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productSheet = ProductSheet::find($id);

        $rows = Excel::toArray(new ProductsImport(), Config::get('constants.PRODUCT_SHEET_PATH') . '/' . $productSheet->file);

        dd($rows);

        return Response::json($rows);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
