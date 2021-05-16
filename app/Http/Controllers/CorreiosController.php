<?php

namespace App\Http\Controllers;

use Cagartner\CorreiosConsulta\CorreiosConsulta;
use Illuminate\Http\Request;

class CorreiosController extends Controller
{
    public function cep(Request $request) {
        $correios = new CorreiosConsulta;
        $req_cep = $request->input('cep');
        $cep = $correios->cep($req_cep);

        echo json_encode($cep);
        return;
    }
}
