@extends('layouts.admin')

@section('title', 'Transactions')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

@section('content')
<div class="container">
    <h2 class="mb-4">Transactions</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>მომხმარებელი</th> 
                <th>თანხა</th>
                <th>სტატუსი</th>
                <th>Delivery</th>
                <th>შექმნის თარიღი</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>
                        <a href="{{ route('admin.transactions.userTransactions', $transaction->id) }}">
                            {{ $transaction->u_name ?? 'Guest' }}
                        </a>
                    </td>                    <td>{{ $transaction->amount ?? '-' }} ლარი</td>
                    <td>{{ $transaction->status ?? '-' }}</td>
                    <td>
                    
                            @if($transaction->status === 'delivered')
                                <form action="{{ route('admin.transactions.undoDelivered', $transaction->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-warning d-flex align-items-center gap-2">
                                        <i class="bi bi-check-lg text-success"></i> დასრულებული
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.transactions.markAsDelivered', $transaction->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-2">
                                        <i class="bi bi-x"></i> დაუსრულებელი
                                    </button>
                                </form>
                            @endif
                      
                    </td>
                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
<div class="d-flex justify-content-center">
    {{ $transactions->links('pagination::bootstrap-5') }}
</div>
</div>
@endsection
