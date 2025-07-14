<?php

namespace App\Events;

use App\Models\FoodDonation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $donation;

    public function __construct(FoodDonation $donation)
    {
        $this->donation = $donation->load('donor:id,name');
    }

    public function broadcastOn()
    {
        return new Channel('donations');
    }

    public function broadcastAs()
    {
        return 'donation.created';
    }

    public function broadcastWith()
    {
        return [
            'donation' => [
                'id' => $this->donation->id,
                'title' => $this->donation->title,
                'description' => $this->donation->description,
                'quantity' => $this->donation->quantity,
                'unit' => $this->donation->unit,
                'food_type' => $this->donation->food_type,
                'pickup_latitude' => $this->donation->pickup_latitude,
                'pickup_longitude' => $this->donation->pickup_longitude,
                'pickup_location' => $this->donation->pickup_location,
                'donor_name' => $this->donation->donor->name,
                'images' => $this->donation->images,
                'created_at' => $this->donation->created_at,
            ]
        ];
    }
}
