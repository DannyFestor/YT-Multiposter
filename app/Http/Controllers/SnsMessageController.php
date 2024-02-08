<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Enums\SnsServices;
use App\Http\Requests\SnsMessageRequest;
use App\Models\SnsMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;

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

        $content = $request->get('content');
        if ($request->has(SnsServices::Twitter->value) && $request->get(SnsServices::Twitter->value) === 'on') {
            $this->postToTwitter($content, $snsMessage);
        }

        if ($request->has(SnsServices::BlueSky->value) && $request->get(SnsServices::BlueSky->value) === 'on') {
            $this->postToBlueSky($content, $snsMessage);
        }

        if ($request->has(SnsServices::Mastodon->value) && $request->get(SnsServices::Mastodon->value) === 'on') {
            $this->postToMastodon($content, $snsMessage);
        }

        return redirect()->route('index')->with('success', 'Messages sent');
    }

    public function postToTwitter(string $content, SnsMessage $snsMessage): void
    {
        // https://developer.twitter.com/en/portal/projects-and-apps
        $connection = new TwitterOAuth(
            config('services.sns.twitter.api_key'),
            config('services.sns.twitter.api_secret'),
            config('services.sns.twitter.client_id'),
            config('services.sns.twitter.client_secret')
        );
        $connection->setApiVersion('2');
        $response = $connection->post("tweets", ["text" => $content]);

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

    public function postToBlueSky(string $content, SnsMessage $snsMessage): void
    {
        // https://www.docs.bsky.app/docs/get-started
        /*
         * curl -X POST https://bsky.social/xrpc/com.atproto.server.createSession \
            -H "Content-Type: application/json" \
            -d '{"identifier": "'"$BLUESKY_HANDLE"'", "password": "'"$BLUESKY_PASSWORD"'"}'
         */
        $sessionResponse = Http::contentType('application/json')
            ->post('https://bsky.social/xrpc/com.atproto.server.createSession', [
                "identifier" => config('services.sns.bluesky.handle'),
                "password" => config('services.sns.bluesky.password'),
            ]);

        $session = $sessionResponse->json();
        /**
        {
            ...
            "accessJwt": "<ACCESS TOKEN>",
            "refreshJwt": "<REFRESH TOKEN>"
        }
         */

        if (!isset($session['accessJwt'])) {
            return;
        }

        // curl -X POST https://bsky.social/xrpc/com.atproto.repo.createRecord \
        //    -H "Authorization: Bearer $ACCESS_JWT" \
        //    -H "Content-Type: application/json" \
        //    -d "{\"repo\": \"$BLUESKY_HANDLE\", \"collection\": \"app.bsky.feed.post\", \"record\": {\"text\": \"Hello world! I posted this via the API.\", \"createdAt\": \"$(date -u +%Y-%m-%dT%H:%M:%SZ)\"}}"
        $postResponse = Http::contentType('application/json')
            ->withHeader('Authorization', 'Bearer ' . $session['accessJwt'])
            ->post('https://bsky.social/xrpc/com.atproto.repo.createRecord', [
                'repo' => config('services.sns.bluesky.handle'),
                'collection' => 'app.bsky.feed.post',
                'record' => [
                    'text' => $content,
                    'createdAt' => now()->format('c'),
                ],
            ]);
        /**
         * RESPONSE BODY
         * {
         *   "uri": "at://did:plc:aqlyoppmd2hqlw4axte6mvsb/app.bsky.feed.post/3kkuzywbuny2o",
         *   "cid": "bafyreib6752heuq5lthnvg7runmurtoqnkdfvuyjhd46ftbodhlz36zxke"
         * }
         *
         * POST URL
         * https://bsky.app/profile/danakin.bsky.social/post/3kkuzywbuny2o
         */
        $url = explode('/', $postResponse->json()['uri']);
        $id = $url[count($url) - 1];

        $snsMessage->snsLinks()->create([
            'service' => SnsServices::BlueSky,
            'url' => $id,
        ]);
    }

    public function postToMastodon(string $content, SnsMessage $snsMessage): void
    {
        $response = Http::withHeader('Authorization', 'Bearer ' . config('services.sns.mastodon.token'))
            ->post('https://phpc.social/api/v1/statuses', [
                'status' => $content,
                'language' => 'eng',
                'visibility' => 'public',
            ]);

        /**
         * Response Body:
         *
         * { "id": "111894270231189037", ... }
         *
         * URL
         * https://phpc.social/@dannyfestor/111894270231189037
         */

        $snsMessage->snsLinks()->create([
            'service' => SnsServices::Mastodon,
            'url' => $response->json()['id'] ?? '',
        ]);
    }
}
