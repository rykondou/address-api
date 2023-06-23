<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddressData;

class PostalCodeController extends Controller
{
    public function search(Request $request)
    {
        //リクエストからpostalCodeというkeyでvalueを受け取る
        $code = $request->postalCode;
        //DB接続用try-catch
        try {
            $addresses = AddressData::where('postalCode', $code)->get();
        } catch (\Exception) {
            return response()->json(
                [
                    'message' => 'データベースに接続できませんでした',
                    'result' => null,
                    'status' => 500
                ],
                500,
                options: JSON_UNESCAPED_UNICODE
            );
        }
        //一致するレコードがからの場合return
        if (empty($addresses)) {
            return response()->json(
                [
                    'message' => '指定した郵便番号の住所が見つかりませんでした',
                    'result' => null,
                    'status' => 404
                ],
                404,
                options: JSON_UNESCAPED_UNICODE
            );
        }
        //DBから取得した$addressから必要なカラムのみ抜粋し$resultに代入
        $result = [];
        foreach ($addresses as $key => $value) {
            $address = [
                'address1' => $value->address1,
                'address2' => $value->address2,
                'address3' => $value->address3,
                'kana1' => $value->kana1,
                'kana2' => $value->kana2,
                'kana3' => $value->kana3,
                'prefCode' => $value->prefCode,
                'postalCode' => $value->postalCode,
            ];
            $result[$key] = $address;
        };
        //JSONで$resultを返す
        return response()->json(
            [
                'message' => null,
                'result' => $result,
                'status' => 200
            ],
            200,
            options: JSON_UNESCAPED_UNICODE
        );
    }
}
