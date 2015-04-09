<?php

class MetricController{
    static function addDataPoint($metricName){
        $metric = MetricQuery::create()->findOneByLabel($metricName);
        if($metric === null){
            $metric = new Metric();
            $metric->setLabel($metricName);
            $metric->setMissingalert(60*60*24); //default one day
            
        }

        $dataPoint = new MetricData();
        $dataPoint->setTime(time());
        $dataPoint->setSuccess(empty($_GET['status']) ? 'error' : $_GET['status']);
        if(!empty($_GET['value'])){
            $dataPoint->setValue($_GET['value']);
        }
        

        $metric->addMetricData($dataPoint);


        $metric->save();
    }

    static function getMetricInfo($metricName){
        $metric = MetricQuery::create()->findOneByLabel($metricName);

        if($metric === null){
            openPage('404.html',array('error' => 'Er is geen metric gevonden met de naam $metricName'));
        }

        openPage('metric.html',array('metric' => $metric));

    }
    
}