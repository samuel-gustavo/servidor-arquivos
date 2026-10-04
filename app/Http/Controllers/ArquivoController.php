<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;


class ArquivoController extends Controller
{
    // Carraga a tela enviadno a lista de arquivos salvos
    public function index()
    {
        $files = Storage::disk('local')->files('local_arquivos');
        $cleanFiles = array_map(fn($file) => basename($file), $files);

        return view('index', ['files' => $cleanFiles]);
    }

    // Realiza o Upload do arquivo para a pasta 'storage/app/local_arquivos'
    public function upload(Request $request) {
        $nomeArquivo = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                // Se o arquivo for válido, pegamos o nome real dele, senão usamos um padrão
                $nomeArquivo["files.{$key}"] = $file ? $file->getClientOriginalName() : "arquivo (" . ($key + 1) . ")";
            }
        }

        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:20480', // Limite de 20MB por arquivo
        ], [
            // Mensagens para o campo 'files' (o array em si)
            'files.required' => 'Você precisa selecionar pelo menos um arquivo.',
            'files.array'    => 'O formato do envio está inválido.',
            'files.min'      => 'Selecione no mínimo :min arquivo para enviar.',

            // Mensagens para cada arquivo individual dentro do array (files.*)
            'files.*.required' => 'Um dos arquivos selecionados está vazio ou corrompido.',
            'files.*.file'     => 'O item enviado precisa ser um arquivo válido.',
            'files.*.max'      => 'O arquivo (:attribute) não pode ser maior que 20MB.',
        ], $nomeArquivo);

        $files = $request->file('files');

        $uploadedFiles = [];
        $failedFiles = [];

        foreach($files as $file) {

            if(!$file->isValid()) {
                $failedFiles[] = $file->getClientOriginalName();
                continue;
            }

            try {
                $file->storeAs(
                    'local_arquivos',
                    $file->getClientOriginalName(),
                    'local'
                );

                $uploadedFiles[] = $file->getClientOriginalName();
            } catch(\Throwable $e) {
                $failedFiles[] = $file->getClientOriginalName();
            }
        }

        if(!empty($failedFiles)) {
            $mensagem = 'Alguns arquivos não puderam ser enviados: '.implode(', ', $failedFiles);
            return redirect()->route('home')->with('error', $mensagem);
        }
        return redirect()->route('home')->with('sucess', 'Upload realizado com sucesso!');
    }

    // Faz o Download seguro localizando o arquivo de forma privada
    public function download($filename)
    {
        $filename = basename($filename);
        $path = "local_arquivos/{$filename}";
        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Arquivo não encontrado.');
        }
        $fullpath = Storage::disk('local')->path($path);
        return response()->download($fullpath);
    }

    // Exclui um arquivo do servidor local
    public function destroy($filename)
    {
        $filename = basename($filename);
        $path = "local_arquivos/{$filename}";
        if(!Storage::disk('local')->exists($path)) {
            abort(404, 'Arquivo não encontrado.');
        }
        Storage::disk('local')->delete($path);
        return redirect()->route('home')->with('sucess', 'Arquivo excluído com sucesso!');
    }

    // Lista os arquivos salvos no servidor local
    public function list()
    {
        $files = Storage::disk('local')->files('local_arquivos');
        // Formata para trazer apenas os nomes limpos dos arquivos
        $cleanFiles = array_map(fn($file) => basename($file), $files);
        return response()->json(['files' => $cleanFiles]);
    }
}
