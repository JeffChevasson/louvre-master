<?php


namespace App\Services;


class PublicHolidaysService
{
    public function getPublicHolidaysOfThisYear($year = null)
    {
        if ($year === null)
        {
            $year = intval (date ('Y'));
        }

        $easterDate = easter_date ($year);
        $easterDay  = date ('d', $easterDate);
        $easterMonth = date ('m', $easterDate);
        $easterYear = date ('Y', $easterDate);

        $publicHolidays = [
            // Dates fixes
            date('Y-m-d', mktime (0,0,0,1,1, $year)), //1er janvier
            date('Y-m-d', mktime (0,0,0,5,1, $year)), //1er mai
            date('Y-m-d', mktime (0,0,0,5,8, $year)), //8 mai
            date('Y-m-d', mktime (0,0,0,7,14, $year)), //14 juillet
            date('Y-m-d', mktime (0,0,0,8,15, $year)), //15 aout
            date('Y-m-d', mktime (0,0,0,11,1, $year)), //1er novembre
            date('Y-m-d', mktime (0,0,0,11,11, $year)), //11 novembre
            date('Y-m-d', mktime (0,0,0,12,25, $year)), //25 décembre

            // Dates variables
            date('Y-m-d', mktime (0,0,0, $easterMonth, $easterDay + 1 , $easterYear)), //Lundi de pâque
            date('Y-m-d', mktime (0,0,0, $easterMonth, $easterDay + 39 , $easterYear)), //Ascension
            date('Y-m-d', mktime (0,0,0, $easterMonth, $easterDay + 50 , $easterYear)), //lundi de Pentecôte
        ];

        sort ($publicHolidays);

        return $publicHolidays;
    }

    public function getPublicHolidaysOnTheseTwoYears()
    {
        $publicHolidayOfCurrentYear = $this->getPublicHolidaysOfThisYear ();
        $publicHolidayOfNextYear =$this->getPublicHolidaysOfThisYear (intval (date ('Y')+1));

        $result = array_merge ($publicHolidayOfCurrentYear,$publicHolidayOfNextYear);

        return $result;
    }

    public function checkIsHoliday(\DateTime $day)
    {
        $publicHolidays = $this->getPublicHolidaysOfThisYear ($year = null);

        if(in_array ($day->format ('Y-m-d'), $publicHolidays))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


}