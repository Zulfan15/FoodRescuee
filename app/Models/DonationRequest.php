<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationRequest extends Model
{
    /** @use HasFactory<\Database\Factories\DonationRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'food_donation_id',
        'recipient_id',
        'message',
        'requested_quantity',
        'status',
        'approved_at',
        'picked_up_at',
        'pickup_notes',
        'is_priority',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'picked_up_at' => 'datetime',
            'is_priority' => 'boolean',
        ];
    }

    // Relationships
    public function foodDonation()
    {
        return $this->belongsTo(FoodDonation::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper methods
    public function canBeApproved()
    {
        return $this->status === 'pending' && 
               $this->foodDonation->getRemainingQuantity() >= $this->requested_quantity;
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            default => 'secondary',
        };
    }
}
