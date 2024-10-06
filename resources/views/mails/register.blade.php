@extends('layouts.mail')
@section('content')
<tr>
    <td>
    <table  border="0" align="center" bgcolor="#ffffff" sellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 90%; color: #ffffff;">
    <tbody>
    <tr style="min-width:100%;text-align:center;height:30px;vertical-align:bottom;color:#26a647;font-size:17px;background-color: rgb(255 , 255 , 255)">
            <td colspan="2" >
                                    <span>
                                        <strong>
                                             {{ trans('letter.welcome_to_gen' , [] , $data['lang'] ?? 'en') }}
                                        </strong>
                                    </span>
            </td>
        </tr>

        <tr style="min-width:100%;text-align:center;height:10px;color:#9b9b9b">
            <td colspan="2">
                                    <span style="font-size:14px">
                                        {{ trans('letter.for_signing' , [] , $data['lang'] ?? 'en') }}
                                    </span>
            </td>
        </tr>

        <tr style="min-width:100%;text-align:center;height:10px;color:#9b9b9b">
            <td colspan="2">
                                    <span style="font-size:14px">
                                         {{ trans('letter.account_with' , [] , $data['lang'] ?? 'en') }}
                                            <a href="mailto:info@gloreal.ee" style="color:#26a647" target="_blank">info@gloreal.ee</a>
                                    </span>
            </td>
        </tr>


        <tr style="min-width:100%;text-align:center;height:40px;vertical-align:bottom;color:#9b9b9b">
            <td colspan="2">
                                    <span style="font-size:14px">
                                         {{ trans('letter.continue_the_registration' , [] , $data['lang'] ?? 'en') }}
                                    </span>
            </td>
        </tr>

        <tr style="min-width:100%;text-align:center;height:10px">
            <td colspan="2" style="height:50px">
                                    <span>
                                        <a href="{{'https://i-gen.ee/login?email='.$data['mail'] .'&password='. $data['password'] }}" style="padding:5px 40px 5px 40px;width:200px;background-color:#fdb92d;border-radius:5px;color:#ffffff">
                                             {{ trans('letter.continue' , [] , $data['lang'] ?? 'en') }}
                                        </a>
                                    </span>
            </td>
        </tr>

        <tr style="min-width:100%;text-align:center;color:#9b9b9b">
            <td colspan="2">
                                    <span style="font-size:14px">
                                        {{ trans('letter.complete_procedures' , [] , $data['lang'] ?? 'en') }}
                                    </span>
            </td>
        </tr>

        <tr style="min-width:100%;text-align:center;height:60px;color:#9b9b9b">
            <td colspan="2">
                                    <span style="font-size:18px;color:#000000">
                                        <b>{{ $data['password'] }}</b>
                                    </span>
            </td>
        </tr>

        <tr style="min-width:100%;text-align:center;height:70px;vertical-align:top;color:#9b9b9b;border-bottom:2px;border-bottom-style:solid;border-bottom-color:#26a647">
            <td colspan="2">
                                    <span style="font-size:14px">
                                        {{ trans('letter.generated_password' , [] ,  $data['lang'] ?? 'en') }}
                                    </span>
            </td>
        </tr>

        <tr style="min-width:100%;text-align:center;height:90px;color:#9b9b9b">
            <td colspan="2">
                                    <span style="font-size:14px">
                                        {{ trans('letter.please_ignore' , [] , $data['lang'] ?? 'en') }}
                                    </span>
            </td>
        </tr>
        </tbody>
        </table>
        </td>
    </tr>
@endsection
