<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostNotification;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class postController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website_id' => 'required|exists:websites,id',
            'title' => 'required|max:255',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorResponse('Input validation failed', $validator->errors());
        }

        $post = new Post();
        $post->website_id = $request->website_id;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();
        // Get all subscriptions for this post's website
        $subscriptions = Subscription::where('website_id', $post->website_id)->get();

        // Dispatch the SendNewPostNotification job for each subscription
        foreach ($subscriptions as $subscription) {
            if ($post->id > $subscription->last_post_id) {
                dispatch(new SendNewPostNotification($subscription, $post));
                $subscription->last_post_id = $post->id;
                $subscription->save();
            }
        }

        // Return a success response
        return response()->json(['message' => 'Post published successfully.']);
    }
}
