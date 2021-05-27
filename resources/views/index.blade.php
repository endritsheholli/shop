@php
$config = [
'appName' => config('app.name'),
'locale' => $locale = app()->getLocale(),
'locales' => config('app.locales'),
'githubAuth' => config('services.github.client_id'),
'filesRoot' => config('filesystems.disks.public.url'),
'rootUrl' => config('app.url'),
'facebookAuth' => config('services.facebook.client_id')
];

$polyfills = [
'Promise',
'Object.assign',
'Object.values',
'Array.prototype.find',
'Array.prototype.findIndex',
'Array.prototype.includes',
'String.prototype.includes',
'String.prototype.startsWith',
'String.prototype.endsWith',
];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>{{ config('app.name') }}</title>


  <meta name="description" content="Nje ekosistem virtual i kompletuar per blerje, shitje dhe dergesa te produkteve te ndryshme." />

  <!-- Schema.org markup for Google+ -->
  <meta itemprop="name" content="{{ config('app.name') }}">
  <meta itemprop="description" content="Nje ekosistem virtual i kompletuar per blerje, shitje dhe dergesa te produkteve te ndryshme.">
  <meta itemprop="image" content="https://dy-po.com/images/logo.png">

  <!-- Twitter Card data -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="{{ config('app.name') }}">
  <meta name="twitter:description" content="Nje ekosistem virtual i kompletuar per blerje, shitje dhe dergesa te produkteve te ndryshme.">
  <meta name="twitter:image" content="https://dy-po.com/images/cover-fb.png">

  <!-- Open Graph data -->
  <meta property="og:title" content="{{ config('app.name') }}" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="https://dy-po.com" />
  <meta property="og:image" content="https://dy-po.com/images/cover-fb.png" />
  <meta property="fb:app_id" content="111634053864067" />
  <meta property="og:description" content="Nje ekosistem virtual i kompletuar per blerje, shitje dhe dergesa te produkteve te ndryshme." />

  <meta NAME="geo.placename" CONTENT="Kosove">
  <meta NAME="geo.region" CONTENT="XK">



  <link rel="stylesheet" href="css/bootstrap-multiselect/bootstrap-multiselect.css" type="text/css">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body>

  <div id="app"></div>

  <script>
    window.config = @json($config);
  </script>

  <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={{ implode(',', $polyfills) }}"></script>

  @if (app()->isLocal())
  <script src="{{ mix('js/app.js') }}"></script>
  @else
  <script src="{{ mix('js/manifest.js') }}"></script>
  <script src="{{ mix('js/vendor.js') }}"></script>
  <script src="{{ mix('js/app.js') }}"></script>
  @endif
<!--Start of Tawk.to Script-->
<script type="text/javascript">
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/5ed18c528ee2956d73a5e6d9/default';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();
  </script>
  <!--End of Tawk.to Script-->
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-165437920-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-165437920-1');
</script>

<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '282576269571186');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=282576269571186&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
</body>

</html>