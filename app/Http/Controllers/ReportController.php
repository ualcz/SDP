<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ReportRecipient;

class ReportController extends Controller
{
    public function report()
    {
        return view('emails.report');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mensagem' => 'required|string|min:5',
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $destinatarios = ReportRecipient::where('is_active', true)
            ->with('usuario')
            ->get()
            ->pluck('usuario.email')
            ->filter()
            ->toArray();

        if (empty($destinatarios)) {
            $destinatarios = ['20261seaads0004@ifba.edu.br'];
        }


        // Envia o e-mail
        Mail::send([], [], function ($message) use ($validatedData, $request, $destinatarios) {
            $message->to($destinatarios)
                ->subject('🚨 Novo Reporte de Erro / Sugestão no Sistema')
                ->html("
                    <h3>Novo Reporte Recebido</h3>
                    <p><strong>Nome:</strong> {$validatedData['nome']}</p>
                    <p><strong>Email:</strong> {$validatedData['email']}</p>
                    <hr>
                    <p><strong>Mensagem / Descrição:</strong></p>
                    <p>{$validatedData['mensagem']}</p>
                ");

            // Se o usuário anexou uma imagem, adiciona como anexo no e-mail
            if ($request->hasFile('img')) {
                $file = $request->file('img');
                $message->attach($file->getRealPath(), [
                    'as' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                ]);
            }
        });

        return redirect()
            ->route('home')
            ->with('success', 'Relatório enviado por e-mail com sucesso! Agradecemos sua colaboração.');
    }
}
