<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Modules\Teaching_1\Models\Student;
use App\Modules\Teaching_1\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Events\Models\EventManager;
use App\Modules\Events\Models\EventRegistration;
use App\Modules\Events\Models\EventUser;
use App\Modules\Blog\Models\Blog;
use App\Modules\TuongTac\Models\Vote;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'global_id',
        'full_name',
        'username',
        'email',
        'password',
        'email_verified_at',
        'photo',
        'phone',
        'address',
        'description',
        'ship_id',
        'ugroup_id',
        'role',
        'budget',
        'totalpoint',
        'totalrevenue',
        'taxcode',
        'taxname',
        'taxaddress',
        'status',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function deleteUser($user_id){
        $user = User::find($user_id);
        if(auth()->user()->role =='admin')
        {
            $user->delete();
            return 1;
        }
        else{
            $user->status = "inactive";
            $user->save();
            return 0;
        }     
        
    }
    public static function c_create($data)
    {
        
        $pro = User::create($data);
        $pro->code = "CUS" . sprintf('%09d',$pro->id);
        $pro->save();
       
        
       
        return $pro;
    }
    /**
     * Cập nhật ảnh đại diện cho người dùng
     *
     * @param string $photoPath
     * @return void
     */
    public function updatePhoto($photoPath)
    {
        // Cập nhật giá trị 'photo' trong bảng users
        $this->photo = $photoPath;
        $this->save();
    }

    // Khai báo quan hệ
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id'); // 'user_id' là khóa ngoại trong bảng 'students'
    }
    
    // Khai báo quan hệ
    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'user_id'); // 'user_id' là khóa ngoại trong bảng 'teachers'
    }
    
    public function hasRole($role)
    {
        return $this->role->title === $role;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isEventManager()
    {
        return $this->hasRole('event_manager');
    }

    public function isParticipant()
    {
        return $this->hasRole('participant');
    }
    public function eventManager()
    {
        return $this->hasOne(EventManager::class, 'user_id');
    }
    public function eventRegistration()
    {
        return $this->hasOne(EventRegistration::class, 'user_id');
    }
    public function eventuser()
    {
        return $this->hasOne(EventUser::class, 'user_id');
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->photo) {
            // Kiểm tra nếu photo đã là một URL đầy đủ (bắt đầu bằng http hoặc https) hoặc đã chứa 'storage/'
            if (filter_var($this->photo, FILTER_VALIDATE_URL) || Str::startsWith($this->photo, 'storage/')) {
                return asset($this->photo);
            } else {
                // Nếu chỉ là tên file hoặc đường dẫn tương đối trong storage
            return asset('storage/' . $this->photo);
            }
        }
        // Đường dẫn mặc định nếu không có ảnh đại diện
        return asset('storage/resources/default.png');
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id');
    }
    public function votes() {
        return $this->morphMany(Vote::class, 'votable');
    }
    public function averageRating() {
        return $this->votes()->avg('rating');
    }
}   
class OauthClient extends Model
{
    use HasApiTokens;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}


