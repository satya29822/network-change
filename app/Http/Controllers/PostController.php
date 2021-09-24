<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Post::latest()->paginate(5);

        return view('index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
            'others_document' => 'required'
        ]);
        $input=$request->all();
        if($request->image){
            $input['image_name'] = $strFileName = uniqid() . time() . '.' . $request->image->getClientOriginalExtension();

        $filePath = 'public/bulk/';
        $path = $request->image->storeAs($filePath, $strFileName);
        \Storage::url($path);
        $input['image_path'] = 'storage/bulk/' . $strFileName;
        // $input['path'] = public_path() . '/' . $input['image_path'];
        }
        if($request->others_document){
            $strFileName = uniqid() . time() . '.' . $request->others_document->getClientOriginalExtension();

            $filePath = 'public/bulk/';
            $path = $request->others_document->storeAs($filePath, $strFileName);
            \Storage::url($path);
            $input['others_document'] = 'storage/bulk/' . $strFileName;
        // $input['path'] = public_path() . '/' . $input['image_path'];
        }

        Post::create($input);

        return redirect()->route('posts.index')
                        ->with('success','Post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
        return view('show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
        return view('edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $input['title'] = $request->title;
        $input['description']=$request->description;
        if($request->image){
            $input['image_name'] = $strFileName = uniqid() . time() . '.' . $request->image->getClientOriginalExtension();

        $filePath = 'public/bulk/';
        $path = $request->image->storeAs($filePath, $strFileName);
        \Storage::url($path);
        $input['image_path'] = 'storage/bulk/' . $strFileName;
        // $input['path'] = public_path() . '/' . $input['image_path'];
        }
        if($request->others_document){
            $strFileName = uniqid() . time() . '.' . $request->others_document->getClientOriginalExtension();

            $filePath = 'public/bulk/';
            $path = $request->others_document->storeAs($filePath, $strFileName);
            \Storage::url($path);
            $input['others_document'] = 'storage/bulk/' . $strFileName;
        // $input['path'] = public_path() . '/' . $input['image_path'];
        }
        Post::where('id', '=',$post->id)->update($input);

        return redirect()->route('posts.index')
                        ->with('success','Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
        $post->delete();

        return redirect()->route('posts.index')
                        ->with('success','Post deleted successfully');
    }
}
