<?php

namespace App\Models\Enums;

class CastMemberType {
  const DIRECTOR = 1;
  const ACTOR = 2;
  public static $types = [self::DIRECTOR, self::ACTOR];
}