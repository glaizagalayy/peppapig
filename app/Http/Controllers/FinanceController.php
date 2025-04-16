public function profile()
{
    $finance = Auth::user()->finance;
    return view('finance.profile', compact('finance'));
}