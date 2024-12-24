<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::with('user', 'category')->when(request()->search, function ($tasks) {
            $tasks = $tasks->where('title', 'like', '%' . request()->search . '%');
        })->where('user_id', auth()->user()->id)->latest()->paginate(5);

        //append query string to pagination links
        $tasks->appends(['search' => request()->search]);

        //return with Api Resource
        return new TaskResource(true, 'List Data Tasks', $tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|unique:tasks',
            'description'   => 'required',
            'status'        => 'required|in:Selesai,Belum Selesai',
            'user_id'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $task = Task::create([
            'title'       => $request->title,
            'description' => $request->description,
            'status'     => $request->status,
            'user_id'     => auth()->guard('api')->user()->id,
        ]);


        if ($task) {
            //return success with Api Resource
            return new TaskResource(true, 'Data Task Berhasil Disimpan!', $task);
        }

        //return failed with Api Resource
        return new TaskResource(false, 'Data Task Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::whereId($id)->first();

        if ($task) {
            //return success with Api Resource
            return new TaskResource(true, 'Detail Data Task!', $task);
        }

        //return failed with Api Resource
        return new TaskResource(false, 'Detail Data Task Tidak DItemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|unique:tasks,title,' . $task->id,
            'description'   => 'required',
            'status'        => 'required|in:Selesai,Belum Selesai',
            'user_id'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $task->update([
            'title'       => $request->title,
            'description' => $request->description,
            'status'     => $request->status,
            'user_id'     => auth()->guard('api')->user()->id,
        ]);

        if ($task) {
            //return success with Api Resource
            return new TaskResource(true, 'Data Task Berhasil Diupdate!', $task);
        }

        //return failed with Api Resource
        return new TaskResource(false, 'Data Task Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        if ($task->delete()) {
            //return success with Api Resource
            return new TaskResource(true, 'Data Task Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new TaskResource(false, 'Data Task Gagal Dihapus!', null);
    }
}
