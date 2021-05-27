<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Lang;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function respondWithSuccess($data, $message = '', $status = 'success')
    {
        $dataresponse = gettype($data) == 'object' ? $data->toArray() : $data;
        return new JsonResponse([
            'message' => $message,
            'status' => $status,
            'response' => $dataresponse,
        ]);
    }

    public function respondWithError($message = '', $status = 'failed')
    {
        return new JsonResponse([
            'message' => $message,
            'status' => $status,
        ]);
    }

    public function respondWithException($message = '')
    {
        return $this->respondWithError($message, 'exception');
    }

    public function getCurrentLanguage()
    {
        $locale = Lang::getLocale();

        $locales = config('app.locales');
        $locale = substr($locale, 0, strpos($locale, ',') ?: strlen($locale));

        if (array_key_exists($locale, $locales)) {
            return $locale;
        }

        if (array_key_exists($locale, $locales)) {
            return $locale;
        }

        $locale = substr($locale, 0, 2);
        if (array_key_exists($locale, $locales)) {
            return $locale;
        }
    }
    public function slugify($text, $db = null, $field = 'slug')
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            $text = 'n-a';
        }
        if ($db) {
            $ifExistSlug = \DB::table($db)->where($field, $text)->exists();
            if ($ifExistSlug) {
                $text = $text . '-' . rand(0, 1000000);
                $ifExistSlug = \DB::table($db)->where($field, $text)->exists();
                if ($ifExistSlug) {
                    $text = $text . '-' . rand(0, 1000000);
                }
            }
        }
        return $text;
    }

    public function order_email($to, $subject, $templatename, $data)
    {
        $mailer = app()['mailer'];
        $templatename = 'mail.' . $templatename;
        $to = $to ? $to : 'dypoapp@gmail.com';
        $data = array(
            'email' => $to,
            'from' => 'dypoapp@gmail.com',
            'subject' => $subject,
            'other_data' => $data
        );

        $send_mail = $mailer->send(['html' => $templatename], ['data' => $data], function ($msg) use ($data) {
            $msg->to($data['email']);
            $msg->subject($data['subject']);
            $msg->from($data['from'], 'Zogu');
        });

        return true;
    }
}
