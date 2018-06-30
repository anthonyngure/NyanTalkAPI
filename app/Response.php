<?php
    
    namespace App;
    
    use App\Traits\HasUuid;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    /**
 * App\Response
 *
 * @property int                 $id
 * @property string              $ticket_id
 * @property string              $official_id
 * @property string              $details
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereOfficialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\User      $official
 * @property string|null         $user_id
 * @property string|null         $deleted_at
 * @property-read \App\User|null $citizen
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Response whereUserId($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Response onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Response withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Response withoutTrashed()
 */
    class Response extends Model
    {
        //
        use HasUuid, SoftDeletes;
        public $incrementing = false;
        
        protected $hidden = ['official_id', 'ticket_id'];
        protected $fillable = ['details', 'user_id'];
        
        const ALLOWED_INCLUDES = ['official', 'citizen','official.department','citizen.ward.sub-county'];
        
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function official()
        {
            return $this->belongsTo(User::class, 'user_id')
                ->where('type', User::TYPE_OFFICIAL);
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function citizen()
        {
            return $this->belongsTo(User::class, 'user_id')
                ->where('type', User::TYPE_CITIZEN);
        }
    }
