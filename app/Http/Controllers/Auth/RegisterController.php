<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserOtp;
use App\Models\Coin;
use App\Models\Asset;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RegisterController extends Controller
{
    
  
    /**
    * Display the form for creating a new user.
    *
    * @method GET
    * @return \Illuminate\View\View
    */
    public function index($mid=null)
    {   
        return view('auth.register', compact('mid'));
    }

    /**
    * Register.
    *
    * @method POST
    * @return \Illuminate\Http\JsonResponse
    */
    public function register(Request $request)
    {
        $validated = $this->validator($request->all())->validate();

        try {
            
            $user = $this->create($validated);

            Auth::login($user);

            return response()->json([
                'status' => 'success',
                'message' => __('auth.register_success_notice'),
                'url' => route('home'),
            ]);

        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => __('auth.register_failed_notice'),
            ]);
        }
    }

   
    public function accountCheck(Request $request)
    {
        $account = trim($request->account);

        if ($account === '') {
            return response()->json([
                'status' => 'error',
                'message' => __('auth.id_enter_notice'),
            ]);
        }

        $exists = $account !== '' && User::where('account', $account)->exists();

        return response()->json([
            'status' => $exists ? 'error' : 'success',
            'message' => $exists ? __('auth.id_already_taken_notice') : __('auth.id_available_notice'),
        ]);
    }

    public function emailCheck(Request $request)
    {
        
        $exists = UserProfile::where('email', $request->email)->exists();

        return response()->json([
            'status' => $exists ? 'error' : 'success',
            'message' => $exists ? __('auth.email_already_taken_notice') : __('auth.email_available_notice'),
        ]);
    }

    public function parentCheck(Request $request)
    {
        
        $exists = User::where('id', $request->parentId)->exists();

        return response()->json([
            'status' => $exists ? 'success' : 'error',
            'message' => $exists ? __('auth.recommender_available_notice') : __('auth.recommender_not_found_notice'),
        ]);
    }

     /**
    * Form validation.
    *
    *
    * @return Illuminate\Support\Facades\Validator
    */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'account' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:16', 'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/', 'confirmed'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:9', 'max:12'],
            'parentId' => ['required', 'integer'],
            'metaUid' => ['nullable', 'string', 'max:50'],
        ]);
    }


    /**
    * Creating a new user.
    *
    *
    * @return App\Models\User
    */
    private function create(array $data)
    {
        DB::beginTransaction();

        try {

            $parent = DB::table('users')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('users.id', 'user_profiles.level')
            ->where('users.id', '=', $data['parentId'])
            ->first();

            if (!$parent) {
                throw new Exception('존재하지 않는 추천인 UID입니다.');
            }

            $user = User::create([
                'name' => $data['name'],
                'account' => $data['account'],
                'password' => Hash::make($data['password'])
            ]);
    
            $user_profile = UserProfile::create([
                'user_id' => $user->id,
                'parent_id' => $parent->id,
                'level' => $parent->level + 1,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'meta_uid' => $data['metaUid'],
            ]);

            UserOtp::create([
                'user_id' => $user->id,
            ]);

            $coins = Coin::pluck('id');

            foreach($coins as $id) {
                Asset::create([
                    'user_id' => $user->id,
                    'coin_id' => $id,
                ]);

                Income::create([
                    'user_id' => $user->id,
                    'coin_id' => $id,
                ]);
            }

            DB::commit();

            Log::channel('user')->info('Success to join', ['user_id' => $user->id]);

            return $user;

        } catch (Exception $e) {
          
            DB::rollBack();
            
            throw new Exception('회원가입에 실패하였습니다. 다시 시도해주세요.' . $e->getMessage());
        }        
    }

}
