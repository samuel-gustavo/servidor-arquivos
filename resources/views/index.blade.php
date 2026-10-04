@php
    /*
    |--------------------------------------------------------------------------
    | IDENTIFICA O TIPO DO ARQUIVO
    |--------------------------------------------------------------------------
    */

    function getFileType(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'pdf',

            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico' => 'image',

            'doc', 'docx' => 'word',

            'xls', 'xlsx', 'csv' => 'excel',

            'ppt', 'pptx' => 'powerpoint',

            'zip', 'rar', '7z', 'tar', 'gz' => 'archive',

            'mp3', 'wav', 'ogg', 'flac' => 'audio',

            'mp4', 'avi', 'mkv', 'mov', 'webm' => 'video',

            'txt', 'md', 'log' => 'text',

            'php',
            'js',
            'ts',
            'jsx',
            'tsx',
            'py',
            'java',
            'c',
            'cpp',
            'h',
            'css',
            'html',
            'json',
            'xml',
            'sql',
            'sh'
                => 'code',

            default => 'file',
        };
    }
@endphp

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Arquivos</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('imgs/pasta.png') }}">

    {{-- ========================================================
         TAILWIND VIA CDN
    ========================================================= --}}

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="min-h-screen bg-slate-950 text-white">


    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- ====================================================
             CABEÇALHO
        ===================================================== --}}

        <header class="mb-10">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="mb-3 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-600 shadow-lg shadow-violet-600/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7.5A2.25 2.25 0 015.25 5.25h3.879a2.25 2.25 0 011.591.659l1.121 1.121a2.25 2.25 0 001.591.659h6.318A2.25 2.25 0 0122 9.939v7.811A2.25 2.25 0 0119.75 20H5.25A2.25 2.25 0 013 17.75V7.5z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-violet-400">
                            Gerenciador de arquivos
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Meus arquivos
                    </h1>

                    <p class="mt-2 text-sm text-slate-400 sm:text-base">
                        Envie, armazene e baixe seus arquivos de forma simples.
                    </p>
                </div>

                {{-- =================================================
                     CONTADOR
                ================================================== --}}
                <div
                    class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-800 bg-slate-900 px-4 py-2 text-sm text-slate-300">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    {{ count($files) }}
                    {{ count($files) === 1 ? 'arquivo' : 'arquivos' }}
                </div>
            </div>

        </header>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                    stroke="currentColor" class="h-5 w-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                    stroke="currentColor" class="h-5 w-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m0 3h.008v.008H12V15.75zM10.34 3.94L2.86 17.25a1.875 1.875 0 001.63 2.813h14.02a1.875 1.875 0 001.63-2.813L12.66 3.94a1.875 1.875 0 00-2.32 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-500/20 bg-red-500/10 p-4">
                <div class="mb-2 font-medium text-red-300">
                    Não foi possível enviar o arquivo.
                </div>
                <ul class="space-y-1 text-sm text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>
                            • {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[380px_1fr]">
            <section>
                <div class="rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-xl shadow-black/10">
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-white">
                            Enviar arquivo
                        </h2>
                        <p class="mt-1 text-sm text-slate-400">
                            Selecione um arquivo para armazenar no servidor.
                        </p>
                    </div>
                    <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data"
                        id="uploadForm">
                        @csrf
                        <label for="file" id="dropzone" class="group flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-700 bg-slate-950/50 px-6 py-10 text-center transition hover:border-violet-500 hover:bg-violet-500/5">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-violet-500/10 text-violet-400 transition group-hover:scale-105 group-hover:bg-violet-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.7" stroke="currentColor" class="h-7 w-7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 16.5V3.75m0 0L7.5 8.25M12 3.75l4.5 4.5M6.75 15v2.25A2.25 2.25 0 009 19.5h6a2.25 2.25 0 002.25-2.25V15" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-200" id="fileText">
                                Clique para selecionar arquivos
                            </span>
                            <span class="mt-2 text-xs text-slate-500">
                                ou arraste os arquivos para esta área
                            </span>
                            <span class="mt-4 rounded-lg bg-slate-800 px-3 py-1.5 text-xs text-slate-400">
                                Máximo: 20 MB por arquivo
                            </span>
                            <input type="file" name="files[]" id="file" class="hidden" multiple required>
                        </label>

                        <div id="selectedFiles" class="mt-4 hidden space-y-2">
                        </div>

                        <button type="submit" id="uploadButton" class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-600/20 transition hover:bg-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.8" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 16.5V3.75m0 0L7.5 8.25M12 3.75l4.5 4.5M6.75 15v2.25A2.25 2.25 0 009 19.5h6a2.25 2.25 0 002.25-2.25V15" />
                            </svg>
                            Enviar arquivos
                        </button>
                    </form>

                    <div class="mt-5 border-t border-slate-800 pt-5">
                        <div class="flex items-start gap-3 text-xs text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.7" stroke="currentColor" class="mt-0.5 h-4 w-4 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>
                                Os arquivos são armazenados de forma privada
                                no servidor e podem ser baixados posteriormente.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="border-b border-slate-800 px-6 py-4">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                            stroke="currentColor"
                            class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.5-4.5m2.25-5.25a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                        </svg>
                        <input type="text" id="searchFiles" placeholder="Pesquisar arquivos..."
                            class="w-full rounded-xl border border-slate-800 bg-slate-950 py-3 pl-10 pr-4 text-sm text-slate-200 outline-none transition placeholder:text-slate-600 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20">
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 shadow-xl shadow-black/10">
                    <div class="flex flex-col gap-3 border-b border-slate-800 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-white">
                                Arquivos armazenados
                            </h2>
                            <p class="mt-1 text-sm text-slate-400">
                                Arquivos disponíveis para download.
                            </p>
                        </div>
                        <div class="inline-flex w-fit items-center gap-2 rounded-lg bg-slate-800 px-3 py-2 text-xs text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.7" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            {{ count($files) }}
                        </div>
                    </div>

                    @if (count($files) > 0)
                        <div class="divide-y divide-slate-800">
                            @foreach ($files as $file)
                                @php
                                    $fileType = getFileType($file);

                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                @endphp

                                <div class="file-item group flex flex-col gap-4 px-6 py-5 transition hover:bg-slate-800/40 sm:flex-row sm:items-center sm:justify-between"
                                    data-filename="{{ strtolower($file) }}">
                                    <div class="flex min-w-0 items-center gap-4">

                                        <div @class([
                                            'flex h-12 w-12 shrink-0 items-center justify-center rounded-xl transition',

                                            'bg-red-500/10 text-red-400' => $fileType === 'pdf',

                                            'bg-purple-500/10 text-purple-400' => $fileType === 'image',

                                            'bg-blue-500/10 text-blue-400' => $fileType === 'word',

                                            'bg-green-500/10 text-green-400' => $fileType === 'excel',

                                            'bg-orange-500/10 text-orange-400' => $fileType === 'powerpoint',

                                            'bg-yellow-500/10 text-yellow-400' => $fileType === 'archive',

                                            'bg-cyan-500/10 text-cyan-400' => $fileType === 'audio',

                                            'bg-pink-500/10 text-pink-400' => $fileType === 'video',

                                            'bg-amber-500/10 text-amber-400' => $fileType === 'text',

                                            'bg-violet-500/10 text-violet-400' => $fileType === 'code',

                                            'bg-slate-800 text-slate-400' => $fileType === 'file',
                                        ])>

                                            @if ($fileType === 'pdf')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
                                                    class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m0 0H6.375A3.375 3.375 0 003 5.625v12.75A3.375 3.375 0 006.375 21.75h8.25A3.375 3.375 0 0018 18.375V15" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M7.5 15.75h1.5a1.5 1.5 0 000-3H7.5v3zm4.5-3v3m0 0h1.125a1.5 1.5 0 000-3H12v3zm3.375 0h1.125" />
                                                </svg>
                                            @elseif ($fileType === 'image')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
                                                    class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 15.75l4.5-4.5a2.25 2.25 0 013.182 0l3.318 3.318m0 0l1.318-1.318a2.25 2.25 0 013.182 0l3.318 3.318M3.75 19.5h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8.25 8.25h.008v.008H8.25V8.25z" />
                                                </svg>
                                            @elseif ($fileType === 'word')
                                                <span class="text-sm font-black">
                                                    W
                                                </span>
                                            @elseif ($fileType === 'excel')
                                                <span class="text-sm font-black">
                                                    X
                                                </span>
                                            @elseif ($fileType === 'powerpoint')
                                                <span class="text-sm font-black">
                                                    P
                                                </span>
                                            @elseif ($fileType === 'archive')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
                                                    class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M20.25 7.5l-8.25-4.5-8.25 4.5m16.5 0v9l-8.25 4.5m8.25-13.5l-8.25 4.5m0 9V12m0 9L3.75 16.5v-9m8.25 4.5l-8.25-4.5" />
                                                </svg>
                                            @elseif ($fileType === 'audio')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
                                                    class="h-6 w-6">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 9l10.5-3.75v10.5L9 19.5V9z" />

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 9v10.5A2.25 2.25 0 016.75 21.75 2.25 2.25 0 014.5 19.5a2.25 2.25 0 012.25-2.25H9z" />

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 15.75A2.25 2.25 0 0017.25 18H19.5a2.25 2.25 0 002.25-2.25 2.25 2.25 0 00-2.25-2.25h-1.125" />
                                                </svg>
                                            @elseif ($fileType === 'video')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
                                                    class="h-6 w-6">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.75 10.5l4.5-2.25v7.5l-4.5-2.25M4.5 6.75h9A2.25 2.25 0 0115.75 9v6a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 012.25 15V9A2.25 2.25 0 014.5 6.75z" />
                                                </svg>
                                            @elseif ($fileType === 'text')
                                                <span class="text-lg font-bold">
                                                    TXT
                                                </span>
                                            @elseif ($fileType === 'code')
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
                                                    class="h-6 w-6">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8.25 9.75L4.5 12l3.75 2.25M15.75 9.75L19.5 12l-3.75 2.25M13.5 6.75l-3 10.5" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"
                                                    class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 0H6.375A3.375 3.375 0 003 5.625v12.75A3.375 3.375 0 006.375 21.75h8.25A3.375 3.375 0 0018 18.375V15" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-medium text-slate-200" title="{{ $file }}">
                                                {{ \Illuminate\Support\Str::limit($file, 45, '...') }}
                                            </p>
                                            <div class="mt-1 flex items-center gap-2 text-xs text-slate-500">
                                                @if ($extension)
                                                    <span class="rounded-md bg-slate-800 px-2 py-0.5 uppercase">
                                                        {{ $extension }}
                                                    </span>
                                                @endif
                                                <span>
                                                    Arquivo armazenado
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex shrink-0 items-center justify-end gap-2">
                                        <a href="{{ route('files.download', ['filename' => $file]) }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm font-medium text-slate-200 transition hover:border-violet-500/50 hover:bg-violet-500/10 hover:text-violet-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 3.75v10.5m0 0l-4.5-4.5m4.5 4.5l4.5-4.5M5.25 20.25h13.5" />
                                            </svg>
                                            Baixar
                                        </a>
                                        <form action="{{ route('file.destroy', ['filename' => $file]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este arquivo? {{ $file }}');" >
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-2.5 text-sm font-medium text-red-400 transition hover:border-red-500/40 hover:bg-red-500/20 hover:text-red-300" >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4" >
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12m-10.5 0v10.125A2.375 2.375 0 009.875 20h4.25a2.375 2.375 0 002.375-2.375V7.5m-6.75 0V5.625A1.125 1.125 0 0110.875 4.5h2.25a1.125 1.125 0 011.125 1.125V7.5m-7.5 0h9" />
                                                </svg> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-16 text-center">
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-800 text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 12.75V7.5A2.25 2.25 0 014.5 5.25h4.379a2.25 2.25 0 011.591.659l1.121 1.121a2.25 2.25 0 001.591.659H19.5a2.25 2.25 0 012.25 2.25v2.811" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 19.5h16.5" />
                                </svg>
                            </div>
                            <h3 class="mt-5 text-sm font-semibold text-slate-200">
                                Nenhum arquivo encontrado
                            </h3>
                            <p class="mx-auto mt-2 max-w-sm text-sm text-slate-500">
                                Os arquivos enviados aparecerão aqui e poderão
                                ser baixados posteriormente.
                            </p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
        <footer class="mt-10 text-center">
            <p class="text-xs text-slate-600">
                Armazenamento local • Upload máximo de 20 MB
            </p>
        </footer>
    </div>

    <script>
        // ============================================================
        // ELEMENTOS
        // ============================================================

        const fileInput = document.getElementById('file');
        const fileText = document.getElementById('fileText');
        const selectedFiles = document.getElementById('selectedFiles');
        const uploadButton = document.getElementById('uploadButton');


        // ============================================================
        // ARMAZENA OS ARQUIVOS SELECIONADOS
        // ============================================================

        let selectedFileList = [];


        // ============================================================
        // SELEÇÃO DE ARQUIVOS
        // ============================================================

        fileInput.addEventListener('change', function() {

            const newFiles = Array.from(this.files);

            // Adiciona os novos arquivos à lista
            newFiles.forEach(function(file) {

                // Evita adicionar o mesmo arquivo duas vezes
                const alreadyExists = selectedFileList.some(function(existingFile) {

                    return (
                        existingFile.name === file.name &&
                        existingFile.size === file.size &&
                        existingFile.lastModified === file.lastModified
                    );

                });

                if (!alreadyExists) {
                    selectedFileList.push(file);
                }

            });

            atualizarArquivos();

        });


        // ============================================================
        // ATUALIZA A LISTA VISUAL
        // ============================================================

        function atualizarArquivos() {

            selectedFiles.innerHTML = '';


            // ========================================================
            // NENHUM ARQUIVO
            // ========================================================

            if (selectedFileList.length === 0) {

                selectedFiles.classList.add('hidden');

                fileText.textContent =
                    'Clique para selecionar arquivos';

                uploadButton.innerHTML = `
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.8"
                        stroke="currentColor"
                        class="h-5 w-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 16.5V3.75m0 0L7.5 8.25M12 3.75l4.5 4.5M6.75 15v2.25A2.25 2.25 0 009 19.5h6a2.25 2.25 0 002.25-2.25V15"
                        />
                    </svg>

                    Enviar arquivos
                `;

                atualizarInput();

                return;
            }


            // ========================================================
            // ATUALIZA TEXTO
            // ========================================================

            fileText.textContent =
                `${selectedFileList.length} arquivo(s) selecionado(s)`;


            selectedFiles.classList.remove('hidden');


            // ========================================================
            // CRIA A LISTA DE ARQUIVOS
            // ========================================================

            selectedFileList.forEach(function(file, index) {

                const fileElement = document.createElement('div');

                fileElement.className =
                    'flex items-center gap-3 rounded-xl border border-slate-800 bg-slate-950 p-4';


                fileElement.innerHTML = `

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-violet-500/10 text-violet-400">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.7"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 0H6.375A3.375 3.375 0 003 5.625v12.75A3.375 3.375 0 006.375 21.75h8.25A3.375 3.375 0 0018 18.375V15"
                            />

                        </svg>

                    </div>


                    <div class="min-w-0 flex-1">

                        <p
                            class="truncate text-sm font-medium text-slate-200"
                            title="${file.name}"
                        >
                            ${file.name}
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            ${formatFileSize(file.size)}
                        </p>

                    </div>


                    <button
                        type="button"
                        class="remove-file flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-500 transition hover:bg-red-500/10 hover:text-red-400"
                        data-index="${index}"
                        title="Remover arquivo"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />

                        </svg>

                    </button>

                `;
                selectedFiles.appendChild(fileElement);
            });


            // ========================================================
            // BOTÕES DE REMOVER
            // ========================================================

            document
                .querySelectorAll('.remove-file')
                .forEach(function(button) {

                    button.addEventListener('click', function() {

                        const index = Number(
                            this.dataset.index
                        );

                        removerArquivo(index);

                    });

                });


            // ========================================================
            // ATUALIZA O INPUT
            // ========================================================

            atualizarInput();


            // ========================================================
            // ATUALIZA O BOTÃO
            // ========================================================

            uploadButton.innerHTML = `

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                    class="h-5 w-5"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 16.5V3.75m0 0L7.5 8.25M12 3.75l4.5 4.5M6.75 15v2.25A2.25 2.25 0 009 19.5h6a2.25 2.25 0 002.25-2.25V15"
                    />

                </svg>

                Enviar ${selectedFileList.length} arquivo(s)

            `;
        }


        // ============================================================
        // REMOVE UM ARQUIVO
        // ============================================================

        function removerArquivo(index) {
            selectedFileList.splice(index, 1);
            atualizarArquivos();
        }


        // ============================================================
        // ATUALIZA O FILE INPUT
        // ============================================================

        function atualizarInput() {
            const dataTransfer = new DataTransfer();
            selectedFileList.forEach(function(file) {
                dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;
        }


        // ============================================================
        // FORMATA TAMANHO DO ARQUIVO
        // ============================================================

        function formatFileSize(bytes) {

            if (bytes === 0) {
                return '0 Bytes';
            }

            const units = [
                'Bytes',
                'KB',
                'MB',
                'GB'
            ];

            const index = Math.floor(
                Math.log(bytes) / Math.log(1024)
            );

            const size =
                bytes / Math.pow(1024, index);

            return `${size.toFixed(2)} ${units[index]}`;
        }


        // ============================================================
        // PESQUISA DE ARQUIVOS
        // ============================================================

        const searchFiles = document.getElementById('searchFiles');
        const fileItems = document.querySelectorAll('.file-item');

        if (searchFiles) {

            searchFiles.addEventListener('input', function () {

                const search = this.value
                    .toLowerCase()
                    .trim();

                fileItems.forEach(function (item) {

                    const filename = item.dataset.filename || '';

                    if (filename.includes(search)) {

                        item.classList.remove('hidden');

                    } else {

                        item.classList.add('hidden');

                    }

                });

            });

        }
    </script>
</body>
</html>
