<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans leading-normal tracking-normal">
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0">
                    <span class="text-2xl font-bold text-amber-600">SwapNet</span>
                </div>
                <div>
                    <a href="/admin" class="text-gray-600 hover:text-amber-600 px-3 py-2 rounded-md text-sm font-medium">Admin Panel</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="prose prose-amber max-w-none">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-8">{{ $page->title }}</h1>
            <div class="text-lg text-gray-700">
                {!! $page->content !!}
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} SwapNet. All rights reserved.
        </div>
    </footer>
</body>
</html>
