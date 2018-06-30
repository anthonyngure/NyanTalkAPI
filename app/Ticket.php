<?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Support\Carbon;
    
    /**
 * App\Ticket
 *
 * @property string                                                        $id
 * @property string|null                                                   $number
 * @property string                                                        $citizen_id
 * @property int|null                                                      $ward_id
 * @property int|null                                                      $department_id
 * @property string|null                                                   $assigned_official_id
 * @property string|null                                                   $share_approved_at
 * @property string|null                                                   $share_approved_by
 * @property string|null                                                   $completed_at
 * @property string|null                                                   $completed_by
 * @property string|null                                                   $started_at
 * @property string|null                                                   $started_by
 * @property string                                                        $subject
 * @property string                                                        $details
 * @property string|null                                                   $share_reason
 * @property string                                                        $status
 * @property \Carbon\Carbon|null                                           $created_at
 * @property \Carbon\Carbon|null                                           $updated_at
 * @property string|null                                                   $deleted_at
 * @property-read \App\User                                                $citizen
 * @property-read \App\Department|null                                     $department
 * @property-read mixed                                                    $is_rated
 * @property-read \App\User|null                                           $official
 * @property-read \App\Rating                                              $rating
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Response[] $responses
 * @property-read \App\Ward|null                                           $ward
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereAssignedOfficialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereCitizenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereCompletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereShareApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereShareApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereShareReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereStartedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereWardId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Ticket withoutTrashed()
 * @mixin \Eloquent
 * @property string|null                                                   $share_requested_at
 * @property-read \App\User                                                $completedBy
 * @property-read \App\User|null                                           $shareApprovedBy
 * @property-read \App\User|null                                           $startedBy
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereShareRequestedAt($value)
 * @property string|null                                                   $official_assigned_at
 * @property string|null                                                   $assigned_by
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereAssignedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereOfficialAssignedAt($value)
 * @property string|null                                                   $assigned_official_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereAssignedOfficialAt($value)
 * @property string                                                        $input_mode
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ticket whereInputMode($value)
 * @property-read mixed $time_taken_to_complete
 * @property-read mixed $time_taken_to_start
 */
    class Ticket extends Model
    {
        use SoftDeletes;
        const STATUS_PENDING_ASSIGNMENT = 'PENDING_ASSIGNMENT';
        const STATUS_PENDING_SHARE_APPROVAL = 'PENDING_SHARE_APPROVAL';
        const STATUS_ASSIGNED = 'ASSIGNED';
        const STATUS_ASSIGNED_DEPARTMENT = 'ASSIGNED_DEPARTMENT';
        const STATUS_STARTED = 'STARTED';
        const STATUS_COMPLETED = 'COMPLETED';
        const INPUT_MODE_CITIZEN = 'CITIZEN';
        const INPUT_MODE_PHONE_CALL = 'PHONE_CALL';
        const INPUT_MODE_WALK_IN = 'WALK_IN';
        
        const ALLOWED_INCLUDES = ['citizen', 'department', 'ward.sub-county', 'official',
            'responses', 'responses.official', 'rating', 'share-approved-by', 'completed-by', 'started-by'];
        
        protected $fillable = ['department_id', 'ward_id', 'subject', 'details', 'number', 'citizen_id'];
        
        protected $hidden = ['department_id', 'ward_id', 'citizen_id', 'assigned_official_id'];
        
        const COUNTS = ['responses'];
        
        protected $appends = ['isRated', 'timeTakenToStart', 'timeTakenToComplete'];
        
        protected $casts = [
            'responses_count' => 'integer',
        ];
        
        
        public function getTimeTakenToStartAttribute()
        {
            if ($this->status == self::STATUS_STARTED) {
                return Carbon::createFromTimeString($this->created_at)->diff(Carbon::createFromTimeString($this->started_at));
            } else {
                return 'Not yet started';
            }
        }
        
        public function getTimeTakenToCompleteAttribute()
        {
            if ($this->status == self::STATUS_COMPLETED) {
                return Carbon::createFromTimeString($this->created_at)->diff(Carbon::createFromTimeString($this->completed_at));
            } else {
                return 'Not yet completed';
            }
        }
        
        public function getIsRatedAttribute()
        {
            return !is_null($this->rating);
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function shareApprovedBy()
        {
            return $this->belongsTo(User::class, 'share_approved_by');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function completedBy()
        {
            return $this->belongsTo(User::class, 'completed_by_id');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function startedBy()
        {
            return $this->belongsTo(User::class, 'started_by_id');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function department()
        {
            return $this->belongsTo(Department::class);
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function ward()
        {
            return $this->belongsTo(Ward::class);
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function citizen()
        {
            return $this->belongsTo(User::class, 'citizen_id');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function official()
        {
            return $this->belongsTo(User::class, 'assigned_official_id');
        }
        
        public function responses()
        {
            return $this->hasMany(Response::class);
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasOne
         */
        public function rating()
        {
            return $this->hasOne(Rating::class);
        }
        
    }
