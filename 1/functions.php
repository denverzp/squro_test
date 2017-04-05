<?php

/**
 * 
 * @return array
 */
function getRandomArray()
{
    $result = [];

    //get random number children
    $arr_length = mt_rand(1,100);

    for ($i = 0; $i < $arr_length; $i++) {
        $result[$i] = mt_rand(1, 100);
    }
    
    return $result;
}

/**
 * 
 * @param array $array
 * @param int $child_in_row
 * @return array
 */
function groupByRow(array $array, $items_in_row = 1)
{
    $result = [];
    
    //get array length 
    $arr_count = count($array);
    
    //get row number
    $cicles = ceil($arr_count / $items_in_row);
    
    //loop for rows
    for($i = 0; $i < $cicles; $i++){
        
        $m = $items_in_row;
        
        //loop for items
        while( $m > 0 ){
            
            //check - if isset array item
            if(null !== ( key($array))){
                
                $result[$i][] = current($array);
            
                next($array);
            
            } else {
                
                //if no array items - break the loop
                break 2;
            }
            
            $m--;
        }
    }
    
    return $result;
}