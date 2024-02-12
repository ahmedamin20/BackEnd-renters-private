<?php

namespace Modules\Order\Events;

use Illuminate\Queue\SerializesModels;

class TestEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private readonly string $message)
    {

    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [
            'testChannel',
        ];
    }

    public function broadcastAs()
    {
        return 'TestEvent';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
        ];
    }
}
