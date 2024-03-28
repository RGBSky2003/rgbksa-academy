<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\TransLang;
use Illuminate\Http\Request;
use App\Models\TransIndustry;
use App\Models\ServiceRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceRequestController extends Controller
{
    public function store($user_id, Request $request)
    {
        $request->merge([
            'user_id' => $user_id,
        ]);

        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email',
            'full_name' => 'required|string',
            'phone_number' => 'required|numeric'
        ],[
            'email.required'            => 'Please enter your email',
            'email.email'               => 'Please enter a valid email address',
            'full_name.required'        => 'The name field is required',
            'full_name.string'          => 'Please enter a valid name',
            'full_name.size'            => 'Please enter you full name',
            'phone_number.required'     => 'Please enter you phone number',
            'phone_number.numeric'      => 'Please enter a valid phone number'
        ]);

        if ($validatedData->fails())
        {
            $messages = [];

            $errors = $validatedData->errors()->all();

            foreach($errors as $error)
            {
                $messages[] = $error;
            }

            return response()->json([
                'messages' => $messages
            ]);
        }

        $new_request = ServiceRequest::create($request->except(['lang_from', 'lang_to', 'trans_industry']));

        $lang_from = NULL;
        $lang_to = NULL;
        $trans_industry = NULL;


        switch (strtolower($request->lang_from))
        {
            case 'arabic':
                $lang_from = 1;
                break;
            case 'english':
                $lang_from = 2;
                break;
            case 'spanish':
                $lang_from = 3;
                break;
            case 'german':
                $lang_from = 4;
                break;
            case 'russian':
                $lang_from = 5;
                break;
            case 'turkish':
                $lang_from = 6;
                break;
            case 'itailian':
                $lang_from = 7;
                break;

            default:
                $lang_from = NULL;
        }

        switch (strtolower($request->lang_to)) {
            case 'arabic':
                $lang_to = 1;
                break;
            case 'english':
                $lang_to = 2;
                break;
            case 'spanish':
                $lang_to = 3;
                break;
            case 'german':
                $lang_to = 4;
                break;
            case 'russian':
                $lang_to = 5;
                break;
            case 'turkish':
                $lang_to = 6;
                break;
            case 'itailian':
                $lang_to = 7;
                break;

            default:
                $lang_to = NULL;
        }

        switch (strtolower($request->trans_industry)) {
            case 'legal':
                $trans_industry = 1;
                break;
            case 'finance':
                $trans_industry = 2;
                break;
            case 'medical':
                $trans_industry = 3;
                break;
            case 'academic':
                $trans_industry = 4;
                break;
            case 'political':
                $trans_industry = 5;
                break;

            default:
                $trans_industry = NULL;
        }

        $new_request->lang_from = $lang_from;
        $new_request->lang_to = $lang_to;
        $new_request->trans_industry = $trans_industry;

        $new_request->save();

        if($request->hasFile('attachment'))
        {
            $file = $request->file('attachment');

            $file_name = time() . uniqid() . $user_id . '.' . $file->extension();

            Storage::putFileAs('public/request_attachments', $file, $file_name);

            $new_request->attachment = $file_name;

            $new_request->save();
        }

        return response()->json(['message' => 'Request Sent Successfully' , 'Request' => $new_request]);
    }

    public function getAll()
    {
        $requests = ServiceRequest::all();

        foreach($requests as $request)
        {
            $request->service_id = Service::find($request->service_id)->name;

            if ($request->trans_type == 0 || $request->trans_type == 1)
                $request->trans_type = ($request->trans_type ? 'Interpretation' : 'Translation');

            if($request->lang_from)
                $request->lang_from = TransLang::where('id', $request->lang_from)->first()->name;

            if($request->lang_to)
                $request->lang_to = TransLang::where('id', $request->lang_to)->first()->name;

            if ($request->trans_industry)
                $request->trans_industry = TransIndustry::where('id', $request->trans_industry)->first()->name;
        }

        return response()->json($requests);
    }

    public function get($user_id)
    {
        $requests = ServiceRequest::where('user_id', '=', $user_id)->get();

        foreach ($requests as $request)
        {
            if ($request->trans_type == 0 || $request->trans_type == 1)
                $request->trans_type = ($request->trans_type ? 'Interpretation' : 'Translation');

            if ($request->lang_from)
                $request->lang_from = TransLang::where('id', $request->lang_from)->first()->name;

            if ($request->lang_to)
                $request->lang_to = TransLang::where('id', $request->lang_to)->first()->name;

            if ($request->trans_industry)
                $request->trans_industry = TransIndustry::where('id', $request->trans_industry)->first()->name;
        }

        return response()->json([
            'requests' => $requests
        ]);
    }


    public function update($request_id, Request $request)
    {
        $service_request = ServiceRequest::find($request_id);

        $service_request->update($request->all());

        return response()->json(['message' => 'Request Updated Successfully']);
    }

    public function delete($request_id)
    {
        ServiceRequest::find($request_id)->delete();

        return response()->json(['message' => 'Request Deleted Successfully']);
    }
}
