<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\MenuPositionEnum;
use App\Traits\HasTranslation;
use OCMS\Repository\Contracts\Transformable;
use OCMS\Repository\Traits\TransformableTrait;

/**
 * Class Menu.
 *
 * @package namespace App\Models;
 * @property int $id
 * @property int|null $parent_id
 * @property string $url
 * @property string $name
 * @property string|null $icon
 * @property MenuPositionEnum $position
 * @property int $order
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $template
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Menu> $childs
 * @property-read int|null $childs_count
 * @property-read mixed $full_url
 * @property-read Menu|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translate> $translates
 * @property-read int|null $translates_count
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUrl($value)
 * @mixin \Eloquent
 */
class Menu extends Model implements Transformable
{
    use TransformableTrait, HasTranslation;

    protected $table  = 'appearance_menus';

    public $translatable = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'name',
        'parent_id',
        'icon',
        'active',
        'position',
        'order',
        'template'
    ];


    protected $casts = [
        'active'      => 'boolean',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'position'    => MenuPositionEnum::class
    ];

    protected $appends = [
        'full_url'
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany(Menu::class, 'parent_id')->with('childs')->orderBy('order', 'ASC');
    }

    public function getFullUrlAttribute()
    {
        if(filter_var($this->url, FILTER_VALIDATE_URL)) {
            return $this->url;
        } else {
            return rtrim(config('app.url', 'http://localhost'), '/').'/'.ltrim($this->url, '/');
        }
    }
}
