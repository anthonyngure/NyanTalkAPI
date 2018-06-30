<?php
    
    namespace App;
    
    use App\Traits\HasUuid;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    
    /**
 * App\Topic
 *
 * @property string                                                            $id
 * @property string                                                            $title
 * @property string                                                            $user_id
 * @property \Carbon\Carbon|null                                               $created_at
 * @property \Carbon\Carbon|null                                               $updated_at
 * @property-read \App\User                                                    $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Contribution[] $contributions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereUserId($value)
 * @mixin \Eloquent
 * @property string                                                            $forum_id
 * @property-read \App\Forum                                                   $forum
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereForumId($value)
 * @property string|null                                                       $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereDescription($value)
 * @property string                                                            $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereStatus($value)
 * @property string|null                                                       $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereDeletedAt($value)
 * @property string|null                                                       $approved_by
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Topic onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereApprovedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Topic withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Topic withoutTrashed()
 * @property string|null                                                       $approved_at
 * @property string|null                                                       $rejected_at
 * @property string|null                                                       $rejected_by
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Topic whereRejectedBy($value)
 * @property-read \App\User|null $approvedBy
 * @property-read \App\User|null $rejectedBy
 */
    class Topic extends Model
    {
        //
        use SoftDeletes;
        
        const ALLOWED_INCLUDES = ['author', 'forum', 'rejected-by', 'approved-by'];
        
        const STATUS_PENDING_APPROVAL = 'PENDING_APPROVAL';
        const STATUS_APPROVED = 'APPROVED';
        const STATUS_REJECTED = 'REJECTED';
        
        protected $fillable = ['title', 'user_id', 'forum_id', 'description'];
        protected $hidden = ['user_id'];
        protected $casts = [
            'contributions_count' => 'integer',
        ];
        
        const COUNTS = ['contributions'];
        
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function author()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function rejectedBy()
        {
            return $this->belongsTo(User::class, 'rejected_by');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function approvedBy()
        {
            return $this->belongsTo(User::class, 'approved_by');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function forum()
        {
            return $this->belongsTo(Forum::class, 'forum_id');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function contributions()
        {
            return $this->hasMany(Contribution::class);
        }
    }
