<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'avatar_path',
        'email',
        'password',
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

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function initials(): string
    {
        $first = strtoupper(mb_substr(trim($this->first_name ?: $this->name), 0, 1));
        $last = strtoupper(mb_substr(trim($this->last_name), 0, 1));

        if (!$last) {
            $parts = preg_split('/\s+/', trim($this->name));
            $first = strtoupper(mb_substr($parts[0] ?? '', 0, 1));
            $last = isset($parts[1]) ? strtoupper(mb_substr($parts[1], 0, 1)) : '';
        }

        return $first . $last;
    }

    public function hasAvatar(): bool
    {
        return !empty($this->avatar_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar_path);
    }

    public function avatarUrl(): ?string
    {
        return $this->hasAvatar() ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->avatar_path) : null;
    }
}
