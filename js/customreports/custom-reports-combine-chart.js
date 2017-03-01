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
function getCombinedReportChart(title,subtitle,containerId,dates,average,series,pieData) {
    series.push({
        type: 'spline',
        name: 'Total',
        yAxis: 1,
        data: average,
        marker: {
            lineWidth: 2,
            fillColor: 'white'
        }
    });
    series.push({
        type: 'pie',
        name: subtitle,
        data: pieData,
        center: [900, 50],
        size: 200,
        showInLegend: false,
        dataLabels: {
            enabled: false
        }
    });
Highcharts.chart(containerId, {
    chart: {
        zoomType: 'xy'
    },title: {
        text: title
    },
    xAxis: {
        categories: dates,
    },
    yAxis: [{ // Primary yAxis
        title: {
            text: 'Total Include Tax'
        },labels: {
            format: '{value}'
        }
    }, { // Secondary yAxis
        title: {
            text: 'Total Include Tax'
        },
        
        opposite : true
    }],
        
    labels: {
        items: [{
            html: subtitle,
            style: {
                left: '830px',
                top: '170px',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
            }
        }]
    },
    series: series
});
}