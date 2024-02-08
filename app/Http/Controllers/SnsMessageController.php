<?php

namespace App\Http\Controllers;

use App\Enums\SnsServices;
use App\Http\Requests\SnsMessageRequest;
use App\Models\SnsMessage;
use http\Message;
use Illuminate\Contracts\View\View;

class SnsMessageController extends Controller
{
    public function index(): View
    {
        return view('messages', [
            'SnsMessages' => SnsMessage::with('snsLinks')->latest()->paginate(10),
        ]);
    }

    public function store(SnsMessageRequest $request)
    {
        $snsMessage = SnsMessage::create($request->validated());

        if ($request->has(SnsServices::Twitter->value) && $request->get(SnsServices::Twitter->value) === 'on') {
            // TODO: POST TO Twitter
            $snsMessage->snsLinks()->create([
                'service' => SnsServices::Twitter,
                'url' => \Str::random(), // TODO: RETRIEVE URL FROM Twitter
            ]);
        }

        if ($request->has(SnsServices::BlueSky->value) && $request->get(SnsServices::BlueSky->value) === 'on') {
            // TODO: POST TO BlueSky
            $snsMessage->snsLinks()->create([
                'service' => SnsServices::BlueSky,
                'url' => \Str::random(), // TODO: RETRIEVE URL FROM BlueSky
            ]);
        }

        if ($request->has(SnsServices::Mastodon->value) && $request->get(SnsServices::Mastodon->value) === 'on') {
            // TODO: POST TO Mastodon
            $snsMessage->snsLinks()->create([
                'service' => SnsServices::Mastodon,
                'url' => \Str::random(), // TODO: RETRIEVE URL FROM Mastodon
            ]);
        }

        return redirect()->route('index')->with('success', 'Messages sent');
    }
}
