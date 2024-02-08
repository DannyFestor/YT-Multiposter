<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="antialiased bg-slate-400 min-h-screen grid place-items-center">
<main class="p-8 flex flex-col gap-8">
    <h1 class="text-3xl font-bold text-slate-800 p-4 bg-white rounded w-full text-center">Multiposter!!</h1>

    @if(session()->has('success'))
        <div
            class="rounded p-4 font-bold bg-emerald-300 border-2 border-emerald-700 text-emerald-700">{{ session()->get('success') }}</div>
    @endif

    <form action="{{ route('store') }}" method="post" class="flex flex-col gap-4 p-4 bg-white rounded shadow">
        @csrf
        <label for="content">Your message to the world:</label>
        <textarea name="content" id="content" cols="100" rows="10"
                  class="form-textarea rounded">{{ old('content', '') }}</textarea>
        @error('content')
        <div>{{ $message }}</div>
        @enderror

        <div class="flex flex-row flex-wrap gap-4 items-center">
            @foreach(\App\Enums\SnsServices::cases() as $snsService)
                <?php /** @var \App\Enums\SnsServices $service */ ?>
                <x-service.input :service="$snsService" :checked="old($snsService->value) === 'on'" />
            @endforeach
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 rounded bg-slate-700 text-slate-100">Send !</button>
        </div>
    </form>

    <section class="flex flex-col gap-4">
        @foreach($SnsMessages as $SnsMessage)
            <article class="bg-white rounded flex w-full divide-x divide-slate-300">
                <div class="flex-1 p-4">{{ $SnsMessage->content }}</div>
                @foreach($SnsMessage->snsLinks as $snsLink)
                    <a href="{{ $snsLink->service->url($snsLink->url) }}"
                       target="_blank"
                       class="w-[50px] flex items-center justify-center p-2 hover:bg-slate-300">
                        {!! $snsLink->service->icon() !!}
                    </a>
                @endforeach
            </article>
        @endforeach
    </section>

    {{ $SnsMessages->links() }}
</main>
</body>
</html>
