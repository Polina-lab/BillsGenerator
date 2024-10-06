<html>
<head>
    <meta charset="utf-8">
    <style >
        @font-face {
            font-family: 'Astra';
            font-style: normal;
            font-weight: 500;
            src: url('{{ storage_path('fonts/Astra_font/PT_Astra_Sans_Regular.ttf') }}');
        }

        .under_border{
            border-bottom: 1px solid #000000;
        }

        .invoice-box {
            margin: auto;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, .15); */
            font-size: 15px;
            line-height: 0.9em;
            font-family: Astra, Helvetica , "sans-serif";
            color: rgb(0, 0, 0);
        }

        .invoice-box table{
            width: 100%;
            max-width: 1200px;
            line-height: inherit;
            text-align: left;
            margin: auto;
        }

        .invoice-box.text-mode-width table {
            width: 400px;
        }

        #details_table {
            width: 100%;
            max-width: 1200px;
            line-height: inherit;
            text-align: left;
        }

        #inv {
            width: 100%;
            max-width: 1200px;
            line-height: inherit;
            text-align: left;
        }

        .mw450{
            max-width: 500px;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 2px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
        }

        thead{
            background-color: #00b3ff;
        }

        .invoice-box table tr.details table {
            border: 1px solid lightgray;
            border-collapse: collapse;
        }


        .invoice-box table tr.details table td{
            border: 1px solid lightgray;
            border-collapse: collapse;
        }

        #t1{
            height: 260px;
            width:250px;
            border: #0c1021 solid 1px;
        }

        #t1 table {
            max-width: 250px;
        }

        #t2{
            height: 260px;
            width:250px;
            border: #0c1021 solid 1px;
            float: right;
        }

        #t2 table {
            max-width: 250px;
        }

        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .text-right{
            text-align: right;
        }
        .text-center{
            text-align: center;
            /* font-size: 1.2vw; */
        }

        .text-left{
            text-align: left;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
        }



        p + p{
            margin-bottom: 0.2rem !important;
        }

    </style>

</head>

<body>
<div class="invoice-box">
    <div class="container-fluid" >
        <div class="invoice-box" >
            <table class="main-table" cellpadding="0" cellspacing="0">
                <tr class="top" >
                    <td colspan="2">
                        <table>
                            <tr>
                                <td class="title" colspan="2">
                                    @if(isset($data['firms']))
                                    <img data-attr="firm" class="firm"
                                         src='http://broker.gloreal.ee/image/logo2.png'
                                         alt="{{ $data['firms' ]['company_name'] }}"
                                         style="width:100%; max-width:250px;max-height: 200px;">
                                     @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="information">
                    <td>
                        <div id="t1">
                            @if(isset($data['firms']))
                            <table>
                                <thead>
                                <tr>
                                    <th class="text-left" colspan="2">{{ trans('bills.beneficiary', [] ,$data['locale'])}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($data['firms']['company_name']))
                                <tr>
                                    <td colspan="2" class="text-left">
                                        <strong data-attr="firm" class="firm under_border" >
                                            {{ $data['firms']['company_name'] }}
                                        </strong>
                                    </td>
                                </tr>
                                @endif
                                @if(isset($data['firms']['reg_num']))
                                <tr >
                                    <td colspan="2" class="text-left">
                                        <strong>{{ trans("bills.reg_num", [] ,$data['locale'])}}: </strong> <span  class="firm" id="reg_num" > {{ $data['firms']['reg_num']}}</span>
                                    </td>
                                </tr>
                                @endif
                                @if(isset($data['firms']['address']))
                                <tr>
                                    <td colspan="2" class="text-left">
                                        <strong>{{ trans("bills.address", [] ,$data['locale'])}}: </strong>{{ $data['firms']['address'] }}
                                    </td>
                                </tr>
                                @endif
                                @if(isset($data['firms']['phone']))
                                <tr>
                                    <td colspan="2" class="text-left">
                                        <strong>{{ trans("bills.phone", [] ,$data['locale'])}}: </strong><span  id="phone" class="firm">
                                            {{ $data['firms']['phone']}}</span>
                                    </td>
                                </tr>
                                @endif
                                @if(isset($data['firms']['banks']['bank_name']))
                                <tr>
                                    <td colspan="2" class="text-left">
                                        <strong>{{ trans("bills.bank", [] ,$data['locale'])}}:</strong>
                                        <span id="bank_name" class="bank" >{{ $data['firms']['banks']['bank_name']  }}</span>
                                    </td>
                                </tr>
                                @endif
                                @if(isset($data['firms']['banks']['bank_num']))
                                <tr >
                                    <td colspan="2" class="text-left">
                                        <strong>{{ trans("bills.bank_num", [] ,$data['locale'])}}:</strong>
                                        <span  id="bank_num" class="bank">
                                            {{ $data['firms']['banks']['bank_num']  }}</span>
                                    </td>
                                </tr>
                                @endif
                                @if(isset($data['firms']['banks']['bank_account']))
                                <tr>
                                    <td colspan="2" class="text-left">
                                        <strong>{{ trans("bills.acc_num", [] ,$data['locale'])}}:</strong><span  data-attr="bank"  id="bank_account" class="bank">{{ $data['firms']['banks']['bank_account']}}</span>
                                    </td>
                                </tr>
                                @endif
                                @if(isset($data['firms']['banks']['bank_swift']))
                                <tr>
                                    <td colspan="2" class="text-left">
                                        <strong>{{ trans("bills.swift", [] ,$data['locale'] ??  'en')}}:</strong>
                                        <span  id="bank_swift" class="bank" >{{ $data['firms']['banks']['bank_swift'] }}</span>
                                    </td>
                                </tr>
                                @endif
                                @if(isset($data['firms']['banks']['bank_address']))
                                <tr>
                                    <td colspan="2" class="text-left">
                                        <strong>{{ trans("bills.bank_address", [] ,$data['locale'] ??  'en')}}: </strong>
                                        <span id="bank_address" class="bank">{{ $data['firms']['banks']['bank_address']  }}</span>
                                    </td>
                                </tr>
                                @endif
                                @if(isset($data['firms']['banks']['km_reg_num']))
                                <tr>
                                    <td class="text-left">
                                        <strong>{{ trans("bills.km_reg_nr", [] ,$data['locale'] ??  'en')}}: </strong>
                                        <span  id="km_reg_num" class="bank" >
                                            {{ $data['firms']['banks']['km_reg_num'] }}</span>
                                    </td>
                                </tr>
                                @endif
                                </tbody>
                            </table>
                                @endif
                        </div>
                    </td>
                    <td>
                        <div id="t2">
                            <table>
                                <thead>
                                <tr>
                                    <th colspan="2" class="text-left">
                                        {{ trans('bills.refered_to', [] ,$data['locale'] ??  'en'  )}}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="2" class="text-left">
                                        <strong class="under_border">
                                            @if(isset($data['clients']) && isset($data['clients']['name']) || isset($data['clients'][0]['name']) )
                                                {{ $data['clients']['name'] ??  $data['clients'][0]['name'] }}
                                            @elseif(isset($data['clients']) &&  isset($data['clients']['firstname']) || isset($data['clients'][0]['firstname']))
                                            {{ $data['clients']['firstname'] ?? $data['clients'][0]['firstname']   }} {{ $data['clients']['lastname'] ?? $data['clients'][0]['lastname'] }}
                                            @endif
                                        </strong>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-left">
                                    @if(isset($data['clients']['reg_num']) || isset($data['clients'][0]['reg_num']))
                                        <strong>
                                        {{ trans("bills.reg_num", [] ,$data['locale'] ??  'en')}}:
                                        </strong>
                                        {{ $data['clients']['reg_num']  ?? $data['clients'][0]['reg_num']}}
                                    @elseif(isset($data['clients']['reg_num']) || isset($data['clients'][0]['reg_num']))
                                         <strong >
                                        {{ trans("bills.reg_num", [] ,$data['locale'] ??  'en')}}:
                                         </strong>
                                        {{ $data['clients']['reg_num'] ?? $data['clients'][0]['reg_num'] }}
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-left" >
                                        @if( isset($data['clients']['address']) || isset($data['clients'][0]['address']))
                                        <strong>{{ trans("bills.address" , [] ,$data['locale'] ??  'en')}}:</strong>
                                        {{ $data['clients']['address'] ?? $data['clients'][0]['address']  }}
                                        @elseif(isset($data['clients']['address']) || isset($data['clients'][0]['address']))
                                        <strong>{{ trans("bills.address" , [] ,$data['locale'] ??  'en')}}:</strong>
                                        {{ $data['clients']['address'] ?? $data['clients'][0]['address']  }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-left">
                                        @if(isset($data['clients']['phone']) || isset($data['clients'][0]['phone']))
                                        <strong>{{ trans("bills.phone", [] ,$data['locale'] ??  'en')}}:</strong>
                                        {{ $data['clients']['phone'] ?? $data['clients'][0]['phone'] }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    @if(isset($data['clients']['firstname'])  || isset($data['clients'][0]['firstname']))
                                    <td colspan="2" class="text-left">
                                        <strong>
                                            {{trans("bills.represented_by", [] ,$data['locale'] ??  'en')}}:
                                        </strong>
                                        {{ $data['clients']['firstname'] ?? $data['clients'][0]['firstname'] }} {{  $data['clients']['lastname'] ?? $data['clients'][0]['lastname'] }}
                                    </td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr >
                    <td></td>
                    <td >
                        <table id="inv">
                            @if(isset($data['prepaid']))
                            <tr>
                                <td class="text-right" colspan="2">
                                    @if( $data['prepaid'] === 0  )
                                        <strong >{{ trans("bills.invoice", [] ,$data['locale'] ??  'en') }}</strong>
                                    @elseif( $data['prepaid'] === 1)
                                        <strong >{{ trans("bills.prepaid", [] ,$data['locale'] ??  'en') }}</strong>
                                    @endif
                                </td>
                            </tr>
                            @endif
                                @if(isset($data['invoice']))
                                    <tr>
                                        <td class="text-right"><strong>{{ trans("bills.invoice_number", [] ,$data['locale'] ??  'en')}}:</strong></td>
                                        <td class="text-right" > <!-- :class="{ 'box-width': previewData.is_pdf_pic_mode }" -->
                                            {{ $data['invoice'] }}
                                        </td>
                                    </tr>
                                @endif

                                @if(isset($data['date']))
                                    <tr>
                                        <td class="text-right"><strong>{{ trans("bills.invoice_date", [] ,$data['locale'] ??  'en')}}:</strong></td>
                                        <td class="text-right" > <!--:class="{ 'box-width': previewData.is_pdf_pic_mode }"-->
                                        {{ $data['date'] }}
                                        </td>
                                    </tr>
                                @endif

                                @if(isset($data['deadline']))
                                    <tr >
                                        <td class="text-right"><strong>{{trans("bills.deadline",  [] ,$data['locale'] ??  'en')}}:</strong></td>
                                        <td class="text-right" > <!--:class="{ 'box-width': previewData.is_pdf_pic_mode }"-->
                                            {{ $data['deadline'] }}
                                        </td>
                                </tr>
                                @endif
                                @if(isset($data['payment_method']))
                                    <tr >
                                        <td class="text-right" colspan="2"><strong>{{ trans('bills.' . $data['payment_method_name'], [] ,$data['locale']) }}</strong></td>
                                    </tr>
                                @endif

                        </table>
                    </td>
                </tr>
                <tr class="details">
                    <td colspan="2">
                        <table id="details_table">
                            <thead>
                            <tr>
                                <th colspan="3" class="text-left">{{ trans('bills.explanation', [] ,$data['locale'] ??  'en')}}</th>
                                <th class="text-center">{{ trans('bills.unit', [] ,$data['locale'] ??  'en')}}</th>
                                <th class="text-center">{{ trans('bills.amount', [] ,$data['locale'] ??  'en')}}</th>
                                <th class="text-center">{{ trans('bills.price_per_unit', [] ,$data['locale'] ??  'en')}}</th>
                                <th class="text-center">{{ trans('bills.total', [] ,$data['locale'] ??  'en') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($data['orders']))
                                @foreach($data['orders'] as $d)
                            <tr >
                                <td colspan="3" class="text-left mw450" >
                                    <span >{{ $d['name'] }}</span>
                                    <span >{{ $d['desc'] }}</span>
                                </td>
                                <td  class="text-center">
                                    @if($d['unit'] === 'Another')
                                        {{ $d['unit_custom'] }}
                                    @else
                                       {{ $d['unit'] }}
                                    @endif
                                </td>
                                <td class="text-center">

                                    @if(isset($d['amount']))
                                        {{ $d['amount'] }}
                                    @endif
                                </td>
                                <td class="text-right" style="white-space:nowrap" >
                                        {{ isset($d['unit_price']) ? $d['unit_price'] : '0.00' }}
                                 </td>
                                <td class="text-right" style="white-space:nowrap" >
                                    @if(isset($d['price']))
                                        {{ $d['price']  . " " . $data['currency'] }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endif
                            <tr>
                                <td colspan="7">
                                    <span >{!!  isset($data['comment']) ? $data['comment'] .'<br/>' : '<br/><br/><br/>' !!}</span>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>

                            @if(isset($data['price_no_km']))
                            <tr class="w_b" >
                                <th colspan="6" class="text-right ">{{ trans("bills.total_withoutKM", [] ,$data['locale'] ??  'en')}}:</th>
                                <td>{{ $data['price_no_km']  . " " . $data['currency'] }}</td>
                            </tr>
                            @endif
                            @if(isset($data['price_km']))
                            <tr>
                                <th colspan="6" class="text-right">{{ trans("bills.KM_percent", ['km_percent'=> $data['km_percent'] ?? 0,5] ,$data['locale'] ?? 'en') }}:</th>
                                <td style="white-space:nowrap">
                                <span >{{ $data['price_km'] . " " . $data['currency'] }}</span></td>
                            </tr>
                            @endif
                            @if(isset($data['price_with_km']))
                            <tr>
                                <th colspan="6" class="text-right ">{{ trans("bills.total" , [] ,$data['locale'] ??  'en')}}:</th>
                                <td style="white-space:nowrap">
                                    <span>{{ $data['price_with_km'] . " " . $data['currency']}}</span></td>
                            </tr>
                            @elseif(isset($data['price']))
                            <tr>
                                <th colspan="6" class="text-right ">{{ trans("bills.total" , [] ,[] ,$data['locale'] ??  'en')}}:</th>
                                <td style="white-space:nowrap">
                                    {{ $data['price']. " " . $data['currency'] }}</td>
                            </tr>
                            @endif
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        @if(isset($data['penalty']))
                        <small >
                            {{ trans("bills.pro_text",  ["penalty" => $data['penalty']] , $data['locale'] ?? [] )}}
                        </small>
                        @else
                        <small >
                             {{ trans("bills.pro_text", ["penalty"=> 0.5] , $data['locale'] ?? 'en'  )}}
                        </small>
                        @endif
                    </td>
                </tr>
          {{--      <tr class="notify">
                    <td>
                        <div  style="margin-top: 30px">
                            <strong>{{ trans("bills.notify", $data['locale'] ??  'en')}}</strong>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr class="notify-text">
                    <td colspan="2" >
                        <strong>{{ trans("bills.notify_text", [] ,$data['locale'] ??  'en')}}</strong>
                    </td>
                    <td></td>
                </tr>--}}
                <tr>
                    <td>
                        <div style="width: 260px; margin-top: 40px">
                            <strong>{{ trans("bills.Send_by", [] ,$data['locale'] ??  'en')}}</strong>
                            <br>
                            @if( isset($data['sender_user'])  && isset($data['sender_user']['username']))
                            <p>{{ $data['sender_user']['username'] }}</p>
                            @elseif(isset($data['users']) && isset($data['users']['username']))
                            <p>{{ $data['user']['username'] }}</p>
                            @endif
                        </div>
                    </td>

                    <td >
                        <div style="width: 260px; float: right; margin-top:40px ">
                            <strong style="float: left;">{{ trans("bills.received_by", [] ,$data['locale'] ??  'en')}}</strong>
                            <br>
                            <p style="float: left;">
                                @if(isset($data['clients']) && isset($data['clients']['firstname']) || isset($data['clients'][0]['firstname']))
                                        {{ $data['clients']['firstname'] ?? $data['clients'][0]['firstname'] ?? '' }} {{  $data['clients']['lastname'] ?? $data['clients'][0]['lastname'] ?? '' }}
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
            <!-- #region Footer -->
         {{--@if(isset($data['firms']) && isset($data['firms']['banks']) && sizeof($data['firms']['banks']) > 1)
            <footer>
                <tr>
                    <td>
                        <p>
                            <strong>{{ trans("bills.bank_name", [] ,$data['locale']) }}: </strong>{{ $data['firms']['banks']['bank_name'] }}
                        </p>
                        <p>
                            <strong>{{ trans("bills.bank_num", [] ,$data['locale']) }}: </strong>{{ $data['firms']['banks']['bank_num'] }}
                        </p>
                        <p>
                            <strong>{{ trans("bills.swift", [] ,$data['locale']) }}: </strong>{{ $data['firms']['banks']['bank_swift'] }}
                        </p>
                        <p>
                            <strong>{{ trans("bills.bank_address", [] ,$data['locale']) }}: </strong>{{ $data['firms']['banks']['bank_address'] }}
                        </p>
                    </td>
                </tr>

            </footer>
        @endif--}}
            <!-- #endregion Footer -->
        </div>
    </div>
</div>
</body>
</html>
