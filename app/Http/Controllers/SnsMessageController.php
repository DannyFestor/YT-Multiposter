<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Enums\SnsServices;
use App\Http\Requests\SnsMessageRequest;
use App\Models\SnsMessage;
use Illuminate\Contracts\View\View;

class SnsMessageController extends Controller
{
    public function index(): View
    {
        return view('messages', [
            'SnsMessages' => SnsMessage::with('snsLinks')->latest()->paginate(10),
        ]);
    }

    /**
     * @throws TwitterOAuthException
     */
    public function store(SnsMessageRequest $request)
    {
        $snsMessage = SnsMessage::create($request->validated());

        if ($request->has(SnsServices::Twitter->value) && $request->get(SnsServices::Twitter->value) === 'on') {
            $connection = new TwitterOAuth(
                config('services.sns.twitter.api_key'),
                config('services.sns.twitter.api_secret'),
                config('services.sns.twitter.client_id'),
                config('services.sns.twitter.client_secret')
            );
            $connection->setApiVersion('2');
            $response = $connection->post("tweets", ["text" => $request->get('content')]);

            /**
             * Response Body: (stdObject => access properties via $response->data-id)
             * {
             *      "data": {
             *          "edit_history_tweet_ids": [
             *              "1755442457607827784"
             *          ],
             *          "id": "1755442457607827784",
             *          "text": "Posted this from my app..."
             *      }
             * }
             *
             * Tweet URL:
             * https://twitter.com/Denakino/status/1755442457607827784
             */

            if (isset($response->data->id)) {
                $snsMessage->snsLinks()->create([
                    'service' => SnsServices::Twitter,
                    'url' => $response->data->id,
                ]);
            }

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
