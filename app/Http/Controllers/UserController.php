<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Hash;

class UserController extends Controller
{
    function register(Request $req){
        //Check validation rules
        $credentials = $req->validate([
            'name' => ['bail', 'required'],
            'email' => ['bail', 'required', 'unique:users,email', 'email'],
            'location' => ['bail', 'required', 'alpha'],
            'password' => ['bail', 'required'],
        ]);
        //Add user sanitised
        $user = new User;
        $user->name = strip_tags($req->input('name'));
        $user->email = strip_tags($req->input('email'));
        $user->location = strip_tags($req->input('location'));
        $user->user_type = 'user';
        $user->password = Hash::make($req->input('password'));
        $user->save();
        //Return user
        return $user;
    }

    function login(Request $req){
        $credentials = $req->validate([
            'email' => [],
            'password' => [],
        ]);

        //Check user and authorize session
        if (Auth::attempt($credentials)) {
            $req->session()->regenerate();
            $user = Auth::user();
            return $user;
        } else {
            return ['error' => 'Email or password is incorrect'];
        }

        
    }

    function logout(Request $req){
        //destroy session
        return Auth::logout();
    }


    function users(Request $req){
        //validate user and check admin type
        $user = Auth::user();
        if(!$user || $user->user_type != 'admin'){
            return ['error' => 'Not Logged In'];
        }
        return User::all();
    }


    function search(Request $req){
        //check user and perform search
        $user = Auth::user();
        if(!$user){
            return ['error' => 'Not Logged In'];
        }

        //get location query and apikey
        $location = strip_tags($req->search);
        $apiKey = '73b1bda1a1adff35919566c650cc7f4a';

        //Convert using geocode api from city to lat long (limitation of free api key)
        $geoCode = Http::get("http://api.openweathermap.org/geo/1.0/direct?q={$location}&limit=1&appid={$apiKey}");
        $geoLat = $geoCode[0]['lat'];
        $geoLon = $geoCode[0]['lon'];
        //Perform search and return data requires error handling
        $response = Http::get("https://api.openweathermap.org/data/2.5/onecall?lat={$geoLat}&lon={$geoLon}&exclude=minutely,hourly,alerts&units=metric&appid={$apiKey}");
    
        return $response;
    }

}
