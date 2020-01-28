<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserInfoRequest;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;


class UserInfoController extends Controller
{
    /**
     * Store user data/file in storage
     *
     * @param StoreUserInfoRequest $request
     * @param string $lang
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeUserInfo(StoreUserInfoRequest $request, $lang = 'en')
    {
        $data = $request->only('email', 'userinfo', 'birth_date');
        $path = $request->file('userinfo')->store($lang);
        $date = \Carbon\carbon::createFromFormat('d/m/Y', $data['birth_date'])->format('Y-m-d');

        Auth::user()->update(['birth_date' => $date]);
        $result = Result::create(['user_id' => Auth::id(), 'path' => $path, 'results_filled_date' => now()]);

        return response()->json(['data' => $result]);
    }


    /**
     * Download file from storage
     *
     * @param $result
     * @return \Illuminate\Http\Response
     */
    public function getStorageFile($result)
    {
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $result . '"',
        ];

        return Response::make(Storage::get($result, 200, $headers));
    }

    /**
     * Return redirect to temporary link on file in storage
     *
     * @param $result
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getStorageFileUrl($result)
    {
        $url = Storage::temporaryUrl(
            $result, now()->addMinutes(10)
        );

        return response()->redirectTo($url);
    }


}
