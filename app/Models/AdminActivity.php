<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ActivityActionEnum;

/**
 * App\Models\AdminActivity
 *
 * @property int $id
 * @property int $admin_id
 * @property ActivityActionEnum $action
 * @property string $agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $ip_address
 * @property-read \App\Models\Admin $admin
 * @property-read mixed $action_text
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity whereAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminActivity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdminActivity extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'agent',
        'ip_address'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'action'     => ActivityActionEnum::class
    ];

    protected $appends = [
        'action_text'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id')->select([
            'id',
            'name'
        ]);
    }

    public function getActionTextAttribute()
    {
        if(!$this->action) {
            return 'Unknown';
        }

        return $this->action->getText($this->admin);
    }
}
