<?php

/**
 * @Author: Dennis L.
 * @Test: 3
 * @TimeLimit: 15 minutes
 * @Testing: Recursion
 */
function numberOfItems(array $arr, string $needle): int
{
    // Write some code to tell me how many of my selected fruit is in these lovely nested arrays.
    $result = 0;
    $count = count($arr);
    for ($i = 0; $i < $count; $i++) {
        if (is_array($arr[$i])) {
            $result += numberOfItems($arr[$i], $needle);
            continue;
        }
        if ($arr[$i] === $needle) {
            $result++;
        }
    }
    return $result;
}
$arr = ['apple', ['banana', 'strawberry', 'apple', ['banana', 'strawberry', 'apple']]];
echo numberOfItems($arr, 'apple') . PHP_EOL;
