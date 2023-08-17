<!DOCTYPE html>
<html lang="zh-Hans-HK">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<style>
    body {
        background-color: white;
    }

    .table {
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        border-bottom: none !important;
    }

    .border {
        border: 1px solid #000000 !important;
    }

    .border-left {
        border-left: 1px solid #000000 !important;
    }

    .border-right {
        border-right: 1px solid #000000 !important;
    }

    .border-top {
        border-top: 1px solid #000000 !important;
    }

    .border-bottom {
        border-bottom: 1px solid #000000 !important;
    }

    .info_frame {
        height: 44px;
        width: auto;
        position: relative;
    }

    .underline {
        border-bottom: 2px solid #000000;
        width: 100%;
        display: block;
    }

    .im {
        color: #000000;
    }
</style>
<body>
<div class="container">
    <table class="table table-sm table-borderless">
        <thead>
        <tr>
            <td>
                <div class="row">
                    <div class="col-sm-12 info_frame">
                        <img src="{{asset('img/logo.png')}}" alt="{{__('chees')}}" style="max-height: 150px">
                    </div>
                </div>
                <div class="row" style="padding-top:70px">
                    <div class="col-sm-12 pt-2">
                        <div class="row pb-3">
                            <div class="offset-sm-7 col-sm-5 text-end align-self-end">
                                <h1 class="pt-3 fw-bold text-decoration-underline">{{ __('email.receipt') }}</h1>
                            </div>
                        </div>
                        <div class="row fw-bold">
                            <div class="col-sm-2 text-left" style="width: 30%">
                                <p>{{ __('email.date_text') }}:</p>
                                <p>{{ __('email.booking_ref') }}:</p>
                                <p>{{ __('email.customer_full_name') }}:</p>
                                <p>{{ __('email.contact_number') }}:</p>
                                <p>{{ __('email.address_optional') }}:</p>
                            </div>
                            <div class="col-sm-3 text-left" style="width: 50%">
                                <p>{{$data->created_at->format('d/m/y')}}</p>
                                <p class="underline">{{$data->order_no}}</p>
                                <p class="underline">{{$data->name}}</p>
                                <p class="underline">{{$data->tel}}</p>
                                <p class="underline">{{$data->translate($data->locale)->district}} {{$data->address}}</p>
                            </div>
                            <div class="col-sm-7"></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        </thead>
    </table>

    <p style="padding-top:20px">{{ __('email.payment_for_the_following_service') }}</p>
    <table class="table table-bordered" style="width: 70%">
        <tbody>
        @foreach($data->customer_history_details as $key => $item)
            <tr>
                @if($key === 0)
                    <th style="width: 25%">{{++$key}}. {{ __('email.service_items') }}:</th>
                @else
                    <th style="width: 25%">{{++$key}}.</th>
                @endif
                <th>{{$item->translate($data->locale)->title}}</th>
            </tr>
        @endforeach
        <tr>
            <th></th>
            <th>
                {{ __('email.request_service_date') }} {{$data->blood_date->format('d/m/y')}} {{$data->blood_time }}
            </th>
        </tr>
        <tr>
            <th></th>
            <th>
                {{ __('email.service_amount') }} {{$data->customer_history_details->sum('price')}}
            </th>
        </tr>
        <tr>
            <th></th>
            <th>
                {{ __('email.payment_method') }} {{ __('email.credit_card') }}
            </th>
        </tr>
        </tbody>
    </table>
    <h5 style="padding-top: 30px;padding-bottom: 10px">{{ __('email.general_policy') }}</h5>
    <p>{{__('email.policy_1')}}</p>
    <p>{{__('email.policy_2')}}</p>
    <p>{{__('email.policy_3')}}</p>
    <p>{{__('email.policy_3_note_1')}}</p>
    <p>{{__('email.policy_3_note_2')}}</p>
    <p>{{__('email.policy_4')}}</p>
    <p>{{__('email.policy_5')}}</p>
    <p>{{__('email.policy_6')}}</p>
    <p>{{__('email.policy_7')}}</p>
    <p>{{__('email.policy_8')}}</p>
    <p>{{__('email.policy_8_1')}}</p>
    <p>{{__('email.policy_8_2')}}</p>
    <p>{{__('email.policy_9')}}</p>
    <p>{{__('email.policy_10')}}</p>
    <p>{{__('email.policy_10_1')}}</p>
    <p>{{__('email.policy_10_2')}}</p>
    <p>{{__('email.policy_11')}}</p>
    <p>{{__('email.policy_11_1')}}</p>
    <p>{{__('email.policy_11_2')}}</p>
    <p>{{__('email.policy_11_3')}}</p>
    <p>{{__('email.policy_note')}}</p>
</div>

</body>
</html>
