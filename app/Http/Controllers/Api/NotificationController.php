<?php

namespace App\Http\Controllers\Api;
// require 'vendor/autoload.php';
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Netflie\WhatsAppCloudApi\WebHook;
use App\Models\User;

class NotificationController extends Controller
{
    public function whatsappNotification() {
        $payload = file_get_contents('php://input');
        fwrite(STDOUT, print_r($payload, true). "\n");

        $webhook = new WebHook();

        fwrite(STDOUT, print_r($webhook->read(json_decode($payload, true)), true). "\n");

        fwrite(STDOUT, print_r($webhook->readAll(json_decode($payload, true)), true). "\n");
    }

    public function emailNotification() {
        $userEmail = User::pluck('email')->toArray();
        
        return response()->json([
            'message' => 'Email sent successfully',
            'data' => $userEmail
        ], 200);
    }
}
