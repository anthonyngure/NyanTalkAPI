<?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    
    /**
 * App\Ward
 *
 * @property int                 $id
 * @property string              $sub_county_id
 * @property string              $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ward whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ward whereSubCountyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ward whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\SubCounty $subCounty
 * @property string|null         $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ward whereDeletedAt($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Ward onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Ward withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Ward withoutTrashed()
 */
    class Ward extends Model
    {
        use SoftDeletes;
        protected $fillable = ['name'];
        protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function subCounty()
        {
            return $this->belongsTo(SubCounty::class);
        }
        
    }
