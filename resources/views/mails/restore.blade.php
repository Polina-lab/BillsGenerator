@extends('layouts.mail')
@section('content')
    <tr>
        <td>
        <table  border="0" align="center" bgcolor="#ffffff" sellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 90%; background-color: #ffffff;">
        <tbody>
            <tr style="min-width: 100%; text-align: center; height: 35px; vertical-align: bottom; color: #26a647; font-size: 18px;">
                <td>
                                    <span>
                                        <strong>
                                            {{ trans('letter.hello' , [ 'username' => $data['username'] ?? '' ] , $data['lang'] ?? 'en' ) }}
                                        </strong>
                                    </span>
                </td>
            </tr>
            <tr style="min-width: 100%; text-align: center; color: #9b9b9b;">
                <td>
                                    <span style="font-size: 14px;">
                                        {{ trans('letter.reset_requested' , [] , $data['lang'] ?? 'en') }}
                                    </span>
                </td>
            </tr>
            <tr style="min-width: 100%; text-align: center; color: #9b9b9b;">
                <td>
                                    <span style="font-size: 14px;">
                                        {{ trans('letter.click_create' , [] , $data['lang'] ?? 'en') }}
                                    </span>
                </td>
            </tr>

            <tr style="min-width: 100%; text-align: center; height: 70px; color: #9b9b9b;">
                <td>
                                    <span>
                                        <a href="{{'https://i-gen.ee/login?email='.$data['mail'] .'&password='. $data['password'] }}" target="_blank" style="padding: 5px 20px 5px 20px; width: 200px; background-color: #fdb92d; border-radius: 5px; color: #ffffff;">
                                            {{ trans('letter.res_password' , [] , $data['lang'] ?? 'en') }}
                                        </a>
                                    </span>
                </td>
            </tr>

            <tr style="min-width: 100%; text-align: center; height: 30px; vertical-align: top; color: #9b9b9b;">
                <td>
                                    <span style="font-size: 14px;">
                                        {{ trans('letter.or_use' , [] , $data['lang'] ?? 'en') }}
                                    </span>
                </td>
            </tr>

            <tr style="min-width: 100%; text-align: center; height: 38px; ">
                <td style="height: 50px;">
                                    <span style="font-size: 17px; color: #000000;">
                                        <b>{{ $data['password'] }}</b>
                                    </span>
                </td>
            </tr>

            <tr style="min-width: 100%; text-align: center; height: 80px; vertical-align: top; color: #9b9b9b; border-bottom: 2px; border-bottom-style: solid; border-bottom-color: #26a647;">
                <td>
                                    <span style="font-size: 14px;">
                                        {{ trans('letter.please_login' , [] , $data['lang'] ?? 'en') }}
                                    </span>
                </td>
            </tr>

            <tr style="min-width: 100%; text-align: center; height: 70px; color: #9b9b9b;">
                <td>
                                    <span style="font-size: 14px;">
                                        {{ trans('letter.not_request' , [] , $data['lang'] ?? 'en') }}
                                    </span>
                </td>
            </tr>

            </tbody>
        </table>
        </td>
    </tr>
@endsection('content')
