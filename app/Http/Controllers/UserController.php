<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Spatie\Permission\Models\Permission;
use Str;

class UserController extends Controller
{
    public function index()
    {
        // TODO: Select columns
        $users = User::with('permissions')->get();
        return view('users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        
        /**
         * Handle upload an image
         */
        $filename = '';
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();
            
            $file->storeAs('profile/', $filename, 'public');
        }
        $input_data = $request->all();
        $input_data['uuid'] = Str::uuid();
        $input_data['photo'] = $filename;
        $input_data['password'] = bcrypt($input_data['password']);
        // dd($input_data);
        $user = User::create($input_data);

        return redirect()
            ->route('users.index')
            ->with('success', 'New User has been created!');
    }

    public function show(User $user, $user_id)
    {
        $user = User::find($user_id);
        return view('users.show', [
           'user' => $user
        ]);
    }

    public function edit(User $user, $user_id)
    {   
        $user = User::with('permissions')->find($user_id);
        $permissions = $this->users_permissions();
        return view('users.edit', [
            'user' => $user,
            'permissions' => $permissions ,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user, $user_id)
    {
        /**
         * Handle upload image with Storage.
         */
        
        $user = User::find($user_id);
        if ($user->email != $request->email) {
            $existing_email = User::where('email', $request->email)->first();
            if($existing_email) return redirect()->back()->with('error', 'This email exists already!');
        }
        if($request->hasFile('photo')){

            // Delete Old Photo
            $filePath = public_path('storage/profile/') . $user->photo;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Prepare New Photo
            $file = $request->file('photo');
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();

            // Store an image to Storage
            $file->storeAs('profile/', $fileName, 'public');

            // Save DB
            $user->update([
                'photo' => $fileName
            ]);
        }

        $user->email = $request->email;
        $user->name = $request->name;
        $user->save();
        return redirect()
            ->route('users.index')
            ->with('success', 'User has been updated!');
    }

    public function updatePassword(Request $request, String $user_id)
    {
        # Validation
        $validated = $request->validate([
            'password' => 'required_with:password_confirmation|min:6',
            'password_confirmation' => 'same:password|min:6',
        ]);

        # Update the new Password
        User::where('id', $user_id)->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User has been updated!');
    }

    public function destroy(User $user, $user_id)
    {
        /**
         * Delete photo if exists.
         */

        $user = User::find($user_id);
        if ($user->photo) {
            $filePath = public_path('storage/profile/') . $user->photo;

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User has been deleted!');
    }

    public function users_permissions(){
        $permissions = Permission::all()->toArray();
        return $permissions;
    }

    public function users_permissions_update( Request $request, $user_id){

        $permissions = $request->permissions;
        $user = User::find($user_id);
        $permissionsList = Permission::whereIn('id', $permissions)->get();
        $user->syncPermissions($permissionsList);
       

        return redirect()->back()->with('success', 'Permission added successfuly.');
    }
}
