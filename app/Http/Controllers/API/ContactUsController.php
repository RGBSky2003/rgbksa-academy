<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Mail\ContactFormMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactUsRequest;

class ContactUsController extends Controller
{
    //

      public function index(ContactUsRequest $request)
      {

           $date=$request->validated();

           Mail::to($date['email'])->send(new ContactFormMail($date));

           return response()->json(['message' =>'success']);

      }
}
