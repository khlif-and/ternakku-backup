<?php

namespace App\Http\Controllers\Admin;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user()->load('profile');
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();
            $user->update(['name' => $request->name]);

            if ($request->hasFile('photo')) {
                $profile = $user->profile;
                $file = $request->file('photo');
                $fileName = time() . '-profile.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profiles', $fileName, 'public');

                if ($profile) {
                    if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                        Storage::disk('public')->delete($profile->photo);
                    }
                    $profile->update(['photo' => $path]);
                } else {
                    Profile::create([
                        'user_id' => $user->id,
                        'photo' => $path,
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui profil.']);
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('password_success', 'Password berhasil diubah.');
    }
}
