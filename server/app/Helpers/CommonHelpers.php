<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class CommonHelpers
{
  public static function isWeekend($date)
  {
    return (date('N', strtotime($date)) >= 5);
  }

  public static function transformDate($value, $time)
  {
    try {
      $convertedDate = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
      $convertedDateTime = $convertedDate->setTimeFromTimeString($time)->toDateTimeString();

      return $convertedDateTime;
    } catch (\ErrorException $e) {
      return Carbon::createFromFormat('Y/m/d H:i:s', $value . ' ' . $time);
    }
  }

  public static function randomCharGenerator($length = 4, $dictionary = null)  // default length is used for Ultra Voucher's Code generation
  {
    if (!$dictionary) {
      return Str::random($length);
    } else {
      $result = '';
      for ($i = 0; $i < $length; $i++) {
        $result = $result . $dictionary[rand(0, strlen($dictionary) - 1)];
      }
      return $result;
    }
  }

  public static function phoneTrimmer($phone)
  {
    /* 
    This guy right here prevents phone that does NOT start with '8'. 
      For example:
          '+62811111111' -becomes-> '811111111',
          '62811111111' -becomes-> '811111111',
          '0811111111' -becomes-> '811111111',
          '081-1111-111' -becomes-> '811111111',
          '0811 111 111' -becomes-> '811111111',
    */
    $trimmedPhone = $phone;

    $trimmedPhone = str_replace(' ', '', $trimmedPhone);
    $trimmedPhone = preg_replace('/[^0-9]/', '', $phone);

    if (substr($trimmedPhone, 0, 1) != '8') {
      switch (substr($trimmedPhone, 0, 1)) {
        case '0':
          $trimmedPhone = ltrim($trimmedPhone, '0');
          break;
        case '6' && substr($trimmedPhone, 0, 2) == '62':
          $trimmedPhone = ltrim($trimmedPhone, '62');
          break;
        case '+' && substr($trimmedPhone, 0, 3) == '+62':
          $trimmedPhone = ltrim($trimmedPhone, '+62');
          break;
      }
    }
    return $trimmedPhone;
  }

  public function isPhone($input)
  {
    /* Simple isNumber & Length >= 8 checker */
    if (preg_match('/^[0-9]{8}+$/', $input)) {
      return true;
    } else {
      return false;
    }
  }

  public function getDistance($latitude1, $longitude1, $latitude2, $longitude2)
  {
    $earth_radius = 6371;

    $dLat = deg2rad($latitude2 - $latitude1);
    $dLon = deg2rad($longitude2 - $longitude1);

    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * asin(sqrt($a));
    $d = $earth_radius * $c;

    return round($d, 1);
  }

  public static function slugGenerator($string) {
    return $string; // TODO Make an actual Slug Generator
  }
}
