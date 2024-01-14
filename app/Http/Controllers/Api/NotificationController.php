<?php

namespace App\Http\Controllers\Api;
require 'vendor/autoload.php';
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Netflie\WhatsAppCloudApi\WebHook;

class NotificationController extends Controller
{
    public function whatsappNotification() {
        $payload = file_get_contents('php://input');
        fwrite(STDOUT, print_r($payload, TRUE). "\n");

        $webhook = new WebHook();

        fwrite(STDOUT, print_r($webhook->read(json_decode($payload))))
    }
}
