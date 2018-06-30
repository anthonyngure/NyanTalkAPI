<?php
    
    namespace App;
    
    use App\Traits\HasUuid;
    use Illuminate\Database\Eloquent\Model;
    
    /**
 * App\SmsMessage
 *
 * @property int                 $id
 * @property string              $number
 * @property string              $status
 * @property string              $status_code
 * @property string              $message_id
 * @property string              $cost
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsMessage whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsMessage whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsMessage whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsMessage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsMessage whereStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null         $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SmsMessage whereDeletedAt($value)
 */
    class SmsMessage extends Model
    {
        //
        use HasUuid;
        public $incrementing = false;
        protected $guarded = ['id', 'created_at', 'updated_at'];
    }
