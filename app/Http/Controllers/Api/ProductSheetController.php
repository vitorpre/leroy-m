<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductSheet\StoreProductSheetRequest;
use App\Models\ProductSheet;
use App\Services\StoreProductSheetService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ProductSheetController extends Controller
{
    private $storeProductSheetService;

    public function __construct(StoreProductSheetService $storeProductSheetService)
    {
        $this->storeProductSheetService = $storeProductSheetService;
    }

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

    public function show($id)
    {
        $productSheet = ProductSheet::select('id', 'processed', 'success', 'message')
            ->where('id', $id)
            ->first();


        return Response::json([
            'data' => [
                'type' => 'ProductSheet',
                    $productSheet->toArray()
            ]
        ]);
    }
}
