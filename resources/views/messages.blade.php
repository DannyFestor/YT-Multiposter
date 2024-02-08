<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>
    <body class="antialiased bg-slate-400 min-h-screen grid place-items-center">
        <main class="p-8 flex flex-col gap-8">
            <h1 class="text-3xl font-bold text-slate-800 p-4 bg-white rounded w-full text-center">Multiposter!!</h1>

            @if(session()->has('success'))
            <div class="rounded p-4 font-bold bg-emerald-300 border-2 border-emerald-700 text-emerald-700">{{ session()->get('success') }}</div>
            @endif

            <form action="{{ route('store') }}" method="post" class="flex flex-col gap-4 p-4 bg-white rounded shadow">
                @csrf
                <label for="content">Your message to the world:</label>
                <textarea name="content" id="content" cols="100" rows="10" class="form-textarea rounded">{{ old('content', '') }}</textarea>
                @error('content')
                <div>{{ $message }}</div>
                @enderror

                <div class="flex flex-row flex-wrap items-center">
                    <input type="checkbox" name="twitter" id="twitter" class="form-checkbox rounded">
                    <label for="twitter" class="ml-2">Twitter</label>
                    <input type="checkbox" name="bluesky" id="bluesky" class="form-checkbox rounded ml-4">
                    <label for="bluesky" class="ml-2">Bluesky</label>
                    <input type="checkbox" name="mastodon" id="mastodon" class="form-checkbox rounded ml-4">
                    <label for="mastodon" class="ml-2">Mastodon</label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 rounded bg-slate-700 text-slate-100">Send !</button>
                </div>
            </form>

            <section class="flex flex-col gap-4">
                @foreach($SnsMessages as $SnsMessage)
                <article class="bg-white rounded flex w-full divide-x divide-slate-300">
                    <div class="flex-1 p-4">{{ $SnsMessage->content }}</div>
                    <a href="https://www.titter.com/" class="w-[50px] flex items-center justify-center p-2 hover:bg-slate-300">
                        <svg fill="#1D9BF0" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Twitter</title><path d="M21.543 7.104c.015.211.015.423.015.636 0 6.507-4.954 14.01-14.01 14.01v-.003A13.94 13.94 0 0 1 0 19.539a9.88 9.88 0 0 0 7.287-2.041 4.93 4.93 0 0 1-4.6-3.42 4.916 4.916 0 0 0 2.223-.084A4.926 4.926 0 0 1 .96 9.167v-.062a4.887 4.887 0 0 0 2.235.616A4.928 4.928 0 0 1 1.67 3.148 13.98 13.98 0 0 0 11.82 8.292a4.929 4.929 0 0 1 8.39-4.49 9.868 9.868 0 0 0 3.128-1.196 4.941 4.941 0 0 1-2.165 2.724A9.828 9.828 0 0 0 24 4.555a10.019 10.019 0 0 1-2.457 2.549z"/></svg>
                    </a>
                    <a href="https://bsky.app/" class="w-[50px] flex items-center justify-center p-2 hover:bg-slate-300">
                        <svg fill="#0285FF" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Bluesky</title><path d="M12 10.8c-1.087-2.114-4.046-6.053-6.798-7.995C2.566.944 1.561 1.266.902 1.565.139 1.908 0 3.08 0 3.768c0 .69.378 5.65.624 6.479.815 2.736 3.713 3.66 6.383 3.364.136-.02.275-.039.415-.056-.138.022-.276.04-.415.056-3.912.58-7.387 2.005-2.83 7.078 5.013 5.19 6.87-1.113 7.823-4.308.953 3.195 2.05 9.271 7.733 4.308 4.267-4.308 1.172-6.498-2.74-7.078a8.741 8.741 0 0 1-.415-.056c.14.017.279.036.415.056 2.67.297 5.568-.628 6.383-3.364.246-.828.624-5.79.624-6.478 0-.69-.139-1.861-.902-2.206-.659-.298-1.664-.62-4.3 1.24C16.046 4.748 13.087 8.687 12 10.8Z"/></svg>
                    </a>
                    <a href="https://phpc.social/" class="w-[50px] flex items-center justify-center p-2 hover:bg-slate-300">
                        <svg fill="#6364FF" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Mastodon</title><path d="M23.268 5.313c-.35-2.578-2.617-4.61-5.304-5.004C17.51.242 15.792 0 11.813 0h-.03c-3.98 0-4.835.242-5.288.309C3.882.692 1.496 2.518.917 5.127.64 6.412.61 7.837.661 9.143c.074 1.874.088 3.745.26 5.611.118 1.24.325 2.47.62 3.68.55 2.237 2.777 4.098 4.96 4.857 2.336.792 4.849.923 7.256.38.265-.061.527-.132.786-.213.585-.184 1.27-.39 1.774-.753a.057.057 0 0 0 .023-.043v-1.809a.052.052 0 0 0-.02-.041.053.053 0 0 0-.046-.01 20.282 20.282 0 0 1-4.709.545c-2.73 0-3.463-1.284-3.674-1.818a5.593 5.593 0 0 1-.319-1.433.053.053 0 0 1 .066-.054c1.517.363 3.072.546 4.632.546.376 0 .75 0 1.125-.01 1.57-.044 3.224-.124 4.768-.422.038-.008.077-.015.11-.024 2.435-.464 4.753-1.92 4.989-5.604.008-.145.03-1.52.03-1.67.002-.512.167-3.63-.024-5.545zm-3.748 9.195h-2.561V8.29c0-1.309-.55-1.976-1.67-1.976-1.23 0-1.846.79-1.846 2.35v3.403h-2.546V8.663c0-1.56-.617-2.35-1.848-2.35-1.112 0-1.668.668-1.67 1.977v6.218H4.822V8.102c0-1.31.337-2.35 1.011-3.12.696-.77 1.608-1.164 2.74-1.164 1.311 0 2.302.5 2.962 1.498l.638 1.06.638-1.06c.66-.999 1.65-1.498 2.96-1.498 1.13 0 2.043.395 2.74 1.164.675.77 1.012 1.81 1.012 3.12z"/></svg>
                    </a>
                </article>
                @endforeach
            </section>
        </main>
    </body>
</html>
