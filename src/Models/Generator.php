<?php

namespace Inisiatif\NumberGenerator\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generator extends Model
{
    use SoftDeletes;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'year', 'month', 'day', 'sequence'
    ];

    public function getNumberSequence()
    {
        return $this->year . $this->month . $this->day . $this->getSequence();
    }

    public function getSequence()
    {
        return substr('0000', 0, -strlen($this->sequence)) . $this->sequence;
    }
}