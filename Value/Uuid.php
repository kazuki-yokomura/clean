<?php
declare(strict_types=1);
namespace Clean\Value;

use Clean\Value\Pattern;

/**
 *
 */
class Uuid extends Pattern
{
    protected $pattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
}
