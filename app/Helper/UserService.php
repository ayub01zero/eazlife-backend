<?php

namespace App\Helper;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Twilio\Rest\Client;

class UserService
{
    public $email, $password, $firstName, $lastName, $phoneNumber;

    public function __construct($email, $password, $firstName = "", $lastName = "", $phoneNumber = "")
    {
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phoneNumber = $phoneNumber;
    }

    public function validateInput($auth = false)
    {
        $validationRule = $auth ? 'exists:users' : 'unique:users';

        $validationRules = [
            'email' => ['required', 'email:rfc,dns,', $validationRule],
            'password' => ['required', 'string', Password::min(8)],
        ];

        if (!$auth) {
            $validationRules['firstName'] = ['required'];
            $validationRules['lastName'] = ['required'];
            $validationRules['phoneNumber'] = ['required', 'regex:/^(\+)?[0-9]+$/'];
        }

        $validator = Validator::make(['email' => $this->email, 'password' => $this->password, 'firstName' => $this->firstName, 'lastName' => $this->lastName, 'phoneNumber' => $this->phoneNumber], $validationRules);

        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->messages()];
        } else {
            return ['status' => true];
        }
    }

    public function register($deviceName)
    {
        $validate = $this->validateInput();
        if ($validate['status'] == false) {
            return $validate;
        } else {
            $verificationCode = rand(100000, 999999);
            // $this->sendSms($this->phoneNumber, $verificationCode);
            $user = User::create(['email' => $this->email, 'password' => Hash::make($this->password), 'name' => $this->firstName . ' ' . $this->lastName, 'phone_number' => $this->phoneNumber, 'verification_code' => $verificationCode]);
            $user->roles()->attach(5); // App user role
            $user->save();
            $token = $user->createToken($deviceName)->plainTextToken;
            return ['status' => true, 'token' => $token, 'user' => $user];
        }
    }

    

    public function sendSms($phoneNumber, $code)
    {
        // Your Account SID and Auth Token from console.twilio.com
        $sid = env("TWILIO_SID");
        $token = env("TWIKIO_TOKEN");
        $client = new Client($sid, $token);

        // Use the Client to make requests to the Twilio REST API
        $client->messages->create(
            // The number you'd like to send the message to
            $phoneNumber,
            [
                // A Twilio phone number you purchased at https://console.twilio.com
                'from' => '+14437648159 ', // Replace with your Twilio number
                // The body of the text message you'd like to send
                'body' => "Your verification code is {$code}"
            ]
        );
    }

    public function login($deviceName, $expoPushToken)
    {
        $validate = $this->validateInput(true);
        if ($validate['status'] == false) {
            return $validate;
        } else {
            $user = User::whereEmail($this->email)->first();
            if (!$user) {
                return ['status' => false, 'errors' => ['email' => ['User not found']]];
            } else {
                if (Hash::check($this->password, $user->password)) {
                    if ($user->verification_code !== null) {
                        return ['status' => false, 'errors' => ['verification_code' => ['Verification code not confirmed']], 'phone_number' => $user->phone_number];
                    }

                    $user->savePushToken($expoPushToken);
                    $token = $user->createToken($deviceName)->plainTextToken;
                    return ['status' => true, 'token' => $token, 'user' => $user];
                } else {
                    return ['status' => false, 'errors' => ['password' => ['Incorrect password']]];
                }
            }
        }
    }
}
