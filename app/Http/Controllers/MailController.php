<?php

namespace App\Http\Controllers;

use App\Mail\PostEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class MailController extends Controller
{
    public function send(Request $req)
    {
        $admin = User::where('role', 'admin')->first();
        Mail::to($admin->email)->send(new PostEmail($req->input('author'), $req->input('text'), $req->input('time')));
    }
}
