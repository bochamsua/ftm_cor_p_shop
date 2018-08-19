<?php

class BS_Observation_Model_Observer
{

    /*
     * disable customer registration
     */
    public function disableCustomerRegistration($observer){
        $result = $observer->getResult();

        if ($result->getIsAllowed() === true) {
            $result->setIsAllowed(false);
        }
    }
}
