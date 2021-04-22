<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\UsersRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{
    protected $userRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->userRepository = $usersRepository;
    }
    public function index()
    {
        return Inertia::render('users/index', ['users' => User::all()]);
    }

    public function edit($userId)
    {
        return Inertia::render('users/edit', ['user' => $this->userRepository->getUserById($userId), 'currentUser' => auth()->user()]);
    }

    public function update($userId, UserRequest $request)
    {
        $result = $this->userRepository->updateUserById($userId, $request->all());
        return response($result ? '成功' : '失敗');
    }

    public function create()
    {
        return Inertia::render('users/edit', ['isCreate' => true, 'currentUser' => auth()->user()]);
    }

    public function store(UserRequest $request)
    {
        $result = $this->userRepository->createUser($request->all());
        return response('成功');
    }
}
