<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * Indica os atributos para definição de dados em massa
     *
     * @var array
     */
    protected $fillable = ['name', 'birth', 'gender', 'classroom_id'];

//    /**
//     * Define atributos não mostrados depois da serialização
//     *
//     * @var array
//     */
//    protected $hidden = ['created_at', 'updated_at'];
//
//    /**
//     * Define atributos visíveis depois da serialização
//     *
//     * @var array
//     */
//    //protected $visible = ['name', 'birth', 'gender', 'classroom_id', 'is_accepted'];
//
//    /**
//     * Faz conversão na hora da serialização
//     *
//     * @var array
//     */
//    protected $casts = [
//        'birth' => 'date:d/m/Y'
//    ];
//
//    /**
//     * Define atributos dinâmicos anexos a serialização
//     *
//     * @var array
//     */
//    protected $appends = ['is_accepted'];

    /**
     * mapeamento do relacionamento com salas de aula
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo('App\Models\Classroom');
    }

//    /**
//     * Cria um assessor no model de estudante chamado is_accepted
//     *
//     * @return string
//     */
//    public function getIsAcceptedAttribute()
//    {
//        return $this->attributes['birth'] > '2002-01-01' ? 'aceito' : 'não foi aceito';
//    }
}
