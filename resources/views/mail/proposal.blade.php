<!DOCTYPE html>
<html>
    <head>
        <title>{{ __('mail.proposal') }}</title>
    </head>
    <body>
        <p>{{ __('mail.proposal_description') }}</p>
        <p>{{ __('mail.client') }}: {{$client}} - {{ __('mail.'.$client_type) }}</p>
        <p>{{ __('mail.main_category') }}: {{$main_category}}</p>
        <p>{{ __('mail.sub_category') }}: {{$sub_category}}</p>
        <p>{{ __('mail.properties') }}:</p>
        @if($properties)
            @foreach($properties as $key=>$item)
            <p>&nbsp;{{$key + 1}}. {{$item->name}}</p>
            @endforeach
        @endif
    </body>
</html>
