<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'avatar' => 'nullable|image|mimes:jpg,png,bmp',
            'email' => 'required|string|email|max:100|unique:users',
            // 'password' => 'required|string|min:6|confirmed',
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required_with:password',
            'gender' => 'required|between:3,4',
            'accept' => 'required|in:1'
            // |same:password|min:6
            // 'fcm_token' => 'nullable'
        ]);

        // $accept = $request->has('accept') ? true : false;
        // User::where('accept', $request->accept)->first();

        $errors = $validator->errors();

        if ($errors->has('email')) {
            return response()->json([
                'message_en' => 'The email has already been taken.',
                'message_ar' => "الايميل مستخدم من قبل",
                'code' => 400,
                'status' => false,
            ], 400);
        } else if ($errors->has('accept')) {
            return response()->json([
                'message_en' => 'You don\'t accept terms.',
                'message_ar' => "يجب قراءة الشروط",
                'code' => 400,
                'status' => false,
            ], 400);
        }
        //  else if($errors){
        //     return response()->json([
        //         'message_en' => $errors,
        //         'message_ar' => "حدث خطأ",
        //         'code' => 400,
        //         'status' => false,
        //     ], 400);
        // }

        $user = User::create(
            $validator->validated()
        );

        // && $request->avatar
        // if ($accept)
            return response()->json([
                'message_en' => 'User successfully registered',
                'message_ar' => 'تم التسجيل بنجاح',
                'code' => 200,
                'status' => true,
                'data' => $user
            ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6',
            'device_name' => 'string|max:255',
            // 'remember_me' => 'nullable|in:0,1'
            // 'fcm_token' => 'required'
        ]);
        //  $user = Auth::guard('sanctum')->user();

        $user = User::where('email', $request->email)->first();
        // if(auth('sanctum')->attempt([$user, Hash::check($request->password, $user->password)],$remember_me))
        // if (Auth::guard('sanctum')->attempt([
        //     $user,
        //     Hash::check($request->password, $user->password)
        // ], $remember_me))
        if ($user &&
         Hash::check($request->password, $user->password)
        //  && $remember_me
         ) {

            // $user = auth()->user();
            //  if ( $user) {

            // $user_id = $user->id;
            // $user->update(['fcm_token' => $request->fcm_token]);

            // dd($user);
            $device_name = $request->post('device_name', $request->userAgent());
            $token = $user->createToken($device_name);
            $user['token'] = $token->plainTextToken;
            $user['avatar'] = $user->avatar_url;

            return Response::json([
                'message_en' => "login",
                'message_ar' => "تسجيل الدخول",
                'code' => 200,
                'status' => true,
                'data' => $user,
            ], 201);
        }

        return Response::json([
            'message_en' => ' Login created Faild',
            'message_ar' => 'خطأ في تسجيل الدخول',

            'code' => 200,
            'status' => false,
            'message' => 'Invalid credentials',
        ], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(Request $request) {
        $token = $request->header('Authorization');

        $arr = explode(' ',$token);


        $user_id =$request->user()->id;

        $mytoken =  AccessToken::where('tokenable_id', '=',$user_id)->get();


        return response()->json(
            [
                'message_en' => 'User Profile',
                'message_ar' => 'الملف الشخصي',
                'code' => 200,
                'status' => true,
                'data' => $request->user()
            ], 201);
    }

    // update user-profile
    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|between:2,100',
            'email' => 'nullable|string|email|max:100',
            'avatar' => 'nullable|image|mimes:jpg,png,bmp',
            // 'gender' => 'nullable|string|between:3,4',
            // 'fcm_token' => 'nullable'
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=> 'Validations fails',
                'errors'=>$validator->errors()
            ],422);
        }
        $user = $request->user();

        if($request->hasFile('avatar')){
            if($user->avatar){
                $old_path = public_path().
                'assets/'.$user->avatar;
                if(File::exists($old_path)){
                    File::delete($old_path);
                }
            }
            $image_name = 'profile-image-'.rand().time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('/assets'),$image_name);
        }else{
            $image_name=$user->avatar_url;
        }


        $user->update([
            'name'=>$request->name,
            'phone_number'=>$request->phone_number,
            'email'=>$request->email,
            'avatar'=>$image_name
        ]);

        return response()->json([
            'message_en'=>'Profile successfully updated',
            'message_ar'=>'تم تحديث الملف الشخصي بنجاح',

            'code' => 200,
            'status' => true,
            'data'=>$request->user()
        ],200);


    }


    // public function getOrdersCountAndMembership(Request $request)
    // {

    //     $user_id =    $request->user()->id;

    //     $membership = $request->user()->membership;

    //     $order_count = Order::where('user_id' ,'=' , $user_id)->get()->count();


    //      return $this->returnSuccessMessage('ارجاع بيانات المستخدم بنجاح','Success return user data',200,true,
    //           [
    //           'order_count'=>$order_count,
    //           'membership'=>$membership,
    //           ]
    //     );



    // }
    // public function validateMobile(Request $request)

    //   {
    //     $phoneUser =  $request->phone;


    //   $test =  User::where('phone_number','=',$phoneUser)->first();



    //     if( empty($test) ) {
    //         return response()->json([



    //             'message_en' => 'There is no account associated with this number',
    //             'message_ar' => ' لا يوجد حساب مرتبط بهذا الرقم',
    //             'code' => 200,
    //             'status' => false,
    //         ], 200);


    //     }else {
    //         return response()->json([
    //             'message_en' => 'The phone number has already been taken.',
    //             'message_ar' => 'رقم الهاتف مستخدم من قبل.',
    //             'code' => 200,
    //             'status' => true,
    //         ], 200);
    //     }

    //   }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message_en' => 'User successfully signed out',
            'message_ar' => 'تم تسجيل الخروج بنجاح',
            'code' => 200,
            'status' => true,
            'data' => $request->user()
        ], 200);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function refresh() {
    //     return $this->createNewToken(auth()->refresh());
    // }

    public function deleteAccount(Request $request)
    {

        $user_image = $request->user()->avatar;

        File::delete(public_path('assets/images/auth/' . $user_image));

        $user = Auth::guard('sanctum')->user();
        $user->delete();


        return response()->json([
            'message_en' => 'User account successfully deleted',
            'message_ar' => 'تم حذف الحساب بنجاح',

            'code' => 200,
            'status' => true,
        ], 200);
    }
}
