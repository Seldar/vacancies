<?php

namespace AppBundle\Repository;

use AppBundle\Drivers\IDriver;
use AppBundle\Entity\Vacancy;

/*
 * Class to talk with different datasources using its driver as parameter
 */
class VacancyRepository
{
    //Datasource to read from
    private $_readDriver;
    //Datasources to write to
    private $_mngDrivers = array();

    /*
     * Create an instance using a specific read driver
     */
    public function __construct(IDriver $readDriver)
    {
        $this->_readDriver = $readDriver;
    }

    /*
     * Method to change the read driver and also datasource on the fly
     */
    public function changeReadDriver(IDriver $readDriver)
    {
        $this->_readDriver = $readDriver;
    }
    /*
     * Method to get all vacancies
     */
    public function read()
    {
        return $this->_readDriver->read();
    }
    /*
     * Method to add managing drivers to synch with
     * return current managing driver count
     */

    public function addMngDriver($mngDrivers)
    {
        $this->_mngDrivers = array_unique(array_merge($mngDrivers, $this->_mngDrivers),SORT_REGULAR);
        return count($this->_mngDrivers);
    }
    /*
     * Method to remove drivers to synch with
     * return current managing driver count
     */

    public function removeMngDriver($mngDrivers)
    {
        $this->_mngDrivers = array_diff($this->_mngDrivers,$mngDrivers);
        return count($this->_mngDrivers);
    }

    /*
     * Method to create new vacancies
     * return array of errors if any array of zeroes if none
     */
    public function create(Vacancy $vacancy)
    {
        $result = array();
        foreach($this->_mngDrivers as $mngDriver)
        {
            $result[] = $mngDriver->create($vacancy);
        }
        return $result;
    }

    /*
     * Method to update vacancies
     * return array of errors if any array of zeroes if none
     */
    public function update(Vacancy $vacancy)
    {
        $result = array();
        foreach($this->_mngDrivers as $mngDriver)
        {
            $result[] = $mngDriver->update($vacancy);
        }
        return $result;
    }
    /*
     * Method to delete vacancies
     * return array of errors if any array of zeroes if none
     */
    public function delete($vacancyId)
    {
        $result = array();
        foreach($this->_mngDrivers as $mngDriver)
        {
            $result[] = $mngDriver->delete($vacancyId);
        }
        return $result;
    }

}