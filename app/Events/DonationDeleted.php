<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $donationId;

    public function __construct($donationId)
    {
        $this->donationId = $donationId;
    }

    public function broadcastOn()
    {
        return new Channel('donations');
    }

    public function broadcastAs()
    {
        return 'donation.deleted';
    }

    public function broadcastWith()
    {
        return [
            'donation_id' => $this->donationId
        ];
    }
}
