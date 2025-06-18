use Illuminate\Support\Facades\Auth;

// Inside RegisteredUserController

protected function registered(Request $request, $user)
{
    // Optional: Log the user out after registration
    Auth::logout();

    // Redirect to login page
    return redirect()->route('login');
}