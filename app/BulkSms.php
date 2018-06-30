<?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    
    /**
 * App\BulkSms
 *
 * @mixin \Eloquent
 * @property-read \App\User $createdBy
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\BulkSms onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\BulkSms withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\BulkSms withoutTrashed()
 * @property int $id
 * @property string $text
 * @property int|null $created_by_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkSms whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkSms whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkSms whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkSms whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkSms whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BulkSms whereUpdatedAt($value)
 */
    class BulkSms extends Model
    {
        //
        use SoftDeletes;
        protected $fillable = ['text', 'created_by_id'];
        
        const ALLOWED_INCLUDES = ['created-by'];
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function createdBy()
        {
            return $this->belongsTo(User::class, 'created_by_id');
        }
    }
