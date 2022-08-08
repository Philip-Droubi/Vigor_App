<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\UserDevice;
use App\Models\UserInfo;
use App\Models\Follow;
use App\Models\Post;
use App\Traits\GeneralTrait;
use Database\Seeders\WorkoutExcersisesSeeder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, GeneralTrait;
    protected $primaryKey = "id";
    protected $fillable = [
        'f_name',
        'l_name',
        'email',
        'password',
        'prof_img_url',
        'gender',
        'birth_date',
        'bio',
        'role_id',
        'email_verified_at',
        'deleted_at',
        'lang_country'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //realations
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function info()
    {
        return $this->hasMany(UserInfo::class);
    }

    public function followers() //people follow this user
    {
        return $this->hasMany(Follow::class, 'following');
    }

    public function follows() //people this users follow
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function blocks() //people this users follow
    {
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function providers()
    {
        return $this->hasMany(Provider::class, 'user_id');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function savedPosts()
    {
        return $this->hasMany(SavedPost::class, 'user_id');
    }

    public function postLikes()
    {
        return $this->hasMany(PostLikes::class);
    }

    public function postComments()
    {
        return $this->hasMany(PostComments::class);
    }

    public function postCommentsReports()
    {
        return $this->hasMany(PostCommentReport::class, 'user_id');
    }

    public function postVots()
    {
        return $this->hasMany(PostVote::class, 'user_id');
    }
    public function postsReports()
    {
        return $this->hasMany(PostReport::class, 'user_id');
    }
    public function postsMedia()
    {
        return $this->hasMany(PostMedia::class, 'user_id');
    }
    public function CV()
    {
        return $this->hasOne(CV::class, 'user_id');
    }

    public function challenges()
    {
        return $this->hasMany(Challenge::class, 'user_id');
    }
    public function chReviews()
    {
        return $this->hasMany(ChallengeReview::class, 'user_id');
    }
    public function chSubs()
    {
        return $this->hasMany(ChallengeSub::class, 'user_id');
    }
    public function chReports()
    {
        return $this->hasMany(ChallengeReport::class, 'user_id');
    }



    public function trainees()
    {
        if ($this->role->id == 2) {
            return $this->hasMany(CoachTrainees::class);
        }
    }

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    public function workout_review()
    {
        return $this->hasMany(WorkoutReview::class);
    }

    public function excersise_media()
    {
        return $this->hasMany(ExcersiseMedia::class);
    }

    public function excersise()
    {
        return $this->hasMany(Excersise::class);
    }

    public function workout()
    {
        return $this->hasMany(Workout::class);
    }

    public function categorie()
    {
        return $this->hasMany(WorkoutCategorie::class);
    }

    public function workout_excersise()
    {
        return $this->hasMany(WorkoutExcersisesSeeder::class);
    }

    public function practice()
    {
        return $this->hasMany(Practice::class);
    }

    public function favoriteworkouts()
    {
        return $this->hasMany(FavoriteWorkout::class);
    }

    public function favoritediets()
    {
        return $this->hasMany(FavoriteDiet::class);
    }

    public function subscribed_diet()
    {
        return $this->hasMany(DietSubscribe::class);
    }

    public function diet_review()
    {
        return $this->hasMany(DietReview::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id') && $this->hasMany(Chat::class, 'to_user_id');
    }

    //Accessor
    public function setFNameAttribute($f_name)
    {
        $this->attributes['f_name'] = strtolower($f_name);
    }
    public function setLNameAttribute($l_name)
    {
        $this->attributes['l_name'] = strtolower($l_name);
    }
    public function getFNameAttribute($f_name)
    {
        return ucfirst($f_name);
    }
    public function getLNameAttribute($l_name)
    {
        return ucfirst($l_name);
    }
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }
}
