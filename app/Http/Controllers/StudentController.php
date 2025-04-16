

public function profile()
{
    $student = Auth::user()->student;
    return view('student.profile', compact('student'));
}