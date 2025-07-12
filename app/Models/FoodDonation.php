<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDonation extends Model
{
    /** @use HasFactory<\Database\Factories\FoodDonationFactory> */
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'title',
        'description',
        'food_type',
        'quantity',
        'unit',
        'expiry_date',
        'pickup_time_start',
        'pickup_time_end',
        'pickup_location',
        'pickup_latitude',
        'pickup_longitude',
        'images',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
        'is_perishable',
        'special_instructions',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'datetime',
            'pickup_time_start' => 'datetime',
            'pickup_time_end' => 'datetime',
            'approved_at' => 'datetime',
            'images' => 'array',
            'is_perishable' => 'boolean',
            'pickup_latitude' => 'decimal:8',
            'pickup_longitude' => 'decimal:8',
        ];
    }

    // Relationships
    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function donationRequests()
    {
        return $this->hasMany(DonationRequest::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'approved')
                    ->where('expiry_date', '>', now());
    }

    public function scopeNearby($query, $latitude, $longitude, $radius = 5)
    {
        return $query->whereRaw(
            "ST_Distance_Sphere(
                POINT(pickup_longitude, pickup_latitude),
                POINT(?, ?)
            ) <= ? * 1000",
            [$longitude, $latitude, $radius]
        );
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expiry_date < now();
    }

    public function isAvailable()
    {
        return $this->status === 'approved' && !$this->isExpired();
    }

    public function getRemainingQuantity()
    {
        $claimed = $this->donationRequests()
                        ->where('status', 'approved')
                        ->sum('requested_quantity');
        
        return $this->quantity - $claimed;
    }

    public function canBeRequested()
    {
        // Check if donation is approved and not expired
        if ($this->status !== 'approved' || $this->isExpired()) {
            return false;
        }

        // Check if there's still remaining quantity
        if ($this->getRemainingQuantity() <= 0) {
            return false;
        }

        // Check if pickup time window is still open
        if (now() > $this->pickup_time_end) {
            return false;
        }

        return true;
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            'expired' => 'secondary',
            default => 'secondary'
        };
    }

    public function getDistanceFrom($latitude, $longitude)
    {
        if (!$latitude || !$longitude || !$this->pickup_latitude || !$this->pickup_longitude) {
            return 0;
        }

        // Haversine formula to calculate distance between two points
        $earthRadius = 6371; // Earth's radius in kilometers

        $latFrom = deg2rad((float) $latitude);
        $lonFrom = deg2rad((float) $longitude);
        $latTo = deg2rad((float) $this->pickup_latitude);
        $lonTo = deg2rad((float) $this->pickup_longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}
