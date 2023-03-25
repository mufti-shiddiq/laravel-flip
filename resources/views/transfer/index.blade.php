<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Riwayat Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row my-3">
            <h3>Riwayat Transfer</h3>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <a href="{{ route('transfer.inquiry') }}" class="btn btn-primary btn">Cek Rekening</a>
                <a href="{{ route('transfer.bank') }}" class="btn btn-primary btn">List Bank</a>
            </div>
        </div>
        <div class="row">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Timestamp</th>
                        <th scope="col">Bank Code</th>
                        <th scope="col">Account Number</th>
                        <th scope="col">Recipient Name</th>
                        <th scope="col">Remark</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Fee</th>
                        <th scope="col">Status</th>
                        <th scope="col">Time Served</th>
                        <th scope="col">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $item)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->bank_code }}</td>
                            <td>{{ $item->account_number }}</td>
                            <td>{{ $item->recipient_name }}</td>
                            <td>{{ $item->remark }}</td>
                            <td>{{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->fee, 0, ',', '.') }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ $item->time_served }}</td>
                            <td>
                                <a href="{{ $item->receipt }}" class="btn btn-primary btn">SHOW</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

</html>
