<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::where('name', '<>' , 'developer')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $regions = Region::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.users.create', compact('roles', 'regions'));
    }

    public function store(StoreUserRequest $request)
    {

        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');

    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $regions = Region::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $user->load('roles', 'region');

        return view('admin.users.edit', compact('roles', 'regions', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        //dd($request);
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');

    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles', 'region');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();

    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

     public function block(User $user)
    {
        $user->is_blocked = true;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User blocked successfully.');
    }

    // Unblock a user
    public function unblock(User $user)
    {
        $user->is_blocked = false;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User unblocked successfully.');
    }
}
