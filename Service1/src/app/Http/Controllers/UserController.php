<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Jobs\NotificationJob;

use Illuminate\Support\Facades\{
    DB,
	Validator
};

use PhpAmqpLib\Connection\AMQPLazyConnection;
use PhpAmqpLib\Message\AMQPMessage;

class UserController extends Controller
{
    //
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function newUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data =[
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ];

        $user = $this->userRepository->create($data);
        NotificationJob::dispatch($data);

       
        return response()->json(['message' => 'User created successfully', 'user' => $user], 200);
    }



    
    
}
