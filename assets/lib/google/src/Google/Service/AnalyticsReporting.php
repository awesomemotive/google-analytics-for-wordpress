<?php
/*
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

/**
 * Service definition for AnalyticsReporting (v4).
 *
 * <p>
 * Accesses Analytics report data.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/analytics/devguides/reporting/core/v4/" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class MonsterInsights_GA_Lib_Service_AnalyticsReporting extends MonsterInsights_GA_Lib_Service
{
  /** View and manage your Google Analytics data. */
  const ANALYTICS =
      "https://www.googleapis.com/auth/analytics";
  /** View your Google Analytics data. */
  const ANALYTICS_READONLY =
      "https://www.googleapis.com/auth/analytics.readonly";

  public $reports;
  

  /**
   * Constructs the internal representation of the AnalyticsReporting service.
   *
   * @param MonsterInsights_GA_Lib_Client $client
   */
  public function __construct(MonsterInsights_GA_Lib_Client $client)
  {
    parent::__construct($client);
    $this->rootUrl = 'https://analyticsreporting.googleapis.com/';
    $this->servicePath = '';
    $this->version = 'v4';
    $this->serviceName = 'analyticsreporting';

    $this->reports = new MonsterInsights_GA_Lib_Service_AnalyticsReporting_Reports_Resource(
        $this,
        $this->serviceName,
        'reports',
        array(
          'methods' => array(
            'batchGet' => array(
              'path' => 'v4/reports:batchGet',
              'httpMethod' => 'POST',
              'parameters' => array(),
            ),
          )
        )
    );
  }
}


/**
 * The "reports" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsreportingService = new MonsterInsights_GA_Lib_Service_AnalyticsReporting(...);
 *   $reports = $analyticsreportingService->reports;
 *  </code>
 */
class MonsterInsights_GA_Lib_Service_AnalyticsReporting_Reports_Resource extends MonsterInsights_GA_Lib_Service_Resource
{

  /**
   * Returns the Analytics data. (reports.batchGet)
   *
   * @param Google_GetReportsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return MonsterInsights_GA_Lib_Service_AnalyticsReporting_GetReportsResponse
   */
  public function batchGet(MonsterInsights_GA_Lib_Service_AnalyticsReporting_GetReportsRequest $postBody, $optParams = array())
  {
    $params = array('postBody' => $postBody);
    $params = array_merge($params, $optParams);
    return $this->call('batchGet', array($params), "MonsterInsights_GA_Lib_Service_AnalyticsReporting_GetReportsResponse");
  }
}




class MonsterInsights_GA_Lib_Service_AnalyticsReporting_Cohort extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $dateRangeType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DateRange';
  protected $dateRangeDataType = '';
  public $name;
  public $type;


  public function setDateRange(MonsterInsights_GA_Lib_Service_AnalyticsReporting_DateRange $dateRange)
  {
    $this->dateRange = $dateRange;
  }
  public function getDateRange()
  {
    return $this->dateRange;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_CohortGroup extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'cohorts';
  protected $internal_gapi_mappings = array(
  );
  protected $cohortsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_Cohort';
  protected $cohortsDataType = 'array';
  public $lifetimeValue;


  public function setCohorts($cohorts)
  {
    $this->cohorts = $cohorts;
  }
  public function getCohorts()
  {
    return $this->cohorts;
  }
  public function setLifetimeValue($lifetimeValue)
  {
    $this->lifetimeValue = $lifetimeValue;
  }
  public function getLifetimeValue()
  {
    return $this->lifetimeValue;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_ColumnHeader extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'dimensions';
  protected $internal_gapi_mappings = array(
  );
  public $dimensions;
  protected $metricHeaderType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricHeader';
  protected $metricHeaderDataType = '';


  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  public function getDimensions()
  {
    return $this->dimensions;
  }
  public function setMetricHeader(MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricHeader $metricHeader)
  {
    $this->metricHeader = $metricHeader;
  }
  public function getMetricHeader()
  {
    return $this->metricHeader;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_DateRange extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $endDate;
  public $startDate;


  public function setEndDate($endDate)
  {
    $this->endDate = $endDate;
  }
  public function getEndDate()
  {
    return $this->endDate;
  }
  public function setStartDate($startDate)
  {
    $this->startDate = $startDate;
  }
  public function getStartDate()
  {
    return $this->startDate;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_DateRangeValues extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'values';
  protected $internal_gapi_mappings = array(
  );
  protected $pivotValueRegionsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_PivotValueRegion';
  protected $pivotValueRegionsDataType = 'array';
  public $values;


  public function setPivotValueRegions($pivotValueRegions)
  {
    $this->pivotValueRegions = $pivotValueRegions;
  }
  public function getPivotValueRegions()
  {
    return $this->pivotValueRegions;
  }
  public function setValues($values)
  {
    $this->values = $values;
  }
  public function getValues()
  {
    return $this->values;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_Dimension extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'histogramBuckets';
  protected $internal_gapi_mappings = array(
  );
  public $histogramBuckets;
  public $name;


  public function setHistogramBuckets($histogramBuckets)
  {
    $this->histogramBuckets = $histogramBuckets;
  }
  public function getHistogramBuckets()
  {
    return $this->histogramBuckets;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_DimensionFilter extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'expressions';
  protected $internal_gapi_mappings = array(
  );
  public $caseSensitive;
  public $dimensionName;
  public $expressions;
  public $not;
  public $operator;


  public function setCaseSensitive($caseSensitive)
  {
    $this->caseSensitive = $caseSensitive;
  }
  public function getCaseSensitive()
  {
    return $this->caseSensitive;
  }
  public function setDimensionName($dimensionName)
  {
    $this->dimensionName = $dimensionName;
  }
  public function getDimensionName()
  {
    return $this->dimensionName;
  }
  public function setExpressions($expressions)
  {
    $this->expressions = $expressions;
  }
  public function getExpressions()
  {
    return $this->expressions;
  }
  public function setNot($not)
  {
    $this->not = $not;
  }
  public function getNot()
  {
    return $this->not;
  }
  public function setOperator($operator)
  {
    $this->operator = $operator;
  }
  public function getOperator()
  {
    return $this->operator;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_DimensionFilterClause extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'filters';
  protected $internal_gapi_mappings = array(
  );
  protected $filtersType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DimensionFilter';
  protected $filtersDataType = 'array';
  public $operator;


  public function setFilters($filters)
  {
    $this->filters = $filters;
  }
  public function getFilters()
  {
    return $this->filters;
  }
  public function setOperator($operator)
  {
    $this->operator = $operator;
  }
  public function getOperator()
  {
    return $this->operator;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_DynamicSegment extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $name;
  protected $sessionSegmentType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentDefinition';
  protected $sessionSegmentDataType = '';
  protected $userSegmentType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentDefinition';
  protected $userSegmentDataType = '';


  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setSessionSegment(MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentDefinition $sessionSegment)
  {
    $this->sessionSegment = $sessionSegment;
  }
  public function getSessionSegment()
  {
    return $this->sessionSegment;
  }
  public function setUserSegment(MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentDefinition $userSegment)
  {
    $this->userSegment = $userSegment;
  }
  public function getUserSegment()
  {
    return $this->userSegment;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_GetReportsRequest extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'reportRequests';
  protected $internal_gapi_mappings = array(
  );
  protected $reportRequestsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_ReportRequest';
  protected $reportRequestsDataType = 'array';


  public function setReportRequests($reportRequests)
  {
    $this->reportRequests = $reportRequests;
  }
  public function getReportRequests()
  {
    return $this->reportRequests;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_GetReportsResponse extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'reports';
  protected $internal_gapi_mappings = array(
  );
  protected $reportsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_Report';
  protected $reportsDataType = 'array';


  public function setReports($reports)
  {
    $this->reports = $reports;
  }
  public function getReports()
  {
    return $this->reports;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_Metric extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $alias;
  public $expression;
  public $formattingType;


  public function setAlias($alias)
  {
    $this->alias = $alias;
  }
  public function getAlias()
  {
    return $this->alias;
  }
  public function setExpression($expression)
  {
    $this->expression = $expression;
  }
  public function getExpression()
  {
    return $this->expression;
  }
  public function setFormattingType($formattingType)
  {
    $this->formattingType = $formattingType;
  }
  public function getFormattingType()
  {
    return $this->formattingType;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricFilter extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $comparisonValue;
  public $metricName;
  public $not;
  public $operator;


  public function setComparisonValue($comparisonValue)
  {
    $this->comparisonValue = $comparisonValue;
  }
  public function getComparisonValue()
  {
    return $this->comparisonValue;
  }
  public function setMetricName($metricName)
  {
    $this->metricName = $metricName;
  }
  public function getMetricName()
  {
    return $this->metricName;
  }
  public function setNot($not)
  {
    $this->not = $not;
  }
  public function getNot()
  {
    return $this->not;
  }
  public function setOperator($operator)
  {
    $this->operator = $operator;
  }
  public function getOperator()
  {
    return $this->operator;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricFilterClause extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'filters';
  protected $internal_gapi_mappings = array(
  );
  protected $filtersType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricFilter';
  protected $filtersDataType = 'array';
  public $operator;


  public function setFilters($filters)
  {
    $this->filters = $filters;
  }
  public function getFilters()
  {
    return $this->filters;
  }
  public function setOperator($operator)
  {
    $this->operator = $operator;
  }
  public function getOperator()
  {
    return $this->operator;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricHeader extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'pivotHeaders';
  protected $internal_gapi_mappings = array(
  );
  protected $metricHeaderEntriesType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricHeaderEntry';
  protected $metricHeaderEntriesDataType = 'array';
  protected $pivotHeadersType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_PivotHeader';
  protected $pivotHeadersDataType = 'array';


  public function setMetricHeaderEntries($metricHeaderEntries)
  {
    $this->metricHeaderEntries = $metricHeaderEntries;
  }
  public function getMetricHeaderEntries()
  {
    return $this->metricHeaderEntries;
  }
  public function setPivotHeaders($pivotHeaders)
  {
    $this->pivotHeaders = $pivotHeaders;
  }
  public function getPivotHeaders()
  {
    return $this->pivotHeaders;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricHeaderEntry extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $name;
  public $type;


  public function setName($name)
  {
    $this->name = $name;
  }
  public function getName()
  {
    return $this->name;
  }
  public function setType($type)
  {
    $this->type = $type;
  }
  public function getType()
  {
    return $this->type;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_OrFiltersForSegment extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'segmentFilterClauses';
  protected $internal_gapi_mappings = array(
  );
  protected $segmentFilterClausesType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentFilterClause';
  protected $segmentFilterClausesDataType = 'array';


  public function setSegmentFilterClauses($segmentFilterClauses)
  {
    $this->segmentFilterClauses = $segmentFilterClauses;
  }
  public function getSegmentFilterClauses()
  {
    return $this->segmentFilterClauses;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_OrderBy extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $fieldName;
  public $orderType;
  public $sortOrder;


  public function setFieldName($fieldName)
  {
    $this->fieldName = $fieldName;
  }
  public function getFieldName()
  {
    return $this->fieldName;
  }
  public function setOrderType($orderType)
  {
    $this->orderType = $orderType;
  }
  public function getOrderType()
  {
    return $this->orderType;
  }
  public function setSortOrder($sortOrder)
  {
    $this->sortOrder = $sortOrder;
  }
  public function getSortOrder()
  {
    return $this->sortOrder;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_Pivot extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'metrics';
  protected $internal_gapi_mappings = array(
  );
  protected $dimensionFilterClausesType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DimensionFilterClause';
  protected $dimensionFilterClausesDataType = 'array';
  protected $dimensionsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_Dimension';
  protected $dimensionsDataType = 'array';
  public $maxGroupCount;
  protected $metricsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_Metric';
  protected $metricsDataType = 'array';
  public $startGroup;


  public function setDimensionFilterClauses($dimensionFilterClauses)
  {
    $this->dimensionFilterClauses = $dimensionFilterClauses;
  }
  public function getDimensionFilterClauses()
  {
    return $this->dimensionFilterClauses;
  }
  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  public function getDimensions()
  {
    return $this->dimensions;
  }
  public function setMaxGroupCount($maxGroupCount)
  {
    $this->maxGroupCount = $maxGroupCount;
  }
  public function getMaxGroupCount()
  {
    return $this->maxGroupCount;
  }
  public function setMetrics($metrics)
  {
    $this->metrics = $metrics;
  }
  public function getMetrics()
  {
    return $this->metrics;
  }
  public function setStartGroup($startGroup)
  {
    $this->startGroup = $startGroup;
  }
  public function getStartGroup()
  {
    return $this->startGroup;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_PivotHeader extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'pivotHeaderEntries';
  protected $internal_gapi_mappings = array(
  );
  protected $pivotHeaderEntriesType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_PivotHeaderEntry';
  protected $pivotHeaderEntriesDataType = 'array';
  public $totalPivotGroupsCount;


  public function setPivotHeaderEntries($pivotHeaderEntries)
  {
    $this->pivotHeaderEntries = $pivotHeaderEntries;
  }
  public function getPivotHeaderEntries()
  {
    return $this->pivotHeaderEntries;
  }
  public function setTotalPivotGroupsCount($totalPivotGroupsCount)
  {
    $this->totalPivotGroupsCount = $totalPivotGroupsCount;
  }
  public function getTotalPivotGroupsCount()
  {
    return $this->totalPivotGroupsCount;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_PivotHeaderEntry extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'dimensionValues';
  protected $internal_gapi_mappings = array(
  );
  public $dimensionNames;
  public $dimensionValues;
  protected $metricType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricHeaderEntry';
  protected $metricDataType = '';


  public function setDimensionNames($dimensionNames)
  {
    $this->dimensionNames = $dimensionNames;
  }
  public function getDimensionNames()
  {
    return $this->dimensionNames;
  }
  public function setDimensionValues($dimensionValues)
  {
    $this->dimensionValues = $dimensionValues;
  }
  public function getDimensionValues()
  {
    return $this->dimensionValues;
  }
  public function setMetric(MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricHeaderEntry $metric)
  {
    $this->metric = $metric;
  }
  public function getMetric()
  {
    return $this->metric;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_PivotValueRegion extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'values';
  protected $internal_gapi_mappings = array(
  );
  public $values;


  public function setValues($values)
  {
    $this->values = $values;
  }
  public function getValues()
  {
    return $this->values;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_Report extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $columnHeaderType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_ColumnHeader';
  protected $columnHeaderDataType = '';
  protected $dataType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_ReportData';
  protected $dataDataType = '';
  public $nextPageToken;


  public function setColumnHeader(MonsterInsights_GA_Lib_Service_AnalyticsReporting_ColumnHeader $columnHeader)
  {
    $this->columnHeader = $columnHeader;
  }
  public function getColumnHeader()
  {
    return $this->columnHeader;
  }
  public function setData(MonsterInsights_GA_Lib_Service_AnalyticsReporting_ReportData $data)
  {
    $this->data = $data;
  }
  public function getData()
  {
    return $this->data;
  }
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_ReportData extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'totals';
  protected $internal_gapi_mappings = array(
  );
  public $isDataGolden;
  protected $maximumsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DateRangeValues';
  protected $maximumsDataType = 'array';
  protected $minimumsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DateRangeValues';
  protected $minimumsDataType = 'array';
  public $rowCount;
  protected $rowsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_ReportRow';
  protected $rowsDataType = 'array';
  public $samplesReadCounts;
  public $samplingSpaceSizes;
  protected $totalsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DateRangeValues';
  protected $totalsDataType = 'array';


  public function setIsDataGolden($isDataGolden)
  {
    $this->isDataGolden = $isDataGolden;
  }
  public function getIsDataGolden()
  {
    return $this->isDataGolden;
  }
  public function setMaximums($maximums)
  {
    $this->maximums = $maximums;
  }
  public function getMaximums()
  {
    return $this->maximums;
  }
  public function setMinimums($minimums)
  {
    $this->minimums = $minimums;
  }
  public function getMinimums()
  {
    return $this->minimums;
  }
  public function setRowCount($rowCount)
  {
    $this->rowCount = $rowCount;
  }
  public function getRowCount()
  {
    return $this->rowCount;
  }
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  public function getRows()
  {
    return $this->rows;
  }
  public function setSamplesReadCounts($samplesReadCounts)
  {
    $this->samplesReadCounts = $samplesReadCounts;
  }
  public function getSamplesReadCounts()
  {
    return $this->samplesReadCounts;
  }
  public function setSamplingSpaceSizes($samplingSpaceSizes)
  {
    $this->samplingSpaceSizes = $samplingSpaceSizes;
  }
  public function getSamplingSpaceSizes()
  {
    return $this->samplingSpaceSizes;
  }
  public function setTotals($totals)
  {
    $this->totals = $totals;
  }
  public function getTotals()
  {
    return $this->totals;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_ReportRequest extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'segments';
  protected $internal_gapi_mappings = array(
  );
  protected $cohortGroupType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_CohortGroup';
  protected $cohortGroupDataType = '';
  protected $dateRangesType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DateRange';
  protected $dateRangesDataType = 'array';
  protected $dimensionFilterClausesType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DimensionFilterClause';
  protected $dimensionFilterClausesDataType = 'array';
  protected $dimensionsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_Dimension';
  protected $dimensionsDataType = 'array';
  public $filtersExpression;
  public $hideTotals;
  public $hideValueRanges;
  public $includeEmptyRows;
  protected $metricFilterClausesType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_MetricFilterClause';
  protected $metricFilterClausesDataType = 'array';
  protected $metricsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_Metric';
  protected $metricsDataType = 'array';
  protected $orderBysType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_OrderBy';
  protected $orderBysDataType = 'array';
  public $pageSize;
  public $pageToken;
  protected $pivotsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_Pivot';
  protected $pivotsDataType = 'array';
  public $samplingLevel;
  protected $segmentsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_Segment';
  protected $segmentsDataType = 'array';
  public $viewId;


  public function setCohortGroup(MonsterInsights_GA_Lib_Service_AnalyticsReporting_CohortGroup $cohortGroup)
  {
    $this->cohortGroup = $cohortGroup;
  }
  public function getCohortGroup()
  {
    return $this->cohortGroup;
  }
  public function setDateRanges($dateRanges)
  {
    $this->dateRanges = $dateRanges;
  }
  public function getDateRanges()
  {
    return $this->dateRanges;
  }
  public function setDimensionFilterClauses($dimensionFilterClauses)
  {
    $this->dimensionFilterClauses = $dimensionFilterClauses;
  }
  public function getDimensionFilterClauses()
  {
    return $this->dimensionFilterClauses;
  }
  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  public function getDimensions()
  {
    return $this->dimensions;
  }
  public function setFiltersExpression($filtersExpression)
  {
    $this->filtersExpression = $filtersExpression;
  }
  public function getFiltersExpression()
  {
    return $this->filtersExpression;
  }
  public function setHideTotals($hideTotals)
  {
    $this->hideTotals = $hideTotals;
  }
  public function getHideTotals()
  {
    return $this->hideTotals;
  }
  public function setHideValueRanges($hideValueRanges)
  {
    $this->hideValueRanges = $hideValueRanges;
  }
  public function getHideValueRanges()
  {
    return $this->hideValueRanges;
  }
  public function setIncludeEmptyRows($includeEmptyRows)
  {
    $this->includeEmptyRows = $includeEmptyRows;
  }
  public function getIncludeEmptyRows()
  {
    return $this->includeEmptyRows;
  }
  public function setMetricFilterClauses($metricFilterClauses)
  {
    $this->metricFilterClauses = $metricFilterClauses;
  }
  public function getMetricFilterClauses()
  {
    return $this->metricFilterClauses;
  }
  public function setMetrics($metrics)
  {
    $this->metrics = $metrics;
  }
  public function getMetrics()
  {
    return $this->metrics;
  }
  public function setOrderBys($orderBys)
  {
    $this->orderBys = $orderBys;
  }
  public function getOrderBys()
  {
    return $this->orderBys;
  }
  public function setPageSize($pageSize)
  {
    $this->pageSize = $pageSize;
  }
  public function getPageSize()
  {
    return $this->pageSize;
  }
  public function setPageToken($pageToken)
  {
    $this->pageToken = $pageToken;
  }
  public function getPageToken()
  {
    return $this->pageToken;
  }
  public function setPivots($pivots)
  {
    $this->pivots = $pivots;
  }
  public function getPivots()
  {
    return $this->pivots;
  }
  public function setSamplingLevel($samplingLevel)
  {
    $this->samplingLevel = $samplingLevel;
  }
  public function getSamplingLevel()
  {
    return $this->samplingLevel;
  }
  public function setSegments($segments)
  {
    $this->segments = $segments;
  }
  public function getSegments()
  {
    return $this->segments;
  }
  public function setViewId($viewId)
  {
    $this->viewId = $viewId;
  }
  public function getViewId()
  {
    return $this->viewId;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_ReportRow extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'metrics';
  protected $internal_gapi_mappings = array(
  );
  public $dimensions;
  protected $metricsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DateRangeValues';
  protected $metricsDataType = 'array';


  public function setDimensions($dimensions)
  {
    $this->dimensions = $dimensions;
  }
  public function getDimensions()
  {
    return $this->dimensions;
  }
  public function setMetrics($metrics)
  {
    $this->metrics = $metrics;
  }
  public function getMetrics()
  {
    return $this->metrics;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_Segment extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $dynamicSegmentType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_DynamicSegment';
  protected $dynamicSegmentDataType = '';
  public $segmentId;


  public function setDynamicSegment(MonsterInsights_GA_Lib_Service_AnalyticsReporting_DynamicSegment $dynamicSegment)
  {
    $this->dynamicSegment = $dynamicSegment;
  }
  public function getDynamicSegment()
  {
    return $this->dynamicSegment;
  }
  public function setSegmentId($segmentId)
  {
    $this->segmentId = $segmentId;
  }
  public function getSegmentId()
  {
    return $this->segmentId;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentDefinition extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'segmentFilters';
  protected $internal_gapi_mappings = array(
  );
  protected $segmentFiltersType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentFilter';
  protected $segmentFiltersDataType = 'array';


  public function setSegmentFilters($segmentFilters)
  {
    $this->segmentFilters = $segmentFilters;
  }
  public function getSegmentFilters()
  {
    return $this->segmentFilters;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentDimensionFilter extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'expressions';
  protected $internal_gapi_mappings = array(
  );
  public $caseSensitive;
  public $dimensionName;
  public $expressions;
  public $maxComparisonValue;
  public $minComparisonValue;
  public $operator;


  public function setCaseSensitive($caseSensitive)
  {
    $this->caseSensitive = $caseSensitive;
  }
  public function getCaseSensitive()
  {
    return $this->caseSensitive;
  }
  public function setDimensionName($dimensionName)
  {
    $this->dimensionName = $dimensionName;
  }
  public function getDimensionName()
  {
    return $this->dimensionName;
  }
  public function setExpressions($expressions)
  {
    $this->expressions = $expressions;
  }
  public function getExpressions()
  {
    return $this->expressions;
  }
  public function setMaxComparisonValue($maxComparisonValue)
  {
    $this->maxComparisonValue = $maxComparisonValue;
  }
  public function getMaxComparisonValue()
  {
    return $this->maxComparisonValue;
  }
  public function setMinComparisonValue($minComparisonValue)
  {
    $this->minComparisonValue = $minComparisonValue;
  }
  public function getMinComparisonValue()
  {
    return $this->minComparisonValue;
  }
  public function setOperator($operator)
  {
    $this->operator = $operator;
  }
  public function getOperator()
  {
    return $this->operator;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentFilter extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $not;
  protected $sequenceSegmentType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_SequenceSegment';
  protected $sequenceSegmentDataType = '';
  protected $simpleSegmentType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_SimpleSegment';
  protected $simpleSegmentDataType = '';


  public function setNot($not)
  {
    $this->not = $not;
  }
  public function getNot()
  {
    return $this->not;
  }
  public function setSequenceSegment(MonsterInsights_GA_Lib_Service_AnalyticsReporting_SequenceSegment $sequenceSegment)
  {
    $this->sequenceSegment = $sequenceSegment;
  }
  public function getSequenceSegment()
  {
    return $this->sequenceSegment;
  }
  public function setSimpleSegment(MonsterInsights_GA_Lib_Service_AnalyticsReporting_SimpleSegment $simpleSegment)
  {
    $this->simpleSegment = $simpleSegment;
  }
  public function getSimpleSegment()
  {
    return $this->simpleSegment;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentFilterClause extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  protected $dimensionFilterType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentDimensionFilter';
  protected $dimensionFilterDataType = '';
  protected $metricFilterType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentMetricFilter';
  protected $metricFilterDataType = '';
  public $not;


  public function setDimensionFilter(MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentDimensionFilter $dimensionFilter)
  {
    $this->dimensionFilter = $dimensionFilter;
  }
  public function getDimensionFilter()
  {
    return $this->dimensionFilter;
  }
  public function setMetricFilter(MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentMetricFilter $metricFilter)
  {
    $this->metricFilter = $metricFilter;
  }
  public function getMetricFilter()
  {
    return $this->metricFilter;
  }
  public function setNot($not)
  {
    $this->not = $not;
  }
  public function getNot()
  {
    return $this->not;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentMetricFilter extends MonsterInsights_GA_Lib_Model
{
  protected $internal_gapi_mappings = array(
  );
  public $comparisonValue;
  public $maxComparisonValue;
  public $metricName;
  public $operator;
  public $scope;


  public function setComparisonValue($comparisonValue)
  {
    $this->comparisonValue = $comparisonValue;
  }
  public function getComparisonValue()
  {
    return $this->comparisonValue;
  }
  public function setMaxComparisonValue($maxComparisonValue)
  {
    $this->maxComparisonValue = $maxComparisonValue;
  }
  public function getMaxComparisonValue()
  {
    return $this->maxComparisonValue;
  }
  public function setMetricName($metricName)
  {
    $this->metricName = $metricName;
  }
  public function getMetricName()
  {
    return $this->metricName;
  }
  public function setOperator($operator)
  {
    $this->operator = $operator;
  }
  public function getOperator()
  {
    return $this->operator;
  }
  public function setScope($scope)
  {
    $this->scope = $scope;
  }
  public function getScope()
  {
    return $this->scope;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentSequenceStep extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'orFiltersForSegment';
  protected $internal_gapi_mappings = array(
  );
  public $matchType;
  protected $orFiltersForSegmentType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_OrFiltersForSegment';
  protected $orFiltersForSegmentDataType = 'array';


  public function setMatchType($matchType)
  {
    $this->matchType = $matchType;
  }
  public function getMatchType()
  {
    return $this->matchType;
  }
  public function setOrFiltersForSegment($orFiltersForSegment)
  {
    $this->orFiltersForSegment = $orFiltersForSegment;
  }
  public function getOrFiltersForSegment()
  {
    return $this->orFiltersForSegment;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_SequenceSegment extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'segmentSequenceSteps';
  protected $internal_gapi_mappings = array(
  );
  public $firstStepShouldMatchFirstHit;
  protected $segmentSequenceStepsType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_SegmentSequenceStep';
  protected $segmentSequenceStepsDataType = 'array';


  public function setFirstStepShouldMatchFirstHit($firstStepShouldMatchFirstHit)
  {
    $this->firstStepShouldMatchFirstHit = $firstStepShouldMatchFirstHit;
  }
  public function getFirstStepShouldMatchFirstHit()
  {
    return $this->firstStepShouldMatchFirstHit;
  }
  public function setSegmentSequenceSteps($segmentSequenceSteps)
  {
    $this->segmentSequenceSteps = $segmentSequenceSteps;
  }
  public function getSegmentSequenceSteps()
  {
    return $this->segmentSequenceSteps;
  }
}

class MonsterInsights_GA_Lib_Service_AnalyticsReporting_SimpleSegment extends MonsterInsights_GA_Lib_Collection
{
  protected $collection_key = 'orFiltersForSegment';
  protected $internal_gapi_mappings = array(
  );
  protected $orFiltersForSegmentType = 'MonsterInsights_GA_Lib_Service_AnalyticsReporting_OrFiltersForSegment';
  protected $orFiltersForSegmentDataType = 'array';


  public function setOrFiltersForSegment($orFiltersForSegment)
  {
    $this->orFiltersForSegment = $orFiltersForSegment;
  }
  public function getOrFiltersForSegment()
  {
    return $this->orFiltersForSegment;
  }
}
