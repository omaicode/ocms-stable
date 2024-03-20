<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OCMS\Repository\Contracts\Transformable;
use OCMS\Repository\Traits\TransformableTrait;

/**
 * Class Translate.
 *
 * @package namespace App\Models;
 * @property int $id
 * @property string $language
 * @property int $reference_id
 * @property string $reference_type
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $reference
 * @method static \Illuminate\Database\Eloquent\Builder|Translate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Translate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translate whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translate whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translate whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translate whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Translate extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language',
        'reference_type',
        'reference_id',
        'data'
    ];

    protected $casts = [
        'data' => 'json'
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }    
}
