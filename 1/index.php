<?php
//show all errors
error_reporting(E_ALL);

require_once(__DIR__ . '/functions.php');

$items_in_row = 7;

//source array
$array = getRandomArray();

//group by row
$array = groupByRow($array, $items_in_row);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Part 1 - Array</title>
        <style>
            table {
                width: 100%;
                border-collapse: separate;
                border: 1px solid rgba(0,0,0,1);
            }
            table td {
                border: 1px solid rgba(0,0,0,0);
                text-align: center;
                width: <?php echo (100 / $items_in_row) ?>%;
                empty-cells: show;
            }
            table td:empty {
                border: 1px solid rgba(205,205,205,1);
            }
        </style>
    </head>
    <body>
        <table>
            <?php foreach ($array as $row) { ?>
            <tr>
                <?php for($i = 0; $i < $items_in_row; $i++) { ?>
                    <td><?php if(true === array_key_exists($i, $row)){ ?>
                        <?php echo $row[$i] ?>
                    <?php } ?></td>
                <?php } ?>
            </tr>
            <?php } ?>
        </table>
    </body>
</html>