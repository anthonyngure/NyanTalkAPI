<?php
    
    namespace App;
    
    use App\Traits\HasUuid;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    
    /**
 * App\Rating
 *
 * @property int                 $id
 * @property string              $user_id
 * @property string              $ticket_id
 * @property string              $text
 * @property int                 $stars
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rating whereStars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rating whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rating whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rating whereUserId($value)
 * @mixin \Eloquent
 * @property string|null         $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Rating whereDeletedAt($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Rating onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Rating withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Rating withoutTrashed()
 */
    class Rating extends Model
    {
        //
        use HasUuid, SoftDeletes;
        public $incrementing = false;
        protected $fillable = ['user_id', 'stars', 'text'];
        protected $hidden = ['user_id', 'ticket_id'];
        protected $casts = [
            'stars' => 'integer',
        ];
        
    }
