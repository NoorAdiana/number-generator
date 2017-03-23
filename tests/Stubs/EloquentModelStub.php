<?php

namespace Inisiatif\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\NumberGenerator\Traits\ModelHasNumberGenerate;

class EloquentModelStub extends Model
{
    use ModelHasNumberGenerate;
    
    protected $table = 'users';

    protected $fillable = ['name', 'number_generated'];

    protected function getNumberGeneratorAttribute()
    {
        return 'number_generated';
    }
}