<?php

/**
 * @Author: Dennis L.
 * @Test: 1
 * @TimeLimit: 5 minutes
 * @Testing: Reflection
 * @Task: Make $mySecret public using Reflection.
 */
// Please write some code to output the secret. You cannot adjust the visibility of the
// variable.
final class ReflectionTest
{
    private $mySecret = 'I have 99 problems. This isn\'t one of them.';
}

// Add your code here.
$reflectionTest = new ReflectionTest();
$reflectionObject = new ReflectionObject($reflectionTest);
print_r($reflectionObject->getProperty('mySecret')->getValue($reflectionTest));

// Don't edit anything else!