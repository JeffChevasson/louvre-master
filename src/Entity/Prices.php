<?php


namespace App\Entity;


class Prices
{
    const FULL_DAY_PRICE = 16;
    const FULL_DAY_DISCOUNT = 10;
    const FULL_DAY_SENIOR = 12;
    const FULL_DAY_CHILD = 8;

    const FREE_TICKET = 0;

    const HALF_DAY_PRICE = 8;
    const HALF_DAY_DISCOUNT = 5;
    const HALF_DAY_SENIOR = 6;
    const HALF_DAY_CHILD = 4;

    const MIN_AGE_CHILD = 4;
    const MAX_AGE_CHILD = 12;
    const MIN_AGE_SENIOR = 60;
}