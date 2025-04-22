<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActionLog;
use Illuminate\Support\Facades\Auth;

class ActionLogController extends Controller
{
    public static function log($action, $description = null)
    {
        ActionLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'description'=> $description,
            'ip_address' => request()->ip(),
        ]);
    }
    // Ejemplo de uso
    /*
        use App\Http\Controllers\ActionLogController; // En el controlador donde quieras registrar la acción

        ActionLogController::log('update post', 'El usuario editó el post con ID 15');
    */
}
