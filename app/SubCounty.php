<?php

namespace App;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\SubCounty
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SubCounty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SubCounty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SubCounty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SubCounty whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ward[] $wards
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SubCounty whereDeletedAt($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\SubCounty onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\SubCounty withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\SubCounty withoutTrashed()
 */
class SubCounty extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at','deleted_at'];
    
    const ALLOWED_INCLUDES = ['wards'];
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
