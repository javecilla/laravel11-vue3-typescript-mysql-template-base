<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

Route::middleware('api')->group(function () {
    Route::get('/test1', function() {
        $data = [
            [
                'email' => 'test1@gmail.com',
                'name' => 'test1 name',
                'address' => 'test1 address'
            ],
            [
                'email' => 'test2@gmail.com',
                'name' => 'test2 name',
                'address' => 'test2 address'
            ] 
        ];
        return Response::success($data, 'testing');
    });

    Route::get('/test2', function() {
        $data = [
            'labels' => [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            ],
            'data' => [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11],
            'title' => 'Number of babies born'
        ];

        //return Response::validationError('testing');
        return Response::success($data, 'testing');
    });
});