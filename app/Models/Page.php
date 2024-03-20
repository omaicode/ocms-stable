<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslation;
use App\Media\Interfaces\HasMedia;
use App\Media\Traits\InteractsWithMedia;
use App\Enums\ContentTypeEnum;
use App\Enums\PageStatusEnum;

/**
 * App\Models\Page
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string|null $content
 * @property ContentTypeEnum $content_type
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property int $template
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $featured_image
 * @property-read mixed $image_url
 * @property-read mixed $title
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Translate> $translates
 * @property-read int|null $translates_count
 * @method static \Illuminate\Database\Eloquent\Builder|Page draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page published()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereFeaturedImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Page extends Model implements HasMedia
{
    use InteractsWithMedia, HasTranslation;

    public $translatable = ['name', 'content', 'seo_title', 'seo_description'];

    protected $fillable = [
        'slug',
        'name',
        'content',
        'content_type',
        'template',
        'seo_title',
        'seo_description',
        'status',
        'featured_image'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'content_type' => ContentTypeEnum::class
    ];

    protected $appends = [
        'title',
        'image_url'
    ];

    public function registerMediaSavePath(): void
    {
        $this->setMediaSavePath('page')->useFallbackUrl('/images/default-placeholder.png');
    }

    public function getTitleAttribute()
    {
        return $this->name;
    }

    public function getImageUrlAttribute()
    {
        return $this->getMediaUrl('featured_image');
    }


    public function scopePublished($q)
    {
        return $q->where('status', PageStatusEnum::PUBLISH);
    }

    public function scopeDraft($q)
    {
        return $q->where('status', PageStatusEnum::DRAFT);
    }    
}
