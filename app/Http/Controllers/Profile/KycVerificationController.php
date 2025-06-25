<?php

namespace App\Http\Controllers\Profile;


use App\Models\UserProfile;
use App\Models\KycVerification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\CountryList;

class KycVerificationController extends Controller
{
    public function __construct()
    {
   
    }
    
    public function index(Request $request)
    {
        //$locale = Session::get('app_locale', 'en');
        $countries = CountryList::getCountries('en');

        return view('profile.kyc', compact('countries'));   
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'type' => 'required|string',
            'nationality' => 'required|string',
            'given_name' => 'required|string',
            'surname' => 'required|string',
            'id_number' => 'required|string',
            'file' => 'required|array|size:2',
            'file.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'date_of_birth' => 'required|date',
        ]);

        $file = $request->file('file');

        $timestamp = time();
        $folders = ['front', 'verify'];
        $file_url = [];
        $user_id = auth()->id();

        foreach ($request->file('file') as $index => $file) {
            if (!$file->isValid()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('iamge_upload_notice'),
                ]);
            }

            $folder = $folders[$index];
            $original_name = preg_replace("/[^a-zA-Z0-9._-]/", "", $file->getClientOriginalName());
            $file_name = "_{$timestamp}_{$user_id}_{$original_name}";
            $file->storeAs("public/uploads/kyc/{$folder}/", $file_name);

            $file_url[] = asset("storage/uploads/kyc/{$folder}/" . $file_name);
        }
    
        DB::beginTransaction();

        try {

            $kyc = KycVerification::where('user_id', auth()->id())->first();

            if($kyc) {
                $kyc->update([
                    'type' => $validated['type'],
                    'status' => 'pending',
                    'nationality' => $validated['nationality'],
                    'given_name' => $validated['given_name'],
                    'surname' => $validated['surname'],
                    'id_number' => $validated['id_number'],
                    'image_urls' => $file_url,
                'date_of_birth' => $validated['date_of_birth'],
                ]);
            } else {
                KycVerification::create([
                    'user_id' => auth()->id(),
                    'type' => $validated['type'],
                    'nationality' => $validated['nationality'],
                    'given_name' => $validated['given_name'],
                    'surname' => $validated['surname'],
                    'id_number' => $validated['id_number'],
                    'image_urls' => $file_url,
                    'date_of_birth' => $validated['date_of_birth'],
                ]);
            }

            DB::commit();
        
            return response()->json([
                'status' => 'success',
                'message' => __('system.submit_notice'),
                'url' => route('profile'),
            ]);
        
            
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => __('system.error_notice') . $e->getMessage(),
            ]);
        }

        
    }
}