<?php

namespace App\Helpers;

class Response
{
  public static function error($message = "Failed", $data = [], $extra = [])
  {
    return array_merge(
      [
        "success" => false,
        "message" => $message,
        "data" => $data
      ], $extra);
  }
  public static function error_without_data($message = "Failed", $extra = [])
  {
    return array_merge(
      [
        "success" => false,
        "message" => $message
      ], $extra);
  }
  public static function success($message = "Success", $data = [], $extra = [])
  {
    return array_merge(
      [
        "success" => true,
        "message" => $message,
        "data" => $data
      ], $extra);
  }
  public static function success_without_data($message = "Success", $extra = [])
  {
    return array_merge(
      [
        "success" => true,
        "message" => $message
      ], $extra);
  }
}