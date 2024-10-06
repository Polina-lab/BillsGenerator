@extends('layouts.mail')
@section('content')
            <tr>
                <td>
                    <table  border="0" align="center" bgcolor="#ffffff" sellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 90%; color: #ffffff;">
                        <tbody>
                            <tr style="min-width: 100%; text-align: center; height: 40px; vertical-align: bottom; color: #26a647; font-size: 18px;">
                                <td>
                                    <span>
                                        <strong>
                                            {{ trans('letter.hello' , [ 'username' => $data['username'] ?? 'Demo user' ] , $data['lang'] ?? 'en' ) }}
                                        </strong>
                                    </span>
                                </td>
                            </tr>
                            <tr style="min-width: 100%; text-align: center; color: #9b9b9b;">
                                <td>
                                    <span style="font-size: 14px;">
                                        @if(isset($data['message']))
                                        {!! $data['message'] !!}
                                        @endif
                                    </span>
                                </td>
                            </tr>


                            <tr style="min-width: 100%; text-align: center; height: 30px; vertical-align: top; color: #9b9b9b;">
                                <td>
                                    <span style="font-size: 14px;">
                                        {{ trans('letter.button_download' , [] , $data['lang'] ?? 'en') }}
                                    </span>
                                </td>
                            </tr>

                            <tr style="min-width: 100%; text-align: center;">
                                <td style="height: 35px;">
                                    <span>
                                        <a href="" target="_blank" style="padding: 7px 20px 7px 20px; width: 200px; background-color: #fdb92d; border-radius: 5px; color: #ffffff;">
                                        {{ trans('letter.download_pdf' , [] , $data['lang'] ?? 'en') }}
                                        </a>
                                    </span>
                                </td>
                            </tr>

                            <tr style="min-width: 100%; text-align: center; height: 60px; color: #9b9b9b; border-bottom: 2px; border-bottom-style: solid; border-bottom-color: #26a647;">
                                <td style="line-height: 20px;">
                                    <span style="font-size: 14px;">
                                        {{ trans('letter.link_not_work' , [] , $data['lang'] ?? 'en') }}
                                    </span>
                                </td>
                            </tr>


                            <tr style="min-width: 100%; text-align: center; height: 80px; color: #9b9b9b;">
                                <td style="line-height: 20px;">
                                    <span style="font-size: 14px;">
                                        {{ trans('letter.you_got' , [] , $data['lang'] ?? 'en') }}
                                        Company Name
                                        {{ trans('letter.create_invoice' , [] , $data['lang'] ?? 'en') }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
@endsection
