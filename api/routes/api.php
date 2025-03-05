<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\DB;

Route::post('/schools', [SchoolController::class, 'store']);

Route::get('/test-db-connection', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['success' => true, 'message' => 'Conexão com o banco de dados estabelecida com sucesso!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()], 500);
    }
});
