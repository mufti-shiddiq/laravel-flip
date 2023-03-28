<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Disbursement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <div class="row">
            <h3 class="my-3">Create Disbursement</h3>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('transfer.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="exampleDataList" class="form-label">Bank</label>
                    <select class="form-select" aria-label="Default select example" name="bank_code" required>
                        <option value="" selected disabled hidden>--Pilih--</option>
                        @foreach ($banks as $bank)
                            <option value="{{ $bank->bank_code }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nomor Rekening</label>
                    <input type="number" class="form-control" id="exampleFormControlInput1" placeholder=""
                        name="account_number" required>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="exampleFormControlInput1" placeholder=""
                        name="amount" required>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Remark</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="remark" rows="3"></textarea>
                  </div>
                <button type="submit" class="btn btn-primary mb-3">Submit</button>
                <a href="{{ route('transfer.index') }}" class="btn btn-secondary mb-3">Back</a>
            </form>
        </div>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

</html>
