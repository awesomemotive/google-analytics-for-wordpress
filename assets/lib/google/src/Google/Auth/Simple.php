<?php
/*
 * Copyright 2010 Google Inc.
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

if (!class_exists('MonsterInsights_GA_Lib_Client')) {
  require_once dirname(__FILE__) . '/../autoload.php';
}

/**
 * Simple API access implementation. Can either be used to make requests
 * completely unauthenticated, or by using a Simple API Access developer
 * key.
 */
class MonsterInsights_GA_Lib_Auth_Simple extends MonsterInsights_GA_Lib_Auth_Abstract
{
  private $client;

  public function __construct(MonsterInsights_GA_Lib_Client $client, $config = null)
  {
    $this->client = $client;
  }

  /**
   * Perform an authenticated / signed apiHttpRequest.
   * This function takes the apiHttpRequest, calls apiAuth->sign on it
   * (which can modify the request in what ever way fits the auth mechanism)
   * and then calls apiCurlIO::makeRequest on the signed request
   *
   * @param MonsterInsights_GA_Lib_Http_Request $request
   * @return MonsterInsights_GA_Lib_Http_Request The resulting HTTP response including the
   * responseHttpCode, responseHeaders and responseBody.
   */
  public function authenticatedRequest(MonsterInsights_GA_Lib_Http_Request $request)
  {
    $request = $this->sign($request);
    return $this->io->makeRequest($request);
  }

  public function sign(MonsterInsights_GA_Lib_Http_Request $request)
  {
    $key = $this->client->getClassConfig($this, 'developer_key');
    if ($key) {
      $this->client->getLogger()->debug(
          'Simple API Access developer key authentication'
      );
      $request->setQueryParam('key', $key);
    }
    return $request;
  }
}
