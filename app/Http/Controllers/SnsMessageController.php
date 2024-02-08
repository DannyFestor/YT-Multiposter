<?php

namespace App\Http\Controllers;

use App\Http\Requests\SnsMessageRequest;
use App\Models\SnsMessage;
use http\Message;
use Illuminate\Contracts\View\View;

class SnsMessageController extends Controller
{
    public function index(): View
    {
        return view('messages', [
            'SnsMessages' => SnsMessage::latest()->paginate(10),
        ]);
    }

    public function store(SnsMessageRequest $request)
    {
        SnsMessage::create($request->validated());

        return redirect()->route('index')->with('success', 'Messages sent');
    }
}
