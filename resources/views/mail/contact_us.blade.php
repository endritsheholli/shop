<div class='container' text-align="center">
    <h3 class="panel-title navbar-brand">{!! $emailContent['Header'] !!}</h3>
{!! $emailContent['Content'] !!}
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
      <div>
        <a href="{{url($emailContent['buttonURL'])}}" style="background-color:#2a3e68;border:1px solid #2a3e68;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:300px;-webkit-text-size-adjust:none;mso-hide:all;">{{$emailContent['buttonTitle']}}</a>
      </div>
    </td>
  </tr>
</table>
@include('emails.partial.footer')
        </div>
