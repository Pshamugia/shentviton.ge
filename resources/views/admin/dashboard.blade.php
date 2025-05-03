@extends('layouts.admin')

@section('content')

    <div class="container">
        <h4><i class="bi bi-calculator"></i> ბუღალტერია</h4>

        <div class="row g-3 my-3">
            @php
                $cards = [
                    ['label' => 'არსებული მარაგის სრული ფასი', 'value' => $totalProductValue],
                    ['label' => 'საშუალო ფასი თითო პროდუქტისთვის', 'value' => $avgProductPrice],
                    ['label' => 'პროდუქტების სრული რაოდენობა', 'value' => $productCount],
                    ['label' => 'გაყიდული პროდუქციის ჯამი', 'value' => $totalSoldAmount],
                    ['label' => 'საშუალო ფასი ერთ პროდუქტზე', 'value' => $avgPricePerItem],
                    ['label' => 'გაყიდული რაოდენობა ერთეულებში', 'value' => $totalQuantity],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="fw-bold">{{ $card['label'] }}</div>
                        <div class="fs-5">{{ $card['value'] }} </div>
                    </div>
                </div>
            @endforeach
        </div>

        <form method="GET" class="d-flex gap-3 mt-4">
            <div>
                <label>Start Date:</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div>
                <label>End Date:</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="align-self-end">
                <button type="submit" class="btn btn-primary">გაფილტრე</button>
            </div>
        </form>
    </div>

 

@endsection
 
