<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocialShareButtonsController extends Controller
{
    public function shareWidget(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|string',
        ]);

        error_log("requestURL: " . $request->url);

        if ($validator->fails())
            return response()->json([
                'status' => 'Bad Request',
                'msg' => 'Failed to fetch socials widget. Bad request',
                'errors' => $validator->errors(),
            ], 400);


        $shareComponent = \Share::page(
            $request->url,
            'Wassup ma boi, check this website',
        )
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();

        return response()->json([
            'html' => view('partials.share', compact('shareComponent'))->render(),
        ], 200);
    }
}
