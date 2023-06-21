<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddressData;
use Illuminate\Support\Facades\DB;

class ZipCodeController extends Controller
{
    //
    public function search($code)
    {
        $addresses = AddressData::where('zipcode', $code)->get();
        //一番大外の配列を初期化
        $result = [];
        foreach ($addresses as $key => $value) {
            $address = [
                'address1' => $value->address1,
                'address2' => $value->address2,
                'address3' => $value->address3,
                'kana1' => $value->kana1,
                'kana2' => $value->kana2,
                'kana3' => $value->kana3,
                'prefcode' => $value->prefcode,
                'zipcode' => $value->zipcode,
            ];
            $result[$key] = $address;
        };
        //指定した郵便番号に一致した住所があれば'result'を返す
        if ($result) {
            return response()->json(
                [
                    'message' => null,
                    'result' => $result,
                    'status' => 200
                ],
                200,
                options: JSON_UNESCAPED_UNICODE
            );
        } else {
            return response()->json(
                [
                    'message' => '指定した郵便番号の住所が見つかりません',
                    'result' => null,
                    'status' => 404
                ],
                404,
                options: JSON_UNESCAPED_UNICODE
            );
        }
    }
}
