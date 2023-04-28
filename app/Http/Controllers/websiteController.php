<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class websiteController extends Controller
{
    /**
     * Display a listing of the websites.
     *
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Application|Response
     */
    public function index() {
        $websites = Website::all();
        return $this->sendSuccessResponse('Websites Fetched Successfully', $websites);
    }

    /**
     * Store a website in database records.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Application|Response
     */
    public function store(Request $request) {
        $validate = Validator::make($request->all(), array(
            'name' => 'required|string|max:255|unique:websites,name',
            'url' => 'required|url'
        ));
        if ($validate->fails()) {
            return $this->sendErrorResponse('Input validation failed', $validate->errors(), 403);
        }
        $website = Website::create($request->all());
        return $this->sendSuccessResponse('Website Created Successfully', $website);
    }

    /**
     * Information of specific website.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Application|Response
     */
    public function show($id) {
        $website = Website::find($id);
        if (!$website) {
            return $this->sendErrorResponse('Website Information not found', $website, 404);
        }
        return $this->sendSuccessResponse('Website information found', $website);
    }

    /**
     * Update records in database.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Application|Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), array(
            'name' => ['nullable','string','max:255',Rule::unique('websites')->ignore($id)],
            'url' => 'nullable|url'
        ));
        if ($validate->fails()) {
            return $this->sendErrorResponse('Input validation failed', $validate->errors(), 403);
        }
        $website = Website::find($id);
        if (!$website) {
            return $this->sendErrorResponse('Website Information not found', $website, 404);
        }
        $update = $website->update($request->all());
        if ($update) {
            return $this->sendSuccessResponse('Website Updated Successfully');
        } else {
            return $this->sendErrorResponse('Something Went Wrong', $update, 500);
        }
    }

    /**
     * Remove record from database.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|ResponseFactory|Application|Response
     */
    public function destroy($id)
    {
        $website = Website::find($id);
        if (!$website) {
            return $this->sendErrorResponse('Website Information not found', $website, 404);
        }
        if ($website->delete()) {
            return $this->sendSuccessResponse('Website Deleted Successfully');
        } else {
            return $this->sendErrorResponse('Something Went Wrong', null, 500);
        }
    }
}
