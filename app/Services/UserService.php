<?php

namespace App\Services;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class UserService
{
     public function createUser(UserRequest $request): User
     {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'role'       => $request->role,
            'password'   => bcrypt($request->password),
            'status'     => true,
        ]);

        Alert::success('Created!', $user->first_name . ' ' . $user->last_name . ' has been created.');

        return $user;
     }

     public function updateUser(User $user, UpdateUserRequest $request): User
     {
          $data = [
               'first_name' => $request->first_name,
               'last_name'  => $request->last_name,
               'email'      => $request->email,
               'role'       => $request->role,
          ];

          if ($request->filled('password')) {
               $data['password'] = bcrypt($request->password);
          }

          $user->update($data);

          Alert::success('Updated!', $user->first_name . ' ' . $user->last_name . ' has been updated.');

          return $user;
     }

     public function deleteUser(User $user): void
     {
          $user->delete();
          Alert::success('Deleted!', $user->first_name . ' ' . $user->last_name . ' has been deleted.');
     }
}
