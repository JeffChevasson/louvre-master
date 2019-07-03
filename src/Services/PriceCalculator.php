<?php


namespace App\Services;


use App\Entity\Visit;
use App\Entity\Ticket;
use App\Entity\Prices;

class PriceCalculator
{
    public function computeTicketPrice(Ticket $ticket)
    {
        $price = 0;
        $birthday = $ticket->getBirthdate();
        $visit = $ticket->getVisit();
        $today = new \DateTime();
        $age = date_diff($birthday, $today);
        $discount = $ticket->getDiscount();

        if ($visit->getType() == Visit::TYPE_FULL_DAY)
        {
            if ($age >= Prices::MAX_AGE_CHILD && $age < Prices::MIN_AGE_SENIOR) {
                $price = Prices::FULL_DAY_PRICE;
                if ($age >= Prices::MAX_AGE_CHILD && $age < Prices::MIN_AGE_SENIOR && $discount == true){
                    $price = Prices::FULL_DAY_DISCOUNT;
                }
            } elseif ($age >= Prices::MIN_AGE_SENIOR) {
                $price = Prices::FULL_DAY_SENIOR;
            } elseif ($age >= Prices::MIN_AGE_CHILD && $age < Prices::MAX_AGE_CHILD) {
                $price = Prices::FULL_DAY_CHILD;
            } else {
                $price = Prices::FREE_TICKET;
            }
        }
        elseif ($visit->getType() == Visit::TYPE_HALF_DAY)
        {
            if ($age >= Prices::MAX_AGE_CHILD && $age < Prices::MIN_AGE_SENIOR) {
                $price = Prices::HALF_DAY_PRICE;
                if ($age >= Prices::MAX_AGE_CHILD && $age < Prices::MIN_AGE_SENIOR && $discount == true){
                    $price = Prices::HALF_DAY_DISCOUNT;
                }
            } elseif ($age >= Prices::MIN_AGE_SENIOR) {
                $price = Prices::HALF_DAY_SENIOR;
            } elseif ($age >= Prices::MIN_AGE_CHILD && $age < Prices::MAX_AGE_CHILD) {
                $price = Prices::HALF_DAY_CHILD;
            } else {
                $price = Prices::FREE_TICKET;
            }
        }
        $ticket->setPrice($price);

        return $price;


    }

    public function computePrice(Visit $visit)
    {

        $total = 0;

        foreach ($visit->getTickets() as $ticket){
           $total += $this->computeTicketPrice($ticket);

        }
        $visit->setTotalamount($total);
    }
}