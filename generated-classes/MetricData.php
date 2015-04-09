<?php

use Base\MetricData as BaseMetricData;

/**
 * Skeleton subclass for representing a row from the 'metric_data' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class MetricData extends BaseMetricData
{
    function setSuccess($data){
        if(strtolower($data) === 'success' || strtolower($data) === 'ok'){
            parent::setSuccess(1);
        }else{
            parent::setSuccess(0);
        }
    }
}
