<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\UserRegisteredNotification;
use Brackets\AdminAuth\Models\AdminUser;
use App\Notifications\AdminConfirmUserNotification;
use Illuminate\Support\Facades\Notification;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Register user via application and page and send notifications to user and admin
     *
     * @param Request $request
     * @param string $lang
     * @return JsonResponse Beeil dich! Das Angebot endet in:
     */
    public function register(Request $request, $lang = 'da')
    {
        $data = $request->all();
        $this->validator($data)->validate();

        $data['remember_token'] = md5(time().$data['email']);
        $user = $this->create($data, $lang);

        // Send notification to user.
        $this->registered($request, $user, $lang);

        $admin = AdminUser::firstOrFail();

        // Send notification to admin.
        Notification::send($admin, new AdminConfirmUserNotification($user));

        if($request->ajax()){
            return response()->json(['success' => true ]);
        }

        return response()->json(['user' => $user]);
    }


    /**
     * Send email notification to user
     *
     * @param Request $request
     * @param $user
     * @param $lang
     */
    protected function registered(Request $request, $user, $lang)
    {
        $user->notify(new UserRegisteredNotification($user, $lang));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @param $lang
     * @return \App\User
     */
    protected function create(array $data, $lang)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'remember_token' => $data['remember_token'],
            'lang' => $lang
        ]);
    }
}
