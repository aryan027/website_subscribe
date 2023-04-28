<?php

namespace App\Console\Commands;

use App\Jobs\SendNewPostNotification;
use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Console\Command;

class SendPostNotifications extends Command
{
    protected $signature = 'send:post-notifications';
    protected $description = 'Send email notifications for new posts';

    public function handle()
    {
        // Get all subscriptions for all websites
        $subscriptions = Subscription::all();

        // Loop through each subscription and send email notifications for new posts
        foreach ($subscriptions as $subscription) {
            $latestPostId = $subscription->last_post_id ?? 0;

            $posts = Post::where('website_id', $subscription->website_id)
                ->where('id', '>', $latestPostId)
                ->get();

            foreach ($posts as $post) {
                dispatch(new SendNewPostNotification($subscription, $post));
                $subscription->last_post_id = $post->id;
                $subscription->save();
            }
        }

        $this->info('Post notifications sent successfully.');
    }
}
