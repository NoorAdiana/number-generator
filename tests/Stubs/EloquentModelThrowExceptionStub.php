<?php

namespace Inisiatif\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\NumberGenerator\Traits\ModelHasNumberGenerate;

class EloquentModelThrowExceptionStub extends Model
{
    use ModelHasNumberGenerate;
    
    protected $table = 'users';

    protected $fillable = ['name'];
    
}