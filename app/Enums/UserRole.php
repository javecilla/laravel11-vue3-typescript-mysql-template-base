<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

class UserRole extends Enum
{
  const ADMIN = 'admin';
  const MODERATOR = 'moderator';

  /** Retrieve a map of enum keys and values. */
  public static function map() : array {
    return [
      static::ADMIN => 'Admin',
      static::MODERATOR => 'Moderator',
    ];
  }
}
