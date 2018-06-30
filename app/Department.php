<?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;
    
    /**
 * App\Department
 *
 * @property int                                                             $id
 * @property string                                                          $name
 * @property string|null                                                     $description
 * @property \Carbon\Carbon|null                                             $created_at
 * @property \Carbon\Carbon|null                                             $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null                                                     $deleted_at
 * @property string|null                                                     $last_edited_by
 * @property string|null                                                     $edited_at
 * @property string|null                                                     $created_by
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereEditedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereLastEditedBy($value)
 * @property string|null                                                     $deleted_by
 * @property-read \App\User|null                                             $createdBy
 * @property-read \App\User|null                                             $deletedBy
 * @property-read \App\User|null                                             $lastEditor
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Department onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereDeletedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Department withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Department withoutTrashed()
 * @property-read \App\User|null                                             $lastEditedBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Department[] $tickets
 * @property int|null $created_by_id
 * @property int|null $deleted_by_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Department whereDeletedById($value)
 */
    class Department extends Model
    {
        //
        use SoftDeletes;
        protected $fillable = ['name', 'description', 'created_by_id', 'edited_at', 'last_edited_by_id', 'deleted_by_id'];
        //protected $hidden = ['created_at', 'updated_at'];
        
        const ALLOWED_INCLUDES = ['last-edited-by', 'created-by', 'deleted-by'];
        
        public function lastEditedBy()
        {
            return $this->belongsTo(User::class, 'last_edited_by_id');
        }
        
        public function createdBy()
        {
            return $this->belongsTo(User::class, 'created_by_id');
        }
        
        public function deletedBy()
        {
            return $this->belongsTo(User::class, 'deleted_by_id');
        }
        
        
        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function tickets()
        {
            return $this->hasMany(Ticket::class);
        }
        
    }
