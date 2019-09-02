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


        if($age < Prices::AGE_CHILD){
            $price = Prices::PRICE_FREE;
        }elseif ($age < Prices::AGE_ADULT){
            $price = Prices::PRICE_CHILD;
        }elseif ($age < Prices::AGE_SENIOR){
            $price = Prices::PRICE_ADULT;
        }else{
            $price = Prices::PRICE_SENIOR;
        }

        if($discount && $price > Prices::PRICE_REDUCE){
            $price = Prices::PRICE_REDUCE;
        }


        if ($visit->getType() == Visit::TYPE_HALF_DAY){
            $price *= Prices::HALF_DAY_COEFF;
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