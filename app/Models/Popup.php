<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Popup
 *
 * @property int $id
 * @property string $name
 * @property string|null $content
 * @property int $order
 * @property string|null $position_x
 * @property string|null $position_y
 * @property string|null $width
 * @property string|null $height
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Popup activated()
 * @method static \Illuminate\Database\Eloquent\Builder|Popup deactivated()
 * @method static \Illuminate\Database\Eloquent\Builder|Popup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Popup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Popup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Popup whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup wherePositionX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup wherePositionY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Popup whereWidth($value)
 * @mixin \Eloquent
 */
class Popup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'content',
        'position_x',
        'position_y',
        'width',
        'height',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function scopeActivated($q)
    {
        return $q->where('active', true);
    }

    public function scopeDeactivated($q)
    {
        return $q->where('active', false);
    }
}
