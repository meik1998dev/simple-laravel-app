<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        return view('home', ["user" => $user]);
    }

    public function show()
    {
        $user = auth()->user();
        return view('home', ["user" => $user]);
    }

    public function update(Request $request, $email)
    {
        $data = $request->except(['_token', "_method"]);

        $validator = Validator::make($data, [
            'first_name' => ['required', "regex:/^[a-zA-ZÑñ\s]+$/", 'max:255'],
            'last_name' => ['required', "regex:/^[a-zA-ZÑñ\s]+$/", 'max:255'],
            'mid_initial' => ['string', 'size:1',"nullable"],
            'username' => ['regex:/^[a-zA-ZÑñ\s]+$/' , "nullable"],
            'zip_code' => ['numeric', 'digits_between:5,10' , "nullable"],
        ]);

        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->getMessageBag();

            // redirect our user back to the form with the errors from the validator
            return Redirect::to('home')
                ->withErrors($validator);
        }

        $foundedUser =  User::where("email", $email);
        $foundedUser->update($data);

        return  response()->json("updated successfully");
    }
}
