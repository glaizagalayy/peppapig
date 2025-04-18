<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Admin;
use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function manageUsers(Request $request)
    {
        $query = User::query();

        // Filter by role if provided
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        return view('admin.manageUsers', compact('users'));
    }

    public function addUserForm()
    {
        return view('admin.addUser');
    }

    public function addUser(Request $request)
    {
        try {
            // Basic validation rules for all users
            $rules = [
                'login_id' => 'required|string|max:50|unique:pnph_users',
                'email' => 'required|email|unique:pnph_users',
                'role' => 'required|in:student,finance,admin',
                'first_name' => 'required|string|max:50',
                'last_name' => 'required|string|max:50',
            ];

            // Add student-specific rules only if role is student
            if ($request->input('role') === 'student') {
                $rules += [
                    'batch_year' => 'required|integer',
                    'group_num' => 'required|integer',
                    'student_number' => 'required|integer',
                    'center_training_code' => 'required|string|max:1',
                    'region_code' => 'required|integer',
                ];
            }

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return back()
                    ->withInput()
                    ->withErrors($validator)
                    ->with('error_message', 'Please fix the following errors:');
            }

            DB::beginTransaction();

            $tempPassword = Str::random(8);
            $user = User::create([
                'login_id' => $request->login_id,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($tempPassword),
                'is_active' => true,
                'password_reset_required' => true,
            ]);

            switch ($request->role) {
                case 'student':
                    Student::create([
                        'student_id' => $request->login_id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'middle_initial' => $request->middle_initial,
                        'suffix' => $request->suffix,
                        'email' => $request->email,
                        'batch_year' => $request->batch_year ?? 2025, // Provide default value
                        'group_num' => $request->group_num,
                        'student_number' => $request->student_number,
                        'center_training_code' => $request->center_training_code,
                        'region_code' => $request->region_code,
                    ]);
                    break;

                case 'finance':
                    Finance::create([
                        'user_id' => $user->id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'department' => 'Finance Department',
                    ]);
                    break;

                case 'admin':
                    Admin::create([
                        'user_id' => $user->id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'position' => 'System Administrator',
                    ]);
                    break;
            }

            Mail::to($user->email)->send(new \App\Mail\TemporaryPasswordMail($tempPassword, $user->login_id));

            DB::commit();

            return redirect()->route('admin.manageUsers')
                ->with('success', "User {$request->first_name} {$request->last_name} added successfully! Temporary password has been sent to their email.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to add user. ' . $e->getMessage()])
                ->with('error_message', 'An error occurred while adding the user.');
        }
    }

    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => false]); // Set is_active to false

        return redirect()->route('admin.manageUsers')->with('success', 'User deactivated successfully!');
    }

    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]); // Set is_active to true

        return redirect()->route('admin.manageUsers')->with('success', 'User activated successfully!');
    }

    public function editUserForm($id)
    {
        $user = User::findOrFail($id); // Fetch the user by ID
        return view('admin.editUser', compact('user')); // Pass the user to the view
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.editUser', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_initial' => 'nullable|string|max:10',
            'suffix' => 'nullable|string|max:10',
        ]);

        switch ($user->role) {
            case 'student':
                $student = Student::where('student_id', $user->login_id)->firstOrFail();
                $student->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_initial' => $request->middle_initial,
                    'suffix' => $request->suffix,
                ]);
                break;

            case 'finance':
                $finance = Finance::where('user_id', $user->id)->firstOrFail();
                $finance->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                ]);
                break;

            case 'admin':
                $admin = Admin::where('user_id', $user->id)->firstOrFail();
                $admin->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                ]);
                break;
        }

        return redirect()->route('admin.manageUsers')->with('success', 'User updated successfully!');
    }
}