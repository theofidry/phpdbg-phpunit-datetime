<?php declare(strict_types = 1);

namespace App;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class DateUtil
{
    /**
     * Returns the current time with millisecond precision.
     *
     * @return DateTimeImmutable The current time
     */
    public static function nowWithMilliseconds(): DateTimeImmutable
    {
        $time = sprintf('%.4f', microtime(true));

        return DateTimeImmutable::createFromFormat('U.u', $time)
            ->setTimezone(new DateTimeZone(date_default_timezone_get()));
    }

    /**
     * Returns the biggest of the given dates.
     *
     * @param DateTimeImmutable[] ...$dates A list of dates
     *
     * @return DateTimeImmutable The biggest of the given dates
     */
    public static function max(DateTimeImmutable ...$dates): DateTimeImmutable
    {
        $maxDate = null;

        foreach ($dates as $date) {
            if (null === $maxDate || self::greaterThan($date, $maxDate)) {
                $maxDate = $date;
            }
        }

        return $maxDate;
    }

    /**
     * Makes a DateTimeInterface object immutable.
     *
     * @param DateTimeInterface $time The datetime
     *
     * @return DateTimeImmutable The immutable copy of the datetime
     */
    public static function makeImmutable(DateTimeInterface $time): DateTimeImmutable
    {
        return $time instanceof DateTimeImmutable ? $time : DateTimeImmutable::createFromMutable($time);
    }

    /**
     * Returns whether a datetime is greater than another.
     *
     * @param DateTimeInterface $left  A datetime
     * @param DateTimeInterface $right A datetime
     *
     * @return bool True if the left datetime is greater than the right
     */
    public static function greaterThan(DateTimeInterface $left, DateTimeInterface $right): bool
    {
        return self::compare($left, $right) > 0;
    }

    /**
     * Returns whether a datetime is greater than or equal to another.
     *
     * @param DateTimeInterface $left  A datetime
     * @param DateTimeInterface $right A datetime
     *
     * @return bool True if the left datetime is greater than or equal to the right
     */
    public static function greaterThanOrEqual(DateTimeInterface $left, DateTimeInterface $right): bool
    {
        return self::compare($left, $right) !== -1;
    }

    /**
     * Returns whether a datetime is less than another.
     *
     * @param DateTimeInterface $left  A datetime
     * @param DateTimeInterface $right A datetime
     *
     * @return bool True if the left datetime is less than the right
     */
    public static function lessThan(DateTimeInterface $left, DateTimeInterface $right): bool
    {
        return self::compare($left, $right) < 0;
    }

    /**
     * Returns whether a datetime is less than or equal to another.
     *
     * @param DateTimeInterface $left  A datetime
     * @param DateTimeInterface $right A datetime
     *
     * @return bool True if the left datetime is less than or equal to the right
     */
    public static function lessThanOrEqual(DateTimeInterface $left, DateTimeInterface $right): bool
    {
        return self::compare($left, $right) !== 1;
    }

    /**
     * Compares two datetimes.
     *
     * This method can be used as callback for sorting dates.
     *
     * @param DateTimeInterface $left  A datetime
     * @param DateTimeInterface $right A datetime
     *
     * @return int 1 if the left datetime is greater than the right, -1 if the
     *             left datetime is less than the right and 0 if both are
     *             identical (including milliseconds)
     */
    public static function compare(DateTimeInterface $left, DateTimeInterface $right): int
    {
        $leftSeconds = $left->getTimestamp();
        $rightSeconds = $right->getTimestamp();

        if ($leftSeconds !== $rightSeconds) {
            return $leftSeconds <=> $rightSeconds;
        }

        $leftMilliseconds = (int) $left->format('u');
        $rightMilliseconds = (int) $right->format('u');

        return $leftMilliseconds <=> $rightMilliseconds;
    }

    private function __construct()
    {
    }
}