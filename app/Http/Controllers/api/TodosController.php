<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodosController extends Controller
{
    /**
     * Display a listing of the todos.
     *
     */
    public function index(): JsonResponse
    {
        $todos = Todo::latest()->get();

        return response()->json(compact('todos'));
    }

    /**
     * Store a new todo in the storage.
     *
     * @param Request $request
     */
    public function store(Request $request): Todo
    {

        $request->merge(['is_completed' => false]);
        $data = $this->getData($request);

        return Todo::create($data);
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getData(Request $request): array
    {
        $request->merge(['user_id' => auth()->user()->getAuthIdentifier()]);

        $rules = [
            'user_id' => 'required',
            'text' => 'required|string|min:1|max:512',
            'is_completed' => 'required',
        ];

        return $request->validate($rules);
    }

    /**
     * Show the form for creating a new todo.
     *
     */
    public function create(): JsonResponse
    {
        $Users = User::pluck('name', 'id')->all();

        return response()->json(compact('Users'));
    }

    /**
     * Show the form for editing the specified todo.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $todo = Todo::findOrFail($id);
        $Users = User::pluck('name', 'id')->all();

        return response()->json(compact('todo', 'Users'));
    }

    /**
     * Update the specified todo in the storage.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $data = $this->getData($request);

        $todo = Todo::findOrFail($id);
        $todo->update($data);

        return response()->json(['success' => 'Todo was successfully updated.']);
    }

    /**
     * Remove the specified todo from the storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return response()->json(['success' => 'Todo was successfully deleted.']);
    }
}
