<?php

namespace App\Helpers;

class ReturnResponse{

	/**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public static function returnJson($nameArr, $data, $status, $code){
        return response()->json([
            $nameArr => $data,
            'success' => $status
        ], $code);
    }
}