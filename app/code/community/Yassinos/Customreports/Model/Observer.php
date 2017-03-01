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
 * Custom reports Observer
 *
 * @category   Yassinos
 * @package    Yassinos_Customreports
 * @author      Yassine BELHAJ SALAH <belhajsalahyassine@gmail.com>
 */
class Yassinos_Customreports_Model_Observer {
    
    /**
     * Append custom Chart on the top of the grid
     *
     * @param Varien_Object $observer
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    public function appendReportChart(Varien_Event_Observer $observer)
    {
        $block = $observer->getBlock();
        $gridHTML = $observer->getTransport()->getHtml();
        $myReportChartHtml = Mage::app()->getLayout()
                                 ->createBlock('customreports/adminhtml_charts');
        
        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid)
        {            
            $html = $myReportChartHtml->setData('report', 'order')
                                      ->setTemplate('customreports/charts.phtml')
                                      ->toHtml() 
                    . $gridHTML;
            $observer->getTransport()->setHtml($html);
        }elseif ($block instanceof Mage_Adminhtml_Block_Customer_Grid) 
        {            
            $html = $myReportChartHtml->setData('report', 'customers')
                                      ->setTemplate('customreports/charts.phtml')
                                      ->toHtml() 
                    . $gridHTML;
            $observer->getTransport()->setHtml($html);
        }
        elseif ($block instanceof Mage_Adminhtml_Block_Report_Shopcart_Abandoned_Grid) 
        {
            $html = $myReportChartHtml->setData('report', 'abondonnedcart')
                                      ->setData('collection', $block->getCollection())
                                      ->setTemplate('customreports/charts.phtml')
                                      ->toHtml() 
                    . $gridHTML;
            $observer->getTransport()->setHtml($html);
        }elseif ($block instanceof Mage_Adminhtml_Block_Report_Search_Grid) 
        {
            $html = $myReportChartHtml->setData('report', 'searchTerms')
                                      ->setData('collection', $block->getCollection())
                                      ->setTemplate('customreports/charts.phtml')
                                      ->toHtml() 
                    . $gridHTML;
            $observer->getTransport()->setHtml($html);
        }if ($block instanceof Mage_Adminhtml_Block_Report_Sales_Sales_Grid)
        {            
            $html = $myReportChartHtml->setData('report', 'sales')
                                      ->setData('collection', $block->getCollection())
                                      ->setTemplate('customreports/charts.phtml')
                                      ->toHtml() 
                    . $gridHTML;
            $observer->getTransport()->setHtml($html);
        }
        if ($block instanceof Mage_Adminhtml_Block_Report_Shopcart_Product_Grid)
        {            
            $html = $myReportChartHtml->setData('report', 'products_incart')
                                      ->setData('collection', $block->getCollection())
                                      ->setTemplate('customreports/charts.phtml')
                                      ->toHtml() 
                    . $gridHTML;
            $observer->getTransport()->setHtml($html);
        }
    }
}