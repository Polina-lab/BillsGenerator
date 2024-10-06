<!doctype html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        .invoice-box {
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 14px;
            line-height: 12px;
            font-family: DejaVu Sans , Helvetica , "sans-serif";
            color: #555;
        }

        .invoice-box table {
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
            line-height: 45px;
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

        #t2{
            height: 260px;
            width:250px;
            border: #0c1021 solid 1px;
            float: right;
        }

        .t200l{
            width:200px;
            float: left;
        }

        .t200r{
            width:200px;
            float: right;
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
        }

        .text-left{
            text-align: left;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
        }

    </style>
</head>

<body>

<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top" >
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="http://broker.gloreal.ee/image/logo2.png" style="width:100%; max-width:250px;">
                        </td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td >
                <div id="t1">


                    <table>
                        <thead>
                        <tr>
                            <th class="text-left" colspan="2">{{ trans('bills.beneficiary' ,[] , $data->locale ?? "en")}}</th>
                        </tr>
                        </thead>
                        <tbody >
                        <tr>
                            <th colspan="2" class="text-left">
                                <strong><u>{{ $data->firms->company_name ?? "firm_name not set " }}</u></strong>
                            </th>
                        </tr>
                        @if($data->firms->reg_num)
                            <tr>
                                <td colspan="2" class="text-left">
                                    <strong>{{ trans("bills.reg_num" ,[] , $data->locale ?? "en")}}: </strong> {{ $data->firms->reg_num}}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="2" class="text-left">
                                <strong>{{ trans("bills.phone",[],  $data->locale ?? "en")}}: </strong>{{ $data->firms->phone}}
                            </td>
                        </tr>

                        @if($data->firms->bank_name)
                            <tr>
                                <td colspan="2" class="text-left">
                                    <strong>{{ trans("bills.bank",[] , $data->locale ?? "en")}}: </strong>{{ $data->firms->bank_name}}
                                </td>
                            </tr>
                        @endif


                        @if($data->firms->bank_num)
                            <tr>
                                <td colspan="2" class="text-left">
                                    <strong>{{ trans("bills.bank_num",[] , $data->locale ?? "en")}}: </strong>{{ $data->firms->bank_num}}
                                </td>
                            </tr>
                        @endif


                        @if($data->firms->bank_account)
                            <tr>
                                <td colspan="2" class="text-left">
                                    <strong>{{ trans("bills.acc_num",[] , $data->locale ?? "en")}}: </strong>{{ $data->firms->bank_account}}
                                </td>
                            </tr>
                        @endif

                        @if($data->firms->bank_swift)
                            <tr>
                                <td colspan="2" class="text-left">
                                    <strong>{{ trans("bills.swift",[] , $data->locale ?? "en")}}: </strong>{{ $data->firms->bank_swift}}
                                </td>
                            </tr>
                        @endif
                        @if($data->firms->bank_address)
                            <tr>
                                <td colspan="2" class="text-left">
                                    <strong>{{ trans("bills.bank_address",[] , $data->locale ?? "en")}}: </strong>{{ $data->firms->bank_address}}
                                </td>
                            </tr>
                        @endif
                        @if($data->firms->bank_km_reg_num)
                            <tr>
                                <td class="text-left">
                                    <strong>{{ trans("bills.km_reg_nr",[] , $data->locale ?? "en")}}: </strong>{{ $data->firms->bank_km_reg_num}}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </td>

            <td>
                <div id="t2">
                    <table>
                        <thead>
                        <tr>
                            <th colspan="2" class="text-left">
                                {{ trans('bills.refered_to',[] , $data->locale ?? "en")}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="2" class="text-left">
                                <strong><u>
                                        @if(isset($data->companies->name))
                                        {{  $data->companies->name }}
                                        @elseif(isset($data->clients->firstname))
                                            {{ $data->clients->firstname . " " . $data->clients->lastname  }}
                                        @endif
                                    </u></strong><br>
                            </td>
                        </tr>

                        @if(isset($data->companies->reg_num)  || isset($data->clients->id_code))
                            <tr>
                            <td colspan="2" class="text-left">
                                <strong>{{ trans("bills.reg_num" ,[] , $data->locale ?? "en")}}: </strong>{{ $data->companies->reg_num ?? $data->clients->id_code  }}
                            </td>
                        </tr>
                        @endif

                        @if(isset($data->companies->address)  || isset($data->clients->address))
                            <tr>
                                <td colspan="2" class="text-left" >
                                    <strong>{{ trans("bills.address" ,[] , $data->locale ?? "en")}}: </strong>{{ $data->companies->address ?? $data->clients->address }}
                                </td>
                            </tr>
                        @endif

                        @if(isset($data->companies->phone) | isset($data->clients->phone))
                            <tr>
                                <td colspan="2" class="text-left">
                                    <strong>{{ trans("bills.phone" ,[] , $data->locale ?? "en")}}: </strong>{{ $data->companies->phone ?? $data->clients->phone}}
                                </td>
                            </tr>
                        @endif
                        @if(isset($data->clients->firstname)  & isset($data->clients->lastname))
                        <tr>
                            <td colspan="2" class="text-left">
                                <strong>{{trans("bills.represented_by",[] , $data->locale ?? "en")}}: </strong>{{ $data->clients->firstname  ." ". $data->clients->lastname }}
                            </td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>

        <tr class="inv">
            <td></td>
            <td>
                <table>
                    <tr>
                        <td class="text-right" colspan="2"><strong>{{ trans("bills.invoice",[] , $data->locale ?? "en") }}</strong></td>
                    </tr>
                    @if($data->invoice)
                        <tr>
                            <td class="text-right"><strong>{{ trans("bills.invoice_number",[] , $data->locale ?? "en")}}:</strong></td>
                            <td class="text-right">{{ $data->invoice}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-right"><strong>{{ trans("bills.invoice_date",[] , $data->locale ?? "en")}}:</strong></td>
                        <td class="text-right">{{ \Carbon\Carbon::parse($data->date)->format("d.m.Y")}}</td>
                    </tr>
                    @if($data->deadline)
                        <tr>
                            <td class="text-right"><strong>{{trans("bills.deadline",[] , $data->locale ?? "en")}}:</strong></td>
                            <td class="text-right">{{ \Carbon\Carbon::parse($data->deadline)->format("d.m.Y")}}</td>
                        </tr>
                    @endif
                    @if($data->paid_cash)
                        <tr>
                            <td  class="text-right" colspan="2" ><strong>{{trans("bills.paid_in_cash",[] , $data->locale ?? "en")}}</strong></td>
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
                        <th colspan="3" class="text-left">{{ trans('bills.explanation',[] , $data->locale ?? "en")}}</th>
                        <th class="text-center">{{ trans('bills.unit',[] , $data->locale ?? "en")}}</th>
                        <th class="text-center">{{ trans('bills.amount',[] , $data->locale ?? "en")}}</th>
                        <th class="text-center">{{ trans('bills.price_per_unit',[] , $data->locale ?? "en")}}</th>
                        <th class="text-center">{{ trans('bills.total',[] , $data->locale ?? "en") }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data->orders as $d)
                        <tr>
                            <td colspan="3" class="text-left mw450" >{{ $d->name }}</td>
                            <td class="text-center">{{ $d->unit }}</td>
                            <td class="text-center">{{ $d->amount }}</td>
                            <td class="text-right" style="white-space:nowrap" >{{ number_format($d->unit_price , 2 ,',',  '')}}</td>
                            <td class="text-right" style="white-space:nowrap" > {{ $d->price ?? number_format($d->amount * $d->unit_price , 2 ,',' , '')}} {{ $currency = $d->currency ?? "EUR"}}</td>
                        </tr>
                    @endforeach

                    @if($data->comment )
                        <tr>
                            <td colspan="7">{!!  $data->comment !!}</td>
                        </tr>
                    @endif

                    </tbody>
                    <tfoot>
                    @if(isset($data->price_no_km) & $data->price_km != 0)
                    <tr class="w_b">
                        <th colspan="6" class="text-right ">{{ trans("bills.total_withoutKM",[] , $data->locale ?? "en")}}:</th>
                        <td>{{ $data->price_no_km }} {{ $currency ?? "EUR"}}</td>
                    </tr>
                    @endif
                    @if(isset($data->price_km) & $data->price_km != 0 )
                        <tr >
                            <th colspan="6" class="text-right ">{{ trans("bills.KM20",[] , $data->locale ?? "en")}}:</th>
                            <td style="white-space:nowrap">{{   $data->price_km  }} {{ $currency ?? "EUR"}}</td>
                        </tr>
                    @endif
                    @if(isset($data->price_with_km))
                        <tr>
                            <th colspan="6" class="text-right ">{{ trans("bills.total" ,[] , $data->locale ?? "en")}}:</th>
                            <td style="white-space:nowrap">{{ $data->price_with_km  }} {{ $currency ?? "EUR"}}</td>
                        </tr>
                    @elseif(isset($data->price))
                        <tr>
                            <th colspan="6" class="text-right ">{{ trans("bills.total" ,[] , $data->locale ?? "en")}}:</th>
                            <td style="white-space:nowrap">{{  $data->price }} {{ $currency ?? "EUR"}}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">    <small>{{ trans("bills.pro_text",["penalty" => $data->penalty] , $data->locale ?? "en")}}</small></td>
        </tr>

        <tr class="notify">
            <td>
                <div  style="margin-top: 30px">
                    <strong>{{ trans("bills.notify",[] , $data->locale ?? "en")}}</strong>
                </div>
            </td>
            <td></td>
        </tr>
        <tr class="notify-text">
            <td>
                <strong>{{ trans("bills.notify_text", [] , $data->locale ?? "en")}}</strong>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>
                <div style="width: 260px;margin-top: 40px">
                    <strong>{{ trans("bills.Send_by",[] , $data->locale ?? "en")}}</strong>
                    <br>
                    @if($data->sender_user)
                        <p>{{ $data->sender_user->firstname ?? "" }} {{ $data->sender_user->lastname ?? "" }}</p>
                    @endif
                </div>
            </td>

            <td >
                <div style="width: 260px; float: right;margin-top:40px ">
                    <strong style="float: left;">{{ trans("bills.received_by",[] , $data->locale ?? "en")}}</strong>
                    <br>
                    @if(isset($data->clients->firstname))
                        <p style="float: left;">{{ $data->clients->firstname ?? ""}} {{ $data->clients->lastname ?? ""}}</p>
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td >
                <div  style="width: 260px;">
                    <hr>
                </div>
            </td>
            <td >
                <div style="width: 260px; float: right;">
                    <hr>
                </div>
            </td>
        </tr>

    </table>
</div>
</body>
</html>
