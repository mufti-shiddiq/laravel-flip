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
        // Request Bank Info
        $requestBankInfo = Http::withHeaders([
            'Authorization' => $this->secretKey,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->get(config('flip.base_url_v2') . '/general/banks');

        $banks = $requestBankInfo->object();

        return view('transfer.create', compact('banks'));
    }

    public function store(Request $request)
    {
        // Request Bank Info
        $requestBankInfo = Http::withHeaders([
            'Authorization' => $this->secretKey,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->get(config('flip.base_url_v2') . '/general/banks?code=' . $request->bank_code);

        $bank = $requestBankInfo->object();

        // Return error if bank not operational
        if ($bank[0]->status != 'OPERATIONAL') {
            return back()->withInput()->with('error', 'SORRY, BANK ' . $bank[0]->status);
        }

        // Request Bank Account Inquiry
        do {
            $requestInquiry = Http::withHeaders([
                'Authorization' => $this->secretKey
            ])->post(config('flip.base_url_v2') . '/disbursement/bank-account-inquiry', [
                'bank_code' => $request->bank_code,
                'account_number' => $request->account_number
            ]);

            $responseInquiry = $requestInquiry->object();
        } while ($responseInquiry->status == 'PENDING');

        // If Account Inquiry SUCCESS
        if ($responseInquiry->status == 'SUCCESS') {

            // Generate Idempotency Key
            $idempotencyKey = bin2hex(random_bytes(16));

            // Create Disbursement
            $createDisbursement = Http::withHeaders([
                'Authorization' => $this->secretKey,
                'idempotency-key' => $idempotencyKey
            ])->post(config('flip.base_url_v3') . '/disbursement', [
                'bank_code' => $request->bank_code,
                'account_number' => $request->account_number,
                'amount' => $request->amount,
                'remark' => $request->remark
            ]);

            $response = $createDisbursement->object();

            if ($response->status != 'CANCELLED') {
                if ($response->time_served == '(not set)') {
                    $response->time_served = null;
                }

                Transfer::create([
                    'external_id' => $response->id,
                    'bank_code' => $response->bank_code,
                    'account_number' => $response->account_number,
                    'recipient_name' => $response->recipient_name,
                    'remark' => $response->remark,
                    'sender_bank' => $response->sender_bank,
                    'amount' => $response->amount,
                    'fee' => $response->fee,
                    'status' => $response->status,
                    'time_served' => $response->time_served,
                    'receipt' => $response->receipt
                ]);

                return redirect()->route('transfer.index')->with('status', 'Transaksi sedang diproses...');
            }

            return back()->withInput()->with('error', $response->status);
        }

        return back()->withInput()->with('error', $responseInquiry->status);
    }

    public function callback()
    {
        $response = request()->data;
        $data = json_decode($response);

        if ($data->status != 'DONE') {
            $data->time_served = null;
        }

        $update = Transfer::where('external_id', $data->id)->update([
            'status' => $data->status,
            'reason' => $data->reason,
            'sender_bank' => $data->sender_bank,
            'time_served' => $data->time_served,
            'receipt' => $data->receipt
        ]);

        return response($update);
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
