<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Website;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class subscriptionController extends Controller
{
    /**
     * Creating a subscription records in database.
     *
     * @param Request $request
     * @param $websiteId
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Application|Response
     */
    public function store(Request $request, $websiteId) {
        $validate = Validator::make($request->all(), array(
            'email' => 'required|email'
        ));
        if ($validate->fails()) {
            return $this->sendErrorResponse('Input Validation failed', $validate->errors());
        }
        $website = Website::find($websiteId);
        if (!$website) {
            return $this->sendErrorResponse('Website information not found', $website);
        }
        $subscribe = Subscription::create(array(
            'email' => $request['email'],
            'website_id' => $websiteId
        ));
        if ($subscribe) {
            return $this->sendSuccessResponse('Subscribed Successfully', $subscribe);
        } else {
            return $this->sendErrorResponse('Something Went Wrong', $subscribe, 500);
        }
    }

    /**
     * Deleting a subscription.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Application|Response
     */
    public function destroy($id) {
        $subscription = Subscription::find($id);
        if (!$subscription) {
            return $this->sendErrorResponse('Subscription not found', $subscription, 404);
        }
        $deleted = $subscription->delete();
        if ($deleted) {
            return $this->sendSuccessResponse('Subscription deleted successfully', $deleted);
        } else {
            return $this->sendErrorResponse('Something Went Wrong', $deleted, 500);
        }
    }
}
