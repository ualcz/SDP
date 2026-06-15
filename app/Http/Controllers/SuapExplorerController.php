<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SuapExplorerController extends Controller
{
    public function index()
    {
        return view('admin.suap-explorer');
    }

    public function consultar(Request $request)
    {
        $request->validate([
            'endpoint' => 'required'
        ]);

        $jwt = session('suap_jwt');

        if (!$jwt) {
            return back()->withErrors([
                'erro' => 'JWT não encontrado na sessão.'
            ]);
        }

        try {

            $response = Http::withHeaders([
                'Authorization' => 'JWT '.$jwt,
                'Content-Type' => 'application/json',
            ])
            ->withOptions([
                'verify' => false,
            ])
            ->get(
                'https://suap.ifba.edu.br/api/v2/' .
                ltrim($request->endpoint, '/')
            );

            return view('admin.suap-explorer', [

                'endpoint' => $request->endpoint,

                'resultado' => $response->json(),

                'status' => $response->status()
            ]);

        } catch (\Exception $e) {

            return view('admin.suap-explorer', [

                'endpoint' => $request->endpoint,

                'resultado' => [
                    'erro' => $e->getMessage()
                ],

                'status' => 500
            ]);
        }
    }
}
?>