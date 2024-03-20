<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Media\Interfaces\HasMedia;
use App\Media\Traits\InteractsWithMedia;
use OCMS\Repository\Contracts\Transformable;
use OCMS\Repository\Traits\TransformableTrait;

/**
 * Class Post.
 *
 * @package namespace App\Models;
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property bool $publish
 * @property int|null $created_by
 * @property int|null $category_id
 * @property string|null $featured_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read mixed $image_url
 * @property-read mixed $short_description
 * @method static \Illuminate\Database\Eloquent\Builder|Post draft()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post published()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereFeaturedImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Post extends Model implements Transformable, HasMedia
{
    use TransformableTrait, InteractsWithMedia;
    protected $table = 'blog_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'title',
        'content',
        'seo_title',
        'seo_description',
        'publish',
        'created_by',
        'category_id',
        'featured_image'
    ];

    protected $casts = [
        'publish' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'image_url',
        'short_description'
    ];

    public function registerMediaSavePath(): void
    {
        $this
        ->setMediaSavePath('post')
        ->useFallbackUrl(rtrim(config('app.url', 'http://localhost'), '/').'/images/default-theme.png');
    }

    public function getImageUrlAttribute()
    {
        return $this->getMediaUrl('featured_image');
    }

    public function getShortDescriptionAttribute()
    {
        if(!$this->content) {
            return '';
        }

        $no_html = strip_tags($this->content);
        $limit = 0;

        if(strlen($no_html) >= 199) {
            $limit = 199;
        }

        if(strlen($no_html) >= 99) {
            $limit = 99;

        }
        return substr($no_html, 0, $limit).'...';
    }

    public function category()
    {
      return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function scopePublished($q)
    {
        return $q->where('publish', true);
    }

    public function scopeDraft($q)
    {
        return $q->where('publish', false);
    }
}
