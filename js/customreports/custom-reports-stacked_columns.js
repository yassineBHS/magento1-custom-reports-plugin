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
function getStackedColumnsChart(title,label,containerId,xAxis,data) {
    Highcharts.chart(containerId, {
    chart: {
        type: 'column',
        zoomType: 'xy',
        options3d: {
            enabled: true,
            alpha: 10,
            beta: 25,
            depth: 70
        }
    },
    title: {
        text: title
    },
    xAxis: {
        categories: xAxis
    },
    yAxis: {
        min: 0,
        title: {
            text: label
        }
    },
    tooltip: {
        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
        shared: true
    },
    plotOptions: {
        column: {
            stacking: 'normal'
        }
    },
    series: data
});
}