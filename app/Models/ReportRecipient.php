<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportRecipient extends Model
{
    protected $fillable = [
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Altere de 'user' para 'usuario' para combinar com o with('usuario') do controller
    public function usuario(): BelongsTo
    {
        // Certifique-se de que o nome do Model de usuário é Usuario ou ajuste conforme sua classe
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }
}
