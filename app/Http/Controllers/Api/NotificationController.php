<?php

namespace App\Http\Controllers\Api;
// require 'vendor/autoload.php';
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Netflie\WhatsAppCloudApi\WebHook;
use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;



class NotificationController extends Controller
{
    public function whatsappNotification() {
        try {
            $payload = file_get_contents('php://input');
            fwrite(STDOUT, print_r($payload, true). "\n");

            $webhook = new WebHook();

            fwrite(STDOUT, print_r($webhook->read(json_decode($payload, true)), true). "\n");

            fwrite(STDOUT, print_r($webhook->readAll(json_decode($payload, true)), true). "\n");
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Whatsapp failed to send',
                'data' => $error->getMessage()
            ], 400);
        }
    }

    public function emailNotification() {
        try {
            $notifications = auth()->user()->unreadNotifications;
            foreach($notifications as $notification) {
                $notification->time = $notification->created_at->diffForHumans();
            }

            return response()->json([
                'message' => 'Notification sent successfully',
                'notif' => $notifications
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'Error fetching notification',
                'data' => $error->getMessage()
            ], 400);
        }
    }
}
