<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'email_verified_at', 'password', 'phone', 'avatar', 'bio', 'profile_bg', 'title', 'title_effect', 'show_room', 'role', 'remember_token'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }

    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badge')
            ->withPivot('is_selected', 'unlocked_at')
            ->withTimestamps();
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function unreadChatMessagesCount(): int
    {
        return ChatMessage::query()
            ->whereHas('user', fn ($q) => $q->where('role', 'admin'))
            ->where('created_at', '>', $this->chat_last_read_at ?? $this->created_at)
            ->count();
    }

    public function chatBans(): HasMany
    {
        return $this->hasMany(ChatBan::class, 'user_id');
    }

    public function isChatBanned(): bool
    {
        return $this->chatBans()
            ->where('type', 'ban')
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->exists();
    }

    public function isChatMuted(): bool
    {
        return $this->chatBans()
            ->where('type', 'mute')
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'show_room' => 'boolean',
            'last_seen_at' => 'datetime',
            'chat_last_read_at' => 'datetime',
        ];
    }
}
