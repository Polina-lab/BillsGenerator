<table style="width:100%;color:#ffffff;font-family:'Montserrat',Arial,sans-serif; border:20px;border-style:solid;border-color:#ffffff;" cellpadding="0" border-spacing="0" cellspacing="0" border="0"  bgcolor="#26a647" align="center">
    <tbody><tr>
        <td style="width:2%"></td>
        <td  style="width:50%">
            <img src="https://test3.gloreal.ee/images/email/logoWhite.png" alt="I-gen" style="width: 90px; height: 35px;">
        </td>
        <td  rowspan="3" style="width: 48%;"><img  src="https://test3.gloreal.ee/images/email/letter_fon1.png" alt="I-gen logo" style="width: 290px; height: 110px;">
        </td>
    </tr>
    <tr>
        <td style="width:2%"></td>
        <td style="height:60px;vertical-align:top">
            <span style="font-size:14px">
                @if($data['theme'] == 'register')
                    <b>{{trans('letter.thank' , [] , $data['lang'] ?? 'en')}}</b>
                @elseif($data['theme'] == 'restore')
                     <b>{{trans('letter.reset_password' , [] , $data['lang'] ?? 'en')}}</b>
                @elseif($data['theme'] == 'download')
                    <span style="font-size: 14px">{{trans('letter.bill_from' , [] , $data['lang'] ?? 'en')}}
                        <strong>
                            {{ $data['firm_name'] }}
                        </strong>
                        {{trans('letter.using_gen' , [] , $data['lang'] ?? 'en')}}
                    </span>
                @endif
            </span>
        </td>
    </tr>
    <tr>
        <td style="width:2%"></td>
        <td style="height:50px;vertical-align:top">
            <span style="height:70px">
                     @if($data['theme'] == 'register')

                              <a style="padding:7px 20px;height:70px;background-color:#ffffff;border-radius:5px;color:rgb(38,166,71);font-size:15px;font-weight:600;white-space:nowrap">
                            {{ trans('letter.continue_login' , [] , $data['lang'] ?? 'en') }}
                              </a>
                    @elseif($data['theme'] == 'restore')
                      <a href="https://i-gen.ee/reset_password" target="_blank" style="padding:7px 20px;height:70px;background-color:#ffffff;border-radius:5px;color:#26a647;font-size:15px;font-weight:600;white-space:nowrap">
                                  {{trans('letter.reset' , [] , $data['lang'] ?? 'en')}}
                            </a>
                @elseif($data['theme'] == 'download')
              <a href="" target="_blank" style="padding:7px 20px;height:70px;background-color:#ffffff;border-radius:5px;color:#26a647;font-size:15px;font-weight:600;white-space:nowrap">
                      {{trans('letter.download_bill' , [] , $data['lang'] ?? 'en')}}
              </a>
            @endif
            </span>
        </td>
    </tr>
    </tbody>
</table><!--<table cellspacing="0" border="0" bgcolor="#26a647" align="center">
    <tbody><tr>
    </tr></tbody></table>-->
