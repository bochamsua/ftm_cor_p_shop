<?php
/**
 * BS_Misc extension
 * 
 * @category       BS
 * @package        BS_Misc
 * @copyright      Copyright (c) 2016
 */
/**
 * Misc default helper
 *
 * @category    BS
 * @package     BS_Misc
 * @author Bui Phong
 */
class BS_Crypto_Helper_Date extends Mage_Core_Helper_Abstract
{
    // Format Dates
    const ZEND_DATE_FORMAT_DB_DATE       = 'y-MM-dd';
    const ZEND_DATE_FORMAT_DB_DATETIME   = 'y-MM-dd HH:mm:ss';
    const ZEND_DATE_FORMAT_DB_TIME       = 'HH:mm:ss';
    const ZEND_DATE_FORMAT_XSD_DATETIME  = 'y-MM-ddTHH:mm:ss';
    const ZEND_DATE_FORMAT_TEXT_FULL     = 'EEEE dd/M TT';
    /**
     * Genera y Devuelve un Zend_Date a partir de un string YYYY-MM-DD
     * @return Zend_Date
     */
    public function getDate($date = null, $locale = null)
    {
        $locale = (is_null($locale))
            ? Mage::app()->getLocale()->getLocale()
            : $locale;
        return new Zend_Date($date, self::ZEND_DATE_FORMAT_DB_DATE, $locale);
    }

    /**
     * Genera y Devuelve un Zend_Date a partir de un string YYYY-MM-DD HH:MM:SS
     * @return Zend_Date
     */
    public function getDatetime($date = null, $locale = null)
    {
        $locale = (is_null($locale))
            ? Mage::app()->getLocale()->getLocale()
            : $locale;
        return new Zend_Date($date, self::ZEND_DATE_FORMAT_DB_DATETIME, $locale);
    }

    /**
     * Devuelve un Objeto Zend_Date con el valor NOW para el Store $store
     * si $includeTime = TRUE lo genera con el Horario Actual
     *
     * @param int $store
     * @param bool $includeTime
     * @return Zend_Date
     */
    public function getNowStore($store = null, $includeTime = false)
    {
        $store = (is_null($store)) ? $this->getCurrentStoreId() : $store;
        return Mage::app()->getLocale()->storeDate($store, null, $includeTime);
    }

    /**
     * Devuelve un string con el NOW en formato DATE para el Store "$store"
     *
     * @param int $store
     */
    public function getNowStoreDate($store = null)
    {
        return $this->formatDate($this->getNowStore($store));
    }

    /**
     * Devuelve un string con el NOW en formato DATETIME para el Store "$store"
     *
     * @param int $store
     */
    public function getNowStoreDatetime($store = null)
    {
        return $this->formatDatetime($this->getNowStore($store, true));
    }

    /**
     * Devuelve un string con el NOW en formato TIME para el Store "$store"
     *
     * @param int $store
     */
    public function getNowStoreTime($store = null)
    {
        return $this->formatTime($this->getNowStore($store, true));
    }
    public function getStoreDate($date, $store = null)
    {
        $zdate = $this->getLocaleModel()->storeDate($store, $date, true);
        return $this->formatDate($zdate);
    }
    public function getStoreDatetime($date, $store = null)
    {
        $zdate = $this->getLocaleModel()->storeDate($store, $date, true);
        return $this->formatDatetime($zdate);
    }

    /**
     * Devuelve un Objeto Zend_Date con el valor NOW UTC para el Store $store
     * si $includeTime = TRUE lo genera con el Horario Actual
     *
     * @param int $store
     * @param bool $includeTime
     *
     * @return Zend_Date
     */
    public function getNowUtc($store = null, $includeTime = false)
    {
        $store = (is_null($store)) ? $this->getCurrentStoreId() : $store;
        $date = $this->getNowStoreDatetime($store);
        return Mage::app()->getLocale()->utcDate($store, $date, $includeTime);
    }

    /**
     * Devuelve un string con el NOW (UTC) en formato DATETIME para el Store "$store"
     * @param int $store
     */
    public function getNowUtcDate($store = null, $format = null)
    {
        return $this->formatDate($this->getNowUtc($store), $format);
    }

    /**
     * Devuelve un string con el NOW (UTC) en formato DATETIME para el Store "$store"
     * @param int $store
     */
    public function getNowUtcDatetime($store = null)
    {
        return $this->formatDatetime($this->getNowUtc($store, true));
    }

    /**
     * Devuelve un string con el NOW(UTC) en formato TIME para el Store "$store"
     *
     * @param int $store
     */
    public function getNowUtcTime($store = null)
    {
        return $this->formatTime($this->getNowUtc($store, true));
    }

    /**
     * Devuelve Un Objeto Zend_Date a partirde del Date $date
     *
     * @param string $date
     * @param int $store
     * @param bool $includeTime
     * @return Zend_Date
     */
    public function getUtc($date, $store = null, $includeTime = false)
    {
        $store = (is_null($store)) ? $this->getCurrentStoreId() : $store;
        $date = ($includeTime)
            ? $this->formatDatetime($this->getDatetime($date))
            : $this->formatDate($this->getDate($date));
        return Mage::app()->getLocale()->utcDate($store, $date, $includeTime);
    }

    /**
     * Devuelve un String en Formato YYYY-MM-DD equivalente al deta UTC de $date
     * @param string $date |
     * @param type $store
     * @return type
     */
    public function getUtcDate($date, $store = null)
    {
        return $this->formatDate($this->getUtc($date,$store));
    }

    /**
     * Devuelve un String en Formato YYYY-MM-DD equivalente al deta UTC de $date
     * @param type $date
     * @param type $store
     * @return type
     */
    public function getUtcDatetime($date ,$store = null)
    {
        return $this->formatDatetime($this->getUtc($date,$store,true));
    }

    /**
     * @return Mage_Core_Model_Date
     */
    public function getDateModel()
    {
        return Mage::getModel('core/date');
    }
    /**
     * @return Mage_Core_Model_Locale
     */
    public function getLocaleModel()
    {
        return Mage::getModel('core/locale');
    }

    /**
     * Devuelve el ID del Store Actual
     *
     * @return type
     */
    public function getCurrentStoreId()
    {
        return Mage::app()->getStore()->getId();
    }

    /**
     * Devuelve un String Formato "YYYY-MM-DD" a partir de un Zend_Date
     * @param Zend_Date $date
     * @return string
     */
    public function formatDate(Zend_Date $date, $format = null)
    {
        if($format){
            return $date->toString($format);
        }
        return $date->toString(self::ZEND_DATE_FORMAT_DB_DATE);
    }

    public function formatDisplayDate($date, $format){
        $d = $this->getDate($date);

        return $this->formatDate($d, $format);
    }

    /**
     * Devuelve un String Formato "YYYY-MM-DD HH:MM:SS" a partir de un Zend_Date
     * @param Zend_Date $date
     * @return string
     */
    public function formatDatetime(Zend_Date $date)
    {
        return $date->toString(self::ZEND_DATE_FORMAT_DB_DATETIME);
    }

    /**
     * Devuelve un String Formato "HH:MM:SS" a partir de un Zend_Date
     * @param Zend_Date $date
     * @return string
     */
    public function formatTime(Zend_Date $date)
    {
        return $date->toString(self::ZEND_DATE_FORMAT_DB_TIME);
    }

    /**
     * Devuelve un String Formato "YYYY-MM-DDTHH:MM:SS" a partir de un Zend_Date
     * @param Zend_Date $date
     * @return string
     */
    public function formatXsdDatetime(Zend_Date $date)
    {
        return $date->toString(self::ZEND_DATE_FORMAT_XSD_DATETIME);
    }

    public function validateFromTo($from, $to, $store = null, $useUtc = false)
    {
        if(is_null($to)) {
            return false;
        }

        $now = ($useUtc) ? $this->getNowUtc($store) : $this->getNowStore($store);
        $to = ($useUtc) ? $this->getUtc($to) : $this->getDate($to);

        if ($from) {
            $from = ($useUtc) ? $this->getUtc($from) : $this->getDate($from);
        }

        if ($now->isLater($to)) {
            return false;
        }

        if ($from) {
            if ($now->isEarlier($from)) {
                return false;
            }
        }

        return true;

    }

    public function compareDate($date1, $date2, $fuction = '<'){
        //format
        //$date = ['date'=>$date, 'plus' => $plus, 'minus' => $minus]
        $d1 = new DateTime($date1['date']);
        if(isset($date1['plus']) && intval($date1['plus']) > 0){
            $d1->add(new DateInterval('P'.intval($date1['plus']).'D'));
        }else if(isset($date1['minus']) && intval($date1['minus']) > 0){
            $d1->sub(new DateInterval('P'.intval($date1['minus']).'D'));
        }

        $d2 = new DateTime($date2['date']);
        if(isset($date2['plus']) && intval($date2['plus']) > 0){
            $d2->add(new DateInterval('P'.intval($date2['plus']).'D'));
        }else if(isset($date2['minus']) && intval($date2['minus']) > 0){
            $d2->sub(new DateInterval('P'.intval($date2['minus']).'D'));
        }

        if($fuction == '<'){
            return $d1 < $d2;
        }

        if($fuction == '='){
            return $d1 == $d2;
        }

        if($fuction == '>'){
            return $d1 > $d2;
        }

        if($fuction == '<='){
            return $d1 <= $d2;
        }

        if($fuction == '>='){
            return $d1 >= $d2;
        }
    }


    public function getDays($fromDate, $toDate, $differenceFormat = '%a'){
        $datetime1 = date_create($fromDate);
        $datetime2 = date_create($toDate);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }
}
