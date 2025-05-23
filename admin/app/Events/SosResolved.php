<?php

namespace App\Events;

use App\Models\SOS;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SosResolved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public SOS $sos,
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('user.' . $this->sos->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'sos.resolved';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->sos->id,
            'description' => $this->sos->description,
            'image' => url('storage/' . $this->sos->image_path),
            'status' => $this->sos->status,
            'type' => $this->sos->type,
            'address' => $this->sos->address,
        ];
    }
}
