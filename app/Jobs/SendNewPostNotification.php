<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewPostNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscription;
    protected $post;

    public function __construct(Subscription $subscription, $post)
    {
        $this->subscription = $subscription;
        $this->post = $post;
    }

    public function handle()
    {
        $to = $this->subscription->email;
        $subject = 'New post notification: ' . $this->post->title;
        $body = $this->post->description;
        Mail::to($to)->send(new NewPostNotification($subject, $body));
    }
}
