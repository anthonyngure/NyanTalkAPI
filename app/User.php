<?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Tymon\JWTAuth\Contracts\JWTSubject;
    
    /**
     * App\User
     *
     * @property string
     *                   $id
     * @property string
     *                   $name
     * @property string
     *                   $avatar
     * @property string
     *                   $type
     * @property int|null
     *                   $department_id
     * @property string|null
     *                   $email
     * @property string|null
     *                   $phone
     * @property string|null
     *                   $phone_verification_code
     * @property string|null
     *                   $phone_verification_code_sent_at
     * @property bool
     *                   $phone_verified
     * @property string|null
     *                   $password
     * @property string|null
     *                   $password_recovery_code
     * @property string|null
     *                   $gender
     * @property string|null
     *                   $facebook_id
     * @property string|null
     *                   $facebook_picture_url
     * @property string|null
     *                   $created_by
     * @property string|null
     *                   $deleted_by
     * @property string|null
     *                   $remember_token
     * @property \Carbon\Carbon|null
     *                   $created_at
     * @property \Carbon\Carbon|null
     *                   $updated_at
     * @property string|null
     *                   $deleted_at
     * @property-read mixed
     *                        $admin
     * @property-read mixed
     *                        $all_tickets
     * @property-read mixed
     *                        $closed_tickets
     * @property-read mixed
     *                        $pending_tickets
     * @property-read mixed
     *                        $queued_tickets
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
     *                $notifications
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Response[]
     *                        $responses
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ticket[]
     *                        $tickets
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Topic[]
     *                        $topics
     * @method static bool|null forceDelete()
     * @method static \Illuminate\Database\Query\Builder|\App\User onlyTrashed()
     * @method static bool|null restore()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatar($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDepartmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFacebookId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFacebookPictureUrl($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGender($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePasswordRecoveryCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoneVerificationCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoneVerificationCodeSentAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoneVerified($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
     * @method static \Illuminate\Database\Query\Builder|\App\User withTrashed()
     * @method static \Illuminate\Database\Query\Builder|\App\User withoutTrashed()
     * @mixin \Eloquent
     * @property bool
     *                   $notifiable
     * @property bool
     *                   $deletable
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNotifiable($value)
     * @property bool
     *                   $email_notifiable
     * @property bool
     *                   $sms_notifiable
     * @property int|null
     *                   $created_by_id
     * @property int|null
     *                   $deleted_by_id
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedById($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedById($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailNotifiable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSmsNotifiable($value)
     * @property int|null            $ward_id
     * @property-read \App\Ward|null $ward
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereWardId($value)
     */
    class User extends Authenticatable implements JWTSubject
    {
        use Notifiable, SoftDeletes;
        
        protected $connection = 'pgsql';
        
        protected $casts = [
            'tickets_count'         => 'integer',
            'closed_tickets_count'  => 'integer',
            'queued_tickets_count'  => 'integer',
            'pending_tickets_count' => 'integer',
        ];
        
        const COUNTS = ['tickets'];
        const ALLOWED_INCLUDES = ['tickets'];
        
        const TYPE_CITIZEN = 'CITIZEN';
        const TYPE_OFFICIAL = 'OFFICIAL';
        const TYPE_DEPARTMENT_ADMIN = 'DEPARTMENT_ADMIN';
        const TYPE_CS = 'CS';
        const TYPE_ADMIN = 'ADMIN';
        
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'name', 'email', 'password', 'phone', 'type', 'sms_notifiable', 'email_notifiable',
            'facebook_id', 'facebook_picture_url', 'department_id',
        ];
        
        protected $appends = ['allTickets', 'queuedTickets', 'pendingTickets', 'closedTickets'];
        
        /**
         * The attributes that should be hidden for arrays.
         *
         * @var array
         */
        protected $hidden = [
            'password',
            'remember_token',
            'phone_verification_code',
            'phone_verification_code_sent_at',
            'email_verification_code',
            'email_verification_code_issued_at',
            'password_recovery_code',
            'pivot',
            'email_notifiable',
            'deletable',
        ];
        
        /**
         * Get the identifier that will be stored in the subject claim of the JWT.
         *
         * @return mixed
         */
        public function getJWTIdentifier()
        {
            return $this->id;
        }
        
        /**
         * Return a code value array, containing any custom claims to be added to the JWT.
         *
         * @return array
         */
        public function getJWTCustomClaims()
        {
            
            return [
                'id'  => $this->getKey(),
                'iss',
                'iat' => now()->getTimestamp(),
                'exp',
                'nbf',
                'sub' => $this->getKey(),
                'jti',
            ];
        }
        
        public function isAdmin()
        {
            return $this->type == 'ADMIN';
        }
        
        public function isOfficial()
        {
            return $this->type == 'OFFICIAL';
        }
        
        public function isCitizen()
        {
            return $this->type == 'CITIZEN';
        }
        
        public function isDepartmentAdmin(){
            return $this->type == self::TYPE_DEPARTMENT_ADMIN;
        }
    
        public function isCS(){
            return $this->type == self::TYPE_CS;
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function tickets()
        {
            return $this->hasMany(Ticket::class, $this->isCitizen() ? 'citizen_id' : 'assigned_official_id');
        }
        
        public function routeNotificationForSMS()
        {
            return $this->phone;
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function topics()
        {
            return $this->hasMany(Topic::class);
        }
        
        
        public function getAllTicketsAttribute()
        {
            if ($this->isAdmin()) {
                return Ticket::whereKeyNot(0)->count(['id']);
            } else {
                return $this->tickets()->count();
            }
            
            //return Ticket::whereKeyNot(0)->count();
        }
        
        
        public function getQueuedTicketsAttribute()
        {
            if ($this->isAdmin()) {
                return Ticket::whereStatus(Ticket::STATUS_STARTED)->count();
            } else {
                return $this->tickets()->where('status', Ticket::STATUS_STARTED)->count();
            }
        }
        
        public function getPendingTicketsAttribute()
        {
            if ($this->isAdmin()) {
                return Ticket::whereStatus(Ticket::STATUS_PENDING_ASSIGNMENT)->count();
            } else {
                return $this->tickets()->where('status', Ticket::STATUS_PENDING_ASSIGNMENT)->count();
            }
        }
        
        public function getClosedTicketsAttribute()
        {
            if ($this->isAdmin()) {
                return Ticket::whereStatus(Ticket::STATUS_COMPLETED)->count();
            } else {
                return $this->tickets()->where('status', Ticket::STATUS_COMPLETED)->count();
            }
        }
        
        
        public function responses()
        {
            return $this->hasMany(Response::class, 'user_id');
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
        public function department()
        {
            return $this->belongsTo(Department::class);
        }
        
        
    }
