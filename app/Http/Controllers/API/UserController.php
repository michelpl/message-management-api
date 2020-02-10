<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;
    protected $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){
        if(Auth::attempt(['email' => $request->post('user'), 'password' => $request->post('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['user_id'] = $user->id;

            return response()->json(['success' => $success], $this-> successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorized'], 401);
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);

            $alreadyRegistered = $this->findByEmail($input['email']);

            if (!empty($alreadyRegistered->id)) {
                return response()->json(
                    "E-mail: " . $input['email'] . ' already in use',
                401
                );
            }
            $user = User::create($input);
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['name'] =  $user->name;
            return response()->json(['success'=>$success], $this-> successStatus);
        } catch (\Exception $exception) {
            return response()->json("Can't create user" . $exception->getMessage(), 401);
        }
    }

    public function show($id)
    {
        try {
            $this->user = $this->user->find($id);

            if (!$this->user) {
                return response('User id not found: ' . $id, 404);
            }

            return $this->user;

        } catch (\Exception $exception) {
            return response($exception->getMessage(), 400);
        }
    }

    public function findByEmail($email) {
        try {
            return $this->user->where(['email' => $email])->firstOrFail();

        } catch (\Exception $exception) {
            return response($exception->getMessage(), 400);
        }
    }
}
