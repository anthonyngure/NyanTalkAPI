<?php
    
    namespace App;
    
    use App\Traits\HasUuid;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    
    /**
     * App\Forum
     *
     * @property string                                                     $id
     * @property string                                                     $name
     * @property \Carbon\Carbon|null                                        $created_at
     * @property \Carbon\Carbon|null                                        $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Topic[] $topics
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereUpdatedAt($value)
     * @mixin \Eloquent
     * @property string|null                                                $deleted_at
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereDeletedAt($value)
     * @property string|null                                                $created_by
     * @property string|null                                                $last_edited_by
     * @method static bool|null forceDelete()
     * @method static \Illuminate\Database\Query\Builder|\App\Forum onlyTrashed()
     * @method static bool|null restore()
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereCreatedBy($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereLastEditedBy($value)
     * @method static \Illuminate\Database\Query\Builder|\App\Forum withTrashed()
     * @method static \Illuminate\Database\Query\Builder|\App\Forum withoutTrashed()
     * @property string|null                                                $deleted_by
     * @property-read \App\User|null                                        $createdBy
     * @property-read \App\User|null                                        $deletedBy
     * @property-read \App\User                                             $editedBy
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereDeletedBy($value)
     * @property int|null                                                   $created_by_id
     * @property int|null                                                   $last_edited_by_id
     * @property int|null                                                   $deleted_by_id
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereCreatedById($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereDeletedById($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Forum whereLastEditedById($value)
     */
    class Forum extends Model
    {
        //
        use HasUuid, SoftDeletes;
        public $incrementing = false;
        protected $fillable = ['name'];
        
        const ALLOWED_INCLUDES = ['created-by', 'deleted-by', 'edited-by'];
        
        protected $casts = [
            'topics_count' => 'integer',
        ];
        
        const COUNTS = ['topics'];
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function topics()
        {
            return $this->hasMany(Topic::class);
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function createdBy()
        {
            return $this->belongsTo(User::class, 'created_by_id');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function deletedBy()
        {
            return $this->belongsTo(User::class, 'deleted_by_id');
        }
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function editedBy()
        {
            return $this->belongsTo(User::class, 'edited_by_id');
        }
    }
