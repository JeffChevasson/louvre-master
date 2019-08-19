<?php


namespace App\Services;


use App\Entity\Visit;
use App\Entity\Ticket;
use App\Entity\Prices;

class PriceCalculator
{
    /**
     * @param Ticket $ticket
     * @return int
     * @throws \Exception
     */
    public function computeTicketPrice(Ticket $ticket)
    {
        $price = 0;
        $birthday = $ticket->getBirthdate();
        $visit = $ticket->getVisit();
        $today = new \DateTime();
        $age = date_diff($birthday, $today)->y;
        $discount = $ticket->getDiscount();

        if ($visit->getType() == Visit::TYPE_FULL_DAY)
        {
            if ($age >= Prices::MAX_AGE_CHILD && $age < Prices::MIN_AGE_SENIOR)
                {
                    $price = Prices::FULL_DAY_PRICE;
                    if ($age >= Prices::MAX_AGE_CHILD && $age < Prices::MIN_AGE_SENIOR && $discount == true)
                        {
                            $price = Prices::FULL_DAY_DISCOUNT;
                        }
                }
            elseif ($age >= Prices::MIN_AGE_SENIOR)
                {
                    $price = Prices::FULL_DAY_SENIOR;
                }
            elseif ($age >= Prices::MIN_AGE_CHILD && $age < Prices::MAX_AGE_CHILD)
                {
                    $price = Prices::FULL_DAY_CHILD;
                }
            else
                {
                    $price = Prices::FREE_TICKET;
                }
        }
        elseif ($visit->getType() == Visit::TYPE_HALF_DAY)
        {
            if ($age >= Prices::MAX_AGE_CHILD && $age < Prices::MIN_AGE_SENIOR)
                {
                    $price = Prices::HALF_DAY_PRICE;
                    if ($age >= Prices::MAX_AGE_CHILD && $age < Prices::MIN_AGE_SENIOR && $discount == true)
                        {
                            $price = Prices::HALF_DAY_DISCOUNT;
                        }
                }
            elseif ($age >= Prices::MIN_AGE_SENIOR)
                {
                    $price = Prices::HALF_DAY_SENIOR;
                }
            elseif ($age >= Prices::MIN_AGE_CHILD && $age < Prices::MAX_AGE_CHILD)
                {
                    $price = Prices::HALF_DAY_CHILD;
                }
            else
                {
                    $price = Prices::FREE_TICKET;
                }
        }
        $ticket->setPrice($price);

        return $price;

    }

    /**
     * @param Visit $visit
     * @return int
     * @throws \Exception
     */
    public function computePrice(Visit $visit)
    {

        $totalAmount = 0;

        foreach ($visit->getTickets() as $ticket)
            {
                $priceTicket = $this->computeTicketPrice($ticket);
                $totalAmount += $priceTicket;
            }
        $visit->setTotalAmount($totalAmount);
        return $totalAmount;
    }
}