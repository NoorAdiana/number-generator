<?php

namespace Inisiatif\NumberGenerator\Traits;

use Inisiatif\NumberGenerator\Models\Generator;
use Inisiatif\NumberGenerator\Exceptions\NumberGeneratorException;

trait ModelHasNumberGenerate
{

    public static function bootModelHasNumberGenerate()
    {
        static::creating(function ($model) {

            static::beforeInsert($model);

            $generator = static::findOrCreateGenerator($model);
            $model->attributes[$model->getNumberGeneratorAttribute()] = $generator->getNumberSequence();
            $generator->increment('sequence');
        }, 0);
    }

    private static function findOrCreateGenerator($model) 
    {
        $dt = new \DateTime();

        $generator = Generator::where([
            ['code', '=', $model->getNumberGeneratorCode()],
            ['year', '=', $dt->format('y')],
            ['month', '=', $dt->format('m')],
            ['day', '=', $dt->format('d')]
        ])->first();

        if($generator){
            return $generator;
        }

        return Generator::updateOrCreate(['code' => $model->getNumberGeneratorCode()], [
            'year' => $dt->format('y'), 
            'month' => $dt->format('m'),
            'day' => $dt->format('d'),
            'sequence' => 1,
        ]);
    }

    private static function beforeInsert($model)
    {
        if(!method_exists($model, 'getNumberGeneratorCode')){
            throw new NumberGeneratorException('Canot find getNumberGeneratorCode method');
        }

        if(!method_exists($model, 'getNumberGeneratorAttribute')){
            throw new NumberGeneratorException('Canot find getNumberGeneratorAttribute method');
        }
    }

    protected function getNumberGeneratorCode()
    {
        return get_class($this);
    }
}