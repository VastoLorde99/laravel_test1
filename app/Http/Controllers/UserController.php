<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function store(Request $req)
    {
        $user = new User;
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->role = 'user';
        $user->password = $req->input('password');

        if ($user->save()) {
            session(['user' => $user]);
            return response()->json(['msg' => 'Вы зарегистрированы', 'user' => $user], 200);
        } 
        else {
            return response()->json(array('msg' => 'Ошибка при регистрации'), 200);
        }
        // return response()->json(array('msg' => 'Вы зарегистрированы'), 200);
    }

    public function login(Request $req)
    {
        $user = User::select('id', 'name', 'email', 'role')->whereRaw('email = ? and password = ?', [$req->input('email'), $req->input('password')])->first();
        $posts = Post::orderBy('created_at', 'desc')->take(5)->get();
        session(['user' => $user]);
        // $page = file_get_contents('C:\openserver\domains\test\resources\views\inc\content.blade.php');
        return response()->json(['user' => $user, 'posts' => $posts]);
        // return redirect('/');
    }

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
}
