/******/ (() => { // webpackBootstrap
/*!**********************************************!*\
  !*** ./src/blocks/site-insights/frontend.js ***!
  \**********************************************/
(function (window, $) {
  // Look for all the graph blocks in the page.
  const blocks = document.querySelectorAll(".monsterinsights-graph-item");
  if (blocks.length < 0) {
    // Something is really wrong here. We loaded the script, but we didn't find any selectors.
    return;
  }

  /**
   * This function goes through all the ApexChart options and converts all the `formatter` properties to that function
   * https://apexcharts.com/docs/options/legend/#formatter
   *
   * @param obj
   * @returns {*}
   */
  const applyFormatterFunctions = obj => {
    if (typeof obj !== 'object') {
      return obj;
    }
    Object.keys(obj).map(k => {
      if (typeof obj !== 'object') {
        return obj[k];
      }
      if (k === 'formatter' && obj[k].hasOwnProperty('args') && obj[k].hasOwnProperty('body')) {
        const f = new Function(obj[k]['args'], obj[k]['body']);
        obj[k] = f;
        return f;
      }
      return applyFormatterFunctions(obj[k]);
    });
    return obj;
  };

  // For each block we will take individual options and render a separate chart for each of them.
  blocks.forEach(function (block) {
    const json = block.querySelector('script').textContent;
    let options = null;
    try {
      options = JSON.parse(json);
    } catch (e) {
      return false;
    }
    if (null !== options) {
      const parsedOptions = applyFormatterFunctions(options);
      const chart = new ApexCharts(block, parsedOptions);
      chart.render();
    }
  });
})(window, jQuery);
/******/ })()
;
//# sourceMappingURL=frontend-scripts.js.map