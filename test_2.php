<?php

/** 
 * @Author: Dennis L. 
 * @Test: 2 
 * @TimeLimit: 10 minutes 
 * @Testing: Closures 
 */
var_dump(changeDateFormat(array("2010/03/30", "15/12/2016", "11-15-2012",  "20130720")));
/** 
 * When this method runs, it should return valid dates in the following format:  DD/MM/YYYY. 
 */
function changeDateFormat(array $dates): array
{
    $listOfDates = [];
    // Add code here
    // listOfDates is passed in by reference with the use keyword so the 
    // closure has access to this variable from the parent scope    
    $closure = function ($date) use (&$listOfDates) {
        // check the date_parse for errors since the date format is potentially unknown
        $dateParse = date_parse($date);
        if (!empty($dateParse['errors'])) {
            // When date_parse has errors, PHP likely needs the format to give the correct date.
            // More advanced format checking would be required in a scenario outside of this test,
            // but as in this case the inputted dates are always the same, we can assume that one
            // of these two formats will provide the correct date.
            $dateTime = DateTime::createFromFormat("d/m/Y", $date) ?: DateTime::createFromFormat("m-d-Y", $date);
        } else {
            // date_parse had no errors so PHP can work out the correct date without needing the format
            $dateTime = new DateTime($date);
        }
        $listOfDates[] = $dateTime->format("d/m/Y");
    };
    // Don't edit anything else! 
    array_map($closure, $dates);
    return $listOfDates;
}
