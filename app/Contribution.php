<?php
    
    namespace App;
    
    use App\Traits\HasUuid;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    /**
 * App\Contribution
 *
 * @property string              $id
 * @property string              $user_id
 * @property string              $topic_id
 * @property string              $text
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User      $author
 * @property-read \App\Topic     $topic
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contribution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contribution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contribution whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contribution whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contribution whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contribution whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contribution whereDeletedAt($value)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Contribution onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Contribution withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Contribution withoutTrashed()
 */
    class Contribution extends Model
    {
        //
        use SoftDeletes;
        
        const ALLOWED_INCLUDES = ['author', 'topic'];
        
        protected $fillable = ['text', 'user_id', 'topic_id'];
        protected $hidden = ['user_id', 'topic_id'];
        
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
        public function topic()
        {
            return $this->belongsTo(Topic::class);
        }
    }
