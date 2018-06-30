<?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    /**
 * App\UssdSession
 *
 * @property string              $id
 * @property string              $phone
 * @property string              $code
 * @property string|null         $status
 * @property string|null         $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereUserId($value)
 * @mixin \Eloquent
 * @property int|null            $selected_sub_county_id
 * @property int|null            $selected_ward_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereSelectedSubCountyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereSelectedWardId($value)
 * @property string|null $selected_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereSelectedName($value)
 * @property int|null $selected_department_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereSelectedDepartmentId($value)
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereDeletedAt($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\UssdSession onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\UssdSession withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\UssdSession withoutTrashed()
 * @property string|null $selected_subject
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UssdSession whereSelectedSubject($value)
 */
    class UssdSession extends Model
    {
        //
        use SoftDeletes;
        public $incrementing = false;
        protected $fillable = ['phone', 'id', 'code', 'user_id'];
        
        const STATUS_SELECTING_SUB_COUNTY = 'SELECTING_SUB_COUNTY';
        const STATUS_SELECTING_WARD = 'SELECTING_WARD';
        const STATUS_SELECTING_DEPARTMENT = 'SELECTING_DEPARTMENT';
        const STATUS_TYPING_TICKET = 'TYPING_TICKET';
        const STATUS_TYPING_NAME = 'TYPING_NAME';
        const STATUS_TYPING_SUBJECT = 'TYPING_SUBJECT';
        
        
        public function isSelectingSubCounty()
        {
            return $this->status == self::STATUS_SELECTING_SUB_COUNTY;
        }
        
        public function isSelectingDepartment()
        {
            return $this->status == self::STATUS_SELECTING_DEPARTMENT;
        }
        
        public function isSelectingWard()
        {
            return $this->status == self::STATUS_SELECTING_WARD;
        }
        
        public function isTypingTicket()
        {
            return $this->status == self::STATUS_TYPING_TICKET;
        }
    
        public function isTypingSubject()
        {
            return $this->status == self::STATUS_TYPING_SUBJECT;
        }
        
        public function isTypingName()
        {
            return $this->status == self::STATUS_TYPING_NAME;
        }
        
        
    }
