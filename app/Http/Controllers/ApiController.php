<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();

        $response_data = [
            'status' => 200,
            'count' => $currencies->count(),
            'data' => $currencies,
        ];

        return response()->json($response_data, 200);
    }

    public function show($abbr)
    {
        $currency = Currency::where('abbr', strtoupper($abbr))->first();

        $status = !empty($currency) ? 200 : 404;

        $response_data = [
            'status' => $status,
            'count' => 1,
            'data' => [
                $currency
            ]
        ];

        return response()->json($response_data, $status);
    }

    public function calc($from, $amount, $to)
    {
        $valueInToCurrency = 0.0;
        $status = 200;
        $from_valuta = Currency::where('abbr', strtoupper($from))->first();
        $to_valuta = Currency::where('abbr', strtoupper($to))->first();

        if(!empty($from_valuta) && !empty($to_valuta)) {
            $valueInEuros = floatval($amount) / floatval($from_valuta['value']);
            $valueInToCurrency = floatval($valueInEuros) * floatval($to_valuta['value']);
        } else {
            $status = 404;
        }

        $response_data = [
            'status' => $status,
            'count' => 1,
            'data' => [
                'calculated_value' => $valueInToCurrency,
                'from' => $from_valuta,
                'amount' => $amount,
                'to' => $to_valuta
            ]
        ];

        return response()->json($response_data, $status);
    }
}
