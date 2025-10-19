<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ProjectChatLogger
{
    /**
     * Registra un mensaje en el chat de un proyecto.
     *
     * @param  int    $projectId  ID del proyecto
     * @param  string $message    Texto del mensaje a guardar
     * @param  int|null $userId   ID del usuario (opcional, usa el actual si no se pasa)
     * @return \App\Models\Chat
     */
    public static function log(int $projectId, string $message, ?int $userId = null)
    {
        $userId = $userId ?? Auth::id();

        return Chat::create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'message' => $message,
        ]);
    }

    public static function system(int $projectId, string $message)
    {
        return self::log($projectId, '🤖 ' . $message, null);
    }
}
