<?php

namespace App\Http\Controllers;

use App\Mail\PostEmail;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function index()
    {
        return view('main', ['posts' => Post::orderBy('created_at', 'desc')->paginate(5)]);
    }

    public function store(Request $req)
    {
        // $admin = User::where('role', 'admin')->first();

        $post = new Post;
        $post->text = $req->input('text');
        $post->user_id = session()->has('user') ? session('user.id') : null;
        $post->save();

        // Mail::to('goodle.ok@mail.ru')->send(new PostEmail(session('user.name'), $post->text, $post->created_at));

        $time = date('d.m.Y H:i', strtotime($post->created_at));
        $role = session()->has('user.role') ? 'auth' : 'anon';
        $author = empty(session('user.name')) ? 'аноним' : session('user.name');
        return response()->json(['id' => $post->id, 'time' => $time, 'text' => $post->text, 'role' => $role, 'author' => $author]);
    }

    public function delete(Request $req)
    {
        if (session('user.role') == 'admin') {
            $post = Post::find($req->input('id')); 
            if ($post->delete()) { return response('ok'); }
            else { return response('not'); }
        }
        elseif (session('user.role') == 'user') {
            $post = Post::whereRaw('id = ? and user_id = ?', array($req->input('id'), session('user.id')))->first();

            if (empty($post)) { return response('Empty'); }

            $diff_hour = (time() - strtotime($post->created_at)) / (3600);
            if ($diff_hour > 2) 
                return response('not'); 
            else {
                if ($post->delete()) { return response('ok'); }
                else { return response('not'); }
            }
        }
    }

    public function update(Request $req)
    {
        if (session('user.role') == 'admin') {
            $post = Post::find($req->input('id'));
            $post->text = $req->input('text');
            if ($post->save()) {  return response('save'); }
            else { return response('no save'); }
        }
        elseif (session('user.role') == 'user') {
            $post = Post::whereRaw('id = ? and user_id = ?', array($req->input('id'), session('user.id')))->first();

            if (empty($post)) { return response('Empty'); }

            $diff_hour = (time() - strtotime($post->created_at)) / (3600);

            if ($diff_hour > 2) {
                return response('not'); 
            } else {
                $post->text = $req->input('text');
                if ($post->save()) { return response('save'); }
                else { return response('no save'); }
            }
        }
    }

    public function debug()
    {
        // $post = Post::whereRaw('id = ? and user_id = ?', array(29, session('user.id')))->first();
        // if (empty($post)) {
        //     return response('empty');
        // }
        // else {
        //     return response('not empty');
        // }
        // dd($post);
        // $post = Post::where('id', 29)->whereNull('user_id')->get();
        // if ($post->isEmpty()) {
        //     return response('empty');
        // }else {
        //     return response('not empty');
        // }
        $name = empty(session('user.name')) ? 'аноним' : session('user.name');
        return response($name);
    }
}
