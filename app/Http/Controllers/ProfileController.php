<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

     /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        
        $user = auth()->user();
        // $oldPhone=$user->phone;
        $user->fname = $request->first_name;
        $user->lname = $request->last_name;
        $user->phone = $request->phone;
        $user->save();
        // if ($oldPhone !== $request->phone) {
        //     // $code = User::generateCode(2);
        //     // $user->update([
        //     //     // 'phone_verified_at'=> null,
        //     //     'verification_code'=>Hash::make($code)
        //     // ]);
        //     // Auth::logout();
        //     // event(new NewUserRegistered($user->fname, $request->phone,$code));
        //     // return Redirect::route('phoneverification',['phone'=>$request->phone])->with('success','NB: Only after verification you\'ll be able to use your new phone');
        //     return Redirect::back()->with('success','Profile Updated successfully');
        // }
        return Redirect::route('profile.edit')->with('success', 'profile-updated successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
