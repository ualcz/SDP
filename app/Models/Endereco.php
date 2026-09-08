<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $table = 'enderecos';

    protected $fillable = [
        'usuario_id',
        'rua',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'cep',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Retorna o endereço formatado de maneira legível por extenso.
     */
    public function getFormatadoAttribute(): string
    {
        $partes = [];

        if (!empty($this->rua)) {
            $linhaRua = $this->rua;
            if (!empty($this->numero) && !str_contains($this->rua, $this->numero)) {
                $linhaRua .= ', ' . $this->numero;
            }
            $partes[] = $linhaRua;
        } elseif (!empty($this->numero)) {
            $partes[] = $this->numero;
        }

        if (!empty($this->bairro)) {
            $partes[] = $this->bairro;
        }

        $cidadeEstado = [];
        if (!empty($this->cidade)) {
            $cidadeEstado[] = $this->cidade;
        }
        if (!empty($this->estado)) {
            $cidadeEstado[] = strtoupper($this->estado);
        }

        if (!empty($cidadeEstado)) {
            $partes[] = implode(' - ', $cidadeEstado);
        }

        if (!empty($this->cep)) {
            $partes[] = 'CEP: ' . $this->cep;
        }

        return !empty($partes) ? implode(', ', $partes) : '';
    }

    /**
     * Converte o modelo em string automaticamente como endereço formatado.
     */
    public function __toString(): string
    {
        return $this->formatado;
    }
}
