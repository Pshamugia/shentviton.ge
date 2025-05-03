<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment; // ✅ Using your Payment model
use App\Models\User;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = \App\Models\Payment::latest()->paginate(10); // ✅ Paginate 10 per page
        return view('admin.transactions.index', compact('transactions'));
    }




    public function markAsDelivered($id)
{
    $transaction = \App\Models\Payment::findOrFail($id);
    $transaction->status = 'delivered'; // ✅ just update the existing status field
    $transaction->save();

    return redirect()->back()->with('success', 'მიტანის სტატუსი დასრულდა.');
}

public function undoDelivered($id)
{
    $transaction = \App\Models\Payment::findOrFail($id);
    $transaction->status = 'pending'; // ✅ revert status back to pending
    $transaction->save();

    return redirect()->back()->with('success', 'მიტანის სტატუსი დაბრუნდა დაუსრულებლად.');
}



public function userTransactions($paymentId)
{
    $transaction = \App\Models\Payment::findOrFail($paymentId);

    // Force clean cart_ids
    $cartIds = $transaction->cart_ids;

    if (is_string($cartIds)) {
        $cartIds = trim($cartIds, '[]'); // remove []
        $cartIds = explode(',', $cartIds); // split by ,
        $cartIds = array_map('trim', $cartIds); // remove spaces
    }

    $products = [];
    if (!empty($cartIds)) {
        $products = \App\Models\Cart::whereIn('id', $cartIds)->with('baseProduct')->get();
    }

    return view('admin.transactions.user_transactions', compact('transaction', 'products'));
}




}
