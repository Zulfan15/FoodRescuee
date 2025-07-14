<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string $phone
 * @property string $address
 * @property float $latitude
 * @property float $longitude
 * @property string $profile_picture
 * @property bool $is_active
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FoodDonation> $foodDonations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DonationRequest> $donationRequests
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviewsGiven
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviewsReceived
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'latitude',
        'longitude',
        'profile_picture',
        'is_active',
        'is_verified',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    // Relationships
    public function foodDonations()
    {
        return $this->hasMany(FoodDonation::class, 'donor_id');
    }

    public function donationRequests()
    {
        return $this->hasMany(DonationRequest::class, 'recipient_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    // Helper methods
    public function isDonor()
    {
        return $this->role === 'donor';
    }

    public function isRecipient()
    {
        return $this->role === 'recipient';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getAverageRating()
    {
        return $this->reviewsReceived()->avg('rating') ?? 0;
    }
}
