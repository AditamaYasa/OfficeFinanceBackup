<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Transaction;

class IncomeController extends Controller
{
    public function create()
    {
        return view('income');
    }

    public function store(Request $request)
    {
        $validationRules = [
            'category_id' => 'required|exists:categories,id',
            'customer' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'date_entry' => 'required|date',
            'description' => 'nullable|string|max:255',
            'date_factur' => 'required|date',
            'no_factur' => 'required|integer',
            'date' => 'required|date',
        ];

        if ($request->hasFile('attachment')) {
            $validationRules['attachment'] = 'file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        $request->validate($validationRules);

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        $income = Income::create([
            'category_id' => $request->category_id,
            'customer' => $request->customer,
            'amount' => $request->amount,
            'date_entry' => $request->date_entry,
            'description' => $request->description,
            'date_factur' => $request->date_factur,
            'no_factur' => $request->no_factur,
            'date' => $request->date,
            'attachment' => $attachmentPath,
        ]);

        Transaction::create([
            'type' => 'income',
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'date_factur' => $request->date_factur,
            'no_factur' => $request->no_factur,
            'attachment' => $attachmentPath,
        ]);

        return redirect()->route('income.index')->with('success', 'Income saved successfully!');
    }
}