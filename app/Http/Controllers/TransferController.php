<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transfer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->secretKey = 'Basic ' . config('flip.key_auth');
    }

    public function index()
    {
        $data = Transfer::all();

        return view('transfer.index', compact('data'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function bank()
    {
        // Request Bank Info
        $requestBankInfo = Http::withHeaders([
            'Authorization' => $this->secretKey,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->get(config('flip.base_url_v2') . '/general/banks');

        $banks = $requestBankInfo->object();

        return view('transfer.bank', compact('banks'));
    }

    public function inquiry()
    {
        // Request Bank Info
        $requestBankInfo = Http::withHeaders([
            'Authorization' => $this->secretKey,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->get(config('flip.base_url_v2') . '/general/banks');

        $banks = $requestBankInfo->object();

        return view('transfer.inquiry', compact('banks'));
    }

    public function storeInquiry(Request $request)
    {
        // Request Bank Info
        $requestBankInfo = Http::withHeaders([
            'Authorization' => $this->secretKey,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->get(config('flip.base_url_v2') . '/general/banks?code=' . $request->bank_code);

        $bank = $requestBankInfo->object();

        if ($bank[0]->status != 'OPERATIONAL') {
            return back()->withInput()->with('error', 'SORRY, BANK ' . $bank[0]->status);
        }

        // Request Bank Account Inquiry
        do {
            $dataRequest = Http::withHeaders([
                'Authorization' => $this->secretKey
            ])->post(config('flip.base_url_v2') . '/disbursement/bank-account-inquiry', [
                'bank_code' => $request->bank_code,
                'account_number' => $request->account_number
            ]);

            $response = $dataRequest->object();
        } while ($response->status == 'PENDING');

        // Return Response
        if ($response->status == 'SUCCESS') {
            $data = $response->bank_code . ' ' . $response->account_number . " a.n  " . $response->account_holder;

            return back()->withInput()->with('status', $data);
        }

        return back()->withInput()->with('error', $response->status);
    }
}
