<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
'key',
'value',
'type',
'group',
    ];

    #[Scope()]

    protected function group(Builder $query, string $group){
        $query->where("group",$group);
    }

    public static function get($key,$default=null){
        $setting=static::where("key",$key)->first();
        if(!$setting){
            return $default;
        }
        return static::castValue($setting->value,$setting->type);
    }

    protected static function castValue($value, $type){
        return match ($type){
            'boolean'=>filter_var($value,FILTER_VALIDATE_BOOLEAN),
            'number'=>is_numeric($value) ? (float)$value:$value,
            'json'=>json_decode(json_decode($value),true),

        };
    }
}

// 
