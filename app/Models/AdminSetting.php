<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AdminSetting
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AdminSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminSetting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminSetting whereValue($value)
 * @mixin \Eloquent
 */
class AdminSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description'
    ];
}
