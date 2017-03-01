<?php 
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This file is a limited source, part of the Magento module named "Yassinos_Customreports" 
 * dedicated to add custom chart report before the grid which contain the most significant
 * data in a magento site, orders by category, abondonned shopping carts by total amounts,
 * Top 10 search terms, evolution of customers registration and addition, 
 * products in the cart grouped by categories ..
 * 
 * The objective of this module is to give an analytical visibility to the administrator of
 * a web shop on the categories of the products most in demand the most abundant ones,
 * factors that directly affect the increase in sales .. the augmantaions Of registrants 
 * and the addition of customer groups and their contributions to sales trends.
 * Among the indirect objectives is to facilitate the web shop owner to manage the sales
 * promotions and optimize his marketing strategies based on significant graphical reports 
 * and presenters starting from the same site history.
 * Note that this module is still optimized to further improve reporting and give the user
 * the ability to customize his reports, and add even more profound functionalities.
 * 
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category   Yassinos
 * @package    Yassinos_Customreports
 * @copyright  Copyright (c) Yassine BELHAJ SALAH . (http://www.yassine-belhajsalah.com)
 */
/**
 * Custom reports Block Adminhtml Charts
 *
 * @category   Yassinos
 * @package    Yassinos_Customreports
 * @author      Yassine BELHAJ SALAH <belhajsalahyassine@gmail.com>
 */

class Yassinos_Customreports_Block_Adminhtml_Charts extends Mage_Adminhtml_Block_Template 
{
    /**
     * Provides orders statistics grouped by mounthly informations
     *
     * @param NULL
     * @return Array
     */
    public function getMonthlyOrderStatistics()
    {
        $collection = Mage::getResourceModel('sales/order_item_collection');
        $average = array();
        $averageByCategory = array();
        $categoryContribution = array();        
        foreach ($collection as $item) 
        {
            $itemCreationDate = date('M-Y', strtotime($item->getCreatedAt())); 
            
            foreach($item->getProduct()->getCategoryIds() as $categoryId)
            {
                    $categoryName = Mage::getModel('catalog/category')->load($categoryId)->getName();
                    
                    $categoryContribution[$categoryName] = $categoryContribution[$categoryName] 
                            + $item->getRowTotalInclTax();
                    
                    $averageByCategory[$categoryName][$itemCreationDate] = $averageByCategory[$itemCreationDate][$categoryName]
                            + $item->getRowTotalInclTax(); 
            }
            $average[$itemCreationDate] = $average[$itemCreationDate] 
                    + $item->getRowTotalInclTax();            
        }

        
        $series = array();
        foreach ($averageByCategory as $category => $categoryAverage) 
        {
            $data = array();
            foreach(array_keys($average) as $date)
            {
                $data[] = (!isset($categoryAverage[$date])) ? 0 : $categoryAverage[$date];
            }
            $series[] = array('type' => 'column', 'name' => $category , 'data'=> $data);
        }
        
        
        $categoryContributionPercentage = array();
        $sum = array_sum(array_values($categoryContribution));
        
        foreach ($categoryContribution as $category => $value) 
        {
            $categoryContributionPercentage[] = array('name' => $category , 'y' => round($value*100/$sum,2));
        }
        
        return array("average" => $average 
                ,"category_contribution" => $categoryContributionPercentage 
                ,"average_by_category" => $series);
    }
    
    /**
     * Provides customers statistics grouped by mounthly informations
     *
     * @param NULL
     * @return Array
     */
    function getMonthlyCustomerStatistics()
    {
        $collection = mage::getModel('customer/customer')->getCollection();
        $registredCustomers = array();
        $dates = array();
        foreach ($collection as $customer) 
        {
            $customerCreationDate = date('M-Y', strtotime($customer->getCreatedAt()));
            $dates[$customerCreationDate]++;
            $registredCustomers[$customer->getGroupId()][$customerCreationDate]++ ;
            
        }
        $groupsCollection = Mage::getModel('customer/group')->getCollection();
        $groupsCustomers = array();
        foreach ($groupsCollection as $group) 
        {
            $groupsCustomers[$group->getCustomerGroupId()] = $group->getCustomerGroupCode();
        }
        
        $series = array();
        foreach ($registredCustomers as $customerGroupId => $inscriptions) 
        {
            $data = array();
            foreach(array_keys($dates) as $date)
            {
                $data[] = (!isset($inscriptions[$date])) ? 0 : $inscriptions[$date];
            }
            $series[]=array('name'=>$groupsCustomers[$customerGroupId],'data'=>$data);
        }
        return array('xAxis' => json_encode(array_keys($dates)),
            'data' => json_encode($series));
    }
    
    /**
     * Provides abondonned carts statistics grouped by mounthly informations
     *
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract
     * @return Array
     */
    function getAbonndonnedShopCartsStatistics($collection)
    {
        $groupsCollection = Mage::getModel('customer/group')->getCollection();
        $groupsCustomers = array();
        foreach ($groupsCollection as $group) 
        {
            $groupsCustomers[$group->getCustomerGroupId()] = $group->getCustomerGroupCode();
        }
        
        $abondonnedCart = array();
        $dates = array();
        foreach ($collection as $key => $item) {
            $creationDate = date('M-Y', strtotime($item->getCreatedAt()));
            $customerGroupId = $item->getCustomer()->getGroupId();
            $customerGroupName = $groupsCustomers[$customerGroupId];
            $dates[$creationDate] = $dates[$creationDate]+$item->getGrandTotal();
            $abondonnedCart[$customerGroupName][$creationDate] = $abondonnedCart[$customerGroupName][$creationDate] + 
                    $item->getGrandTotal();
        }
        
        $series = array();
        foreach ($abondonnedCart as $customerGroup => $totals) {
            $data = array();
            foreach ($xAxis = array_keys($dates) as $date) {
               $data[] = (!isset($totals[$date])) ? 0 : $totals[$date];
            }
            $series[] = array('name' => $customerGroup,'data' => $data);
        }
        return array ('xAxis' => json_encode($xAxis) , 'series' => json_encode($series));        
    }
    
    /**
     * Provides top 10 searched terms in The Magento webshop
     *
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract
     * @return Array
     */
    function getToSearchTermsStatistics($collection)
    {
        $termsSearched = array();
        foreach ($collection as $key => $item) {
            $termsSearched[$item->getQueryText()] = $item->getPopularity();
        }
        arsort($termsSearched);
        $topSearchedterms = array_slice($termsSearched,0,10);
        return array('xAxis'=> json_encode(array_keys($topSearchedterms)),
            'data'=> json_encode(array_map('intval',array_values($topSearchedterms))));
    }
    
    /**
     * Provides Sales Statistics : Total Amouns & number orders
     *
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract
     * @return Array
     */
    function getSalesStatistics($collection)
    {
        $xAxis = array();
        $salesTotals = array();
        $numberOrders = array();
        foreach ($collection as $key => $item) 
        {
            $xAxis[] = $item->getPeriod();
            $salesTotals[] = $item->getTotalRevenueAmount();
            $numberOrders[] = $item->getOrdersCount();
        }
        
        $data = array();
        $data[] = array('name' => 'total revenue amount' ,'yAxis' => 1 , 'data' => array_map('intval',$salesTotals));
        $data[] = array('name' => 'number orders' , 'data' => array_map('intval',$numberOrders));
        
        return (array('xAxis' => json_encode($xAxis),
            'data' => json_encode($data)
            )
           );
    }
    
    /**
     * Provides products in carts statistics grouped by Categories
     *
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract
     * @return Array
     */
    function getProductsInCartStatistics($collection)
    {
        $totalQuantity = 0;
        $totalByCategory = array();
        foreach($collection as $item)
        {
            $qty = $item->getCarts();
            $totalQuantity = $totalQuantity + $qty ;
            foreach(Mage::getModel('catalog/product')->loadByAttribute('sku',$item->getSku())->getCategoryIds() as $categoryId)
            {
                    $categoryName = Mage::getModel('catalog/category')->load($categoryId)->getName();                 
                    $totalByCategory[$categoryName] = $totalByCategory[$categoryName] + $qty ;                    
            }
        }
        
        $data = array();        
        foreach ($totalByCategory as $categoryName => $contribution) 
        {            
            $data[] = array($categoryName,$contribution*100/$totalQuantity);            
        }         
        return (json_encode($data)) ;
        
    }
}