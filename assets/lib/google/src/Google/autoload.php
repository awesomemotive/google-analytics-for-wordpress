<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

function monsterinsights_google_api_php_client_autoload($className)
{
  $classPath = explode('_', $className);
  if ($classPath[0] != 'Google') {
    return;
  }
  // Drop 'Google', and maximum class file path depth in this project is 3.
  $classPath = array_slice($classPath, 1, 2);
  $filePath = dirname(__FILE__) . '/' . implode('/', $classPath) . '.php';
  if (file_exists($filePath)) {
    require_once($filePath);
  }
}
spl_autoload_register('monsterinsights_google_api_php_client_autoload');

function monsterinsights_renamed_google_api_php_client_autoload($className)
{
  $classPath = explode('_', $className);
  if ( empty( $classPath[0] ) || empty( $classPath[1] ) || empty( $classPath[2] ) || 
      $classPath[0] != 'MonsterInsights' && $classPath[1] != 'GA' && $classPath[2] != 'Lib') {
    return;
  }
  unset( $classPath[0] );
  unset( $classPath[1] );
  $classPath[2] = 'Google';
  // Drop 'MonsterInsights_GA_Lib', and maximum class file path depth in this project is 3.
  $classPath = array_slice($classPath, 1, 2);
  $filePath = dirname(__FILE__) . '/' . implode('/', $classPath) . '.php';
  if (file_exists($filePath)) {
    require_once($filePath);
  }
}
spl_autoload_register('monsterinsights_renamed_google_api_php_client_autoload');
