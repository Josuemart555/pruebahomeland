<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Producto
 * @package App\Models
 * @version November 20, 2022, 10:16 am CST
 *
 * @property string $codigo
 * @property string $nombre
 * @property integer $cantidad
 * @property number $precio
 * @property string $fecha_ingresa
 * @property string $fehca_vencimiento
 */
class Producto extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'productos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'codigo',
        'nombre',
        'cantidad',
        'precio',
        'fecha_ingresa',
        'fehca_vencimiento'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo' => 'string',
        'nombre' => 'string',
        'cantidad' => 'integer',
        'precio' => 'decimal:2',
        'fecha_ingresa' => 'date',
        'fehca_vencimiento' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo' => 'required|string|max:255',
        'nombre' => 'required|string|max:255',
        'cantidad' => 'required|integer',
        'precio' => 'required|numeric',
        'fecha_ingresa' => 'required',
        'fehca_vencimiento' => 'required',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
