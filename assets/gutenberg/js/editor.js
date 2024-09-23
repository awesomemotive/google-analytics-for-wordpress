/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/blocks/components/HierarchicalTerms-Lite.js":
/*!*********************************************************!*\
  !*** ./src/blocks/components/HierarchicalTerms-Lite.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */


const {
  CheckboxControl
} = wp.components;
class HierarchicalTermSelector extends _wordpress_element__WEBPACK_IMPORTED_MODULE_3__.Component {
  constructor() {
    super();
    this.state = {};
  }
  renderTerms(renderedTerms) {
    return renderedTerms.map(term => {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        key: term.id,
        className: "editor-post-taxonomies__hierarchical-terms-choice"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(CheckboxControl, {
        checked: true,
        label: (0,lodash__WEBPACK_IMPORTED_MODULE_1__.unescape)(term.name)
      }));
    });
  }
  render() {
    return [(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
      key: "monsterinsights-hierarchical-term-list-label",
      className: "components-base-control__label"
    }, this.props.label, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "monsterinsights-popular-posts-pro-pill"
    }, "PRO")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "editor-post-taxonomies__hierarchical-terms-list monsterinsights-hierarchical-terms monsterinsights-hierarchical-terms-disabled",
      key: "monsterinsights-hierarchical-term-list",
      tabIndex: "0",
      role: "group"
    }, this.renderTerms([{
      name: 'News',
      id: 1
    }, {
      name: 'Technology',
      id: 2
    }]))];
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HierarchicalTermSelector);

/***/ }),

/***/ "./src/blocks/components/PopularPostsLicenseCheck-Lite.js":
/*!****************************************************************!*\
  !*** ./src/blocks/components/PopularPostsLicenseCheck-Lite.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const PopularPostsLicenseCheck = {};
PopularPostsLicenseCheck.canaccess = level => {
  if ('lite' === level) {
    return true;
  }
  return false;
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PopularPostsLicenseCheck);

/***/ }),

/***/ "./src/blocks/components/PopularPostsThemePicker.js":
/*!**********************************************************!*\
  !*** ./src/blocks/components/PopularPostsThemePicker.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ PopularPostsThemePickerControl)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _PopularPostsLicenseCheck_GUTENBERG_APP_VERSION__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./PopularPostsLicenseCheck-GUTENBERG_APP_VERSION */ "./src/blocks/components/PopularPostsLicenseCheck-Lite.js");
/* harmony import */ var _GUTENBERG_APP_THEME_Icons__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./GUTENBERG_APP_THEME-Icons */ "./src/blocks/components/monsterinsights-Icons.js");
/* harmony import */ var pure_react_carousel__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! pure-react-carousel */ "./node_modules/pure-react-carousel/dist/index.es.js");

/**
 * External dependencies
 */





/**
 * WordPress dependencies
 */
const {
  useInstanceId
} = wp.compose;


/**
 * Internal dependencies
 */
const {
  BaseControl
} = wp.components;
function PopularPostsThemePickerControl({
  className,
  selected,
  help,
  onChange,
  options = [],
  icons = [],
  itemsPerGroup = 4
}) {
  const instanceId = useInstanceId(PopularPostsThemePickerControl);
  const id = `inspector-monsterinsights-popular-posts-theme-control-${instanceId}`;
  const onChangeValue = event => onChange(event.target.value);
  let slides = [];
  let groupBreakIndex = 0;
  let currentIndex = 0;
  let selectedSlide = 0;
  for (const option_key in options) {
    if (!options.hasOwnProperty(option_key)) {
      continue;
    }
    const option = options[option_key];
    if ('undefined' === typeof slides[groupBreakIndex]) {
      slides[groupBreakIndex] = [];
    }
    slides[groupBreakIndex][currentIndex] = option;
    if (option.value === selected) {
      selectedSlide = groupBreakIndex;
    }
    if (currentIndex === itemsPerGroup - 1) {
      currentIndex = 0;
      groupBreakIndex++;
    } else {
      currentIndex++;
    }
  }
  const carouselNavigation = () => {
    if (slides.length > 1) {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "monsterinsights-carousel-navigation"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(pure_react_carousel__WEBPACK_IMPORTED_MODULE_5__.ButtonBack, null, _GUTENBERG_APP_THEME_Icons__WEBPACK_IMPORTED_MODULE_4__["default"].chevronleft), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(pure_react_carousel__WEBPACK_IMPORTED_MODULE_5__.DotGroup, {
        className: "monsterinsights-carousel-navigation-dots"
      }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(pure_react_carousel__WEBPACK_IMPORTED_MODULE_5__.ButtonNext, null, _GUTENBERG_APP_THEME_Icons__WEBPACK_IMPORTED_MODULE_4__["default"].chevronright));
    }
  };
  const checkIcon = () => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: "monsterinsights-theme-picker-check"
    }, _GUTENBERG_APP_THEME_Icons__WEBPACK_IMPORTED_MODULE_4__["default"].checkmark);
  };
  const labelClass = option => {
    let labelClass = 'monsterinsights-theme-picker-label-' + option.value;
    if (option.value === selected) {
      labelClass += ' monsterinsights-theme-picker-label-selected';
    }
    if (!_PopularPostsLicenseCheck_GUTENBERG_APP_VERSION__WEBPACK_IMPORTED_MODULE_3__["default"].canaccess(option.level)) {
      labelClass += ' monsterinsights-theme-picker-label-disabled';
    }
    return labelClass;
  };
  return !(0,lodash__WEBPACK_IMPORTED_MODULE_1__.isEmpty)(options) && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(BaseControl, {
    id: id,
    help: help,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()(className, 'monsterinsights-theme-picker')
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(pure_react_carousel__WEBPACK_IMPORTED_MODULE_5__.CarouselProvider, {
    naturalSlideWidth: 250,
    totalSlides: slides.length,
    className: "monsterinsights-theme-picker-carousel",
    currentSlide: selectedSlide,
    dragEnabled: false
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(pure_react_carousel__WEBPACK_IMPORTED_MODULE_5__.Slider, null, slides.map((slide, slideIndex) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(pure_react_carousel__WEBPACK_IMPORTED_MODULE_5__.Slide, {
    className: "monsterinsights-theme-picker-slide",
    index: slideIndex,
    key: `${id}-${slideIndex}`
  }, slide.map((option, index) => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    key: `${id}-${slideIndex}-${index}`,
    className: "monsterinsights-slider-theme-option"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    id: `${id}-${slideIndex}-${index}`,
    className: "monsterinsights-slider-theme-input",
    type: "radio",
    name: id,
    value: option.value,
    onChange: onChangeValue,
    checked: option.value === selected,
    "aria-describedby": !!help ? `${id}__help` : undefined
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: `${id}-${slideIndex}-${index}`,
    className: labelClass(option)
  }, option.value === selected && checkIcon(), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "monsterinsights-theme-picker-label-icon"
  }, icons[option.value] ? icons[option.value] : ''), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "monsterinsights-theme-picker-label-text"
  }, option.label))))))), carouselNavigation()));
}

/***/ }),

/***/ "./src/blocks/components/monsterinsights-Icons.js":
/*!********************************************************!*\
  !*** ./src/blocks/components/monsterinsights-Icons.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

let icons = {};
icons.checkmark = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "24",
  height: "20",
  viewBox: "0 0 24 20",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M21.0565 2.94355C23.0188 4.87903 24 7.23118 24 10C24 12.7688 23.0188 15.1344 21.0565 17.0968C19.121 19.0323 16.7688 20 14 20C11.2312 20 8.86559 19.0323 6.90323 17.0968C4.96774 15.1344 4 12.7688 4 10C4 7.23118 4.96774 4.87903 6.90323 2.94355C8.86559 0.981183 11.2312 0 14 0C16.7688 0 19.121 0.981183 21.0565 2.94355ZM12.8306 15.2823L20.25 7.8629C20.5726 7.5672 20.5726 7.27151 20.25 6.97581L19.3629 6.04839C19.0403 5.75269 18.7312 5.75269 18.4355 6.04839L12.3871 12.0968L9.56452 9.27419C9.26882 8.97849 8.95968 8.97849 8.6371 9.27419L7.75 10.2016C7.42742 10.4973 7.42742 10.793 7.75 11.0887L11.9435 15.2823C12.2392 15.6048 12.5349 15.6048 12.8306 15.2823Z",
  fill: "#338EEF"
}));
icons.chevronright = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "8",
  height: "12",
  viewBox: "0 0 8 12",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M7.77369 5.53211L2.28571 0.19266C2.1537 0.0642201 1.9934 -6.52786e-08 1.80481 -5.7035e-08C1.61622 -4.87915e-08 1.45592 0.0642201 1.3239 0.192661L0.701556 0.798165C0.569543 0.926605 0.503536 1.08257 0.503536 1.26605C0.484677 1.44954 0.541254 1.6055 0.673267 1.73394L5.05799 6L0.673268 10.2661C0.541255 10.3945 0.484678 10.5505 0.503537 10.7339C0.503537 10.9174 0.569543 11.0734 0.701556 11.2018L1.3239 11.8073C1.45592 11.9358 1.61622 12 1.80481 12C1.9934 12 2.1537 11.9358 2.28571 11.8073L7.77369 6.46789C7.92456 6.33945 8 6.18349 8 6C8 5.81651 7.92456 5.66055 7.77369 5.53211Z",
  fill: "#C4C4C4"
}));
icons.chevronleft = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "8",
  height: "12",
  viewBox: "0 0 8 12",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0.226309 5.53211L5.71429 0.19266C5.8463 0.0642201 6.0066 -6.52786e-08 6.19519 -5.7035e-08C6.38378 -4.87915e-08 6.54408 0.0642201 6.6761 0.192661L7.29844 0.798165C7.43046 0.926605 7.49646 1.08257 7.49646 1.26605C7.51532 1.44954 7.45875 1.6055 7.32673 1.73394L2.94201 6L7.32673 10.2661C7.45875 10.3945 7.51532 10.5505 7.49646 10.7339C7.49646 10.9174 7.43046 11.0734 7.29844 11.2018L6.6761 11.8073C6.54408 11.9358 6.38378 12 6.19519 12C6.0066 12 5.8463 11.9358 5.71429 11.8073L0.226309 6.46789C0.0754363 6.33945 6.83386e-07 6.18349 6.91406e-07 6C6.99426e-07 5.81651 0.0754363 5.66055 0.226309 5.53211Z",
  fill: "#C4C4C4"
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (icons);

/***/ }),

/***/ "./src/blocks/index-Lite.js":
/*!**********************************!*\
  !*** ./src/blocks/index-Lite.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _popular_posts_inline__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./popular-posts-inline */ "./src/blocks/popular-posts-inline/index.js");
/* harmony import */ var _popular_posts_widget__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./popular-posts-widget */ "./src/blocks/popular-posts-widget/index.js");



/***/ }),

/***/ "./src/blocks/popular-posts-inline/components/monsterinsights-InlineIcons.js":
/*!***********************************************************************************!*\
  !*** ./src/blocks/popular-posts-inline/components/monsterinsights-InlineIcons.js ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

let icons = {};
icons.inlinepop = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "20",
  height: "16",
  viewBox: "0 0 20 16",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fillRule: "evenodd",
  clipRule: "evenodd",
  d: "M0 0H20V2H0V0ZM20 14V16H0V14H20ZM2 4C0.895431 4 0 4.89543 0 6V10C0 11.1046 0.895431 12 2 12H6C7.10457 12 8 11.1046 8 10V6C8 4.89543 7.10457 4 6 4H2ZM10 5H20V7H10V5ZM18 9H10V11H18V9Z",
  fill: "#555D66"
}));
icons.alpha = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "38",
  viewBox: "0 0 70 38",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "70",
  height: "37.8947",
  rx: "3",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "5.72729",
  y: "19.8947",
  width: "58.5455",
  height: "8.52632",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "5.72729",
  y: "7.57892",
  width: "17.1818",
  height: "8.52632",
  rx: "3",
  fill: "#338EEF"
}));
icons.beta = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "31",
  y: "21",
  width: "32",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "31",
  y: "10",
  width: "18",
  height: "9",
  rx: "3",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "7",
  y: "10",
  width: "20",
  height: "20",
  rx: "3",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "1.5",
  y: "1.5",
  width: "67",
  height: "37",
  rx: "1.5",
  stroke: "#E7F2FD",
  strokeWidth: "3"
}));
icons.charlie = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "28",
  viewBox: "0 0 70 28",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "16",
  y: "19",
  width: "54",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  y: "11",
  width: "70",
  height: "3",
  rx: "1.5",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "26",
  height: "7",
  rx: "3",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("circle", {
  cx: "4.5",
  cy: "23.5",
  r: "4.5",
  fill: "#1170D5"
}));
icons.delta = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "21",
  width: "54",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "22",
  y: "9",
  width: "18",
  height: "9",
  rx: "3",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M12.0303 9.5625C12.4613 8.8125 13.5387 8.8125 13.9697 9.5625L17.8483 16.3125C18.2793 17.0625 17.7406 18 16.8787 18H9.12134C8.25942 18 7.72072 17.0625 8.15168 16.3125L12.0303 9.5625Z",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "1.5",
  y: "1.5",
  width: "67",
  height: "37",
  rx: "1.5",
  stroke: "#E7F2FD",
  strokeWidth: "3"
}));
icons.echo = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "70",
  height: "40",
  rx: "3",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "24.8182",
  y: "16",
  width: "39.4545",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "5.72729",
  y: "16",
  width: "15.2727",
  height: "9",
  rx: "3",
  fill: "#338EEF"
}));
icons.foxtrot = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "26",
  y: "22",
  width: "44",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "26",
  y: "9",
  width: "28",
  height: "9",
  rx: "3",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  y: "9",
  width: "22",
  height: "22",
  rx: "3",
  fill: "#338EEF"
}));
icons.golf = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "29",
  viewBox: "0 0 70 29",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  y: "11",
  width: "70",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  y: "26",
  width: "70",
  height: "3",
  rx: "1.5",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "21",
  height: "7",
  rx: "3",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "25",
  y: "1",
  width: "45",
  height: "3",
  rx: "1.5",
  fill: "#B8D8F9"
}));
icons.hotel = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "70",
  height: "40",
  rx: "3",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "22",
  y: "16",
  width: "40",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M12.0303 16.5625C12.4613 15.8125 13.5387 15.8125 13.9697 16.5625L17.8483 23.3125C18.2793 24.0625 17.7406 25 16.8787 25H9.12134C8.25942 25 7.72072 24.0625 8.15168 23.3125L12.0303 16.5625Z",
  fill: "#338EEF"
}));
icons.india = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("g", {
  clipPath: "url(#clip0)"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "70",
  height: "40",
  rx: "3",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "26",
  y: "16",
  width: "36",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "16",
  width: "14",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  y: "41",
  width: "41",
  height: "4",
  transform: "rotate(-90 0 41)",
  fill: "#338EEF"
})), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("defs", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("clipPath", {
  id: "clip0"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "70",
  height: "40",
  rx: "3",
  fill: "white"
}))));
icons.juliett = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "70",
  height: "40",
  rx: "3",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "30",
  y: "20",
  width: "32",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M50 14C50 12.3431 51.3431 11 53 11H60C61.1046 11 62 11.8954 62 13V15C62 16.6569 60.6569 18 59 18H53C51.3431 18 50 16.6569 50 15V14Z",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "30",
  y: "11",
  width: "32",
  height: "4",
  rx: "2",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "11",
  width: "18",
  height: "18",
  rx: "3",
  fill: "#338EEF"
}));
icons.kilo = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  y: "22",
  width: "70",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  y: "9",
  width: "26",
  height: "9",
  rx: "3",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "12.7273",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "25.4545",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "38.1818",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "50.9091",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "63.6364",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  y: "37",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "12.7273",
  y: "37",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "25.4545",
  y: "37",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "38.1818",
  y: "37",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "50.9091",
  y: "37",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "63.6364",
  y: "37",
  width: "6.36364",
  height: "3",
  rx: "1",
  fill: "#B8D8F9"
}));
icons.lima = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "70",
  height: "40",
  rx: "3",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "6",
  y: "22",
  width: "32",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "6",
  y: "8",
  width: "20",
  height: "9",
  rx: "3",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "42",
  y: "9",
  width: "22",
  height: "22",
  rx: "3",
  fill: "#338EEF"
}));
icons.mike = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "70",
  height: "40",
  rx: "3",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "35",
  y: "15",
  width: "27",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fillRule: "evenodd",
  clipRule: "evenodd",
  d: "M28.8438 28H10.1562C9.55729 28 9.04818 27.7934 8.62891 27.3802C8.20964 26.967 8 26.4653 8 25.875V22.3C8 21.0297 9.02974 20 10.3 20H21.7C22.9703 20 24 18.9703 24 17.7V13.3C24 12.0297 25.0297 11 26.3 11H28.8438C29.4427 11 29.9518 11.2066 30.3711 11.6198C30.7904 12.033 31 12.5347 31 13.125V25.875C31 26.4653 30.7904 26.967 30.3711 27.3802C29.9518 27.7934 29.4427 28 28.8438 28ZM10.3 11C9.02974 11 8 12.0297 8 13.3V15.7C8 16.9703 9.02974 18 10.3 18H19.7C20.9703 18 22 16.9703 22 15.7V13.3C22 12.0297 20.9703 11 19.7 11H10.3Z",
  fill: "#338EEF"
}));
icons.november = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "70",
  height: "40",
  viewBox: "0 0 70 40",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "29",
  y: "22",
  width: "33",
  height: "9",
  rx: "3",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "29",
  y: "9",
  width: "18",
  height: "9",
  rx: "3",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fillRule: "evenodd",
  clipRule: "evenodd",
  d: "M17 28C21.4183 28 25 24.4183 25 20C25 15.5817 21.4183 12 17 12C12.5817 12 9 15.5817 9 20C9 24.4183 12.5817 28 17 28ZM20.544 21.8756L17.5907 16.7081C17.3281 16.2488 16.6719 16.2488 16.4093 16.7081L13.456 21.8756C13.1935 22.3349 13.5217 22.9091 14.0467 22.9091H19.9533C20.4783 22.9091 20.8065 22.3349 20.544 21.8756Z",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "1.5",
  y: "1.5",
  width: "67",
  height: "37",
  rx: "1.5",
  stroke: "#E7F2FD",
  strokeWidth: "3"
}));
icons.themeIcon = (color = '#EB5757') => {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "14",
    height: "19",
    viewBox: "0 0 14 19",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M7.875 0.899463C7.875 1.59183 8.0816 2.24711 8.49479 2.8653C8.93229 3.48349 9.44271 4.06458 10.026 4.60859C10.6337 5.15259 11.2292 5.73369 11.8125 6.35188C12.4201 6.97007 12.9306 7.76135 13.3438 8.72572C13.7812 9.66537 14 10.7163 14 11.8785C14 13.832 13.3073 15.5011 11.9219 16.8858C10.5608 18.2953 8.92014 19 7 19C5.07986 19 3.42708 18.2953 2.04167 16.8858C0.680556 15.5011 0 13.832 0 11.8785C0 9.94973 0.668403 8.28062 2.00521 6.87116C2.27257 6.57443 2.58854 6.50024 2.95312 6.64861C3.31771 6.79697 3.5 7.08134 3.5 7.50171V10.6545C3.5 11.3221 3.71875 11.8908 4.15625 12.3607C4.61806 12.8305 5.16493 13.0654 5.79688 13.0654C6.45312 13.0654 7.01215 12.8428 7.47396 12.3978C7.93576 11.9279 8.16667 11.3592 8.16667 10.6916C8.16667 10.2712 8.04514 9.86318 7.80208 9.46754C7.58333 9.0719 7.31597 8.71336 7 8.3919C6.68403 8.07044 6.34375 7.73662 5.97917 7.39043C5.63889 7.04425 5.34722 6.66097 5.10417 6.2406C4.88542 5.82024 4.73958 5.35041 4.66667 4.83114C4.59375 4.31186 4.67882 3.68131 4.92188 2.93948C5.18924 2.17293 5.63889 1.33219 6.27083 0.417277C6.51389 0.0463641 6.84201 -0.0772735 7.25521 0.0463641C7.6684 0.170002 7.875 0.454368 7.875 0.899463Z",
    fill: color
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (icons);

/***/ }),

/***/ "./src/blocks/popular-posts-inline/index.js":
/*!**************************************************!*\
  !*** ./src/blocks/popular-posts-inline/index.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_GUTENBERG_APP_THEME_InlineIcons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/GUTENBERG_APP_THEME-InlineIcons */ "./src/blocks/popular-posts-inline/components/monsterinsights-InlineIcons.js");
/* harmony import */ var _components_PopularPostsThemePicker__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/PopularPostsThemePicker */ "./src/blocks/components/PopularPostsThemePicker.js");





const blockId = 'monsterinsights/popular-posts-inline';

/**
 * Internal block libraries
 */
const {
  __
} = wp.i18n;
const {
  registerBlockType
} = wp.blocks;
const {
  apiFetch
} = wp;
const {
  registerStore,
  withSelect
} = wp.data;
const {
  InspectorControls
} = wp.blockEditor;
const {
  PanelBody,
  PanelRow,
  TextControl,
  ColorPalette,
  RangeControl
} = wp.components;
const MonsterInsightsVars = window.monsterinsights_gutenberg_tool_vars;
const actions = {
  setThemes(themes) {
    return {
      type: 'SET_THEMES',
      themes
    };
  },
  receiveThemes(path) {
    return {
      type: 'RECEIVE_THEMES',
      path
    };
  }
};
const store = registerStore('monsterinsights/v1/popular-posts/inline', {
  reducer(state = {
    themes: {}
  }, action) {
    switch (action.type) {
      case 'SET_THEMES':
        return {
          ...state,
          themes: action.themes
        };
    }
    return state;
  },
  actions,
  selectors: {
    receiveThemes(state) {
      const {
        themes
      } = state;
      return themes;
    }
  },
  controls: {
    RECEIVE_THEMES(action) {
      return apiFetch({
        path: action.path
      });
    }
  },
  resolvers: {
    *receiveThemes(state) {
      const themes = yield actions.receiveThemes('/monsterinsights/v1/popular-posts/themes/inline');
      if ('undefined' !== typeof themes.themes) {
        for (const slug in themes.themes) {
          if (themes.themes.hasOwnProperty(slug)) {
            themes.themes[slug]['value'] = slug;
          }
        }
        return actions.setThemes(themes);
      }
    }
  }
});

/**
 * Register block
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (registerBlockType(blockId, {
  title: __('Inline Popular Posts', "google-analytics-for-wordpress"),
  description: __('Add inline popular posts from MonsterInsights', "google-analytics-for-wordpress"),
  category: 'widgets',
  icon: _components_GUTENBERG_APP_THEME_InlineIcons__WEBPACK_IMPORTED_MODULE_1__["default"].inlinepop,
  keywords: [__('Popular', "google-analytics-for-wordpress"), __('Posts', "google-analytics-for-wordpress"), __('Inline', "google-analytics-for-wordpress"), 'MonsterInsights'],
  example: {},
  attributes: {
    theme: {
      type: 'string'
    },
    title_size: {
      type: 'number'
    },
    title_color: {
      type: 'string'
    },
    label_color: {
      type: 'string'
    },
    label_text: {
      type: 'string'
    },
    label_background: {
      type: 'string'
    },
    background_color: {
      type: 'string'
    },
    background_border: {
      type: 'string'
    },
    icon_background: {
      type: 'string'
    },
    icon_color: {
      type: 'string'
    },
    border_color: {
      type: 'string'
    },
    border_color2: {
      type: 'string'
    }
  },
  edit: withSelect(select => {
    return {
      themes: select('monsterinsights/v1/popular-posts/inline').receiveThemes()
    };
  })(props => {
    const {
      attributes: {
        theme,
        title_size,
        title_color,
        label_color,
        label_text,
        label_background,
        background_color,
        background_border,
        icon_background,
        icon_color,
        border_color,
        border_color2
      },
      themes,
      setAttributes
    } = props;
    const colors = [{
      name: 'pink',
      color: '#F58EA8'
    }, {
      name: 'red',
      color: '#CD3034'
    }, {
      name: 'orange',
      color: '#FD6A21'
    }, {
      name: 'yellow',
      color: '#FBB82B'
    }, {
      name: 'green',
      color: '#7FDBB6'
    }, {
      name: 'green',
      color: '#21CF86'
    }, {
      name: 'blue',
      color: '#91D2FA'
    }, {
      name: 'blue',
      color: '#1B95E0'
    }, {
      name: 'purple',
      color: '#9A57DD'
    }, {
      name: 'gray',
      color: '#EEEEEE'
    }, {
      name: 'gray',
      color: '#ACB8C3'
    }, {
      name: 'black',
      color: '#000000'
    }];
    const loadedThemes = 'undefined' !== typeof themes.themes ? themes.themes : {};
    const defaultSelected = 'undefined' === typeof props.attributes['theme'] ? themes.selected : theme;
    const themeObject = 'undefined' !== typeof defaultSelected && 'undefined' !== typeof loadedThemes[defaultSelected] ? loadedThemes[defaultSelected] : {};
    const themeStyles = 'undefined' !== typeof themeObject['styles'] ? themeObject['styles'] : {};
    let displayValues = {};
    for (const styleElement in themeStyles) {
      if (!themeStyles.hasOwnProperty(styleElement)) {
        continue;
      }
      for (const styleProp in themeStyles[styleElement]) {
        if (!themeStyles[styleElement].hasOwnProperty(styleProp)) {
          continue;
        }
        const attributeKey = styleElement + '_' + styleProp;
        if ('undefined' === typeof props.attributes[attributeKey]) {
          displayValues[attributeKey] = themeStyles[styleElement][styleProp];
        } else {
          displayValues[attributeKey] = props.attributes[attributeKey];
        }
      }
    }
    const titleControls = () => {
      if ('undefined' === typeof themeStyles['title']) {
        return;
      }
      let elements = [];
      if ('undefined' !== typeof themeStyles['title']['size']) {
        elements.push(FontSizeInput(__('Title Font Size', "google-analytics-for-wordpress"), 'title_size'));
      }
      if ('undefined' !== typeof themeStyles['title']['color']) {
        elements.push(ColorInput(__('Title Color', "google-analytics-for-wordpress"), 'title_color'));
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Title Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-inline-title"
      }, elements);
    };
    const labelControls = () => {
      if ('undefined' === typeof themeStyles['label']) {
        return;
      }
      let elements = [];
      if ('undefined' !== typeof themeStyles['label']['text']) {
        elements.push(TextInput(__('Label Text', "google-analytics-for-wordpress"), 'label_text'));
      }
      if ('undefined' !== typeof themeStyles['label']['color']) {
        elements.push(ColorInput(__('Label Color', "google-analytics-for-wordpress"), 'label_color'));
      }
      if ('undefined' !== typeof themeStyles['label']['background']) {
        elements.push(ColorInput(__('Label Background', "google-analytics-for-wordpress"), 'label_background'));
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Label Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-inline-label"
      }, " ", elements, " ");
    };
    const borderControls = () => {
      let elements = [];
      if ('undefined' !== typeof themeStyles['border'] && 'undefined' !== typeof themeStyles['border']['color']) {
        elements.push(ColorInput(__('Border Color', "google-analytics-for-wordpress"), 'border_color'));
      }
      if ('undefined' !== typeof themeStyles['border'] && 'undefined' !== typeof themeStyles['border']['color2']) {
        elements.push(ColorInput(__('Bottom Border Color', "google-analytics-for-wordpress"), 'border_color2'));
      }
      if ('undefined' !== typeof themeStyles['background'] && 'undefined' !== typeof themeStyles['background']['border']) {
        elements.push(ColorInput(__('Border Color', "google-analytics-for-wordpress"), 'background_border'));
      }
      if (0 === elements.length) {
        return;
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Border Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-inline-border"
      }, " ", elements, " ");
    };
    const backgroundControls = () => {
      if ('undefined' === typeof themeStyles['background'] || 'undefined' === typeof themeStyles['background']['color']) {
        return;
      }
      let elements = [];
      if ('undefined' !== typeof themeStyles['background']['color']) {
        elements.push(ColorInput(__('Background Color', "google-analytics-for-wordpress"), 'background_color'));
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Background Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-inline-background"
      }, " ", elements, " ");
    };
    const iconControls = () => {
      if ('undefined' === typeof themeStyles['icon']) {
        return;
      }
      let elements = [];
      if ('undefined' !== typeof themeStyles['icon']['color']) {
        elements.push(ColorInput(__('Icon Color', "google-analytics-for-wordpress"), 'icon_color'));
      }
      if ('undefined' !== typeof themeStyles['icon']['background']) {
        elements.push(ColorInput(__('Icon Background Color', "google-analytics-for-wordpress"), 'icon_background'));
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Icon Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-inline-icon"
      }, " ", elements, " ");
    };
    const FontSizeInput = (label, attribute) => {
      const value = displayValues[attribute];
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(RangeControl, {
        key: 'monsterinsights-popular-posts-input' + attribute,
        label: label,
        value: parseInt(value),
        min: 1,
        max: 100,
        onChange: newValue => setAttributes({
          [attribute]: '' === newValue ? '' : parseInt(newValue)
        })
      });
    };
    const TextInput = (label, attribute) => {
      const value = displayValues[attribute];
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(TextControl, {
        key: 'monsterinsights-popular-posts-input' + attribute,
        label: label,
        type: "text",
        value: value,
        onChange: newValue => setAttributes({
          [attribute]: newValue
        })
      });
    };
    const ColorInput = (label, attribute) => {
      const value = displayValues[attribute];
      return [(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
        key: 'monsterinsights-popular-posts-label' + attribute
      }, label), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ColorPalette, {
        key: 'monsterinsights-popular-posts-input' + attribute,
        value: value,
        colors: colors,
        onChange: value => {
          setAttributes({
            [attribute]: value
          });
        }
      })];
    };
    const ThemeImage = () => {
      if ('undefined' !== typeof themeObject['image'] && themeObject['image']) {
        const imageName = 'undefined' !== typeof themeStyles['image'] ? themeStyles['image'] : 'theme-preview-image.jpg';
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
          className: "monsterinsights-inline-popular-posts-image"
        }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
          src: MonsterInsightsVars.vue_assets_path + 'img/' + imageName
        }));
      }
    };
    const ThemeLabel = () => {
      if ('undefined' !== typeof themeStyles['label']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          style: {
            color: displayValues['label_color'],
            backgroundColor: displayValues['label_background']
          },
          className: "monsterinsights-inline-popular-posts-label"
        }, displayValues['label_text']);
      }
    };
    const ThemeTitle = () => {
      if ('undefined' !== typeof themeStyles['title'] && 'undefined' === typeof themeObject['list'] && 'undefined' !== typeof themeStyles['title']['text']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
          href: "#",
          className: "monsterinsights-inline-popular-posts-title",
          style: {
            color: displayValues['title_color'],
            fontSize: displayValues['title_size'] + 'px'
          }
        }, themeStyles['title']['text']);
      }
    };
    const ThemeList = () => {
      if ('undefined' !== typeof themeObject['list']) {
        const items = [];
        for (const index in themeObject['list']) {
          items.push((0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
            key: 'monsterinsights-popular-posts-preview-list-item-' + index,
            style: {
              color: displayValues['title_color'],
              fontSize: displayValues['title_size'] + 'px'
            }
          }, themeObject['list'][index]));
        }
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
          className: "monsterinsights-inline-popular-posts-list"
        }, items);
      }
    };
    const ThemeIcon = () => {
      if ('undefined' !== typeof themeStyles['icon']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          className: "monsterinsights-inline-popular-posts-icon",
          style: {
            backgroundColor: displayValues['icon_background']
          }
        }, _components_GUTENBERG_APP_THEME_InlineIcons__WEBPACK_IMPORTED_MODULE_1__["default"].themeIcon(displayValues['icon_color']));
      }
    };
    const ThemeBorder = () => {
      if ('undefined' !== typeof themeStyles['border'] && 'undefined' !== typeof themeStyles['border']['color']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          className: "monsterinsights-inline-popular-posts-border",
          style: {
            borderColor: displayValues['border_color']
          }
        });
      }
    };
    const ThemeBorder2 = () => {
      if ('undefined' !== typeof themeStyles['border'] && 'undefined' !== typeof themeStyles['border']['color2']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          className: "monsterinsights-inline-popular-posts-border-2",
          style: {
            borderColor: displayValues['border_color2']
          }
        });
      }
    };
    return [(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(InspectorControls, {
      key: "monsterinsights-popular-posts-inline-inspector-controls"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
      title: __('Theme', "google-analytics-for-wordpress"),
      key: "monsterinsights-popular-posts-inline-theme"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, {
      key: "monsterinsights-popular-posts-inline-theme-row"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_PopularPostsThemePicker__WEBPACK_IMPORTED_MODULE_2__["default"], {
      id: "monsterinsights-popular-posts-inline-theme",
      options: loadedThemes,
      selected: defaultSelected,
      icons: _components_GUTENBERG_APP_THEME_InlineIcons__WEBPACK_IMPORTED_MODULE_1__["default"],
      onChange: option => {
        setAttributes({
          theme: option
        });
      }
    }))), titleControls(), labelControls(), borderControls(), backgroundControls(), iconControls()), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: "monsterinsights-popular-posts-inline-preview"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: 'monsterinsights-inline-popular-posts-widget monsterinsights-inline-popular-posts-' + defaultSelected,
      style: {
        backgroundColor: displayValues['background_color'],
        borderColor: displayValues['background_border']
      }
    }, ThemeImage(), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-inline-popular-posts-text"
    }, ThemeIcon(), ThemeLabel(), ThemeBorder(), ThemeTitle(), ThemeList(), ThemeBorder2())))];
  }),
  save: props => {
    return null;
  }
}));

/***/ }),

/***/ "./src/blocks/popular-posts-widget/components/monsterinsights-WidgetIcons.js":
/*!***********************************************************************************!*\
  !*** ./src/blocks/popular-posts-widget/components/monsterinsights-WidgetIcons.js ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

let icons = {};
icons.widgetpop = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "16",
  height: "20",
  viewBox: "0 0 16 20",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  fillRule: "evenodd",
  clipRule: "evenodd",
  d: "M2 0C0.895431 0 0 0.895431 0 2V10C0 11.1046 0.895431 12 2 12H14C15.1046 12 16 11.1046 16 10V2C16 0.895431 15.1046 0 14 0H2ZM8.94046 4.70557L8 2L7.05954 4.70557L4.19577 4.76393L6.47831 6.49443L5.64886 9.23607L8 7.6L10.3511 9.23607L9.52169 6.49443L11.8042 4.76393L8.94046 4.70557ZM0 14H14V16H0V14ZM10 18H0V20H10V18Z",
  fill: "#555D66"
}));
icons.alpha = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "74",
  height: "46",
  viewBox: "0 0 74 46",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "74",
  height: "46",
  rx: "5",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "7.40002",
  y: "12",
  width: "59.2",
  height: "9",
  rx: "2",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "7.40002",
  y: "25",
  width: "51.8",
  height: "9",
  rx: "2",
  fill: "#338EEF"
}));
icons.beta = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "74",
  height: "46",
  viewBox: "0 0 74 46",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0 15C0 13.3431 1.34315 12 3 12H4V34H3C1.34315 34 0 32.6569 0 31V15Z",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "12",
  y: "12",
  width: "54",
  height: "9",
  rx: "2",
  fill: "#4296F0"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "12",
  y: "25",
  width: "44",
  height: "9",
  rx: "2",
  fill: "#4296F0"
}));
icons.charlie = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "74",
  height: "46",
  viewBox: "0 0 74 46",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "74",
  height: "46",
  rx: "3",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "74",
  height: "4",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "14",
  width: "58",
  height: "9",
  rx: "2",
  fill: "#4296F0"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "27",
  width: "40",
  height: "9",
  rx: "2",
  fill: "#4296F0"
}));
icons.delta = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "74",
  height: "39",
  viewBox: "0 0 74 39",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "31",
  y: "12",
  width: "43",
  height: "7",
  rx: "2",
  fill: "#4296F0"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "31",
  y: "23",
  width: "32",
  height: "7",
  rx: "2",
  fill: "#4296F0"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "31",
  y: "34",
  width: "43",
  height: "5",
  rx: "2",
  fill: "#A0CBF8"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  y: "12",
  width: "27",
  height: "27",
  rx: "2",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "74",
  height: "4",
  fill: "#1170D5"
}));
icons.echo = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "74",
  height: "76",
  viewBox: "0 0 74 76",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "74",
  height: "76",
  rx: "5",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "41",
  width: "58",
  height: "7",
  rx: "2",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "61",
  width: "7",
  height: "7",
  rx: "3.5",
  fill: "#A0CBF8"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "8",
  width: "58",
  height: "29",
  rx: "2",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "52",
  width: "34",
  height: "5",
  rx: "2",
  fill: "#A0CBF8"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "46",
  y: "52",
  width: "20",
  height: "5",
  rx: "2",
  fill: "#A0CBF8"
}));
icons.foxtrot = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "74",
  height: "43",
  viewBox: "0 0 74 43",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "74",
  height: "43",
  rx: "5",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "8",
  width: "58",
  height: "7",
  rx: "2",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "28",
  width: "7",
  height: "7",
  rx: "3.5",
  fill: "#A0CBF8"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "19",
  width: "34",
  height: "5",
  rx: "2",
  fill: "#A0CBF8"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "46",
  y: "19",
  width: "20",
  height: "5",
  rx: "2",
  fill: "#A0CBF8"
}));
icons.golf = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "74",
  height: "56",
  viewBox: "0 0 74 56",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "74",
  height: "56",
  rx: "5",
  fill: "#E7F2FD"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "21",
  width: "58",
  height: "7",
  rx: "2",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "41",
  width: "7",
  height: "7",
  rx: "3.5",
  fill: "#A0CBF8"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "8",
  width: "16",
  height: "9",
  rx: "2",
  fill: "#1170D5"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "32",
  width: "34",
  height: "5",
  rx: "2",
  fill: "#A0CBF8"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "46",
  y: "32",
  width: "20",
  height: "5",
  rx: "2",
  fill: "#A0CBF8"
}));
icons.hotel = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "74",
  height: "50",
  viewBox: "0 0 74 50",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  width: "74",
  height: "50",
  rx: "5",
  fill: "#338EEF"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M54.0625 42H19.9375C18.8438 42 17.9141 41.6111 17.1484 40.8333C16.3828 40.0556 16 39.1111 16 38V14C16 12.8889 16.3828 11.9444 17.1484 11.1667C17.9141 10.3889 18.8438 10 19.9375 10H54.0625C55.1562 10 56.0859 10.3889 56.8516 11.1667C57.6172 11.9444 58 12.8889 58 14V38C58 39.1111 57.6172 40.0556 56.8516 40.8333C56.0859 41.6111 55.1562 42 54.0625 42ZM28.3867 16.0833C27.5117 15.1389 26.4453 14.6667 25.1875 14.6667C23.9297 14.6667 22.8359 15.1389 21.9062 16.0833C21.0312 16.9722 20.5938 18.0556 20.5938 19.3333C20.5938 20.6111 21.0312 21.7222 21.9062 22.6667C22.8359 23.5556 23.9297 24 25.1875 24C26.4453 24 27.5117 23.5556 28.3867 22.6667C29.3164 21.7222 29.7812 20.6111 29.7812 19.3333C29.7812 18.0556 29.3164 16.9722 28.3867 16.0833ZM21.25 36.6667H52.75V27.3333L45.5312 20C45.0938 19.5556 44.6562 19.5556 44.2188 20L33.0625 31.3333L28.4688 26.6667C28.0312 26.2222 27.5938 26.2222 27.1562 26.6667L21.25 32.6667V36.6667Z",
  fill: "#59A3F2"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "26",
  width: "58",
  height: "7",
  rx: "2",
  fill: "#F1F7FE"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "8",
  y: "37",
  width: "34",
  height: "5",
  rx: "2",
  fill: "#A0CBF8"
}), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("rect", {
  x: "46",
  y: "37",
  width: "20",
  height: "5",
  rx: "2",
  fill: "#A0CBF8"
}));
icons.comments = color => {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "13",
    height: "12",
    viewBox: "0 0 13 12",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg",
    style: {
      fill: color
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M7.8251 1.25893C8.70332 2.09821 9.14243 3.10714 9.14243 4.28571C9.14243 5.46429 8.70332 6.47321 7.8251 7.3125C6.94689 8.15179 5.8887 8.57143 4.65056 8.57143C3.78674 8.57143 2.98771 8.34821 2.25346 7.90179C1.63439 8.34821 0.993719 8.57143 0.331456 8.57143C0.302662 8.57143 0.273868 8.5625 0.245074 8.54464C0.216279 8.50893 0.194684 8.47321 0.180287 8.4375C0.151493 8.34821 0.158691 8.26786 0.201882 8.19643C0.50422 7.83929 0.763366 7.35714 0.979321 6.75C0.432235 6.01786 0.158691 5.19643 0.158691 4.28571C0.158691 3.10714 0.5978 2.09821 1.47602 1.25893C2.35424 0.419643 3.41242 0 4.65056 0C5.8887 0 6.94689 0.419643 7.8251 1.25893ZM11.7771 10.1786C11.993 10.7857 12.2522 11.2679 12.5545 11.625C12.5977 11.6964 12.6049 11.7768 12.5761 11.8661C12.5473 11.9554 12.4969 12 12.425 12C11.7627 12 11.122 11.7768 10.5029 11.3304C9.7687 11.7768 8.96967 12 8.10585 12C7.18444 12 6.34941 11.7589 5.60076 11.2768C4.85212 10.7946 4.30503 10.1607 3.9595 9.375C4.21865 9.41071 4.449 9.42857 4.65056 9.42857C6.07587 9.42857 7.29241 8.92857 8.30021 7.92857C9.32239 6.91071 9.83349 5.69643 9.83349 4.28571C9.83349 4.08929 9.82629 3.91071 9.81189 3.75C10.6325 4.07143 11.302 4.59821 11.8203 5.33036C12.3386 6.04464 12.5977 6.83929 12.5977 7.71429C12.5977 8.625 12.3242 9.44643 11.7771 10.1786Z"
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (icons);

/***/ }),

/***/ "./src/blocks/popular-posts-widget/index.js":
/*!**************************************************!*\
  !*** ./src/blocks/popular-posts-widget/index.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_GUTENBERG_APP_THEME_WidgetIcons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/GUTENBERG_APP_THEME-WidgetIcons */ "./src/blocks/popular-posts-widget/components/monsterinsights-WidgetIcons.js");
/* harmony import */ var _components_PopularPostsThemePicker__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/PopularPostsThemePicker */ "./src/blocks/components/PopularPostsThemePicker.js");
/* harmony import */ var _components_HierarchicalTerms_GUTENBERG_APP_VERSION__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../components/HierarchicalTerms-GUTENBERG_APP_VERSION */ "./src/blocks/components/HierarchicalTerms-Lite.js");






const blockId = 'monsterinsights/popular-posts-widget';

/**
 * Internal block libraries
 */
const {
  __
} = wp.i18n;
const {
  registerBlockType
} = wp.blocks;
const {
  apiFetch
} = wp;
const {
  registerStore,
  withSelect
} = wp.data;
const {
  InspectorControls
} = wp.blockEditor;
const {
  PanelBody,
  PanelRow,
  TextControl,
  ColorPalette,
  RadioControl,
  ToggleControl,
  SelectControl,
  RangeControl
} = wp.components;
const MonsterInsightsVars = window.monsterinsights_gutenberg_tool_vars;
const actions = {
  setThemes(themes) {
    return {
      type: 'SET_THEMES',
      themes
    };
  },
  receiveThemes(path) {
    return {
      type: 'RECEIVE_THEMES',
      path
    };
  }
};
const store = registerStore('monsterinsights/v1/popular-posts/widget', {
  reducer(state = {
    themes: {}
  }, action) {
    switch (action.type) {
      case 'SET_THEMES':
        return {
          ...state,
          themes: action.themes
        };
    }
    return state;
  },
  actions,
  selectors: {
    receiveThemes(state) {
      const {
        themes
      } = state;
      return themes;
    }
  },
  controls: {
    RECEIVE_THEMES(action) {
      return apiFetch({
        path: action.path
      });
    }
  },
  resolvers: {
    *receiveThemes(state) {
      const themes = yield actions.receiveThemes('/monsterinsights/v1/popular-posts/themes/widget');
      if ('undefined' !== typeof themes.themes) {
        for (const slug in themes.themes) {
          if (themes.themes.hasOwnProperty(slug)) {
            themes.themes[slug]['value'] = slug;
          }
        }
        return actions.setThemes(themes);
      }
    }
  }
});

/**
 * Register block
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (registerBlockType(blockId, {
  title: __('Popular Posts', "google-analytics-for-wordpress"),
  description: __('Add popular posts from MonsterInsights', "google-analytics-for-wordpress"),
  category: 'widgets',
  icon: _components_GUTENBERG_APP_THEME_WidgetIcons__WEBPACK_IMPORTED_MODULE_1__["default"].widgetpop,
  keywords: [__('Popular', "google-analytics-for-wordpress"), __('Posts', "google-analytics-for-wordpress"), __('Widget', "google-analytics-for-wordpress"), 'MonsterInsights'],
  example: {},
  attributes: {
    theme: {
      type: 'string'
    },
    title_size: {
      type: 'number'
    },
    title_color: {
      type: 'string'
    },
    label_color: {
      type: 'string'
    },
    label_text: {
      type: 'string'
    },
    label_background: {
      type: 'string'
    },
    background_color: {
      type: 'string'
    },
    background_border: {
      type: 'string'
    },
    meta_color: {
      type: 'string'
    },
    meta_size: {
      type: 'string'
    },
    meta_author: {
      type: 'boolean'
    },
    meta_date: {
      type: 'boolean'
    },
    meta_comments: {
      type: 'boolean'
    },
    comments_color: {
      type: 'string'
    },
    columns: {
      type: 'number',
      default: 1
    },
    widget_title: {
      type: 'boolean'
    },
    widget_title_text: {
      type: 'string'
    },
    post_count: {
      type: 'number',
      default: 5
    },
    categories: {
      type: 'array',
      default: []
    }
  },
  edit: withSelect(select => {
    return {
      themes: select('monsterinsights/v1/popular-posts/widget').receiveThemes()
    };
  })(props => {
    const {
      attributes: {
        theme,
        title_size,
        title_color,
        label_color,
        label_text,
        label_background,
        background_color,
        background_border,
        meta_color,
        meta_size,
        meta_author,
        meta_comments,
        meta_date,
        comments_color,
        columns,
        widget_title,
        widget_title_text,
        post_count,
        categories
      },
      themes,
      setAttributes
    } = props;
    const colors = [{
      name: 'pink',
      color: '#F58EA8'
    }, {
      name: 'red',
      color: '#CD3034'
    }, {
      name: 'orange',
      color: '#FD6A21'
    }, {
      name: 'yellow',
      color: '#FBB82B'
    }, {
      name: 'green',
      color: '#7FDBB6'
    }, {
      name: 'green',
      color: '#21CF86'
    }, {
      name: 'blue',
      color: '#91D2FA'
    }, {
      name: 'blue',
      color: '#1B95E0'
    }, {
      name: 'purple',
      color: '#9A57DD'
    }, {
      name: 'gray',
      color: '#EEEEEE'
    }, {
      name: 'gray',
      color: '#ACB8C3'
    }, {
      name: 'black',
      color: '#000000'
    }];
    const loadedThemes = 'undefined' !== typeof themes.themes ? themes.themes : {};
    const defaultSelected = 'undefined' === typeof props.attributes['theme'] ? themes.selected : theme;
    const themeObject = 'undefined' !== typeof defaultSelected && 'undefined' !== typeof loadedThemes[defaultSelected] ? loadedThemes[defaultSelected] : {};
    const themeStyles = 'undefined' !== typeof themeObject['styles'] ? themeObject['styles'] : {};
    let displayValues = {};
    for (const styleElement in themeStyles) {
      if (!themeStyles.hasOwnProperty(styleElement)) {
        continue;
      }
      for (const styleProp in themeStyles[styleElement]) {
        if (!themeStyles[styleElement].hasOwnProperty(styleProp)) {
          continue;
        }
        const attributeKey = styleElement + '_' + styleProp;
        if ('undefined' === typeof props.attributes[attributeKey]) {
          displayValues[attributeKey] = themeStyles[styleElement][styleProp];
        } else {
          displayValues[attributeKey] = props.attributes[attributeKey];
        }
        if ('on' === displayValues[attributeKey]) {
          displayValues[attributeKey] = true;
        }
        if ('off' === displayValues[attributeKey]) {
          displayValues[attributeKey] = false;
        }
      }
    }
    const titleControls = () => {
      if ('undefined' === typeof themeStyles['title']) {
        return;
      }
      let elements = [];
      if ('undefined' !== typeof themeStyles['title']['size']) {
        elements.push(FontSizeInput(__('Title Font Size', "google-analytics-for-wordpress"), 'title_size'));
      }
      if ('undefined' !== typeof themeStyles['title']['color']) {
        elements.push(ColorInput(__('Title Color', "google-analytics-for-wordpress"), 'title_color'));
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Title Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-widget-title"
      }, elements);
    };
    const metaStyleControls = () => {
      if ('undefined' === typeof themeStyles['meta']) {
        return;
      }
      let elements = [];
      if ('undefined' !== typeof themeStyles['meta']['size']) {
        elements.push(FontSizeInput(__('Meta Font Size', "google-analytics-for-wordpress"), 'meta_size'));
      }
      if ('undefined' !== typeof themeStyles['meta']['color']) {
        elements.push(ColorInput(__('Meta Color', "google-analytics-for-wordpress"), 'meta_color'));
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Meta Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-widget-meta-styles"
      }, elements);
    };
    const commentsStyleControls = () => {
      if ('undefined' === typeof themeStyles['comments']) {
        return;
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Comment Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-widget-comment-styles"
      }, ColorInput(__('Comment Color', "google-analytics-for-wordpress"), 'comments_color'));
    };
    const labelControls = () => {
      if ('undefined' === typeof themeStyles['label']) {
        return;
      }
      let elements = [];
      if ('undefined' !== typeof themeStyles['label']['text']) {
        elements.push(TextInput(__('Label Text', "google-analytics-for-wordpress"), 'label_text'));
      }
      if ('undefined' !== typeof themeStyles['label']['color']) {
        elements.push(ColorInput(__('Label Color', "google-analytics-for-wordpress"), 'label_color'));
      }
      if ('undefined' !== typeof themeStyles['label']['background']) {
        elements.push(ColorInput(__('Label Background', "google-analytics-for-wordpress"), 'label_background'));
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Label Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-widget-label"
      }, " ", elements, " ");
    };
    const columnsControls = () => {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Wide-Layout Options', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-widget-columns"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(RadioControl, {
        label: __('Choose Layout', "google-analytics-for-wordpress"),
        help: __('Adjust the number of columns displayed when the widget is placed in a wide container.', "google-analytics-for-wordpress"),
        className: "monsterinsights-wide-column-options monsterinsights-popular-posts-widget-columns-control",
        key: "monsterinsights-popular-posts-widget-columns-control",
        options: [{
          value: 1,
          label: column1span()
        }, {
          value: 2,
          label: column2span()
        }, {
          value: 3,
          label: column3span()
        }],
        selected: columns,
        onChange: newValue => {
          setAttributes({
            ['columns']: '' === newValue ? '' : parseInt(newValue)
          });
          const updatedOptions = postCountOptions(parseInt(newValue));
          if (-1 === updatedOptions.indexOf(post_count)) {
            setAttributes({
              ['post_count']: parseInt(updatedOptions[0])
            });
          }
        }
      }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(SelectControl, {
        label: __('Post Count', "google-analytics-for-wordpress"),
        help: __('Number of posts displayed.', "google-analytics-for-wordpress"),
        options: postCountOptions(columns, true),
        value: post_count,
        key: "monsterinsights-popular-posts-widget-post-count",
        onChange: newValue => {
          setAttributes({
            ['post_count']: parseInt(newValue)
          });
        }
      }));
    };
    const postCountOptions = (columns, labeled) => {
      let options = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
      if (2 === columns) {
        options = [2, 4, 6, 8, 10];
      }
      if (3 === columns) {
        options = [3, 6, 9];
      }
      if (labeled) {
        options = options.map(function (option) {
          return {
            value: option,
            label: option
          };
        });
      }
      return options;
    };
    const metaControls = () => {
      if ('undefined' !== typeof themeStyles['meta']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
          title: __('Display Options', "google-analytics-for-wordpress"),
          key: "monsterinsights-popular-posts-widget-meta-options"
        }, meta_authorControl(), meta_dateControl(), meta_commentsControl());
      }
    };
    const behaviorControls = () => {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Behavior Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-widget-behavior-options"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ToggleControl, {
        label: __('Display Widget Title', "google-analytics-for-wordpress"),
        checked: widget_title,
        onChange: newValue => {
          setAttributes({
            ['widget_title']: newValue
          });
        }
      }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(TextControl, {
        key: 'monsterinsights-popular-posts-input-widget-title-text',
        label: __('Widget Title', "google-analytics-for-wordpress"),
        type: "text",
        value: widget_title_text,
        onChange: newValue => setAttributes({
          ['widget_title_text']: newValue
        })
      }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HierarchicalTerms_GUTENBERG_APP_VERSION__WEBPACK_IMPORTED_MODULE_3__["default"], {
        label: __('Only Show Posts From These Categories', "google-analytics-for-wordpress"),
        slug: "category",
        onUpdateTerms: newValue => {
          setAttributes({
            ['categories']: newValue
          });
        },
        terms: categories
      }));
    };
    const inArray = (needle, haystack) => {
      var length = haystack.length;
      for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) {
          return true;
        }
      }
      return false;
    };
    const meta_authorControl = () => {
      if ('undefined' !== typeof themeStyles['meta']['author']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ToggleControl, {
          label: __('Display Author', "google-analytics-for-wordpress"),
          checked: displayValues['meta_author'],
          onChange: newValue => {
            setAttributes({
              ['meta_author']: newValue
            });
          }
        });
      }
    };
    const meta_dateControl = () => {
      if ('undefined' !== typeof themeStyles['meta']['date']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ToggleControl, {
          label: __('Display Date', "google-analytics-for-wordpress"),
          checked: displayValues['meta_date'],
          onChange: newValue => {
            setAttributes({
              ['meta_date']: newValue
            });
          }
        });
      }
    };
    const meta_commentsControl = () => {
      if ('undefined' !== typeof themeStyles['meta']['comments']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ToggleControl, {
          label: __('Display Comments', "google-analytics-for-wordpress"),
          checked: displayValues['meta_comments'],
          onChange: newValue => {
            setAttributes({
              ['meta_comments']: newValue
            });
          }
        });
      }
    };
    const column1span = () => {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: "monsterinsights-wide-column monsterinsights-wide-column-one"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null));
    };
    const column2span = () => {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: "monsterinsights-wide-column monsterinsights-wide-column-two"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null));
    };
    const column3span = () => {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: "monsterinsights-wide-column monsterinsights-wide-column-three"
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null));
    };
    const borderControls = () => {
      let elements = [];
      if ('undefined' !== typeof themeStyles['border'] && 'undefined' !== typeof themeStyles['border']['color']) {
        elements.push(ColorInput(__('Border Color', "google-analytics-for-wordpress"), 'borderColor'));
      }
      if ('undefined' !== typeof themeStyles['border'] && 'undefined' !== typeof themeStyles['border']['color2']) {
        elements.push(ColorInput(__('Bottom Border Color', "google-analytics-for-wordpress"), 'borderColor2'));
      }
      if ('undefined' !== typeof themeStyles['background'] && 'undefined' !== typeof themeStyles['background']['border']) {
        elements.push(ColorInput(__('Border Color', "google-analytics-for-wordpress"), 'background_border'));
      }
      if (0 === elements.length) {
        return;
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Border Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-inline-border"
      }, " ", elements, " ");
    };
    const backgroundControls = () => {
      if ('undefined' === typeof themeStyles['background'] || 'undefined' === typeof themeStyles['background']['color']) {
        return;
      }
      let elements = [];
      if ('undefined' !== typeof themeStyles['background']['color']) {
        elements.push(ColorInput(__('Background Color', "google-analytics-for-wordpress"), 'background_color'));
      }
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
        title: __('Background Settings', "google-analytics-for-wordpress"),
        key: "monsterinsights-popular-posts-widget-background"
      }, " ", elements, " ");
    };
    const FontSizeInput = (label, attribute) => {
      const value = displayValues[attribute];
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(RangeControl, {
        key: 'monsterinsights-popular-posts-input' + attribute,
        label: label,
        value: parseInt(value),
        min: 1,
        max: 100,
        onChange: newValue => setAttributes({
          [attribute]: '' === newValue ? '' : parseInt(newValue)
        })
      });
    };
    const TextInput = (label, attribute) => {
      const value = displayValues[attribute];
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(TextControl, {
        key: 'monsterinsights-popular-posts-input' + attribute,
        label: label,
        type: "text",
        value: value,
        onChange: newValue => setAttributes({
          [attribute]: newValue
        })
      });
    };
    const ColorInput = (label, attribute) => {
      const value = displayValues[attribute];
      return [(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
        key: 'monsterinsights-popular-posts-label' + attribute
      }, label), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ColorPalette, {
        key: 'monsterinsights-popular-posts-input' + attribute,
        value: value,
        colors: colors,
        onChange: value => {
          setAttributes({
            [attribute]: value
          });
        }
      })];
    };
    const ThemeImage = (list, index) => {
      if (list.length > 0 && 'undefined' !== typeof list[index]) {
        const imageName = list[index];
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
          className: "monsterinsights-widget-popular-posts-image"
        }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
          src: MonsterInsightsVars.vue_assets_path + 'img/' + imageName
        }));
      }
    };
    const ThemeLabel = () => {
      if ('undefined' !== typeof themeStyles['label']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          style: {
            color: displayValues['label_color'],
            backgroundColor: displayValues['label_background']
          },
          className: "monsterinsights-widget-popular-posts-label"
        }, displayValues['label_text']);
      }
    };
    const ThemeMeta = () => {
      if ('undefined' !== typeof themeStyles['meta']) {
        return [(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
          className: "monsterinsights-widget-popular-posts-meta",
          key: "monsterinsights-widget-popular-posts-meta-options",
          style: {
            color: displayValues['meta_color'],
            fontSize: displayValues['meta_size'] + 'px'
          }
        }, Thememeta_author(), ThemeMetaSeparator(), Thememeta_date()), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          key: "monsterinsights-widget-popular-posts-comments-options"
        }, Thememeta_comments())];
      }
    };
    const Thememeta_author = () => {
      if (displayValues['meta_author']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          className: "monsterinsights-widget-popular-posts-author"
        }, "by Aazim Akhtar");
      }
    };
    const ThemeMetaSeparator = () => {
      if (displayValues['meta_author'] && displayValues['meta_date']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          dangerouslySetInnerHTML: {
            __html: themeStyles['meta']['separator']
          }
        });
      }
    };
    const Thememeta_comments = () => {
      if ('undefined' !== typeof themeStyles['meta'] && 'undefined' !== typeof themeStyles['meta']['comments'] && displayValues['meta_comments']) {
        let comments_color = 'undefined' !== typeof themeStyles['comments'] ? displayValues['comments_color'] : displayValues['meta_color'];
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          className: "monsterinsights-widget-popular-posts-comments",
          style: {
            color: comments_color,
            fontSize: displayValues['meta_size'] + 'px'
          }
        }, _components_GUTENBERG_APP_THEME_WidgetIcons__WEBPACK_IMPORTED_MODULE_1__["default"].comments(comments_color), " 24");
      }
    };
    const Thememeta_date = () => {
      if (displayValues['meta_date']) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
          className: "monsterinsights-widget-popular-posts-date"
        }, "Yesterday");
      }
    };
    const fillListItems = listItems => {
      while (listItems.length <= 10) {
        listItems = listItems.concat(listItems);
      }
      return listItems;
    };
    const ThemeList = () => {
      if ('undefined' !== typeof themeObject['list'] && 'undefined' !== typeof themeObject['list']['items']) {
        // Make sure we have at least 10 items.
        let listItems = fillListItems(themeObject['list']['items']);
        let imageItems = 'undefined' !== typeof themeObject['list']['images'] ? fillListItems(themeObject['list']['images']) : [];
        const items = [];
        for (const index in listItems) {
          if (parseInt(index) === parseInt(post_count)) {
            break;
          }
          items.push((0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
            key: 'monsterinsights-popular-posts-preview-list-item-' + index,
            style: {
              backgroundColor: displayValues['background_color'],
              borderColor: displayValues['background_border']
            }
          }, ThemeImage(imageItems, index), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
            className: "monsterinsights-widget-popular-posts-text"
          }, ThemeLabel(), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
            className: "monsterinsights-widget-popular-posts-title",
            style: {
              color: displayValues['title_color'],
              fontSize: displayValues['title_size'] + 'px'
            }
          }, listItems[index]), ThemeMeta())));
        }
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
          className: "monsterinsights-widget-popular-posts-list"
        }, items);
      }
    };
    const WidgetTitle = () => {
      if (widget_title) {
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
          className: "monsterinsights-widget-popular-posts-widget-title"
        }, widget_title_text);
      }
    };
    return [(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(InspectorControls, {
      key: "monsterinsights-popular-posts-widget-inspector-controls"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
      title: __('Theme', "google-analytics-for-wordpress"),
      key: "monsterinsights-popular-posts-widget-theme"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, {
      key: "monsterinsights-popular-posts-widget-theme-row"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_PopularPostsThemePicker__WEBPACK_IMPORTED_MODULE_2__["default"], {
      id: "monsterinsights-popular-posts-widget-theme",
      options: loadedThemes,
      selected: defaultSelected,
      icons: _components_GUTENBERG_APP_THEME_WidgetIcons__WEBPACK_IMPORTED_MODULE_1__["default"],
      onChange: option => {
        setAttributes({
          theme: option
        });
      }
    }))), titleControls(), backgroundControls(), labelControls(), borderControls(), metaStyleControls(), commentsStyleControls(), columnsControls(), metaControls(), behaviorControls()), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      key: "monsterinsights-popular-posts-widget-preview"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: 'monsterinsights-widget-popular-posts-widget monsterinsights-widget-popular-posts-' + defaultSelected + ' monsterinsights-widget-popular-posts-columns-' + columns
    }, WidgetTitle(), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-widget-popular-posts-text"
    }, ThemeList())))];
  }),
  save: props => {
    return null;
  }
}));

/***/ }),

/***/ "./src/hooks/components/EnviraPromo.js":
/*!*********************************************!*\
  !*** ./src/hooks/components/EnviraPromo.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   EnviraPromo: () => (/* binding */ EnviraPromo),
/* harmony export */   EnviraPromoPortal: () => (/* binding */ EnviraPromoPortal)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);






const EnviraPromo = ({
  children
}) => {
  const [state, setState] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)({
    dismissed: monsterinsights_gutenberg_tool_vars.dismiss_envira_promo,
    isResolving: false,
    isError: false,
    isDone: window.sessionStorage.getItem('monsterinsights_envira_installed') === 'true'
  });
  const {
    dismissed,
    isResolving,
    isError,
    isDone
  } = state;

  // This session storage item is only needed for the current session.
  // Once the user leaves the page we need to rely on monsterinsights_gutenberg_tool_vars.dismiss_envira_promo which tests for lite and pro.
  // So let's remove it when the user leaves the page or reloads.
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    const handleOnUnload = () => {
      window.sessionStorage.removeItem("monsterinsights_envira_installed");
    };
    window.addEventListener("beforeunload", handleOnUnload);
    return () => {
      window.removeEventListener("beforeunload", handleOnUnload);
    };
  }, [isDone]);

  // Check if the user can install plugins.
  const {
    canUserInstallPlugins
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useSelect)(select => {
    return {
      canUserInstallPlugins: select('core').canUser('create', 'plugins')
    };
  });
  if (dismissed || !canUserInstallPlugins) {
    return null;
  }

  // @TODO - Should we create a confirm dialog for this?
  const onDismiss = () => {
    setState({
      ...state,
      isResolving: true
    });
    wp.ajax.post('monsterinsights_ajax_dismiss_editor_notice', {
      notice: 'envira_promo',
      nonce: monsterinsights_admin_common.dismiss_notice_nonce
    }).done(() => {
      setState({
        ...state,
        dismissed: true,
        isResolving: false
      });
    }).fail(error => {
      setState({
        ...state,
        isError: true,
        isResolving: false
      });
      console.log('error: ', error);
    });
  };
  const handleEnviraInstall = () => {
    setState({
      ...state,
      isResolving: true
    });
    const promise = _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
      path: `/wp/v2/plugins/`,
      method: 'POST',
      data: {
        slug: 'envira-gallery-lite',
        status: 'active'
      }
    });
    promise.then(() => {
      setState({
        ...state,
        dismissed: false,
        isResolving: false,
        isDone: true
      });
      // persistently store this decision along all the Gallery blocks so they don't show this notice again.
      window.sessionStorage.setItem('monsterinsights_envira_installed', true);
    });
    promise.catch(error => {
      // if the folder already exists, we can consider it as a success.
      if (error.code === 'folder_exists') {
        setState({
          ...state,
          dismissed: false,
          isResolving: false,
          isDone: true
        });
        return;
      }
      setState({
        ...state,
        isError: true,
        isResolving: false
      });
    });
  };

  // Note: <Tooltip> cannot hold a custom component as `child`, so we need to wrap it in a <span>. https://github.com/WordPress/gutenberg/issues/43129
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-editor-notice monsterinsights-editor-notice-envira-promo"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Card, {
    isBorderless: true
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.CardDivider, {
    style: {
      margin: "0"
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.CardHeader, {
    isBorderless: true,
    size: "small",
    style: {
      pading: '16px 16px 0'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.__experimentalHeading, {
    level: 2,
    style: {
      marginBottom: '0'
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Envira Gallery Plugin', "google-analytics-for-wordpress")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Button, {
    className: "monsterinsights-editor-notice-dismiss",
    onClick: onDismiss
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Dashicon, {
    icon: "no-alt"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "screen-reader-text"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Dismiss "Envira Promo" panel!', "google-analytics-for-wordpress")))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.CardBody, {
    isBorderless: true,
    size: "small",
    style: {
      padding: '0 16px'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-editor-notice-content"
  }, !isDone && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Adds lightboxes, drag and drop galleries and more.', "google-analytics-for-wordpress"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Tooltip, {
    delay: "200",
    placement: "top",
    text: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Recommended by MonsterInsights.', "google-analytics-for-wordpress")
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Dashicon, {
    icon: "info-outline",
    size: "14",
    style: {
      marginLeft: '2px',
      padding: '2px'
    }
  })))), isDone && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      color: 'green'
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Success! Save your work and then reload this page to start using Envira Gallery.", "google-analytics-for-wordpress")), isError && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      color: 'red'
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('An error occurred!', "google-analytics-for-wordpress")))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.CardFooter, {
    isBorderless: true,
    size: "small"
  }, !isDone && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Button, {
    className: "monsterinsights-editor-notice-action",
    variant: "secondary",
    onClick: handleEnviraInstall,
    disabled: isResolving || isError,
    isBusy: isResolving
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Install Now', "google-analytics-for-wordpress")), isDone && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Button, {
    className: "monsterinsights-editor-notice-action",
    variant: "secondary",
    icon: "image-rotate",
    onClick: () => window.location.reload()
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Reload', "google-analytics-for-wordpress")))));
};
class EnviraPromoPortal extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {
  el = document.createElement("div");
  componentDidMount() {
    setTimeout(() => {
      this.targetEl = window.document.querySelector('.block-editor-block-inspector');
      this.targetEl.appendChild(this.el);
    }, 100);
  }
  render() {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createPortal)((0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(EnviraPromo, null), this.el);
  }
}


/***/ }),

/***/ "./src/hooks/components/index.js":
/*!***************************************!*\
  !*** ./src/hooks/components/index.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   EnviraPromo: () => (/* reexport safe */ _EnviraPromo__WEBPACK_IMPORTED_MODULE_0__.EnviraPromo),
/* harmony export */   EnviraPromoPortal: () => (/* reexport safe */ _EnviraPromo__WEBPACK_IMPORTED_MODULE_0__.EnviraPromoPortal)
/* harmony export */ });
/* harmony import */ var _EnviraPromo__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EnviraPromo */ "./src/hooks/components/EnviraPromo.js");


/***/ }),

/***/ "./src/hooks/gallery.js":
/*!******************************!*\
  !*** ./src/hooks/gallery.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components___WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/ */ "./src/hooks/components/index.js");




const withInspectorControls = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__.createHigherOrderComponent)(BlockEdit => {
  const imageBlocks = ['core/gallery', 'core/image', 'core/cover'];
  return props => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(BlockEdit, {
      ...props
    }), props.isSelected && imageBlocks.includes(props.name) && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components___WEBPACK_IMPORTED_MODULE_3__.EnviraPromoPortal, null));
  };
}, 'withInspectorControl');

// Avoid adding the filter if it is not needed; performance reasons.
if (!monsterinsights_gutenberg_tool_vars.dismiss_envira_promo) {
  (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__.addFilter)('editor.BlockEdit', 'monsterinsights/editor-promo', withInspectorControls);
}

/***/ }),

/***/ "./src/hooks/index.js":
/*!****************************!*\
  !*** ./src/hooks/index.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _gallery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./gallery */ "./src/hooks/gallery.js");


/***/ }),

/***/ "./src/plugins/index.js":
/*!******************************!*\
  !*** ./src/plugins/index.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _GUTENBERG_APP_THEME_Headline_Analyzer__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./GUTENBERG_APP_THEME-Headline-Analyzer */ "./src/plugins/monsterinsights-Headline-Analyzer/index.js");
/* harmony import */ var _metabox__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./metabox */ "./src/plugins/metabox/index.js");
/**
 * Import Sidebar Plugins
 */




/***/ }),

/***/ "./src/plugins/metabox/components/page-insights-Lite.js":
/*!**************************************************************!*\
  !*** ./src/plugins/metabox/components/page-insights-Lite.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _page_insights_show_btn__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./page-insights-show-btn */ "./src/plugins/metabox/components/page-insights-show-btn.js");
/* harmony import */ var _page_insights_hide_btn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./page-insights-hide-btn */ "./src/plugins/metabox/components/page-insights-hide-btn.js");
/* harmony import */ var _page_insights_tab__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./page-insights-tab */ "./src/plugins/metabox/components/page-insights-tab.js");
/* harmony import */ var _page_insights_tab_result_item__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./page-insights-tab-result-item */ "./src/plugins/metabox/components/page-insights-tab-result-item.js");

const {
  __
} = wp.i18n;





class PageInsights extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {
  constructor() {
    super();
    this.state = {
      showBtnClicked: false,
      tabs: {
        last30days: true,
        yesterday: false
      }
    };
    this.texts = {
      last30days: __("Last 30 days", "google-analytics-for-wordpress"),
      yesterday: __("Yesterday", "google-analytics-for-wordpress"),
      bouncerate: __("Bounce Rate", "google-analytics-for-wordpress"),
      timeonpage: __("Time On Page", "google-analytics-for-wordpress"),
      loadingtime: __("Load Time", "google-analytics-for-wordpress"),
      entrances: __("Entrances", "google-analytics-for-wordpress"),
      pageviews: __("Page Views", "google-analytics-for-wordpress"),
      exits: __("Exits", "google-analytics-for-wordpress")
    };
  }
  showPageInsightsClick = () => {
    this.setState({
      showBtnClicked: true
    });
  };
  hidePageInsightsClick = () => {
    this.setState({
      showBtnClicked: false
    });
  };
  tabclick = current_tab => {
    let tabs = this.state.tabs;
    for (const [key, value] of Object.entries(tabs)) {
      tabs[key] = key === current_tab;
    }
    this.setState({
      tabs
    });
  };
  render() {
    if (this.props.hidden) {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null);
    }
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-metabox lite",
      id: "monsterinsights-metabox-page-insights"
    }, !this.state.showBtnClicked && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_page_insights_show_btn__WEBPACK_IMPORTED_MODULE_2__["default"], {
      onClick: this.showPageInsightsClick
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      id: "monsterinsights-page-insights-content",
      className: this.state.showBtnClicked ? 'active' : ''
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-page-insights__tabs"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_page_insights_tab__WEBPACK_IMPORTED_MODULE_4__["default"], {
      text: this.texts.last30days,
      tab: "tab-last-30-days-content",
      active: this.state.tabs.last30days,
      onClick: this.tabclick,
      interval: "last30days"
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_page_insights_tab__WEBPACK_IMPORTED_MODULE_4__["default"], {
      text: this.texts.yesterday,
      tab: "tab-yesterday-content",
      active: this.state.tabs.yesterday,
      onClick: this.tabclick,
      interval: "yesterday"
    })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-page-insights-tabs-content"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-page-insights-tabs-content__tab active",
      id: "tab-last-30-days-content"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-page-insights-tabs-content__tab-items"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_page_insights_tab_result_item__WEBPACK_IMPORTED_MODULE_5__["default"], {
      value: "1m 43s",
      label: this.texts.timeonpage
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_page_insights_tab_result_item__WEBPACK_IMPORTED_MODULE_5__["default"], {
      value: "19056",
      label: this.texts.entrances
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_page_insights_tab_result_item__WEBPACK_IMPORTED_MODULE_5__["default"], {
      value: "26558",
      label: this.texts.pageviews
    }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_page_insights_tab_result_item__WEBPACK_IMPORTED_MODULE_5__["default"], {
      value: "13428",
      label: this.texts.exits
    }))))), this.state.showBtnClicked && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_page_insights_hide_btn__WEBPACK_IMPORTED_MODULE_3__["default"], {
      onClick: this.hidePageInsightsClick
    }));
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PageInsights);

/***/ }),

/***/ "./src/plugins/metabox/components/page-insights-hide-btn.js":
/*!******************************************************************!*\
  !*** ./src/plugins/metabox/components/page-insights-hide-btn.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);

const {
  __
} = wp.i18n;

class PageInsightsHideBtn extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {
  constructor() {
    super();
    this.state = {};
    this.btn_text = __("Hide Page Insights", "google-analytics-for-wordpress");
  }
  render() {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      className: "button",
      href: "#",
      id: "monsterinsights_hide_page_insights",
      onClick: this.props.onClick
    }, this.btn_text);
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PageInsightsHideBtn);

/***/ }),

/***/ "./src/plugins/metabox/components/page-insights-show-btn.js":
/*!******************************************************************!*\
  !*** ./src/plugins/metabox/components/page-insights-show-btn.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);

const {
  __
} = wp.i18n;

class PageInsightsShowBtn extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {
  constructor() {
    super();
    this.state = {};
    this.btn_show_text = __("Show Page Insights", "google-analytics-for-wordpress");
  }
  render() {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      className: "button",
      href: "#",
      id: "monsterinsights_show_page_insights",
      onClick: this.props.onClick
    }, this.btn_show_text);
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PageInsightsShowBtn);

/***/ }),

/***/ "./src/plugins/metabox/components/page-insights-tab-result-item.js":
/*!*************************************************************************!*\
  !*** ./src/plugins/metabox/components/page-insights-tab-result-item.js ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);

const {
  __
} = wp.i18n;

class PageInsightsTabResultItem extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-page-insights-tabs-content__tab-item"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-page-insights-tabs-content__tab-item__result"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, this.props.value)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "monsterinsights-page-insights-tabs-content__tab-item__title"
    }, this.props.label));
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PageInsightsTabResultItem);

/***/ }),

/***/ "./src/plugins/metabox/components/page-insights-tab.js":
/*!*************************************************************!*\
  !*** ./src/plugins/metabox/components/page-insights-tab.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);

const {
  __
} = wp.i18n;

class PageInsightsTab extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {
  constructor(props) {
    super(props);
  }
  handleClick = () => {
    this.props.onClick(this.props.interval);
  };
  render() {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
      href: "#",
      className: 'monsterinsights-page-insights__tabs-tab ' + (this.props.active ? 'active' : ''),
      "data-tab": "this.props.tab",
      onClick: this.handleClick
    }, this.props.text);
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PageInsightsTab);

/***/ }),

/***/ "./src/plugins/metabox/components/pro-badge.js":
/*!*****************************************************!*\
  !*** ./src/plugins/metabox/components/pro-badge.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const ProBadge = props => {
  if ('lite' !== props.license) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null);
  }
  let texts = {
    description: __("This is a PRO feature.", "google-analytics-for-wordpress"),
    upgrade: __("Upgrade", "google-analytics-for-wordpress")
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-metabox-pro-badge"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    width: "15",
    height: "14",
    viewBox: "0 0 15 14",
    fill: "none",
    xmlns: "http://www.w3.org/2000/svg"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M6.57617 1.08203L4.92578 4.45898L1.19336 4.99219C0.533203 5.09375 0.279297 5.90625 0.761719 6.38867L3.42773 9.00391L2.79297 12.6855C2.69141 13.3457 3.40234 13.8535 3.98633 13.5488L7.3125 11.7969L10.6133 13.5488C11.1973 13.8535 11.9082 13.3457 11.8066 12.6855L11.1719 9.00391L13.8379 6.38867C14.3203 5.90625 14.0664 5.09375 13.4062 4.99219L9.69922 4.45898L8.02344 1.08203C7.74414 0.498047 6.88086 0.472656 6.57617 1.08203Z",
    fill: "#31862D"
  })), texts.description), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-metabox-pro-badge-upgrade"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    href: props.upgrade_url,
    target: "_blank",
    rel: "noopener"
  }, texts.upgrade))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ProBadge);

/***/ }),

/***/ "./src/plugins/metabox/components/site-notes.js":
/*!******************************************************!*\
  !*** ./src/plugins/metabox/components/site-notes.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);


const {
  __
} = wp.i18n;

const {
  ToggleControl,
  TextareaControl,
  SelectControl
} = wp.components;
const {
  dispatch,
  select,
  subscribe
} = wp.data;
const {
  isSavingPost
} = select('core/editor');
var checked = true; // Var to check if the post was saved. Start in a checked state.

class SiteNotes extends _wordpress_element__WEBPACK_IMPORTED_MODULE_2__.Component {
  constructor() {
    super();
    const metas = select('core/editor').getEditedPostAttribute('meta');
    this.state = {
      addSiteNote: !!metas['_monsterinsights_sitenote_active'],
      siteNoteText: metas['_monsterinsights_sitenote_note'] ? metas['_monsterinsights_sitenote_note'] : '',
      customSiteNote: false,
      category: metas['_monsterinsights_sitenote_category'] ? metas['_monsterinsights_sitenote_category'] : 0
    };
    this.texts = {
      checkbox: {
        help: __("Add a Site Note when publishing this post", "google-analytics-for-wordpress"),
        label: __("Add a Site Note", "google-analytics-for-wordpress")
      },
      category: {
        label: __("Category", "google-analytics-for-wordpress")
      },
      published_template: __('Published: %s', "google-analytics-for-wordpress")
    };
  }
  componentDidMount() {
    this.categories = window.monsterinsights_gutenberg_tool_vars ? window.monsterinsights_gutenberg_tool_vars['site_notes_categories'] : [];
    if (this.categories.length > 0 && this.state.category === 0) {
      this.setState({
        category: this.categories[0]['value']
      }, this.saveCategoryField);
    }

    // Listener to trigger a function after the post is saved.
    subscribe(() => {
      if (isSavingPost()) {
        checked = false;
      } else {
        if (!checked) {
          checked = true;
          this.setState({
            addSiteNote: false,
            siteNoteText: '',
            customSiteNote: false,
            ['_monsterinsights_sitenote_note']: '',
            ['_monsterinsights_sitenote_category']: 0
          });
          dispatch('core/editor').editPost({
            meta: {
              _monsterinsights_sitenote_active: false,
              _monsterinsights_sitenote_note: null,
              _monsterinsights_sitenote_id: null,
              _monsterinsights_sitenote_category: null
            }
          });
        }
      }
    });
  }
  refreshNoteText(current_title = '') {
    if (!current_title) {
      current_title = select("core/editor").getEditedPostAttribute('title');
    }
    this.setState({
      siteNoteText: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.sprintf)(this.texts.published_template, current_title)
    }, this.saveNoteField);
  }
  saveActiveField(value = null) {
    if (null === value) {
      value = this.state.addSiteNote;
    }
    dispatch('core/editor').editPost({
      meta: {
        _monsterinsights_sitenote_active: !!value
      }
    });
  }
  saveNoteField(value = null) {
    if (null === value) {
      value = this.state.siteNoteText;
    }
    dispatch('core/editor').editPost({
      meta: {
        _monsterinsights_sitenote_note: value
      }
    });
  }
  saveCategoryField(value = null) {
    if (null === value) {
      value = this.state.category;
    }
    dispatch('core/editor').editPost({
      meta: {
        _monsterinsights_sitenote_category: value
      }
    });
  }
  render() {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ToggleControl, {
      checked: this.state.addSiteNote,
      help: this.texts.checkbox.help,
      label: this.texts.checkbox.label,
      onChange: value => {
        this.setState({
          addSiteNote: !!value
        }, this.saveActiveField);
        this.refreshNoteText();
      }
    }), this.state.addSiteNote && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(TextareaControl, {
      help: "",
      label: "",
      value: this.state.siteNoteText,
      onChange: text => {
        let templateRegex = new RegExp('^' + this.texts.published_template.replace('%s', '.*').replaceAll('"', '\\"') + '$', 'g');
        let siteNoteNotChanged = templateRegex.test(text);
        this.setState({
          siteNoteText: text,
          customSiteNote: !siteNoteNotChanged
        }, this.saveNoteField);
      }
    }), this.state.addSiteNote && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(SelectControl, {
      label: this.texts.category.label,
      options: this.categories,
      value: this.state.category,
      className: 'site-notes-select',
      onChange: category_id => {
        this.setState({
          category: category_id
        }, this.saveCategoryField);
      },
      __nextHasNoMarginBottom: true
    }));
  }
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SiteNotes);

/***/ }),

/***/ "./src/plugins/metabox/index.js":
/*!**************************************!*\
  !*** ./src/plugins/metabox/index.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_pro_badge__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/pro-badge */ "./src/plugins/metabox/components/pro-badge.js");
/* harmony import */ var _components_page_insights_GUTENBERG_APP_VERSION__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/page-insights-GUTENBERG_APP_VERSION */ "./src/plugins/metabox/components/page-insights-Lite.js");
/* harmony import */ var _components_site_notes__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/site-notes */ "./src/plugins/metabox/components/site-notes.js");

const {
  ToggleControl
} = wp.components;
const {
  registerPlugin
} = wp.plugins;
const {
  dispatch,
  select
} = wp.data;
const {
  __
} = wp.i18n;
const {
  PluginDocumentSettingPanel
} = wp.editPost;
const {
  useState,
  Fragment
} = wp.element;



const MonsterInsightsMetabox = () => {
  const MonsterInsightsVars = window.monsterinsights_gutenberg_tool_vars;
  if (!MonsterInsightsVars || "1" !== MonsterInsightsVars['supports_custom_fields']) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null);
  }
  if ("1" !== MonsterInsightsVars['public_post_type']) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PluginDocumentSettingPanel, {
      name: "monsterinsights-metabox",
      title: "MonsterInsights",
      className: "monsterinsights-metabox-wrapper",
      icon: "mi"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_site_notes__WEBPACK_IMPORTED_MODULE_3__["default"], null));
  }
  const metas = select("core/editor").getEditedPostAttribute('meta') || [];
  if (!metas['_monsterinsights_skip_tracking']) {
    metas['_monsterinsights_skip_tracking'] = false;
  }
  const [hasSkipTracking, setHasSkipTracking] = useState(!!metas['_monsterinsights_skip_tracking']);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PluginDocumentSettingPanel, {
    name: "monsterinsights-metabox",
    title: "MonsterInsights",
    className: "monsterinsights-metabox-wrapper",
    icon: "mi"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(ToggleControl, {
    checked: hasSkipTracking,
    help: __("Toggle to prevent Google Analytics from tracking this page.", "google-analytics-for-wordpress"),
    label: __('Exclude page from Google Analytics Tracking', "google-analytics-for-wordpress"),
    disabled: 'lite' === MonsterInsightsVars.license_type,
    onChange: value => {
      setHasSkipTracking(!!value);
      dispatch('core/editor').editPost({
        meta: {
          '_monsterinsights_skip_tracking': value
        }
      });
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_page_insights_GUTENBERG_APP_VERSION__WEBPACK_IMPORTED_MODULE_2__["default"], {
    addonInstalled: MonsterInsightsVars['page_insights_addon_active'],
    hidden: hasSkipTracking
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_pro_badge__WEBPACK_IMPORTED_MODULE_1__["default"], {
    license: MonsterInsightsVars.license_type,
    upgrade_url: MonsterInsightsVars.upgrade_url
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_site_notes__WEBPACK_IMPORTED_MODULE_3__["default"], null));
};
registerPlugin("monsterinsights-metabox", {
  render: MonsterInsightsMetabox
});

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelCharacterCount.js":
/*!*************************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelCharacterCount.js ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../icons */ "./src/plugins/monsterinsights-Headline-Analyzer/icons.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _HeadlinePieChart__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./HeadlinePieChart */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePieChart.js");

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;



const HeadlinePanelCharacterCount = props => {
  const textPanelTitle = __("Character Count", "google-analytics-for-wordpress");
  const characterLength = props.data.result.length;
  const barScore = characterLength >= 66 ? 100 : Math.round(characterLength * 1.51);
  let classOnLength = '';
  let statusOnLength = '';
  let barColor = '#1EC185';
  let descOnCharLength = '';
  if (characterLength <= 19) {
    classOnLength = 'red';
    barColor = '#EB5757';
  } else if (characterLength >= 20 && characterLength <= 34) {
    classOnLength = 'orange';
    barColor = '#F2994A';
  } else if (characterLength >= 35 && characterLength <= 66) {
    classOnLength = 'green';
    barColor = '#1EC185';
  } else if (characterLength >= 67 && characterLength <= 79) {
    classOnLength = 'orange';
    barColor = '#F2994A';
  } else if (characterLength >= 80) {
    classOnLength = 'red';
    barColor = '#EB5757';
  }
  if (characterLength <= 34) {
    statusOnLength = __("Too Short", "google-analytics-for-wordpress");
    descOnCharLength = __("You have space to add more keywords and power words to boost your rankings and click-through rate.", "google-analytics-for-wordpress");
  } else if (characterLength >= 35 && characterLength <= 66) {
    statusOnLength = __("Good", "google-analytics-for-wordpress");
    descOnCharLength = __("Headlines that are about 55 characters long will display fully in search results and tend to get more clicks.", "google-analytics-for-wordpress");
  } else if (characterLength >= 67) {
    statusOnLength = __("Too Long", "google-analytics-for-wordpress");
    descOnCharLength = __("At this length, it will get cut off in search results. Try reducing it to about 55 characters.", "google-analytics-for-wordpress");
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: textPanelTitle,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("monsterinsights-headline-analyzer-panel-character-count", "monsterinsights-headline-analyzer-panel-has-icon", classOnLength),
    icon: 'green' === classOnLength ? _icons__WEBPACK_IMPORTED_MODULE_1__["default"].check : _icons__WEBPACK_IMPORTED_MODULE_1__["default"].warning
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-pie-chart-container"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("monsterinsights-headline-analyzer-character-length", classOnLength)
  }, characterLength), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlinePieChart__WEBPACK_IMPORTED_MODULE_3__["default"], {
    barScore: barScore,
    barColor: barColor
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "monsterinsights-headline-analyzer-status-on-character-length"
  }, statusOnLength)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, descOnCharLength))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlinePanelCharacterCount);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelNewScore.js":
/*!*******************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelNewScore.js ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _HeadlinePieChart__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./HeadlinePieChart */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePieChart.js");

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;


const HeadlineNewScorePanel = props => {
  const postTitle = props.analyzer.currentHeadlineData.sentence;
  const textPanelTitle = __("New Score", "google-analytics-for-wordpress");
  const textGuideline = __("A good score is between 40 and 60. For best results, you should strive for 70 and above.", "google-analytics-for-wordpress");
  const textCurrentScore = __("Current Score", "google-analytics-for-wordpress");
  const newTitle = 'undefined' !== typeof props.analyzer.newHeadlineData ? props.analyzer.newHeadlineData.sentence : '';
  const newScore = 'undefined' !== typeof props.analyzer.newHeadlineData ? props.analyzer.newHeadlineData.score : '';
  const currentScore = 'undefined' !== typeof props.analyzer.currentHeadlineData.score ? props.analyzer.currentHeadlineData.score : '';
  const classOnNewScore = newScore < 40 ? 'red' : newScore <= 60 ? 'orange' : 'green';
  const barColor = 'red' === classOnNewScore ? '#EB5757' : 'orange' === classOnNewScore ? '#F2994A' : '#1EC185';
  const classOnCurrentScore = currentScore < 40 ? 'red' : currentScore <= 60 ? 'orange' : 'green';
  const classOnCurrentScoreBg = currentScore < 40 ? 'red-bg' : currentScore <= 60 ? 'orange-bg' : 'green-bg';
  const scoreDifference = Math.abs(newScore - currentScore);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: textPanelTitle
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-new-score-panel"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, textGuideline), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h4", null, "\u201C", newTitle, "\u201D"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-pie-chart-container"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("monsterinsights-headline-analyzer-new-score", classOnNewScore)
  }, newScore), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlinePieChart__WEBPACK_IMPORTED_MODULE_2__["default"], {
    barScore: newScore,
    barColor: barColor
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "current-score"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("monsterinsights-headline-analyzer-score-difference", classOnNewScore)
  }, newScore > currentScore ? '+ ' : newScore === currentScore ? '' : '- ', scoreDifference), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, textCurrentScore), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("monsterinsights-headline-analyzer-score", classOnCurrentScoreBg)
  }, currentScore), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, "\u201C", postTitle, "\u201D"))))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlineNewScorePanel);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelPreviousScores.js":
/*!*************************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelPreviousScores.js ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;

const HeadlinePanelPreviousScores = props => {
  const textPanelTitle = __("Previous Scores", "google-analytics-for-wordpress");
  const previousScores = 'undefined' !== props.analyzer.previousHeadlinesData ? props.analyzer.previousHeadlinesData : [];
  const sidebar = document.querySelector('.edit-post-sidebar');
  const activeTab = 'undefined' !== props.analyzer.activeTab ? props.analyzer.activeTab : 'current-score';
  const scrollPosition = 'current-score' === activeTab ? 390 : 300;
  const setNewHeadlineData = index => {
    props.setAnalyzer({
      newHeadlineData: previousScores[index],
      headlineData: previousScores[index],
      newHeadline: previousScores[index].sentence,
      isNewData: true
    });
    if (sidebar) {
      sidebar.scrollTop = scrollPosition;
    }
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: textPanelTitle
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
    className: "monsterinsights-headline-analyzer-previous-scores"
  }, previousScores.map((headlineData, index) => {
    if (index < 10 && ("undefined" !== typeof headlineData.sentence || "undefined" !== typeof headlineData.score)) {
      let classOnScore = headlineData.score < 40 ? 'red-bg' : headlineData.score <= 60 ? 'orange-bg' : 'green-bg';
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
        key: index,
        onClick: () => setNewHeadlineData(index)
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("monsterinsights-headline-analyzer-score", classOnScore)
      }, headlineData.score), headlineData.sentence);
    } else {
      return;
    }
  })))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlinePanelPreviousScores);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelSearchPreview.js":
/*!************************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelSearchPreview.js ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;
const {
  select
} = wp.data;
const HeadlinePanelSearchPreview = props => {
  const panelTitle = __("Search Preview", "google-analytics-for-wordpress");
  const descText = __('Here is how your headline will look like in google search results page.', "google-analytics-for-wordpress");
  const postUrl = select("core/editor").getPermalink();
  const postUrlAttribute = {
    'href': postUrl
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: panelTitle,
    className: "monsterinsights-headline-analyzer-panel-search-preview"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h4", null, props.data.sentence), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "monsterinsights-headline-analyzer-post-url"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    ...postUrlAttribute,
    target: "_blank"
  }, postUrl)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, descText))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlinePanelSearchPreview);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelSentiment.js":
/*!********************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelSentiment.js ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../icons */ "./src/plugins/monsterinsights-Headline-Analyzer/icons.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;


const HeadlinePanelSentiment = props => {
  const textNeutralSentiment = __("Your headline has a neutral sentiment.", "google-analytics-for-wordpress");
  const textNeutralSentimentGuideline = __("Headlines that are strongly positive or negative tend to get more engagement then neutral ones.", "google-analytics-for-wordpress");
  const textPositiveSentiment = __("Your headline has a positive sentiment.", "google-analytics-for-wordpress");
  const textPositiveSentimentGuideline = __("Positive headlines tend to get better engagement than neutral or negative ones.", "google-analytics-for-wordpress");
  const textNegativeSentiment = __("Your headline has a negative sentiment.", "google-analytics-for-wordpress");
  const textNegativeSentimentGuideline = __("Negative headlines are attention-grabbing and tend to perform better than neutral ones.", "google-analytics-for-wordpress");
  const textPanelTitle = __("Sentiment", "google-analytics-for-wordpress");
  const sentiment = 'neu' === props.data.result.sentiment ? __("Neutral", "google-analytics-for-wordpress") : 'pos' === props.data.result.sentiment ? __("Positive", "google-analytics-for-wordpress") : __("Negative", "google-analytics-for-wordpress");
  const sentimentIcon = 'neu' === props.data.result.sentiment ? _icons__WEBPACK_IMPORTED_MODULE_1__["default"].neutral : 'pos' === props.data.result.sentiment ? _icons__WEBPACK_IMPORTED_MODULE_1__["default"].smile : _icons__WEBPACK_IMPORTED_MODULE_1__["default"].negative;
  const classOnSentiment = 'neu' === props.data.result.sentiment ? 'orange' : 'pos' === props.data.result.sentiment ? 'green' : 'red';
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: textPanelTitle,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("monsterinsights-headline-analyzer-panel-sentiment", "monsterinsights-headline-analyzer-panel-has-icon", classOnSentiment),
    icon: sentimentIcon
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h4", null, sentiment), 'neu' === props.data.result.sentiment ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, textNeutralSentiment, " ", (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), " ", textNeutralSentimentGuideline) : '', 'pos' === props.data.result.sentiment ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, textPositiveSentiment, " ", (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), " ", textPositiveSentimentGuideline) : '', 'neg' === props.data.result.sentiment ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, textNegativeSentiment, " ", (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("br", null), " ", textNegativeSentimentGuideline) : '')));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlinePanelSentiment);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelStartEndWords.js":
/*!************************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelStartEndWords.js ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;
const HeadlinePanelStartEndWords = props => {
  const textPanelTitle = __("Beginning & Ending Words", "google-analytics-for-wordpress");
  const words = props.data.result.input_array_orig;
  const guideLineText = __('Most readers only look at the first and last 3 words of a headline before deciding whether to click.', "google-analytics-for-wordpress");
  let beginningWords = '';
  let endingWords = '';
  if (words.length >= 6) {
    beginningWords = words.slice(0, 3).join(' ');
    endingWords = words.slice(-3).join(' ');
  } else if (words.length > 3 && words.length <= 5) {
    beginningWords = words.slice(0, 3).join(' ');
    endingWords = words.slice(3).join(' ');
  } else {
    beginningWords = words.slice(0, 3).join(' ');
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: textPanelTitle,
    className: "monsterinsights-headline-analyzer-panel-beginning-ending-words"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, beginningWords ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-words beginning"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, beginningWords)) : '', endingWords ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-words ending"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, endingWords)) : '', (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "monsterinsights-headline-analyzer-words-guideline"
  }, guideLineText))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlinePanelStartEndWords);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelType.js":
/*!***************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelType.js ***!
  \***************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

const {
  __,
  sprintf
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;
const HeadlinePanelType = props => {
  const headlineTypes = props.data.result.headline_types.join(', ');
  const panelTitle = __("Headline Type", "google-analytics-for-wordpress");
  const typePanelTitle = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "monsterinsights-headline-analyzer-panel-types-title"
  }, panelTitle, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, headlineTypes));
  const typeLinkText = sprintf(__('Headlines that are lists and how-to get more engagement on average than other types of headlines. %1sRead more about %2sdifferent types of headlines here.%3s', "google-analytics-for-wordpress"), '<br/><br/>', '<a href="https://optinmonster.com/why-these-21-headlines-went-viral-and-how-you-can-copy-their-success/" target="_blank" className="monsterinsights-headline-analyzer-link">', '</a>');
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: typePanelTitle,
    className: "monsterinsights-headline-analyzer-panel-types"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h4", null, headlineTypes), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    dangerouslySetInnerHTML: {
      __html: typeLinkText
    }
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlinePanelType);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelWordBalance.js":
/*!**********************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelWordBalance.js ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../icons */ "./src/plugins/monsterinsights-Headline-Analyzer/icons.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _HeadlineWordsBlock__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./HeadlineWordsBlock */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineWordsBlock.js");

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;



const HeadlinePanelWordBalance = props => {
  const textPanelTitle = __("Word Balance", "google-analytics-for-wordpress");
  const textGuideline = __("Compare the percentages of your results to the goal for each category and adjust as necessary.", "google-analytics-for-wordpress");
  const classOnScore = props.data.score < 40 ? 'red' : props.data.score <= 60 ? 'orange' : 'green';
  const classOnScoreBg = props.data.score < 40 ? 'red-bg' : props.data.score <= 60 ? 'orange-bg' : 'green-bg';
  const classOnCommonWords = 0 === props.data.result.common_words_per ? 'red' : props.data.result.common_words_per < 0.2 ? 'orange' : 'green';
  const classOnCommonWordsBg = 0 === props.data.result.common_words_per ? 'red-bg' : props.data.result.common_words_per < 0.2 ? 'orange-bg' : 'green-bg';
  const textGetMoreClicks = __("Your headline would be more likely to get clicks if it had more uncommon words.", "google-analytics-for-wordpress");
  const guideLineOnCommonWords = props.data.result.common_words_per < 0.2 ? textGetMoreClicks : __("Headlines with 20-30% common words are more likely to get clicks.", "google-analytics-for-wordpress");
  const classOnUnCommonWords = 0 === props.data.result.uncommon_words_per ? 'red' : props.data.result.uncommon_words_per < 0.1 ? 'orange' : 'green';
  const classOnUnCommonWordsBg = 0 === props.data.result.uncommon_words_per ? 'red-bg' : props.data.result.uncommon_words_per < 0.1 ? 'orange-bg' : 'green-bg';
  const guideLineOnUnCommonWords = props.data.result.uncommon_words_per < 0.1 ? textGetMoreClicks : __("Headlines with uncommon words are more likely to get clicks.", "google-analytics-for-wordpress");
  const classOnEmotionalWords = 0 === props.data.result.emotion_words_per ? 'red' : props.data.result.emotion_words_per < 0.1 ? 'orange' : 'green';
  const classOnEmotionalWordsBg = 0 === props.data.result.emotion_words_per ? 'red-bg' : props.data.result.emotion_words_per < 0.1 ? 'orange-bg' : 'green-bg';
  const guideLineOnEmotionalWords = __("Emotionally triggered headlines are likely to drive more clicks.", "google-analytics-for-wordpress");
  const classOnPowerWords = 0 === props.data.result.power_words.length ? 'orange' : 'green';
  const classOnPowerWordsBg = 0 === props.data.result.power_words.length ? 'orange' : 'green-bg';
  const guideLineOnPowerWords = __("Headlines with Power Words are more likely to get clicks.", "google-analytics-for-wordpress");
  const textCommonWords = __("Common Words", "google-analytics-for-wordpress");
  const textTwentyThirty = __("20-30%", "google-analytics-for-wordpress");
  const textUncommonWords = __("Uncommon Words", "google-analytics-for-wordpress");
  const textTenTwenty = __("10-20%", "google-analytics-for-wordpress");
  const textEmotionalWords = __("Emotional Words", "google-analytics-for-wordpress");
  const textTenFifteen = __("10-15%", "google-analytics-for-wordpress");
  const textPowerWords = __("Power Words", "google-analytics-for-wordpress");
  const textLeastOne = __("At least one", "google-analytics-for-wordpress");
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: textPanelTitle,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("monsterinsights-headline-analyzer-panel-word-balance", "monsterinsights-headline-analyzer-panel-has-icon", classOnScore),
    icon: 'green' === classOnScore ? _icons__WEBPACK_IMPORTED_MODULE_1__["default"].check : _icons__WEBPACK_IMPORTED_MODULE_1__["default"].warning
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h4", null, props.data.result.word_balance), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, textGuideline), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlineWordsBlock__WEBPACK_IMPORTED_MODULE_3__["default"], {
    title: textCommonWords,
    value: Math.round(props.data.result.common_words_per * 100),
    goalValue: textTwentyThirty,
    words: props.data.result.common_words,
    guideLine: guideLineOnCommonWords,
    classOnScore: classOnCommonWords,
    classOnScoreBg: classOnCommonWordsBg
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlineWordsBlock__WEBPACK_IMPORTED_MODULE_3__["default"], {
    title: textUncommonWords,
    value: Math.round(props.data.result.uncommon_words_per * 100),
    goalValue: textTenTwenty,
    words: props.data.result.uncommon_words,
    guideLine: guideLineOnUnCommonWords,
    classOnScore: classOnUnCommonWords,
    classOnScoreBg: classOnUnCommonWordsBg
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlineWordsBlock__WEBPACK_IMPORTED_MODULE_3__["default"], {
    title: textEmotionalWords,
    value: Math.round(props.data.result.emotion_words_per * 100),
    goalValue: textTenFifteen,
    words: props.data.result.emotion_words,
    guideLine: guideLineOnEmotionalWords,
    classOnScore: classOnEmotionalWords,
    classOnScoreBg: classOnEmotionalWordsBg
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlineWordsBlock__WEBPACK_IMPORTED_MODULE_3__["default"], {
    title: textPowerWords,
    value: Math.round(props.data.result.power_words_per * 100),
    goalValue: textLeastOne,
    words: props.data.result.power_words,
    guideLine: guideLineOnPowerWords,
    classOnScore: classOnPowerWords,
    classOnScoreBg: classOnPowerWordsBg
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlinePanelWordBalance);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelWordCount.js":
/*!********************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelWordCount.js ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../icons */ "./src/plugins/monsterinsights-Headline-Analyzer/icons.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _HeadlinePieChart__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./HeadlinePieChart */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePieChart.js");

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;



const HeadlinePanelWordCount = props => {
  const textWordCount = __("Word Count", "google-analytics-for-wordpress");
  const wordLength = props.data.result.word_count;
  const barScore = wordLength >= 10 ? 100 : Math.round(wordLength * 10);
  let classOnLength = '';
  let statusOnLength = '';
  let barColor = '#1EC185';
  let descOnWordLength = '';
  if (wordLength <= 4) {
    classOnLength = 'red';
    barColor = '#EB5757';
    statusOnLength = __("Not Enough Words", "google-analytics-for-wordpress");
    descOnWordLength = __("Your headline doesn’t use enough words. You have more space to add keywords and power words to improve your SEO and get more engagement.", "google-analytics-for-wordpress");
  } else if (wordLength >= 5 && wordLength <= 9) {
    classOnLength = 'green';
    barColor = '#1EC185';
    statusOnLength = __("Good", "google-analytics-for-wordpress");
    descOnWordLength = __("Your headline has the right amount of words. Headlines are more likely to be clicked on in search results if they have about 6 words.", "google-analytics-for-wordpress");
  } else if (wordLength >= 10 && wordLength <= 11) {
    classOnLength = 'orange';
    barColor = '#F2994A';
    statusOnLength = __("Reduce Word Count", "google-analytics-for-wordpress");
  } else {
    classOnLength = 'red';
    barColor = '#EB5757';
    statusOnLength = __("Too Many Words", "google-analytics-for-wordpress");
    descOnWordLength = __("Your headline has too many words. Long headlines will get cut off in search results and won’t get as many clicks.", "google-analytics-for-wordpress");
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: textWordCount,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("monsterinsights-headline-analyzer-panel-word-count", "monsterinsights-headline-analyzer-panel-has-icon", classOnLength),
    icon: 'green' === classOnLength ? _icons__WEBPACK_IMPORTED_MODULE_1__["default"].check : _icons__WEBPACK_IMPORTED_MODULE_1__["default"].warning
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-pie-chart-container"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("monsterinsights-headline-analyzer-word-length", classOnLength)
  }, wordLength), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlinePieChart__WEBPACK_IMPORTED_MODULE_3__["default"], {
    barScore: barScore,
    barColor: barColor
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "monsterinsights-headline-analyzer-status-on-word-length"
  }, statusOnLength)), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, descOnWordLength))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlinePanelWordCount);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePieChart.js":
/*!**************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePieChart.js ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

const {
  Fragment
} = wp.element;
const HeadlinePieChart = props => {
  const score = props.barScore;
  const width = props.width ? props.width + 'px' : '80px';
  const rightDeg = score > 50 ? 0 : 180 - 360 / 100 * score;
  const color = props.barColor;
  const fragmentLeft = () => {
    if (score > 50) {
      const leftDeg = 180 + (score - 50) * 360 / 100;
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "monsterinsights-donut-section monsterinsights-donut-section-left",
        style: {
          transform: 'rotate(0deg)'
        }
      }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
        className: "monsterinsights-donut-filler",
        style: {
          backgroundColor: color,
          transform: 'rotate(' + leftDeg + 'deg)'
        }
      }));
    }
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-donut-container",
    style: {
      flexDirection: 'column'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-donut",
    style: {
      width: width,
      paddingBottom: width,
      backgroundColor: '#f2f2f2'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-donut-sections",
    style: {
      transform: 'rotate(0deg)'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-donut-section monsterinsights-donut-section-right",
    style: {
      transform: 'rotate(0deg)'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-donut-filler",
    style: {
      backgroundColor: color,
      transform: 'rotate(-' + rightDeg + 'deg)'
    }
  })), fragmentLeft()), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-donut-overlay",
    style: {
      height: '70%',
      width: '70%',
      top: 'calc(15%)',
      left: 'calc(15%)',
      backgroundColor: 'rgb(255, 255, 255)'
    }
  }))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlinePieChart);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineSwitcher.js":
/*!**************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineSwitcher.js ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _HeadlineTabCurrentScore__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./HeadlineTabCurrentScore */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineTabCurrentScore.js");
/* harmony import */ var _HeadlineTabNewScore__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./HeadlineTabNewScore */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineTabNewScore.js");
/* harmony import */ var _HeadlinePanelNewScore__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./HeadlinePanelNewScore */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelNewScore.js");

const {
  __
} = wp.i18n;
const {
  Fragment,
  useState,
  useEffect
} = wp.element;




const HeadlineSwitcher = props => {
  const textCurrentScore = __("Current Score", "google-analytics-for-wordpress");
  const textNewHeadline = __("Try New Headline", "google-analytics-for-wordpress");
  const [activeTab, setActiveTab] = useState("current-score");
  const activeCurrentScore = () => setActiveTab("current-score");
  const activeNewHeadline = () => setActiveTab("new-headline");
  const isNewData = 'undefined' !== typeof props.analyzer.isNewData ? props.analyzer.isNewData : false;
  useEffect(() => {
    props.setAnalyzer({
      activeTab: activeTab
    });
  }, [activeTab]);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-inline-buttons"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    onClick: activeCurrentScore,
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("monsterinsights-switcher-button", {
      "active": "current-score" === activeTab
    })
  }, textCurrentScore), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    onClick: activeNewHeadline,
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("monsterinsights-switcher-button", {
      "active": "new-headline" === activeTab
    })
  }, textNewHeadline)), 'new-headline' === activeTab ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlineTabNewScore__WEBPACK_IMPORTED_MODULE_3__["default"], {
    analyzer: props.analyzer,
    setAnalyzer: props.setAnalyzer
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlineTabCurrentScore__WEBPACK_IMPORTED_MODULE_2__["default"], {
    analyzer: props.analyzer
  }), isNewData ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlinePanelNewScore__WEBPACK_IMPORTED_MODULE_4__["default"], {
    analyzer: props.analyzer
  }) : '');
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlineSwitcher);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineTabCurrentScore.js":
/*!*********************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineTabCurrentScore.js ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _HeadlinePieChart__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./HeadlinePieChart */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePieChart.js");

const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow
} = wp.components;


const HeadlineTabCurrentScore = props => {
  const postTitle = props.analyzer.currentHeadlineData.sentence;
  const textScore = __("Score", "google-analytics-for-wordpress");
  const textGuideLine = __("A good score is between 40 and 60. For best results, you should strive for 70 and above.", "google-analytics-for-wordpress");
  const currentScore = props.analyzer.currentHeadlineData.score;
  const classOnScore = currentScore < 40 ? 'red' : currentScore <= 60 ? 'orange' : 'green';
  const barColor = 'red' === classOnScore ? '#EB5757' : 'orange' === classOnScore ? '#F2994A' : '#1EC185';
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: textScore
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-current-score-tab"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h4", {
    className: "monsterinsights-headline-analyzer-current-title"
  }, "\u201C", postTitle, "\u201D"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-pie-chart-container"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("monsterinsights-headline-analyzer-current-score", classOnScore)
  }, currentScore), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_HeadlinePieChart__WEBPACK_IMPORTED_MODULE_2__["default"], {
    barScore: currentScore,
    barColor: barColor
  })), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, textGuideLine)))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlineTabCurrentScore);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineTabNewScore.js":
/*!*****************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineTabNewScore.js ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! axios */ "./node_modules/axios/lib/axios.js");


const {
  __
} = wp.i18n;
const {
  Fragment
} = wp.element;
const {
  PanelBody,
  PanelRow,
  TextControl,
  Button
} = wp.components;
const HeadlineTabNewScore = props => {
  const textPanelTitle = __("Try New Headline", "google-analytics-for-wordpress");
  const textNewHeadlineInputLabel = __("Enter a different headline than your post title to see how it compares.", "google-analytics-for-wordpress");
  const textAnalyze = __("Analyze", "google-analytics-for-wordpress");
  const newHeadline = 'undefined' !== typeof props.analyzer.newHeadline ? props.analyzer.newHeadline : '';
  const previousScores = 'undefined' !== typeof props.analyzer.previousHeadlinesData ? props.analyzer.previousHeadlinesData : [];
  const isDisabled = newHeadline ? false : true;
  const fetchNewHeadlineData = value => {
    const headline = value.trim();
    if (!headline) {
      return;
    }
    let formData = new FormData();
    formData.append('_ajax_nonce', monsterinsights_gutenberg_tool_vars.nonce);
    formData.append('action', 'monsterinsights_gutenberg_headline_analyzer_get_results');
    formData.append('dataType', 'json');
    formData.append('q', headline);
    axios__WEBPACK_IMPORTED_MODULE_1__["default"].post(monsterinsights_gutenberg_tool_vars.ajaxurl, formData).then(response => {
      props.setAnalyzer({
        newHeadlineData: response.data.data,
        headlineData: response.data.data,
        previousHeadlinesData: [props.analyzer.headlineData, ...previousScores],
        isNewData: true
      });
    }).catch(error => {
      props.setAnalyzer({
        isNewData: false
      });
      console.log(error);
    });
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
    title: textPanelTitle
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelRow, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-new-tab"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("form", {
    onSubmit: e => {
      e.preventDefault();
      fetchNewHeadlineData(newHeadline);
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(TextControl, {
    label: textNewHeadlineInputLabel,
    value: newHeadline,
    onChange: newValue => {
      if (" " !== newValue) {
        props.setAnalyzer({
          newHeadline: newValue
        });
      }
    },
    className: "monsterinsights-headline-analyzer-input-field"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Button, {
    className: "monsterinsights-headline-analyzer-button",
    isPrimary: true,
    onClick: () => {
      fetchNewHeadlineData(newHeadline);
    },
    disabled: isDisabled
  }, textAnalyze))))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlineTabNewScore);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineWordsBlock.js":
/*!****************************************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineWordsBlock.js ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);

const {
  __
} = wp.i18n;

const HeadlineWordsBlock = props => {
  const textGoal = __("Goal: ", "google-analytics-for-wordpress");
  const progressBarStyle = {
    width: props.value + "%"
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-words-block"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h5", null, props.title), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-words-block-data"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("monsterinsights-headline-analyzer-words-block-percentage", props.classOnScore)
  }, props.value, "%"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "monsterinsights-headline-analyzer-words-block-goal"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, textGoal), props.goalValue), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "monsterinsights-headline-analyzer-words-block-progressbar"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "monsterinsights-headline-analyzer-progressbar-bg"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()("monsterinsights-headline-analyzer-progressbar-part", props.classOnScoreBg),
    style: progressBarStyle
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
    className: "monsterinsights-headline-analyzer-words-tag-list"
  }, props.words.length > 0 ? props.words.map((word, index) => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
      key: index
    }, word);
  }) : ''), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "monsterinsights-headline-analyzer-words-guideline"
  }, props.guideLine));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HeadlineWordsBlock);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/icons.js":
/*!****************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/icons.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

const icons = {};
icons.headline = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "15",
  height: "14",
  viewBox: "0 0 15 14",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M13.5 2V12H14.5C14.6458 12 14.7604 12.0521 14.8438 12.1562C14.9479 12.2396 15 12.3542 15 12.5V13.5C15 13.6458 14.9479 13.7604 14.8438 13.8438C14.7604 13.9479 14.6458 14 14.5 14H9.5C9.35417 14 9.22917 13.9479 9.125 13.8438C9.04167 13.7604 9 13.6458 9 13.5V12.5C9 12.3542 9.04167 12.2396 9.125 12.1562C9.22917 12.0521 9.35417 12 9.5 12H10.5V8H4.5V12H5.5C5.64583 12 5.76042 12.0521 5.84375 12.1562C5.94792 12.2396 6 12.3542 6 12.5V13.5C6 13.6458 5.94792 13.7604 5.84375 13.8438C5.76042 13.9479 5.64583 14 5.5 14H0.5C0.354167 14 0.229167 13.9479 0.125 13.8438C0.0416667 13.7604 0 13.6458 0 13.5V12.5C0 12.3542 0.0416667 12.2396 0.125 12.1562C0.229167 12.0521 0.354167 12 0.5 12H1.5V2H0.5C0.354167 2 0.229167 1.95833 0.125 1.875C0.0416667 1.77083 0 1.64583 0 1.5V0.5C0 0.354167 0.0416667 0.239583 0.125 0.15625C0.229167 0.0520833 0.354167 0 0.5 0H5.5C5.64583 0 5.76042 0.0520833 5.84375 0.15625C5.94792 0.239583 6 0.354167 6 0.5V1.5C6 1.64583 5.94792 1.77083 5.84375 1.875C5.76042 1.95833 5.64583 2 5.5 2H4.5V6H10.5V2H9.5C9.35417 2 9.22917 1.95833 9.125 1.875C9.04167 1.77083 9 1.64583 9 1.5V0.5C9 0.354167 9.04167 0.239583 9.125 0.15625C9.22917 0.0520833 9.35417 0 9.5 0H14.5C14.6458 0 14.7604 0.0520833 14.8438 0.15625C14.9479 0.239583 15 0.354167 15 0.5V1.5C15 1.64583 14.9479 1.77083 14.8438 1.875C14.7604 1.95833 14.6458 2 14.5 2H13.5Z",
  fill: "white"
}));
icons.headlineBlack = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "15",
  height: "14",
  viewBox: "0 0 15 14",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M13.5 2V12H14.5C14.6458 12 14.7604 12.0521 14.8438 12.1562C14.9479 12.2396 15 12.3542 15 12.5V13.5C15 13.6458 14.9479 13.7604 14.8438 13.8438C14.7604 13.9479 14.6458 14 14.5 14H9.5C9.35417 14 9.22917 13.9479 9.125 13.8438C9.04167 13.7604 9 13.6458 9 13.5V12.5C9 12.3542 9.04167 12.2396 9.125 12.1562C9.22917 12.0521 9.35417 12 9.5 12H10.5V8H4.5V12H5.5C5.64583 12 5.76042 12.0521 5.84375 12.1562C5.94792 12.2396 6 12.3542 6 12.5V13.5C6 13.6458 5.94792 13.7604 5.84375 13.8438C5.76042 13.9479 5.64583 14 5.5 14H0.5C0.354167 14 0.229167 13.9479 0.125 13.8438C0.0416667 13.7604 0 13.6458 0 13.5V12.5C0 12.3542 0.0416667 12.2396 0.125 12.1562C0.229167 12.0521 0.354167 12 0.5 12H1.5V2H0.5C0.354167 2 0.229167 1.95833 0.125 1.875C0.0416667 1.77083 0 1.64583 0 1.5V0.5C0 0.354167 0.0416667 0.239583 0.125 0.15625C0.229167 0.0520833 0.354167 0 0.5 0H5.5C5.64583 0 5.76042 0.0520833 5.84375 0.15625C5.94792 0.239583 6 0.354167 6 0.5V1.5C6 1.64583 5.94792 1.77083 5.84375 1.875C5.76042 1.95833 5.64583 2 5.5 2H4.5V6H10.5V2H9.5C9.35417 2 9.22917 1.95833 9.125 1.875C9.04167 1.77083 9 1.64583 9 1.5V0.5C9 0.354167 9.04167 0.239583 9.125 0.15625C9.22917 0.0520833 9.35417 0 9.5 0H14.5C14.6458 0 14.7604 0.0520833 14.8438 0.15625C14.9479 0.239583 15 0.354167 15 0.5V1.5C15 1.64583 14.9479 1.77083 14.8438 1.875C14.7604 1.95833 14.6458 2 14.5 2H13.5Z",
  fill: "#000"
}));
icons.warning = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "16",
  height: "16",
  viewBox: "0 0 16 16",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M16 8C16 6.58065 15.6129 5.22581 14.9032 4C14.1935 2.77419 13.2258 1.80645 12 1.09677C10.7742 0.387097 9.41936 0 8 0C6.54839 0 5.22581 0.387097 4 1.09677C2.77419 1.80645 1.77419 2.77419 1.06452 4C0.354839 5.22581 0 6.58065 0 8C0 9.45161 0.354839 10.7742 1.06452 12C1.77419 13.2258 2.77419 14.2258 4 14.9355C5.22581 15.6452 6.54839 16 8 16C9.41936 16 10.7742 15.6452 12 14.9355C13.2258 14.2258 14.1935 13.2258 14.9032 12C15.6129 10.7742 16 9.45161 16 8ZM8 9.6129C8.3871 9.6129 8.74194 9.77419 9.03226 10.0645C9.32258 10.3548 9.48387 10.7097 9.48387 11.0968C9.48387 11.5161 9.32258 11.871 9.03226 12.1613C8.74194 12.4516 8.3871 12.5806 8 12.5806C7.58065 12.5806 7.22581 12.4516 6.93548 12.1613C6.64516 11.871 6.51613 11.5161 6.51613 11.0968C6.51613 10.7097 6.64516 10.3548 6.93548 10.0645C7.22581 9.77419 7.58065 9.6129 8 9.6129ZM6.58065 4.29032C6.58065 4.16129 6.6129 4.06452 6.67742 4C6.74194 3.93548 6.83871 3.87097 6.96774 3.87097H9.03226C9.12903 3.87097 9.22581 3.93548 9.29032 4C9.35484 4.06452 9.41936 4.16129 9.41936 4.29032L9.16129 8.67742C9.16129 8.77419 9.09677 8.87097 9.03226 8.93548C8.96774 9 8.87097 9.03226 8.77419 9.03226H7.22581C7.09677 9.03226 7 9 6.93548 8.93548C6.87097 8.87097 6.83871 8.77419 6.83871 8.67742L6.58065 4.29032Z",
  fill: "#F2994A"
}));
icons.smile = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "16",
  height: "16",
  viewBox: "0 0 16 16",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M2.32258 2.35484C3.89247 0.784946 5.78495 0 8 0C10.2151 0 12.0968 0.784946 13.6452 2.35484C15.2151 3.90323 16 5.78495 16 8C16 10.2151 15.2151 12.1075 13.6452 13.6774C12.0968 15.2258 10.2151 16 8 16C5.78495 16 3.89247 15.2258 2.32258 13.6774C0.774194 12.1075 0 10.2151 0 8C0 5.78495 0.774194 3.90323 2.32258 2.35484ZM11.2903 5.74194C11.0968 5.52688 10.8602 5.41935 10.5806 5.41935C10.3011 5.41935 10.0538 5.52688 9.83871 5.74194C9.64516 5.93548 9.54839 6.17204 9.54839 6.45161C9.54839 6.73118 9.64516 6.97849 9.83871 7.19355C10.0538 7.3871 10.3011 7.48387 10.5806 7.48387C10.8602 7.48387 11.0968 7.3871 11.2903 7.19355C11.5054 6.97849 11.6129 6.73118 11.6129 6.45161C11.6129 6.17204 11.5054 5.93548 11.2903 5.74194ZM6.12903 5.74194C5.93548 5.52688 5.69892 5.41935 5.41935 5.41935C5.13978 5.41935 4.89247 5.52688 4.67742 5.74194C4.48387 5.93548 4.3871 6.17204 4.3871 6.45161C4.3871 6.73118 4.48387 6.97849 4.67742 7.19355C4.89247 7.3871 5.13978 7.48387 5.41935 7.48387C5.69892 7.48387 5.93548 7.3871 6.12903 7.19355C6.34409 6.97849 6.45161 6.73118 6.45161 6.45161C6.45161 6.17204 6.34409 5.93548 6.12903 5.74194ZM11.7097 10.9032C11.7957 10.8172 11.8387 10.7204 11.8387 10.6129C11.8387 10.4839 11.8065 10.3871 11.7419 10.3226C11.6774 10.2366 11.5914 10.172 11.4839 10.129C11.3978 10.0645 11.3011 10.043 11.1935 10.0645C11.086 10.086 10.9892 10.1505 10.9032 10.2581C10.1505 11.1613 9.1828 11.6129 8 11.6129C6.8172 11.6129 5.84946 11.1613 5.09677 10.2581C5.01075 10.1505 4.91398 10.086 4.80645 10.0645C4.69892 10.043 4.5914 10.0645 4.48387 10.129C4.39785 10.172 4.32258 10.2366 4.25806 10.3226C4.19355 10.3871 4.16129 10.4839 4.16129 10.6129C4.16129 10.7204 4.2043 10.8172 4.29032 10.9032C5.25806 12.0645 6.49462 12.6452 8 12.6452C9.50538 12.6452 10.7419 12.0645 11.7097 10.9032Z",
  fill: "#1EC185"
}));
icons.neutral = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "16",
  height: "16",
  viewBox: "0 0 16 16",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M2.32258 2.35484C3.89247 0.784946 5.78495 0 8 0C10.2151 0 12.0968 0.784946 13.6452 2.35484C15.2151 3.90323 16 5.78495 16 8C16 10.2151 15.2151 12.1075 13.6452 13.6774C12.0968 15.2258 10.2151 16 8 16C5.78495 16 3.89247 15.2258 2.32258 13.6774C0.774194 12.1075 0 10.2151 0 8C0 5.78495 0.774194 3.90323 2.32258 2.35484ZM6.12903 5.74194C5.93548 5.52688 5.69892 5.41935 5.41935 5.41935C5.13978 5.41935 4.89247 5.52688 4.67742 5.74194C4.48387 5.93548 4.3871 6.17204 4.3871 6.45161C4.3871 6.73118 4.48387 6.97849 4.67742 7.19355C4.89247 7.3871 5.13978 7.48387 5.41935 7.48387C5.69892 7.48387 5.93548 7.3871 6.12903 7.19355C6.34409 6.97849 6.45161 6.73118 6.45161 6.45161C6.45161 6.17204 6.34409 5.93548 6.12903 5.74194ZM11.0968 11.6129C11.4409 11.6129 11.6129 11.4409 11.6129 11.0968C11.6129 10.7527 11.4409 10.5806 11.0968 10.5806H4.90323C4.55914 10.5806 4.3871 10.7527 4.3871 11.0968C4.3871 11.4409 4.55914 11.6129 4.90323 11.6129H11.0968ZM9.83871 7.19355C10.0538 7.3871 10.3011 7.48387 10.5806 7.48387C10.8602 7.48387 11.0968 7.3871 11.2903 7.19355C11.5054 6.97849 11.6129 6.73118 11.6129 6.45161C11.6129 6.17204 11.5054 5.93548 11.2903 5.74194C11.0968 5.52688 10.8602 5.41935 10.5806 5.41935C10.3011 5.41935 10.0538 5.52688 9.83871 5.74194C9.64516 5.93548 9.54839 6.17204 9.54839 6.45161C9.54839 6.73118 9.64516 6.97849 9.83871 7.19355Z",
  fill: "#626D83"
}));
icons.negative = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "16",
  height: "16",
  viewBox: "0 0 16 16",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M2.32258 2.35484C3.89247 0.784946 5.78495 0 8 0C10.2151 0 12.0968 0.784946 13.6452 2.35484C15.2151 3.90323 16 5.78495 16 8C16 10.2151 15.2151 12.1075 13.6452 13.6774C12.0968 15.2258 10.2151 16 8 16C5.78495 16 3.89247 15.2258 2.32258 13.6774C0.774194 12.1075 0 10.2151 0 8C0 5.78495 0.774194 3.90323 2.32258 2.35484ZM4.3871 7.48387C4.3871 7.76344 4.48387 8.01075 4.67742 8.22581C4.89247 8.41935 5.13978 8.51613 5.41935 8.51613C5.69892 8.51613 5.93548 8.41935 6.12903 8.22581C6.34409 8.01075 6.45161 7.76344 6.45161 7.48387C6.45161 7.44086 6.43011 7.35484 6.3871 7.22581C6.4086 7.22581 6.41935 7.22581 6.41935 7.22581C6.44086 7.22581 6.45161 7.22581 6.45161 7.22581C6.70968 7.22581 6.87097 7.10753 6.93548 6.87097C6.95699 6.78495 6.96774 6.70968 6.96774 6.64516C6.96774 6.58065 6.94624 6.52688 6.90323 6.48387C6.88172 6.41935 6.83871 6.36559 6.77419 6.32258C6.73118 6.27957 6.67742 6.24731 6.6129 6.22581L4.03226 5.45161C3.88172 5.4086 3.74194 5.41935 3.6129 5.48387C3.50538 5.54839 3.43011 5.64516 3.3871 5.77419C3.36559 5.86021 3.35484 5.93548 3.35484 6C3.37634 6.06452 3.39785 6.12903 3.41935 6.19355C3.44086 6.23656 3.47312 6.27957 3.51613 6.32258C3.58065 6.36559 3.65591 6.39785 3.74194 6.41935L4.70968 6.74194C4.49462 6.93548 4.3871 7.1828 4.3871 7.48387ZM9.80645 12.4516C9.93548 12.6237 10.0968 12.6882 10.2903 12.6452C10.4839 12.6021 10.6129 12.4946 10.6774 12.3226C10.7419 12.1505 10.7097 11.9785 10.5806 11.8065C9.91398 10.9892 9.05376 10.5806 8 10.5806C6.94624 10.5806 6.07527 10.9892 5.3871 11.8065C5.34409 11.871 5.31183 11.9462 5.29032 12.0323C5.26882 12.1183 5.26882 12.1935 5.29032 12.2581C5.31183 12.3226 5.34409 12.3871 5.3871 12.4516C5.43011 12.5161 5.48387 12.5699 5.54839 12.6129C5.6129 12.6344 5.67742 12.6559 5.74194 12.6774C5.82796 12.6774 5.90323 12.6667 5.96774 12.6452C6.05376 12.6021 6.12903 12.5376 6.19355 12.4516C6.64516 11.914 7.24731 11.6452 8 11.6452C8.75269 11.6452 9.35484 11.914 9.80645 12.4516ZM12.2903 6.41935C12.4194 6.37634 12.5161 6.30107 12.5806 6.19355C12.6452 6.06452 12.6559 5.92473 12.6129 5.77419C12.5914 5.68817 12.5484 5.6129 12.4839 5.54839C12.4194 5.48387 12.3441 5.45161 12.2581 5.45161C12.172 5.43011 12.086 5.43011 12 5.45161L9.41935 6.22581C9.26882 6.26882 9.16129 6.35484 9.09677 6.48387C9.03226 6.5914 9.02151 6.72043 9.06452 6.87097C9.12903 7.10753 9.29032 7.22581 9.54839 7.22581C9.56989 7.22581 9.5914 7.22581 9.6129 7.22581C9.56989 7.33333 9.54839 7.41935 9.54839 7.48387C9.54839 7.76344 9.64516 8.01075 9.83871 8.22581C10.0538 8.41935 10.3011 8.51613 10.5806 8.51613C10.8602 8.51613 11.0968 8.41935 11.2903 8.22581C11.5054 8.01075 11.6129 7.76344 11.6129 7.48387C11.6129 7.1828 11.5054 6.93548 11.2903 6.74194L12.2903 6.41935Z",
  fill: "#626D83"
}));
icons.check = (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  width: "16",
  height: "16",
  viewBox: "0 0 16 16",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg"
}, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M13.6452 2.35484C15.2151 3.90323 16 5.78495 16 8C16 10.2151 15.2151 12.1075 13.6452 13.6774C12.0968 15.2258 10.2151 16 8 16C5.78495 16 3.89247 15.2258 2.32258 13.6774C0.774194 12.1075 0 10.2151 0 8C0 5.78495 0.774194 3.90323 2.32258 2.35484C3.89247 0.784946 5.78495 0 8 0C10.2151 0 12.0968 0.784946 13.6452 2.35484ZM7.06452 12.2258L13 6.29032C13.2581 6.05376 13.2581 5.8172 13 5.58065L12.2903 4.83871C12.0323 4.60215 11.7849 4.60215 11.5484 4.83871L6.70968 9.67742L4.45161 7.41935C4.21505 7.1828 3.96774 7.1828 3.70968 7.41935L3 8.16129C2.74194 8.39785 2.74194 8.63441 3 8.87097L6.35484 12.2258C6.5914 12.4839 6.82796 12.4839 7.06452 12.2258Z",
  fill: "#1EC185"
}));
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (icons);

/***/ }),

/***/ "./src/plugins/monsterinsights-Headline-Analyzer/index.js":
/*!****************************************************************!*\
  !*** ./src/plugins/monsterinsights-Headline-Analyzer/index.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./icons */ "./src/plugins/monsterinsights-Headline-Analyzer/icons.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! axios */ "./node_modules/axios/lib/axios.js");
/* harmony import */ var _components_HeadlineSwitcher__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/HeadlineSwitcher */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlineSwitcher.js");
/* harmony import */ var _components_HeadlinePanelPreviousScores__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/HeadlinePanelPreviousScores */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelPreviousScores.js");
/* harmony import */ var _components_HeadlinePanelWordBalance__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/HeadlinePanelWordBalance */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelWordBalance.js");
/* harmony import */ var _components_HeadlinePanelSentiment__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/HeadlinePanelSentiment */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelSentiment.js");
/* harmony import */ var _components_HeadlinePanelType__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/HeadlinePanelType */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelType.js");
/* harmony import */ var _components_HeadlinePanelCharacterCount__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/HeadlinePanelCharacterCount */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelCharacterCount.js");
/* harmony import */ var _components_HeadlinePanelWordCount__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./components/HeadlinePanelWordCount */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelWordCount.js");
/* harmony import */ var _components_HeadlinePanelStartEndWords__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./components/HeadlinePanelStartEndWords */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelStartEndWords.js");
/* harmony import */ var _components_HeadlinePanelSearchPreview__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./components/HeadlinePanelSearchPreview */ "./src/plugins/monsterinsights-Headline-Analyzer/components/HeadlinePanelSearchPreview.js");

const {
  __
} = wp.i18n;
const {
  Fragment,
  useState,
  useEffect
} = wp.element;
const {
  registerPlugin
} = wp.plugins;
const {
  PluginSidebar,
  PluginSidebarMoreMenuItem
} = wp.editPost;
const {
  useSelect
} = wp.data;











const MonsterInsightsHeadlineAnalyzer = props => {
  const postTitle = useSelect(select => select('core/editor').getEditedPostAttribute('title'));
  const textHeadlineAnalyzer = __("Headline Analyzer", "google-analytics-for-wordpress");
  const emptyTitleWarning = __("Write your post title to see the analyzer data. This Headline Analyzer tool enables you to write irresistible SEO headlines that drive traffic, shares, and rank better in search results.", "google-analytics-for-wordpress");
  const [analyzer, setAnalyzer] = useState({});
  const pinnedIcon = document.querySelector(`.components-button[aria-label='${textHeadlineAnalyzer}'] svg`);
  const previousScores = 'undefined' !== typeof analyzer.previousHeadlinesData ? analyzer.previousHeadlinesData : [];
  const notice = sprintf(__('This headline analyzer is part of MonsterInsights to help you increase your traffic. See your %1swebsite traffic reports%2s.', "google-analytics-for-wordpress"), `<a href="${monsterinsights_gutenberg_tool_vars.reports_url}" className="monsterinsights-headline-analyzer-link">`, '</a>');
  const wrapper = document.querySelector('.monsterinsights-headline-analyzer-wrapper');
  if (pinnedIcon) {
    let scoreTag = document.createElement('span');
    if (analyzer.dataExist && 'undefined' !== typeof analyzer.currentHeadlineData.score) {
      const currentScore = analyzer.currentHeadlineData.score;
      const classOnScore = currentScore < 40 ? 'red' : currentScore <= 60 ? 'orange' : 'green';
      pinnedIcon.parentNode.setAttribute('monsterinsights-button-color', classOnScore);
      if (!pinnedIcon.nextElementSibling) {
        scoreTag.innerHTML = `${currentScore}/100`;
        pinnedIcon.parentNode.insertBefore(scoreTag, pinnedIcon.nextSibling);
      } else {
        pinnedIcon.nextElementSibling.innerHTML = `${currentScore}/100`;
      }
    } else {
      pinnedIcon.parentNode.setAttribute('monsterinsights-button-color', 'red');
      if (!pinnedIcon.nextElementSibling) {
        scoreTag.innerHTML = '00/100';
        pinnedIcon.parentNode.insertBefore(scoreTag, pinnedIcon.nextSibling);
      } else {
        pinnedIcon.nextElementSibling.innerHTML = '00/100';
      }
    }
  }
  if (wrapper) {
    const headingElements = wrapper.parentNode.querySelectorAll('.components-panel__header');
    if (headingElements) {
      headingElements.forEach(function (element) {
        let button = element.querySelector('[aria-pressed="true"]');
        if (button && null !== button && null !== button.offsetParent) {
          button.style.display = 'none';
        }
      });
    }
  }
  const updateAnalyzerData = data => {
    setAnalyzer({
      ...analyzer,
      ...data
    });
  };
  useEffect(() => {
    clearTimeout(window.HaPostTitleTimer);
    window.HaPostTitleTimer = setTimeout(() => {
      let formData = new FormData();
      formData.append('_ajax_nonce', monsterinsights_gutenberg_tool_vars.nonce);
      formData.append('action', 'monsterinsights_gutenberg_headline_analyzer_get_results');
      formData.append('dataType', 'json');
      formData.append('q', postTitle);
      axios__WEBPACK_IMPORTED_MODULE_11__["default"].post(monsterinsights_gutenberg_tool_vars.ajaxurl, formData).then(response => {
        let newAnalyzerData = {
          dataExist: false
        };
        if (response.data.data.analysed) {
          newAnalyzerData.currentHeadlineData = response.data.data;
          newAnalyzerData.headlineData = response.data.data;
          newAnalyzerData.dataExist = true;
          if ('undefined' !== typeof analyzer.headlineData) {
            newAnalyzerData.previousHeadlinesData = [analyzer.headlineData, ...previousScores];
          }
          ;
        }
        setAnalyzer({
          ...analyzer,
          ...newAnalyzerData
        });
      }).catch(error => {
        let newAnalyzerData = {
          dataExist: false
        };
        setAnalyzer({
          ...analyzer,
          ...newAnalyzerData
        });
        console.log(error);
      });
    }, 1000);
  }, [postTitle]);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PluginSidebarMoreMenuItem, {
    target: "monsterinsights-headline-analyzer"
  }, textHeadlineAnalyzer), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(PluginSidebar, {
    name: "monsterinsights-headline-analyzer",
    title: textHeadlineAnalyzer,
    className: "monsterinsights-headline-analyzer-wrapper",
    icon: _icons__WEBPACK_IMPORTED_MODULE_1__["default"].headline
  }, 'undefined' !== typeof analyzer.headlineData && analyzer.dataExist && analyzer.headlineData.analysed ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HeadlineSwitcher__WEBPACK_IMPORTED_MODULE_2__["default"], {
    analyzer: analyzer,
    setAnalyzer: updateAnalyzerData
  }) : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "monsterinsights-headline-analyzer-empty-title-warning"
  }, emptyTitleWarning), 'undefined' !== typeof analyzer.headlineData && analyzer.dataExist && analyzer.headlineData.analysed && previousScores.length > 0 ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HeadlinePanelPreviousScores__WEBPACK_IMPORTED_MODULE_3__["default"], {
    analyzer: analyzer,
    setAnalyzer: updateAnalyzerData
  }) : '', 'undefined' !== typeof analyzer.headlineData && analyzer.dataExist && analyzer.headlineData.analysed ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HeadlinePanelWordBalance__WEBPACK_IMPORTED_MODULE_4__["default"], {
    data: analyzer.headlineData
  }) : '', 'undefined' !== typeof analyzer.headlineData && analyzer.dataExist && analyzer.headlineData.analysed ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HeadlinePanelSentiment__WEBPACK_IMPORTED_MODULE_5__["default"], {
    data: analyzer.headlineData
  }) : '', 'undefined' !== typeof analyzer.headlineData && analyzer.dataExist && analyzer.headlineData.analysed ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HeadlinePanelType__WEBPACK_IMPORTED_MODULE_6__["default"], {
    data: analyzer.headlineData
  }) : '', 'undefined' !== typeof analyzer.headlineData && analyzer.dataExist && analyzer.headlineData.analysed ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HeadlinePanelCharacterCount__WEBPACK_IMPORTED_MODULE_7__["default"], {
    data: analyzer.headlineData
  }) : '', 'undefined' !== typeof analyzer.headlineData && analyzer.dataExist && analyzer.headlineData.analysed ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HeadlinePanelWordCount__WEBPACK_IMPORTED_MODULE_8__["default"], {
    data: analyzer.headlineData
  }) : '', 'undefined' !== typeof analyzer.headlineData && analyzer.dataExist && analyzer.headlineData.analysed ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HeadlinePanelStartEndWords__WEBPACK_IMPORTED_MODULE_9__["default"], {
    data: analyzer.headlineData
  }) : '', 'undefined' !== typeof analyzer.headlineData && analyzer.dataExist && analyzer.headlineData.analysed ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_HeadlinePanelSearchPreview__WEBPACK_IMPORTED_MODULE_10__["default"], {
    data: analyzer.headlineData
  }) : '', (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "monsterinsights-headline-analyzer-bottom-notice"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    dangerouslySetInnerHTML: {
      __html: notice
    }
  }))));
};

// register & render editor sidebar plugin for post_type `post` only
if ('undefined' !== typeof monsterinsights_gutenberg_tool_vars && monsterinsights_gutenberg_tool_vars.allowed_post_types.includes(monsterinsights_gutenberg_tool_vars.current_post_type) && monsterinsights_gutenberg_tool_vars.is_headline_analyzer_enabled) {
  registerPlugin("monsterinsights-headline-analyzer", {
    icon: _icons__WEBPACK_IMPORTED_MODULE_1__["default"].headlineBlack,
    render: MonsterInsightsHeadlineAnalyzer
  });
}

/***/ }),

/***/ "./src/assets/scss/monsterinsights/editor.scss":
/*!*****************************************************!*\
  !*** ./src/assets/scss/monsterinsights/editor.scss ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/assets/scss/monsterinsights/frontend-Lite.scss":
/*!************************************************************!*\
  !*** ./src/assets/scss/monsterinsights/frontend-Lite.scss ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./node_modules/pure-react-carousel/dist/index.es.js":
/*!***********************************************************!*\
  !*** ./node_modules/pure-react-carousel/dist/index.es.js ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ButtonBack: () => (/* binding */ index),
/* harmony export */   ButtonFirst: () => (/* binding */ index$1),
/* harmony export */   ButtonLast: () => (/* binding */ index$3),
/* harmony export */   ButtonNext: () => (/* binding */ index$2),
/* harmony export */   ButtonPlay: () => (/* binding */ index$4),
/* harmony export */   CarouselContext: () => (/* binding */ Context),
/* harmony export */   CarouselProvider: () => (/* binding */ CarouselProvider),
/* harmony export */   Dot: () => (/* binding */ Dot$1),
/* harmony export */   DotGroup: () => (/* binding */ index$5),
/* harmony export */   Image: () => (/* binding */ Image$1),
/* harmony export */   ImageWithZoom: () => (/* binding */ index$6),
/* harmony export */   Slide: () => (/* binding */ index$7),
/* harmony export */   Slider: () => (/* binding */ index$8),
/* harmony export */   Spinner: () => (/* binding */ Spinner),
/* harmony export */   Store: () => (/* binding */ Store),
/* harmony export */   WithStore: () => (/* binding */ WithStore)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
function ownKeys(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);t&&(i=i.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),r.push.apply(r,i)}return r}function _objectSpread2(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?ownKeys(Object(r),!0).forEach(function(t){_defineProperty(e,t,r[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):ownKeys(Object(r)).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))})}return e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _defineProperties(e,t){for(var r=0;r<t.length;r++){var i=t[r];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}function _createClass(e,t,r){return t&&_defineProperties(e.prototype,t),r&&_defineProperties(e,r),Object.defineProperty(e,"prototype",{writable:!1}),e}function _defineProperty(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}function _extends(){return(_extends=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var i in r)Object.prototype.hasOwnProperty.call(r,i)&&(e[i]=r[i])}return e}).apply(this,arguments)}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),Object.defineProperty(e,"prototype",{writable:!1}),t&&_setPrototypeOf(e,t)}function _getPrototypeOf(e){return(_getPrototypeOf=Object.setPrototypeOf?Object.getPrototypeOf.bind():function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function _setPrototypeOf(e,t){return(_setPrototypeOf=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(e,t){return e.__proto__=t,e})(e,t)}function _isNativeReflectConstruct(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],function(){})),!0}catch(e){return!1}}function _objectWithoutPropertiesLoose(e,t){if(null==e)return{};var r,i,n={},s=Object.keys(e);for(i=0;i<s.length;i++)r=s[i],t.indexOf(r)>=0||(n[r]=e[r]);return n}function _objectWithoutProperties(e,t){if(null==e)return{};var r,i,n=_objectWithoutPropertiesLoose(e,t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);for(i=0;i<s.length;i++)r=s[i],t.indexOf(r)>=0||Object.prototype.propertyIsEnumerable.call(e,r)&&(n[r]=e[r])}return n}function _assertThisInitialized(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function _possibleConstructorReturn(e,t){if(t&&("object"==typeof t||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return _assertThisInitialized(e)}function _createSuper(e){var t=_isNativeReflectConstruct();return function(){var r,i=_getPrototypeOf(e);if(t){var n=_getPrototypeOf(this).constructor;r=Reflect.construct(i,arguments,n)}else r=i.apply(this,arguments);return _possibleConstructorReturn(this,r)}}function _toConsumableArray(e){return _arrayWithoutHoles(e)||_iterableToArray(e)||_unsupportedIterableToArray(e)||_nonIterableSpread()}function _arrayWithoutHoles(e){if(Array.isArray(e))return _arrayLikeToArray(e)}function _iterableToArray(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}function _unsupportedIterableToArray(e,t){if(e){if("string"==typeof e)return _arrayLikeToArray(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(e):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?_arrayLikeToArray(e,t):void 0}}function _arrayLikeToArray(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,i=new Array(t);r<t;r++)i[r]=e[r];return i}function _nonIterableSpread(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function createCommonjsModule(e,t){return t={exports:{}},e(t,t.exports),t.exports}function emptyFunction(){}function emptyFunctionWithReset(){}function cn(e){return e.map(function(e){return!1===e?null:e}).join(" ").replace(/\s+/g," ").trim()}function slideSize(e,t){return 100/e*t/t}function slideTraySize(e,t){return 100*e/t}function pct(e){return"".concat(e,"%")}function equal(e,t,r){if(e===t)return!0;var i=types[jkrosoType(e)],n=types[jkrosoType(t)];return!(!i||i!==n)&&i(e,t,r)}function memoGaurd(e){return function(t,r,i){if(!i)return e(t,r,[]);for(var n,s=i.length;n=i[--s];)if(n[0]===t&&n[1]===r)return!0;return e(t,r,i)}}function arrayEqual(e,t,r){var i=e.length;if(i!==t.length)return!1;for(r.push([e,t]);i--;)if(!equal(e[i],t[i],r))return!1;return!0}function objectEqual(e,t,r){if("function"==typeof e.equal)return r.push([e,t]),e.equal(t,r);var i=getEnumerableProperties(e),n=getEnumerableProperties(t),s=i.length;if(s!==n.length)return!1;for(i.sort(),n.sort();s--;)if(i[s]!==n[s])return!1;for(r.push([e,t]),s=i.length;s--;){var o=i[s];if(!equal(e[o],t[o],r))return!1}return!0}function getEnumerableProperties(e){var t=[];for(var r in e)"constructor"!==r&&t.push(r);return t}function isNonNullObject(e){return!!e&&"object"==typeof e}function isSpecial(e){var t=Object.prototype.toString.call(e);return"[object RegExp]"===t||"[object Date]"===t||isReactElement(e)}function isReactElement(e){return e.$$typeof===REACT_ELEMENT_TYPE}function emptyTarget(e){return Array.isArray(e)?[]:{}}function cloneUnlessOtherwiseSpecified(e,t){return!1!==t.clone&&t.isMergeableObject(e)?deepmerge(emptyTarget(e),e,t):e}function defaultArrayMerge(e,t,r){return e.concat(t).map(function(e){return cloneUnlessOtherwiseSpecified(e,r)})}function mergeObject(e,t,r){var i={};return r.isMergeableObject(e)&&Object.keys(e).forEach(function(t){i[t]=cloneUnlessOtherwiseSpecified(e[t],r)}),Object.keys(t).forEach(function(n){r.isMergeableObject(t[n])&&e[n]?i[n]=deepmerge(e[n],t[n],r):i[n]=cloneUnlessOtherwiseSpecified(t[n],r)}),i}function deepmerge(e,t,r){(r=r||{}).arrayMerge=r.arrayMerge||defaultArrayMerge,r.isMergeableObject=r.isMergeableObject||isMergeableObject;var i=Array.isArray(t);return i===Array.isArray(e)?i?r.arrayMerge(e,t,r):mergeObject(e,t,r):cloneUnlessOtherwiseSpecified(t,r)}function WithStore(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:function(){return{}},r=function(r){function i(e,r){var s;return _classCallCheck(this,i),s=n.call(this,e,r),s.state=t(_objectSpread2({},r.state)),s.updateStateProps=s.updateStateProps.bind(_assertThisInitialized(s)),s}_inherits(i,r);var n=_createSuper(i);return _createClass(i,[{key:"componentDidMount",value:function(){this.context.subscribe(this.updateStateProps)}},{key:"shouldComponentUpdate",value:function(e,t){return!equals(t,this.state)||!equals(e,this.props)}},{key:"componentWillUnmount",value:function(){this.context.unsubscribe(this.updateStateProps)}},{key:"updateStateProps",value:function(){this.setState(t(_objectSpread2({},this.context.state)))}},{key:"render",value:function(){var t=this,r=deepmerge_1(this.state,this.props);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(e,_extends({ref:function(e){t.instance=e}},r,{carouselStore:{getStoreState:this.context.getStoreState,masterSpinnerError:this.context.masterSpinnerError,masterSpinnerSuccess:this.context.masterSpinnerSuccess,setStoreState:this.context.setStoreState,subscribeMasterSpinner:this.context.subscribeMasterSpinner,unsubscribeAllMasterSpinner:this.context.unsubscribeAllMasterSpinner,unsubscribeMasterSpinner:this.context.unsubscribeMasterSpinner}}),this.props.children)}}]),i}((react__WEBPACK_IMPORTED_MODULE_0___default().Component));return _defineProperty(r,"contextType",Context),_defineProperty(r,"propTypes",{children:CarouselPropTypes.children}),_defineProperty(r,"defaultProps",{children:null}),r}var ReactPropTypesSecret="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED",ReactPropTypesSecret_1=ReactPropTypesSecret;emptyFunctionWithReset.resetWarningCache=emptyFunction;var factoryWithThrowingShims=function(){function e(e,t,r,i,n,s){if(s!==ReactPropTypesSecret_1){var o=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw o.name="Invariant Violation",o}}function t(){return e}e.isRequired=e;var r={array:e,bigint:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:emptyFunctionWithReset,resetWarningCache:emptyFunction};return r.PropTypes=r,r},propTypes=createCommonjsModule(function(e){e.exports=factoryWithThrowingShims()}),LOADING="loading",SUCCESS="success",ERROR="error",CarouselPropTypes={children:propTypes.oneOfType([propTypes.arrayOf(propTypes.node),propTypes.node]),direction:propTypes.oneOf(["forward","backward"]),height:function(e,t){var r=e[t];return"vertical"!==e.orientation||null!==r&&"number"==typeof r?null:new Error("Missing required property '".concat(t,"' when orientation is vertical.  You must supply a number representing the height in pixels"))},orientation:propTypes.oneOf(["horizontal","vertical"]),isBgImage:function(e,t){return!0===e[t]&&"img"===e.tag?new Error("HTML img elements should not have a backgroundImage.  Please use ".concat(t," for other block-level HTML tags, like div, a, section, etc...")):null}},boundedRange=function(e){var t=e.min,r=e.max,i=e.x;return Math.min(r,Math.max(t,i))},s={buttonBack:"buttonBack___1mlaL"},_excluded=["carouselStore","className","currentSlide","disabled","onClick","step","totalSlides","visibleSlides","infinite"],ButtonBack=function(e){function t(e){var i;return _classCallCheck(this,t),i=r.call(this,e),i.handleOnClick=i.handleOnClick.bind(_assertThisInitialized(i)),i}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"handleOnClick",value:function(e){var t=this.props,r=t.carouselStore,i=t.currentSlide,n=t.onClick,s=t.step,o=t.infinite,a=t.visibleSlides,l=t.totalSlides-a,c=Math.max(i-s,0);o&&(c=0===i?l:c),r.setStoreState({currentSlide:c,isPlaying:!1},null!==n&&n.call(this,e))}},{key:"render",value:function(){var e=this.props,r=(e.carouselStore,e.className),i=(e.currentSlide,e.disabled,e.onClick,e.step,e.totalSlides,e.visibleSlides,e.infinite),n=_objectWithoutProperties(e,_excluded),o=cn([s.buttonBack,"carousel__back-button",r]),a=t.setDisabled(this.props.disabled,this.props.currentSlide,i);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("button",_extends({type:"button","aria-label":"previous",className:o,onClick:this.handleOnClick,disabled:a},n),this.props.children)}}],[{key:"setDisabled",value:function(e,t,r){return null!==e?e:0===t&&!r}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().Component));_defineProperty(ButtonBack,"propTypes",{carouselStore:propTypes.object.isRequired,children:CarouselPropTypes.children.isRequired,className:propTypes.string,currentSlide:propTypes.number.isRequired,disabled:propTypes.bool,onClick:propTypes.func,step:propTypes.number.isRequired,totalSlides:propTypes.number.isRequired,visibleSlides:propTypes.number.isRequired,infinite:propTypes.bool}),_defineProperty(ButtonBack,"defaultProps",{className:null,disabled:null,onClick:null,infinite:!1});var jkrosoType=createCommonjsModule(function(e,t){var r={}.toString,i="undefined"!=typeof window?window.Node:Function;e.exports=t=function(e){var t=typeof e;if("object"!=t)return t;if(t=n[r.call(e)],"object"==t)return e instanceof Map?"map":e instanceof Set?"set":"object";if(t)return t;if(e instanceof i)switch(e.nodeType){case 1:return"element";case 3:return"text-node";case 9:return"document";case 11:return"document-fragment";default:return"dom-node"}};var n=t.types={"[object Function]":"function","[object Date]":"date","[object RegExp]":"regexp","[object Arguments]":"arguments","[object Array]":"array","[object Set]":"set","[object String]":"string","[object Null]":"null","[object Undefined]":"undefined","[object Number]":"number","[object Boolean]":"boolean","[object Object]":"object","[object Map]":"map","[object Text]":"text-node","[object Uint8Array]":"bit-array","[object Uint16Array]":"bit-array","[object Uint32Array]":"bit-array","[object Uint8ClampedArray]":"bit-array","[object Error]":"error","[object FormData]":"form-data","[object File]":"file","[object Blob]":"blob"}}),jkrosoType_1=jkrosoType.types,types={};types.number=function(e,t){return e!==e&&t!==t},types.function=function(e,t,r){return e.toString()===t.toString()&&types.object(e,t,r)&&equal(e.prototype,t.prototype)},types.date=function(e,t){return+e==+t},types.regexp=function(e,t){return e.toString()===t.toString()},types.element=function(e,t){return e.outerHTML===t.outerHTML},types.textnode=function(e,t){return e.textContent===t.textContent},types.arguments=types["bit-array"]=types.array=memoGaurd(arrayEqual),types.object=memoGaurd(objectEqual);var equals=equal,isMergeableObject=function(e){return isNonNullObject(e)&&!isSpecial(e)},canUseSymbol="function"==typeof Symbol&&Symbol.for,REACT_ELEMENT_TYPE=canUseSymbol?Symbol.for("react.element"):60103;deepmerge.all=function(e,t){if(!Array.isArray(e))throw new Error("first argument should be an array");return e.reduce(function(e,r){return deepmerge(e,r,t)},{})};var deepmerge_1=deepmerge,Context=react__WEBPACK_IMPORTED_MODULE_0___default().createContext(),deepFreeze=function e(t){return Object.freeze(t),Object.getOwnPropertyNames(t).forEach(function(r){!t.hasOwnProperty(r)||null===t[r]||"object"!=typeof t[r]&&"function"!=typeof t[r]||Object.isFrozen(t[r])||e(t[r])}),t},DEFAULT_STATE={masterSpinnerFinished:!1},Store=function(){function e(t){_classCallCheck(this,e),this.state=deepFreeze(deepmerge_1(DEFAULT_STATE,t)),this.subscriptions=[],this.masterSpinnerSubscriptions={},this.setStoreState=this.setStoreState.bind(this),this.getStoreState=this.getStoreState.bind(this),this.subscribe=this.subscribe.bind(this),this.unsubscribe=this.unsubscribe.bind(this),this.updateSubscribers=this.updateSubscribers.bind(this),this.subscribeMasterSpinner=this.subscribeMasterSpinner.bind(this),this.unsubscribeMasterSpinner=this.unsubscribeMasterSpinner.bind(this),this.unsubscribeAllMasterSpinner=this.unsubscribeAllMasterSpinner.bind(this),this.masterSpinnerSuccess=this.masterSpinnerSuccess.bind(this),this.masterSpinnerError=this.masterSpinnerError.bind(this)}return _createClass(e,[{key:"setStoreState",value:function(e,t){this.state=deepFreeze(deepmerge_1(this.state,e)),this.updateSubscribers(t)}},{key:"getStoreState",value:function(){return deepmerge_1({},this.state)}},{key:"subscribe",value:function(e){this.subscriptions.push(e)}},{key:"unsubscribe",value:function(e){var t=this.subscriptions.indexOf(e);-1!==t&&this.subscriptions.splice(t,1)}},{key:"updateSubscribers",value:function(e){this.subscriptions.forEach(function(e){return e()}),"function"==typeof e&&e(this.getStoreState())}},{key:"subscribeMasterSpinner",value:function(e){-1===Object.keys(this.masterSpinnerSubscriptions).indexOf(e)&&(this.masterSpinnerSubscriptions[e]={success:!1,error:!1,complete:!1})}},{key:"unsubscribeMasterSpinner",value:function(e){return-1!==Object.keys(this.masterSpinnerSubscriptions).indexOf(e)&&(this.setMasterSpinnerFinished(),delete this.masterSpinnerSubscriptions[e])}},{key:"unsubscribeAllMasterSpinner",value:function(){this.masterSpinnerSubscriptions={},this.setMasterSpinnerFinished()}},{key:"masterSpinnerSuccess",value:function(e){this.masterSpinnerSubscriptions[e].success=!0,this.masterSpinnerSubscriptions[e].complete=!0,this.setMasterSpinnerFinished()}},{key:"masterSpinnerError",value:function(e){this.masterSpinnerSubscriptions[e].error=!0,this.masterSpinnerSubscriptions[e].complete=!0,this.setMasterSpinnerFinished()}},{key:"setMasterSpinnerFinished",value:function(){this.setStoreState({masterSpinnerFinished:this.isMasterSpinnerFinished()})}},{key:"isMasterSpinnerFinished",value:function(){var e=this;return 0===Object.keys(this.masterSpinnerSubscriptions).filter(function(t){return!0!==e.masterSpinnerSubscriptions[t].complete}).length}}]),e}(),_excluded$1=["children","className","currentSlide","disableAnimation","disableKeyboard","hasMasterSpinner","interval","isPageScrollLocked","isPlaying","lockOnWindowScroll","naturalSlideHeight","naturalSlideWidth","orientation","playDirection","step","dragStep","tag","totalSlides","touchEnabled","dragEnabled","visibleSlides","infinite","isIntrinsicHeight"],_class,CarouselProvider=(_class=function(e){function t(e){var i;if(_classCallCheck(this,t),i=r.call(this,e),e.isIntrinsicHeight&&"horizontal"!==e.orientation)throw Error('isIntrinsicHeight can only be used in "horizontal" orientation. See Readme for more information.');var n={currentSlide:e.currentSlide,disableAnimation:e.disableAnimation,disableKeyboard:e.disableKeyboard,hasMasterSpinner:e.hasMasterSpinner,imageErrorCount:0,imageSuccessCount:0,interval:e.interval,isPageScrollLocked:e.isPageScrollLocked,isPlaying:e.isPlaying,lockOnWindowScroll:e.lockOnWindowScroll,masterSpinnerThreshold:0,naturalSlideHeight:e.naturalSlideHeight,naturalSlideWidth:e.naturalSlideWidth,orientation:e.orientation,playDirection:e.playDirection,privateUnDisableAnimation:!1,slideSize:slideSize(e.totalSlides,e.visibleSlides),slideTraySize:slideTraySize(e.totalSlides,e.visibleSlides),step:e.step,dragStep:e.dragStep,totalSlides:e.totalSlides,touchEnabled:e.touchEnabled,dragEnabled:e.dragEnabled,visibleSlides:e.visibleSlides,infinite:e.infinite,isIntrinsicHeight:e.isIntrinsicHeight};return i.carouselStore=new Store(n),i}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"componentDidUpdate",value:function(e){var t=this,r={};["currentSlide","disableAnimation","disableKeyboard","hasMasterSpinner","interval","isPlaying","naturalSlideHeight","naturalSlideWidth","lockOnWindowScroll","orientation","playDirection","step","dragStep","totalSlides","touchEnabled","dragEnabled","visibleSlides"].forEach(function(i){e[i]!==t.props[i]&&(r[i]=t.props[i])}),this.props.currentSlide!==e.currentSlide&&!this.props.disableAnimation&&(r.disableAnimation=!0,r.privateUnDisableAnimation=!0),this.props.totalSlides===e.totalSlides&&this.props.visibleSlides===e.visibleSlides||(r.slideSize=slideSize(this.props.totalSlides,this.props.visibleSlides),r.slideTraySize=slideTraySize(this.props.totalSlides,this.props.visibleSlides)),this.carouselStore.state.currentSlide>=this.props.totalSlides&&(r.currentSlide=Math.max(this.props.totalSlides-1,0)),Object.keys(r).length>0&&this.carouselStore.setStoreState(r)}},{key:"componentWillUnmount",value:function(){this.carouselStore.unsubscribeAllMasterSpinner()}},{key:"getStore",value:function(){return this.carouselStore}},{key:"render",value:function(){var e=this.props,t=(e.children,e.className,e.currentSlide,e.disableAnimation,e.disableKeyboard,e.hasMasterSpinner,e.interval,e.isPageScrollLocked,e.isPlaying,e.lockOnWindowScroll,e.naturalSlideHeight,e.naturalSlideWidth,e.orientation,e.playDirection,e.step,e.dragStep,e.tag),r=(e.totalSlides,e.touchEnabled,e.dragEnabled,e.visibleSlides,e.infinite,e.isIntrinsicHeight,_objectWithoutProperties(e,_excluded$1)),i=cn(["carousel",this.props.className]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(t,_extends({className:i},r),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Context.Provider,{value:this.carouselStore},this.props.children))}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().Component)),_defineProperty(_class,"propTypes",{children:CarouselPropTypes.children.isRequired,className:propTypes.string,currentSlide:propTypes.number,disableAnimation:propTypes.bool,disableKeyboard:propTypes.bool,hasMasterSpinner:propTypes.bool,interval:propTypes.number,isPageScrollLocked:propTypes.bool,isPlaying:propTypes.bool,lockOnWindowScroll:propTypes.bool,naturalSlideHeight:propTypes.number.isRequired,naturalSlideWidth:propTypes.number.isRequired,orientation:CarouselPropTypes.orientation,playDirection:CarouselPropTypes.direction,step:propTypes.number,dragStep:propTypes.number,tag:propTypes.string,totalSlides:propTypes.number.isRequired,touchEnabled:propTypes.bool,dragEnabled:propTypes.bool,visibleSlides:propTypes.number,infinite:propTypes.bool,isIntrinsicHeight:propTypes.bool}),_defineProperty(_class,"defaultProps",{className:null,currentSlide:0,disableAnimation:!1,disableKeyboard:!1,hasMasterSpinner:!1,interval:5e3,isPageScrollLocked:!1,isPlaying:!1,lockOnWindowScroll:!1,orientation:"horizontal",playDirection:"forward",step:1,dragStep:1,tag:"div",touchEnabled:!0,dragEnabled:!0,visibleSlides:1,infinite:!1,isIntrinsicHeight:!1}),_class);Context.Consumer;var index=WithStore(ButtonBack,function(e){return{currentSlide:e.currentSlide,step:e.step,totalSlides:e.totalSlides,visibleSlides:e.visibleSlides,infinite:e.infinite}}),s$1={buttonFirst:"buttonFirst___2rhFr"},_excluded$2=["carouselStore","className","currentSlide","disabled","onClick","totalSlides"],_class$1,ButtonFirst=(_class$1=function(e){function t(){var e;return _classCallCheck(this,t),e=r.call(this),e.handleOnClick=e.handleOnClick.bind(_assertThisInitialized(e)),e}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"handleOnClick",value:function(e){var t=this.props,r=t.carouselStore,i=t.onClick;r.setStoreState({currentSlide:0,isPlaying:!1},null!==i&&i.call(this,e))}},{key:"render",value:function(){var e=this.props,t=(e.carouselStore,e.className),r=e.currentSlide,i=e.disabled,n=(e.onClick,e.totalSlides,_objectWithoutProperties(e,_excluded$2)),s=cn([s$1.buttonFirst,"carousel__first-button",t]),o=null!==i?i:0===r;return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("button",_extends({type:"button","aria-label":"first",className:s,onClick:this.handleOnClick,disabled:o},n),this.props.children)}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().Component)),_defineProperty(_class$1,"propTypes",{carouselStore:propTypes.object.isRequired,children:CarouselPropTypes.children.isRequired,className:propTypes.string,currentSlide:propTypes.number.isRequired,disabled:propTypes.bool,onClick:propTypes.func,totalSlides:propTypes.number.isRequired}),_defineProperty(_class$1,"defaultProps",{className:null,disabled:null,onClick:null}),_class$1),index$1=WithStore(ButtonFirst,function(e){return{currentSlide:e.currentSlide,totalSlides:e.totalSlides}}),s$2={buttonNext:"buttonNext___2mOCa"},_excluded$3=["carouselStore","className","currentSlide","disabled","onClick","step","totalSlides","visibleSlides","infinite"],_class$2,ButtonNext=(_class$2=function(e){function t(e){var i;return _classCallCheck(this,t),i=r.call(this,e),i.handleOnClick=i.handleOnClick.bind(_assertThisInitialized(i)),i}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"handleOnClick",value:function(e){var t=this.props,r=t.currentSlide,i=t.onClick,n=t.step,s=t.carouselStore,o=t.infinite,a=t.totalSlides-t.visibleSlides,l=n+r,c=Math.min(l,a);o&&(c=a===r?0:c),s.setStoreState({currentSlide:c,isPlaying:!1},null!==i&&i.call(this,e))}},{key:"render",value:function(){var e=this.props,r=(e.carouselStore,e.className),i=e.currentSlide,n=e.disabled,s=(e.onClick,e.step,e.totalSlides),o=e.visibleSlides,a=e.infinite,l=_objectWithoutProperties(e,_excluded$3),c=cn([s$2.buttonNext,"carousel__next-button",r]),u=t.setDisabled(n,i,o,s,a);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("button",_extends({type:"button","aria-label":"next",className:c,onClick:this.handleOnClick,disabled:u},l),this.props.children)}}],[{key:"setDisabled",value:function(e,t,r,i,n){return null!==e?e:t>=i-r&&!n}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().PureComponent)),_defineProperty(_class$2,"propTypes",{carouselStore:propTypes.object.isRequired,children:CarouselPropTypes.children.isRequired,className:propTypes.string,currentSlide:propTypes.number.isRequired,disabled:propTypes.bool,onClick:propTypes.func,step:propTypes.number.isRequired,totalSlides:propTypes.number.isRequired,visibleSlides:propTypes.number.isRequired,infinite:propTypes.bool}),_defineProperty(_class$2,"defaultProps",{className:null,disabled:null,onClick:null,infinite:!1}),_class$2),index$2=WithStore(ButtonNext,function(e){return{currentSlide:e.currentSlide,step:e.step,totalSlides:e.totalSlides,visibleSlides:e.visibleSlides,infinite:e.infinite}}),s$3={buttonLast:"buttonLast___2yuh0"},_excluded$4=["carouselStore","className","currentSlide","disabled","onClick","totalSlides","visibleSlides"],_class$3,ButtonLast=(_class$3=function(e){function t(){var e;return _classCallCheck(this,t),e=r.call(this),e.handleOnClick=e.handleOnClick.bind(_assertThisInitialized(e)),e}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"handleOnClick",value:function(e){var t=this.props,r=t.carouselStore,i=t.onClick,n=t.totalSlides,s=t.visibleSlides;r.setStoreState({currentSlide:n-s,isPlaying:!1},null!==i&&i.call(this,e))}},{key:"render",value:function(){var e=this.props,t=(e.carouselStore,e.className),r=e.currentSlide,i=e.disabled,n=(e.onClick,e.totalSlides),s=e.visibleSlides,o=_objectWithoutProperties(e,_excluded$4),a=cn([s$3.buttonLast,"carousel__last-button",t]),l=null!==i?i:r>=n-s;return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("button",_extends({type:"button","aria-label":"last",className:a,onClick:this.handleOnClick,disabled:l},o),this.props.children)}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().Component)),_defineProperty(_class$3,"propTypes",{carouselStore:propTypes.object.isRequired,children:CarouselPropTypes.children.isRequired,className:propTypes.string,currentSlide:propTypes.number.isRequired,disabled:propTypes.bool,onClick:propTypes.func,totalSlides:propTypes.number.isRequired,visibleSlides:propTypes.number.isRequired}),_defineProperty(_class$3,"defaultProps",{className:null,disabled:null,onClick:null}),_class$3),index$3=WithStore(ButtonLast,function(e){return{currentSlide:e.currentSlide,totalSlides:e.totalSlides,visibleSlides:e.visibleSlides}}),s$4={buttonNext:"buttonNext___3Lm3s"},_excluded$5=["carouselStore","children","childrenPaused","childrenPlaying","className","isPlaying","onClick"],_class$4,ButtonPlay=(_class$4=function(e){function t(e){var i;return _classCallCheck(this,t),i=r.call(this,e),i.handleOnClick=i.handleOnClick.bind(_assertThisInitialized(i)),i}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"handleOnClick",value:function(e){var t=this.props.onClick;this.props.carouselStore.setStoreState({isPlaying:!this.props.isPlaying},null!==t&&t.call(this,e))}},{key:"render",value:function(){var e=this.props,t=(e.carouselStore,e.children,e.childrenPaused),r=e.childrenPlaying,i=e.className,n=e.isPlaying,s=(e.onClick,_objectWithoutProperties(e,_excluded$5)),o=cn([s$4.buttonNext,"carousel__play-button",i]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("button",_extends({type:"button","aria-label":"play",className:o,onClick:this.handleOnClick},s),n&&r,!n&&t,this.props.children)}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().PureComponent)),_defineProperty(_class$4,"propTypes",{carouselStore:propTypes.object.isRequired,children:propTypes.node,childrenPaused:propTypes.node,childrenPlaying:propTypes.node,className:propTypes.string,isPlaying:propTypes.bool.isRequired,onClick:propTypes.func}),_defineProperty(_class$4,"defaultProps",{children:null,childrenPaused:null,childrenPlaying:null,className:null,onClick:null}),_class$4),index$4=WithStore(ButtonPlay,function(e){return{isPlaying:e.isPlaying}}),s$5={dot:"dot___3c3SI"},_excluded$6=["carouselStore","children","className","currentSlide","disabled","onClick","selected","slide","totalSlides","visibleSlides"],_class$5,Dot=(_class$5=function(e){function t(e){var i;return _classCallCheck(this,t),i=r.call(this,e),i.handleOnClick=i.handleOnClick.bind(_assertThisInitialized(i)),i}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"handleOnClick",value:function(e){var t=this.props,r=t.carouselStore,i=t.onClick,n=t.slide,s=t.totalSlides,o=t.visibleSlides,a=n>=s-o?s-o:n;r.setStoreState({currentSlide:a,isPlaying:!1},null!==i&&i.call(this,e))}},{key:"render",value:function(){var e=this.props,t=(e.carouselStore,e.children,e.className),r=e.currentSlide,i=e.disabled,n=(e.onClick,e.selected),s=e.slide,o=(e.totalSlides,e.visibleSlides),a=_objectWithoutProperties(e,_excluded$6),l=s>=r&&s<r+o,c="boolean"==typeof n?n:l,u=!0===l,p="boolean"==typeof i?i:u,d=cn([s$5.dot,c&&s$5.dotSelected,"carousel__dot","carousel__dot--".concat(s),c&&"carousel__dot--selected",t]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("button",_extends({"aria-label":"slide dot",type:"button",onClick:this.handleOnClick,className:d,disabled:p},a),this.props.children)}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().Component)),_defineProperty(_class$5,"propTypes",{carouselStore:propTypes.object.isRequired,children:CarouselPropTypes.children,className:propTypes.string,currentSlide:propTypes.number.isRequired,disabled:propTypes.bool,onClick:propTypes.func,selected:propTypes.bool,slide:propTypes.number.isRequired,totalSlides:propTypes.number.isRequired,visibleSlides:propTypes.number.isRequired}),_defineProperty(_class$5,"defaultProps",{children:null,className:null,disabled:null,onClick:null,selected:null}),_class$5),Dot$1=WithStore(Dot,function(e){return{currentSlide:e.currentSlide,totalSlides:e.totalSlides,visibleSlides:e.visibleSlides}}),s$6={},_excluded$7=["renderDots"],_excluded2=["carouselStore","children","className","currentSlide","dotNumbers","totalSlides","visibleSlides","disableActiveDots","showAsSelectedForCurrentSlideOnly","renderDots"],_class$6,DotGroup=(_class$6=function(e){function t(){return _classCallCheck(this,t),r.apply(this,arguments)}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"renderDots",value:function(){var e=this.props,t=e.currentSlide,r=e.totalSlides,i=e.visibleSlides,n=e.disableActiveDots,s=e.showAsSelectedForCurrentSlideOnly,o=e.renderDots;if(o){var a=this.props;a.renderDots;return o(_objectWithoutProperties(a,_excluded$7))}for(var l=[],c=0;c<r;c+=1){var u=c>=t&&c<t+i,p=c===t,d=s?p:u,h=c>=r-i?r-i:c;l.push(react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Dot$1,{key:c,slide:h,selected:d,disabled:!!n&&d},react__WEBPACK_IMPORTED_MODULE_0___default().createElement("span",{className:cn["carousel__dot-group-dot"]},this.props.dotNumbers&&c+1)))}return l}},{key:"render",value:function(){var e=this.props,t=(e.carouselStore,e.children),r=e.className,i=(e.currentSlide,e.dotNumbers,e.totalSlides,e.visibleSlides,e.disableActiveDots,e.showAsSelectedForCurrentSlideOnly,e.renderDots,_objectWithoutProperties(e,_excluded2)),n=cn([s$6.DotGroup,"carousel__dot-group",r]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",_extends({className:n},i),this.renderDots(),t)}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().Component)),_defineProperty(_class$6,"propTypes",{children:CarouselPropTypes.children,className:propTypes.string,currentSlide:propTypes.number.isRequired,carouselStore:propTypes.object.isRequired,totalSlides:propTypes.number.isRequired,visibleSlides:propTypes.number.isRequired,dotNumbers:propTypes.bool,disableActiveDots:propTypes.bool,showAsSelectedForCurrentSlideOnly:propTypes.bool,renderDots:propTypes.func}),_defineProperty(_class$6,"defaultProps",{children:null,className:null,dotNumbers:!1,disableActiveDots:!0,showAsSelectedForCurrentSlideOnly:!1,renderDots:null}),_class$6),index$5=WithStore(DotGroup,function(e){return{currentSlide:e.currentSlide,totalSlides:e.totalSlides,visibleSlides:e.visibleSlides}}),s$7={image:"image___xtQGH"},_excluded$8=["src","alt"],_excluded2$1=["carouselStore","children","className","hasMasterSpinner","isBgImage","onError","onLoad","renderError","renderLoading","style","tag"],Image=function(e){function t(e){var i;return _classCallCheck(this,t),i=r.call(this,e),i.state={imageStatus:LOADING},i.handleImageLoad=i.handleImageLoad.bind(_assertThisInitialized(i)),i.handleImageError=i.handleImageError.bind(_assertThisInitialized(i)),i.image=null,i}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"componentDidMount",value:function(){t.subscribeMasterSpinner(this.props),this.initImage()}},{key:"componentDidUpdate",value:function(e){e.src!==this.props.src&&(t.unsubscribeMasterSpinner(e),t.subscribeMasterSpinner(this.props),this.initImage())}},{key:"componentWillUnmount",value:function(){t.unsubscribeMasterSpinner(this.props),this.image.removeEventListener("load",this.handleImageLoad),this.image.removeEventListener("error",this.handleImageError),this.image=null}},{key:"initImage",value:function(){if(this.setState({imageStatus:LOADING}),this.image=document.createElement("img"),this.image.addEventListener("load",this.handleImageLoad,!1),this.image.addEventListener("error",this.handleImageError,!1),this.image.src=this.props.src,this.image.readyState||this.image.complete){var e=this.image.src;this.image.src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",this.image.src=e}}},{key:"handleImageLoad",value:function(e){this.setState({imageStatus:SUCCESS}),this.props.hasMasterSpinner&&this.props.carouselStore.masterSpinnerSuccess(this.props.src),this.props.onLoad&&this.props.onLoad(e)}},{key:"handleImageError",value:function(e){this.setState({imageStatus:ERROR}),this.props.hasMasterSpinner&&this.props.carouselStore.masterSpinnerError(this.props.src),this.props.onError&&this.props.onError(e)}},{key:"tempTag",value:function(){return"img"===this.props.tag?"div":this.props.tag}},{key:"customRender",value:function(e){return"function"==typeof this.props[e]?this.props[e]():this.props.children}},{key:"renderLoading",value:function(e){var t=this.tempTag(),r=cn([s$7.image,s$7.imageLoading,"carousel__image",this.props.isBgImage&&"carousel__image--with-background","carousel__image--loading",this.props.className]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(t,_extends({className:r},e),this.customRender("renderLoading"))}},{key:"renderError",value:function(e){var t=this.tempTag(),r=cn([s$7.image,s$7.imageError,"carousel__image",this.props.isBgImage&&"carousel__image--with-background","carousel__image--error",this.props.className]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(t,_extends({className:r},e),this.customRender("renderError"))}},{key:"renderSuccess",value:function(e){var t=this.props,r=t.style,i=t.tag,n=cn([s$7.image,"carousel__image",this.props.isBgImage&&"carousel__image--with-background","carousel__image--success",this.props.className]),s=_extends({},r),o=e;if("img"!==i){var a=e.src;e.alt;o=_objectWithoutProperties(e,_excluded$8),s=_extends({},r,{backgroundImage:'url("'.concat(a,'")'),backgroundSize:"cover"})}return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(i,_extends({className:n,style:s},o),this.props.children)}},{key:"render",value:function(){var e=this.props,t=(e.carouselStore,e.children,e.className,e.hasMasterSpinner,e.isBgImage,e.onError,e.onLoad,e.renderError,e.renderLoading,e.style,e.tag,_objectWithoutProperties(e,_excluded2$1));switch(this.state.imageStatus){case LOADING:return this.renderLoading(t);case SUCCESS:return this.renderSuccess(t);case ERROR:return this.renderError(t);default:throw new Error("unknown value for this.state.imageStatus")}}}],[{key:"subscribeMasterSpinner",value:function(e){e.hasMasterSpinner&&e.carouselStore.subscribeMasterSpinner(e.src)}},{key:"unsubscribeMasterSpinner",value:function(e){e.hasMasterSpinner&&e.carouselStore.unsubscribeMasterSpinner(e.src)}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().Component));_defineProperty(Image,"propTypes",{alt:propTypes.string,carouselStore:propTypes.object.isRequired,children:CarouselPropTypes.children,className:propTypes.string,hasMasterSpinner:propTypes.bool.isRequired,isBgImage:CarouselPropTypes.isBgImage,onError:propTypes.func,onLoad:propTypes.func,renderError:propTypes.func,renderLoading:propTypes.func,src:propTypes.string.isRequired,style:propTypes.object,tag:propTypes.string}),_defineProperty(Image,"defaultProps",{alt:"",children:null,className:null,isBgImage:!1,onError:null,onLoad:null,renderError:null,renderLoading:null,style:null,tag:"img"});var Image$1=WithStore(Image,function(e){return{hasMasterSpinner:e.hasMasterSpinner,orientation:e.orientation}}),s$8={spinner:"spinner___27VUp",spin:"spin___S3UuE"},_excluded$9=["className"],_class$7,Spinner=(_class$7=function(e){function t(){return _classCallCheck(this,t),r.apply(this,arguments)}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"render",value:function(){var e=this.props,t=e.className,r=_objectWithoutProperties(e,_excluded$9),i=cn([s$8.spinner,"carousel__spinner",t]);return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",_extends({className:i},r))}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().PureComponent)),_defineProperty(_class$7,"propTypes",{className:propTypes.string}),_defineProperty(_class$7,"defaultProps",{className:null}),_class$7),s$9={container:"container___2O72F",overlay:"overlay___IV4qY",hover:"hover___MYy31",zoom:"zoom___3kqYk",loading:"loading___1pvNI",imageLoadingSpinnerContainer:"imageLoadingSpinnerContainer___3UIPD"},_excluded$a=["alt","bgImageProps","bgImageTag","carouselStore","className","imageClassName","overlayClassName","isPinchZoomEnabled","spinner","src","srcZoomed","tag"],_class$8,MOUSE_SCALE=2,MAX_TOUCH_SCALE=3,ImageWithZoom=(_class$8=function(e){function t(e){var i;return _classCallCheck(this,t),i=r.call(this,e),i.state={isImageLoading:!0,isHovering:!1,isZooming:!1,x:null,y:null,scale:1},i.tpCache={},i.handleImageComplete=i.handleImageComplete.bind(_assertThisInitialized(i)),i.handleOnMouseMove=i.handleOnMouseMove.bind(_assertThisInitialized(i)),i.handleOnMouseOut=i.handleOnMouseOut.bind(_assertThisInitialized(i)),i.handleOnMouseOver=i.handleOnMouseOver.bind(_assertThisInitialized(i)),i.handleOnTouchEnd=i.handleOnTouchEnd.bind(_assertThisInitialized(i)),i.handleOnTouchMove=i.handleOnTouchMove.bind(_assertThisInitialized(i)),i.handleOnTouchStart=i.handleOnTouchStart.bind(_assertThisInitialized(i)),i}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"componentDidUpdate",value:function(e,t){!1===t.isZooming&&!0===this.state.isZooming&&this.props.carouselStore.setStoreState({isPageScrollLocked:!0}),!0===t.isZooming&&!1===this.state.isZooming&&this.props.carouselStore.setStoreState({isPageScrollLocked:!1})}},{key:"handleImageComplete",value:function(){this.setState({isImageLoading:!1})}},{key:"handleOnMouseOver",value:function(){this.state.isZooming||this.setState({isHovering:!0,scale:MOUSE_SCALE})}},{key:"handleOnMouseOut",value:function(){this.state.isZooming||this.setState({isHovering:!1,scale:1})}},{key:"handleOnMouseMove",value:function(e){if(!this.state.isZooming){var t=pct(e.nativeEvent.offsetX/e.target.offsetWidth*100),r=pct(e.nativeEvent.offsetY/e.target.offsetHeight*100);this.setState({x:t,y:r})}}},{key:"handleOnTouchStart",value:function(e){var t=this;this.props.isPinchZoomEnabled&&(_toConsumableArray(e.targetTouches).forEach(function(e){t.tpCache[e.identifier]={clientX:e.clientX,clientY:e.clientY}}),this.setState(function(e){return{isZooming:e.isZooming||Object.keys(t.tpCache).length>1}}))}},{key:"handleOnTouchMove",value:function(e){var r=this;if(this.state.isZooming){e.persist();var i=_toConsumableArray(e.targetTouches).filter(function(e){return r.tpCache[e.identifier]}).slice(0,2);if(2===i.length){e.stopPropagation();var n=e.target.getBoundingClientRect(),s=i[0].identifier,o=i[1].identifier,a={x1:this.tpCache[s].clientX,y1:this.tpCache[s].clientY,x2:this.tpCache[o].clientX,y2:this.tpCache[o].clientY};a.distance=t.distanceBetweenTwoTouches(_objectSpread2({},a));var l=t.midpointBetweenTwoTouches(_objectSpread2({},a));a.cx=l.x,a.cy=l.y;var c={x1:i[0].clientX,y1:i[0].clientY,x2:i[1].clientX,y2:i[1].clientY};c.distance=t.distanceBetweenTwoTouches(_objectSpread2({},c));var u=t.midpointBetweenTwoTouches(_objectSpread2({},c));c.cx=u.x,c.cy=u.y;var p=pct(boundedRange({min:0,max:100,x:(c.cx-n.left)/n.width*100})),d=pct(boundedRange({min:0,max:100,x:(c.cy-n.top)/n.height*100})),h=function(e){return boundedRange({min:1,max:MAX_TOUCH_SCALE,x:e.scale+(c.distance-a.distance)/100})};this.setState(function(e){return{isZooming:1!==h(e),scale:h(e),x:p,y:d}})}}}},{key:"handleOnTouchEnd",value:function(e){var t=this;this.props.isPinchZoomEnabled&&(_toConsumableArray(e.changedTouches).forEach(function(e){delete t.tpCache[e.identifier]}),0===Object.keys(this.tpCache).length&&this.setState({isZooming:!1}))}},{key:"renderLoading",value:function(){if(this.state.isImageLoading){var e=this.props.spinner;return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:cn([s$9.imageLoadingSpinnerContainer,"carousel__image-loading-spinner-container"])},e&&e(),!e&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Spinner,null))}return null}},{key:"render",value:function(){var e=this.props,t=e.alt,r=e.bgImageProps,i=e.bgImageTag,n=(e.carouselStore,e.className),s=e.imageClassName,o=e.overlayClassName,a=(e.isPinchZoomEnabled,e.spinner,e.src),l=e.srcZoomed,c=e.tag,u=_objectWithoutProperties(e,_excluded$a),p=cn([s$9.container,n]),d=cn([s$9.image,"carousel__zoom-image",s]),h=cn([s$9.overlay,"carousel__zoom-image-overlay",this.state.isHovering&&s$9.hover,this.state.isZooming&&s$9.zoom,this.state.isHovering&&"carousel__zoom-image-overlay--hovering",this.state.isZooming&&"carousel__zoom-image-overlay--zooming",o]),S={};return(this.state.isHovering||this.state.isZooming)&&(S.transformOrigin="".concat(this.state.x," ").concat(this.state.y),S.transform="scale(".concat(this.state.scale,")")),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(c,_extends({className:p},u),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Image$1,_extends({alt:t,className:d,tag:i,src:a,onLoad:this.handleImageComplete,onError:this.handleImageComplete},r)),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Image$1,{className:h,tag:"div",src:l||a,style:S,isBgImage:!0,onFocus:this.handleOnMouseOver,onMouseOver:this.handleOnMouseOver,onBlur:this.handleOnMouseOut,onMouseOut:this.handleOnMouseOut,onMouseMove:this.handleOnMouseMove,onTouchStart:this.handleOnTouchStart,onTouchEnd:this.handleOnTouchEnd,onTouchMove:this.handleOnTouchMove}),this.renderLoading())}}],[{key:"midpointBetweenTwoTouches",value:function(e){var t=e.x1,r=e.y1;return{x:(t+e.x2)/2,y:(r+e.y2)/2}}},{key:"distanceBetweenTwoTouches",value:function(e){var t=e.x1,r=e.y1,i=e.x2,n=e.y2;return Math.sqrt(Math.pow(i-t,2)+Math.pow(n-r,2))}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().Component)),_defineProperty(_class$8,"propTypes",{alt:propTypes.string,bgImageProps:propTypes.object,bgImageTag:propTypes.string,carouselStore:propTypes.object.isRequired,className:propTypes.string,imageClassName:propTypes.string,overlayClassName:propTypes.string,spinner:propTypes.func,src:propTypes.string.isRequired,srcZoomed:propTypes.string,tag:propTypes.string,isPinchZoomEnabled:propTypes.bool}),_defineProperty(_class$8,"defaultProps",{alt:void 0,bgImageProps:{},bgImageTag:"div",className:null,imageClassName:null,overlayClassName:null,isPinchZoomEnabled:!0,spinner:null,srcZoomed:null,tag:"div"}),_class$8),index$6=WithStore(ImageWithZoom,function(){return{}}),s$a={slide:"slide___3-Nqo",slideHorizontal:"slideHorizontal___1NzNV",slideInner:"slideInner___2mfX9",focusRing:"focusRing___1airF"},_excluded$b=["ariaLabel","carouselStore","children","className","classNameHidden","classNameVisible","currentSlide","index","innerClassName","innerTag","naturalSlideHeight","naturalSlideWidth","onBlur","onFocus","orientation","slideSize","style","tabIndex","tag","totalSlides","visibleSlides","isIntrinsicHeight"],_class$9,Slide=(_class$9=function(e){function t(e){var i;return _classCallCheck(this,t),i=r.call(this,e),i.handleOnFocus=i.handleOnFocus.bind(_assertThisInitialized(i)),i.handleOnBlur=i.handleOnBlur.bind(_assertThisInitialized(i)),i.state={focused:!1},i}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"isVisible",value:function(){var e=this.props,t=e.currentSlide,r=e.index,i=e.visibleSlides;return r>=t&&r<t+i}},{key:"handleOnFocus",value:function(e){var t=this,r=this.props.onFocus;this.setState({focused:!0},function(){null!==r&&r.call(t,e)})}},{key:"handleOnBlur",value:function(e){var t=this,r=this.props.onBlur;this.setState({focused:!1},function(){null!==r&&r.call(t,e)})}},{key:"renderFocusRing",value:function(){return this.state.focused?react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:[s$a.focusRing,"carousel__slide-focus-ring"].join(" ")}):null}},{key:"render",value:function(){var e=this,t=this.props,r=t.ariaLabel,i=(t.carouselStore,t.children,t.className),n=t.classNameHidden,s=t.classNameVisible,o=(t.currentSlide,t.index,t.innerClassName),a=t.innerTag,l=t.naturalSlideHeight,c=t.naturalSlideWidth,u=(t.onBlur,t.onFocus,t.orientation),p=t.slideSize,d=t.style,h=t.tabIndex,S=t.tag,y=t.totalSlides,b=(t.visibleSlides,t.isIntrinsicHeight),m=_objectWithoutProperties(t,_excluded$b),f={};"horizontal"===u?(f.width=pct(p),f.paddingBottom=pct(100*l/(c*y))):(f.width=pct(100),f.paddingBottom=pct(100*l/c));var g={};b&&("horizontal"===u?f.height="unset":f.width="unset",f.paddingBottom="unset",g.position="unset");var v=_extends({},f,d),_=this.isVisible(),T=cn([s$a.slide,"horizontal"===u&&s$a.slideHorizontal,"carousel__slide",this.state.focused&&"carousel__slide--focused",_&&s,_&&"carousel__slide--visible",!_&&n,!_&&"carousel__slide--hidden",i]),k=cn([s$a.slideInner,"carousel__inner-slide",o]),C=this.isVisible()?0:-1,O="number"==typeof h?h:C;return react__WEBPACK_IMPORTED_MODULE_0___default().createElement(S,_extends({ref:function(t){e.tagRef=t},tabIndex:O,"aria-selected":this.isVisible(),"aria-label":r,role:"option",onFocus:this.handleOnFocus,onBlur:this.handleOnBlur,className:T,style:v},m),react__WEBPACK_IMPORTED_MODULE_0___default().createElement(a,{ref:function(t){e.innerTagRef=t},className:k,style:g},this.props.children,this.renderFocusRing()))}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().PureComponent)),_defineProperty(_class$9,"propTypes",{ariaLabel:propTypes.string,carouselStore:propTypes.object,children:CarouselPropTypes.children,className:propTypes.string,classNameHidden:propTypes.string,classNameVisible:propTypes.string,currentSlide:propTypes.number.isRequired,index:propTypes.number.isRequired,innerClassName:propTypes.string,innerTag:propTypes.string,naturalSlideHeight:propTypes.number.isRequired,naturalSlideWidth:propTypes.number.isRequired,onBlur:propTypes.func,onFocus:propTypes.func,orientation:CarouselPropTypes.orientation.isRequired,slideSize:propTypes.number.isRequired,style:propTypes.object,tabIndex:propTypes.number,tag:propTypes.string,totalSlides:propTypes.number.isRequired,visibleSlides:propTypes.number.isRequired,isIntrinsicHeight:propTypes.bool}),_defineProperty(_class$9,"defaultProps",{ariaLabel:"slide",carouselStore:null,children:null,className:null,classNameHidden:null,classNameVisible:null,innerClassName:null,innerTag:"div",onBlur:null,onFocus:null,style:{},tabIndex:null,tag:"div",isIntrinsicHeight:!1}),_class$9),index$7=WithStore(Slide,function(e){return{currentSlide:e.currentSlide,naturalSlideHeight:e.naturalSlideHeight,naturalSlideWidth:e.naturalSlideWidth,orientation:e.orientation,slideSize:e.slideSize,totalSlides:e.totalSlides,visibleSlides:e.visibleSlides,isIntrinsicHeight:e.isIntrinsicHeight}}),GetScrollParent=function(){function e(){_classCallCheck(this,e)}return _createClass(e,[{key:"parents",value:function(e,t){return null===e.parentNode?t:this.parents(e.parentNode,t.concat([e]))}},{key:"scrollParent",value:function(t){for(var r=this.parents(t.parentNode,[]),i=0;i<r.length;i+=1)if(e.scroll(r[i]))return r[i];return document.scrollingElement||document.documentElement}},{key:"getScrollParent",value:function(t){return e.isNodeValid(t)?this.scrollParent(t):null}}],[{key:"style",value:function(e,t){return getComputedStyle(e,null).getPropertyValue(t)}},{key:"overflow",value:function(t){return e.style(t,"overflow")+e.style(t,"overflow-y")+e.style(t,"overflow-x")}},{key:"scroll",value:function(t){return/(auto|scroll)/.test(e.overflow(t))}},{key:"isNodeValid",value:function(e){return e instanceof HTMLElement||e instanceof SVGElement}}]),e}(),s$b={horizontalSlider:"horizontalSlider___281Ls",horizontalSliderTray:"horizontalSliderTray___1L-0W",verticalSlider:"verticalSlider___34ZFD",verticalSliderTray:"verticalSliderTray___267D8",verticalTray:"verticalTray___12Key",verticalSlideTrayWrap:"verticalSlideTrayWrap___2nO7o",sliderTray:"sliderTray___-vHFQ",sliderAnimation:"sliderAnimation___300FY",masterSpinnerContainer:"masterSpinnerContainer___1Z6hB"},_excluded$c=["ariaLabel","carouselStore","children","className","classNameAnimation","classNameTray","classNameTrayWrap","currentSlide","disableAnimation","disableKeyboard","dragEnabled","hasMasterSpinner","interval","isPageScrollLocked","isPlaying","lockOnWindowScroll","masterSpinnerFinished","moveThreshold","naturalSlideHeight","naturalSlideWidth","onMasterSpinner","orientation","playDirection","privateUnDisableAnimation","slideSize","slideTraySize","spinner","style","tabIndex","totalSlides","touchEnabled","trayProps","trayTag","visibleSlides","isIntrinsicHeight"],_excluded2$2=["dragStep","step","infinite","preventVerticalScrollOnTouch","preventingVerticalScroll","horizontalPixelThreshold","verticalPixelThreshold"],_excluded3=["className","onClickCapture","onMouseDown","onTouchCancel","onTouchEnd","onTouchMove","onTouchStart","ref","style"],_class$a,Slider=(_class$a=function(e){function t(e){var i;return _classCallCheck(this,t),i=r.call(this,e),i.getSliderRef=i.getSliderRef.bind(_assertThisInitialized(i)),i.handleDocumentScroll=i.handleDocumentScroll.bind(_assertThisInitialized(i)),i.handleOnClickCapture=i.handleOnClickCapture.bind(_assertThisInitialized(i)),i.handleOnKeyDown=i.handleOnKeyDown.bind(_assertThisInitialized(i)),i.handleOnMouseDown=i.handleOnMouseDown.bind(_assertThisInitialized(i)),i.handleOnMouseMove=i.handleOnMouseMove.bind(_assertThisInitialized(i)),i.handleOnMouseUp=i.handleOnMouseUp.bind(_assertThisInitialized(i)),i.handleOnTouchCancel=i.handleOnTouchCancel.bind(_assertThisInitialized(i)),i.handleOnTouchEnd=i.handleOnTouchEnd.bind(_assertThisInitialized(i)),i.handleOnTouchMove=i.handleOnTouchMove.bind(_assertThisInitialized(i)),i.handleOnTouchStart=i.handleOnTouchStart.bind(_assertThisInitialized(i)),i.playBackward=i.playBackward.bind(_assertThisInitialized(i)),i.playForward=i.playForward.bind(_assertThisInitialized(i)),i.callCallback=i.callCallback.bind(_assertThisInitialized(i)),i.blockWindowScroll=i.blockWindowScroll.bind(_assertThisInitialized(i)),i.state={cancelNextClick:!1,deltaX:0,deltaY:0,isBeingMouseDragged:!1,isBeingTouchDragged:!1,preventingVerticalScroll:!1,startX:0,startY:0},i.interval=null,i.isDocumentScrolling=null,i.moveTimer=null,i.originalOverflow=null,i.scrollParent=null,i.scrollStopTimer=null,i}_inherits(t,e);var r=_createSuper(t);return _createClass(t,[{key:"componentDidMount",value:function(){this.props.lockOnWindowScroll&&window.addEventListener("scroll",this.handleDocumentScroll,!1),(this.props.touchEnabled||this.props.preventVerticalScrollOnTouch)&&window.addEventListener("touchmove",this.blockWindowScroll,!1),document.documentElement.addEventListener("mouseleave",this.handleOnMouseUp,!1),document.documentElement.addEventListener("mousemove",this.handleOnMouseMove,!1),document.documentElement.addEventListener("mouseup",this.handleOnMouseUp,!1),this.props.isPlaying&&this.play()}},{key:"componentDidUpdate",value:function(e){!e.isPlaying&&this.props.isPlaying&&this.play(),e.isPlaying&&!this.props.isPlaying&&this.stop(),!e.isPageScrollLocked&&this.props.isPageScrollLocked&&this.lockScroll(),e.isPageScrollLocked&&!this.props.isPageScrollLocked&&this.unlockScroll(),!1===e.privateUnDisableAnimation&&!0===this.props.privateUnDisableAnimation&&this.props.carouselStore.setStoreState({privateUnDisableAnimation:!1,disableAnimation:!1})}},{key:"componentWillUnmount",value:function(){document.documentElement.removeEventListener("mouseleave",this.handleOnMouseUp,!1),document.documentElement.removeEventListener("mousemove",this.handleOnMouseMove,!1),document.documentElement.removeEventListener("mouseup",this.handleOnMouseUp,!1),window.removeEventListener("scroll",this.handleDocumentScroll,!1),window.removeEventListener("touchmove",this.blockWindowScroll,!1),this.stop(),window.cancelAnimationFrame.call(window,this.moveTimer),window.clearTimeout(this.scrollStopTimer),this.isDocumentScrolling=null,this.moveTimer=null,this.scrollStopTimer=null}},{key:"getSliderRef",value:function(e){this.sliderTrayElement=e}},{key:"fakeOnDragStart",value:function(e){var t=e.screenX,r=e.screenY,i=e.touchDrag,n=void 0!==i&&i,s=e.mouseDrag,o=void 0!==s&&s;this.props.carouselStore.setStoreState({isPlaying:!1}),window.cancelAnimationFrame.call(window,this.moveTimer),"vertical"===this.props.orientation&&this.props.carouselStore.setStoreState({isPageScrollLocked:!0}),this.setState({isBeingTouchDragged:n,isBeingMouseDragged:o,startX:t,startY:r})}},{key:"fakeOnDragMove",value:function(e,t){var r=this;this.moveTimer=window.requestAnimationFrame.call(window,function(){r.setState(function(i){return{deltaX:e-i.startX,deltaY:t-i.startY,preventingVerticalScroll:Math.abs(t-i.startY)<=r.props.verticalPixelThreshold&&Math.abs(e-i.startX)>=r.props.horizontalPixelThreshold}})})}},{key:"fakeOnDragEnd",value:function(){window.cancelAnimationFrame.call(window,this.moveTimer),this.computeCurrentSlide(),"vertical"===this.props.orientation&&this.props.carouselStore.setStoreState({isPageScrollLocked:!1}),this.setState({deltaX:0,deltaY:0,isBeingTouchDragged:!1,isBeingMouseDragged:!1}),this.isDocumentScrolling=!this.props.lockOnWindowScroll&&null}},{key:"callCallback",value:function(e,t){var r=this.props.trayProps;r&&"function"==typeof r[e]&&(t.persist(),r[e](t))}},{key:"handleOnMouseDown",value:function(e){if(!this.props.dragEnabled)return void this.callCallback("onMouseDown",e);e.preventDefault(),this.fakeOnDragStart({screenX:e.screenX,screenY:e.screenY,mouseDrag:!0}),this.callCallback("onMouseDown",e)}},{key:"handleOnMouseMove",value:function(e){this.state.isBeingMouseDragged&&(this.setState({cancelNextClick:!0}),e.preventDefault(),this.fakeOnDragMove(e.screenX,e.screenY))}},{key:"handleOnMouseUp",value:function(e){this.state.isBeingMouseDragged&&(e.preventDefault(),this.fakeOnDragEnd())}},{key:"handleOnClickCapture",value:function(e){if(!this.state.cancelNextClick)return void this.callCallback("onClickCapture",e);e.preventDefault(),this.setState({cancelNextClick:!1}),this.callCallback("onClickCapture",e)}},{key:"handleOnTouchStart",value:function(e){if(!this.props.touchEnabled)return void this.callCallback("onTouchStart",e);"vertical"===this.props.orientation&&e.preventDefault();var t=e.targetTouches[0];this.fakeOnDragStart({screenX:t.screenX,screenY:t.screenY,touchDrag:!0}),this.callCallback("onTouchStart",e)}},{key:"handleDocumentScroll",value:function(){var e=this;this.props.touchEnabled&&(this.isDocumentScrolling=!0,window.clearTimeout(this.scrollStopTimer),this.scrollStopTimer=window.setTimeout(function(){e.isDocumentScrolling=!1},66))}},{key:"handleOnTouchMove",value:function(e){if(!this.props.touchEnabled||this.props.lockOnWindowScroll&&this.isDocumentScrolling)return void this.callCallback("onTouchMove",e);window.cancelAnimationFrame.call(window,this.moveTimer);var t=e.targetTouches[0];t&&(this.fakeOnDragMove(t.screenX,t.screenY),this.callCallback("onTouchMove",e))}},{key:"forward",value:function(){var e=this.props,t=e.currentSlide,r=e.step,i=e.totalSlides,n=e.visibleSlides;return Math.min(t+r,i-n)}},{key:"backward",value:function(){var e=this.props,t=e.currentSlide,r=e.step;return Math.max(t-r,0)}},{key:"handleOnKeyDown",value:function(e){var t=e.keyCode,r=this.props,i=r.carouselStore,n=r.currentSlide,s=r.disableKeyboard,o=r.totalSlides,a=r.visibleSlides,l={};!0===s||o<=a||(37===t&&(e.preventDefault(),this.focus(),l.currentSlide=Math.max(0,n-1),l.isPlaying=!1),39===t&&(e.preventDefault(),this.focus(),l.currentSlide=Math.min(o-a,n+1),l.isPlaying=!1),i.setStoreState(l))}},{key:"playForward",value:function(){var e=this.props,t=e.carouselStore,r=e.currentSlide;t.setStoreState({currentSlide:this.forward()===r?0:this.forward()})}},{key:"playBackward",value:function(){var e=this.props,t=e.carouselStore,r=e.currentSlide,i=e.totalSlides,n=e.visibleSlides;t.setStoreState({currentSlide:this.backward()===r?i-n:this.backward()})}},{key:"play",value:function(){var e=this.props.playDirection;this.interval=setInterval("forward"===e?this.playForward:this.playBackward,this.props.interval)}},{key:"stop",value:function(){window.clearInterval(this.interval),this.interval=null}},{key:"lockScroll",value:function(){var e=new GetScrollParent;this.scrollParent=e.getScrollParent(this.sliderTrayElement),this.scrollParent&&(this.originalOverflow=this.originalOverflow||this.scrollParent.style.overflow,this.scrollParent.style.overflow="hidden")}},{key:"unlockScroll",value:function(){this.scrollParent&&(this.scrollParent.style.overflow=this.originalOverflow,this.originalOverflow=null,this.scrollParent=null)}},{key:"blockWindowScroll",value:function(e){this.state.preventingVerticalScroll&&(e.preventDefault(),e.stopImmediatePropagation())}},{key:"computeCurrentSlide",value:function(){var e=t.slideSizeInPx(this.props.orientation,this.sliderTrayElement.clientWidth,this.sliderTrayElement.clientHeight,this.props.totalSlides),r=t.slidesMoved(this.props.moveThreshold,this.props.orientation,this.state.deltaX,this.state.deltaY,e,this.props.dragStep),i=this.props.totalSlides-Math.min(this.props.totalSlides,this.props.visibleSlides),n=boundedRange({min:0,max:i,x:this.props.currentSlide+r});this.props.infinite&&(this.props.currentSlide>=i&&r>0&&(n=0),0===this.props.currentSlide&&r<0&&(n=i)),this.props.carouselStore.setStoreState({currentSlide:n})}},{key:"focus",value:function(){this.sliderElement.focus()}},{key:"handleOnTouchEnd",value:function(e){this.endTouchMove(),this.callCallback("onTouchEnd",e)}},{key:"handleOnTouchCancel",value:function(e){this.endTouchMove(),this.callCallback("onTouchCancel",e)}},{key:"endTouchMove",value:function(){this.props.touchEnabled&&this.fakeOnDragEnd()}},{key:"renderMasterSpinner",value:function(){var e=this.props,t=e.hasMasterSpinner,r=e.masterSpinnerFinished,i=e.spinner;return t&&!r?("function"==typeof this.props.onMasterSpinner&&this.props.onMasterSpinner(),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:cn([s$b.masterSpinnerContainer,"carousel__master-spinner-container"])},i&&i(),!i&&react__WEBPACK_IMPORTED_MODULE_0___default().createElement(Spinner,null))):null}},{key:"render",value:function(){var e=this,t=this.props,r=t.ariaLabel,i=(t.carouselStore,t.children),n=t.className,s=t.classNameAnimation,o=t.classNameTray,a=t.classNameTrayWrap,l=t.currentSlide,c=t.disableAnimation,u=(t.disableKeyboard,t.dragEnabled,t.hasMasterSpinner,t.interval,t.isPageScrollLocked,t.isPlaying,t.lockOnWindowScroll,t.masterSpinnerFinished,t.moveThreshold,t.naturalSlideHeight),p=t.naturalSlideWidth,d=(t.onMasterSpinner,t.orientation),h=(t.playDirection,t.privateUnDisableAnimation,t.slideSize),S=t.slideTraySize,y=(t.spinner,t.style),b=t.tabIndex,m=(t.totalSlides,t.touchEnabled,t.trayProps),f=t.trayTag,g=t.visibleSlides,v=t.isIntrinsicHeight,_=_objectWithoutProperties(t,_excluded$c),T=_extends({},y),k={};"vertical"===d&&(k.height=0,k.paddingBottom=pct(100*u*g/p),k.width=pct(100));var C={},O=pct(h*l*-1);(this.state.isBeingTouchDragged||this.state.isBeingMouseDragged||c)&&(C.transition="none"),v&&(C.display="flex",C.alignItems="stretch"),"vertical"===d?(C.transform="translateY(".concat(O,") translateY(").concat(this.state.deltaY,"px)"),C.width=pct(100),C.flexDirection="column"):(C.width=pct(S),C.transform="translateX(".concat(O,") translateX(").concat(this.state.deltaX,"px)"),C.flexDirection="row");var P=cn(["vertical"===d?s$b.verticalSlider:s$b.horizontalSlider,"carousel__slider","vertical"===d?"carousel__slider--vertical":"carousel__slider--horizontal",n]),E=cn([s$b.sliderTrayWrap,"carousel__slider-tray-wrapper","vertical"===d?s$b.verticalSlideTrayWrap:s$b.horizontalTrayWrap,"vertical"===d?"carousel__slider-tray-wrap--vertical":"carousel__slider-tray-wrap--horizontal",a]),w=cn([s$b.sliderTray,s||s$b.sliderAnimation,"carousel__slider-tray","vertical"===d?s$b.verticalTray:s$b.horizontalTray,"vertical"===d?"carousel__slider-tray--vertical":"carousel__slider-tray--horizontal",o]),M=null!==b?b:0,x=(_.dragStep,_.step,_.infinite,_.preventVerticalScrollOnTouch,_.preventingVerticalScroll,_.horizontalPixelThreshold,_.verticalPixelThreshold,_objectWithoutProperties(_,_excluded2$2)),R=(m.className,m.onClickCapture,m.onMouseDown,m.onTouchCancel,m.onTouchEnd,m.onTouchMove,m.onTouchStart,m.ref,m.style,_objectWithoutProperties(m,_excluded3));return react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",_extends({ref:function(t){e.sliderElement=t},className:P,"aria-live":"polite","aria-label":r,style:T,tabIndex:M,onKeyDown:this.handleOnKeyDown,role:"listbox"},x),react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div",{className:E,style:k},react__WEBPACK_IMPORTED_MODULE_0___default().createElement(f,_extends({ref:this.getSliderRef,className:w,style:C,onTouchStart:this.handleOnTouchStart,onTouchMove:this.handleOnTouchMove,onTouchEnd:this.handleOnTouchEnd,onTouchCancel:this.handleOnTouchCancel,onMouseDown:this.handleOnMouseDown,onClickCapture:this.handleOnClickCapture},R),i),this.renderMasterSpinner()))}}],[{key:"slideSizeInPx",value:function(e,t,r,i){return("horizontal"===e?t:r)/i}},{key:"slidesMoved",value:function(e,t,r,i,n,s){var o="horizontal"===t?r:i,a=Math.abs(Math.round(o/n)),l=Math.abs(o)>=n*e?s:0,c=Math.max(l,a);if(o<0)return c;var u=-c;return 0===u?0:u}}]),t}((react__WEBPACK_IMPORTED_MODULE_0___default().Component)),_defineProperty(_class$a,"propTypes",{ariaLabel:propTypes.string,carouselStore:propTypes.object.isRequired,children:propTypes.node.isRequired,className:propTypes.string,classNameAnimation:propTypes.string,classNameTray:propTypes.string,classNameTrayWrap:propTypes.string,currentSlide:propTypes.number.isRequired,disableAnimation:propTypes.bool,disableKeyboard:propTypes.bool,dragEnabled:propTypes.bool.isRequired,dragStep:propTypes.number,hasMasterSpinner:propTypes.bool.isRequired,infinite:propTypes.bool,interval:propTypes.number.isRequired,isPageScrollLocked:propTypes.bool.isRequired,isPlaying:propTypes.bool.isRequired,lockOnWindowScroll:propTypes.bool.isRequired,preventVerticalScrollOnTouch:propTypes.bool,horizontalPixelThreshold:propTypes.number,verticalPixelThreshold:propTypes.number,masterSpinnerFinished:propTypes.bool.isRequired,moveThreshold:propTypes.number,naturalSlideHeight:propTypes.number.isRequired,naturalSlideWidth:propTypes.number.isRequired,onMasterSpinner:propTypes.func,orientation:CarouselPropTypes.orientation.isRequired,playDirection:CarouselPropTypes.direction.isRequired,privateUnDisableAnimation:propTypes.bool,slideSize:propTypes.number.isRequired,slideTraySize:propTypes.number.isRequired,spinner:propTypes.func,step:propTypes.number.isRequired,style:propTypes.object,tabIndex:propTypes.number,totalSlides:propTypes.number.isRequired,touchEnabled:propTypes.bool.isRequired,trayProps:propTypes.shape({className:propTypes.string,onClickCapture:propTypes.func,onMouseDown:propTypes.func,onTouchCancel:propTypes.func,onTouchEnd:propTypes.func,onTouchMove:propTypes.func,onTouchStart:propTypes.func,ref:propTypes.shape({}),style:propTypes.string}),trayTag:propTypes.string,visibleSlides:propTypes.number,isIntrinsicHeight:propTypes.bool}),_defineProperty(_class$a,"defaultProps",{ariaLabel:"slider",className:null,classNameAnimation:null,classNameTray:null,classNameTrayWrap:null,disableAnimation:!1,disableKeyboard:!1,dragStep:1,infinite:!1,preventVerticalScrollOnTouch:!0,horizontalPixelThreshold:15,verticalPixelThreshold:10,moveThreshold:.1,onMasterSpinner:null,privateUnDisableAnimation:!1,spinner:null,style:{},tabIndex:null,trayProps:{},trayTag:"div",visibleSlides:1,isIntrinsicHeight:!1}),_class$a),index$8=WithStore(Slider,function(e){return{currentSlide:e.currentSlide,disableAnimation:e.disableAnimation,privateUnDisableAnimation:e.privateUnDisableAnimation,disableKeyboard:e.disableKeyboard,dragEnabled:e.dragEnabled,hasMasterSpinner:e.hasMasterSpinner,infinite:e.infinite,interval:e.interval,isPageScrollLocked:e.isPageScrollLocked,isPlaying:e.isPlaying,lockOnWindowScroll:e.lockOnWindowScroll,preventingVerticalScroll:e.preventingVerticalScroll,masterSpinnerFinished:e.masterSpinnerFinished,naturalSlideHeight:e.naturalSlideHeight,naturalSlideWidth:e.naturalSlideWidth,orientation:e.orientation,playDirection:e.playDirection,slideSize:e.slideSize,slideTraySize:e.slideTraySize,step:e.step,dragStep:e.dragStep,totalSlides:e.totalSlides,touchEnabled:e.touchEnabled,visibleSlides:e.visibleSlides,isIntrinsicHeight:e.isIntrinsicHeight}});
//# sourceMappingURL=index.es.js.map


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

"use strict";
module.exports = window["React"];

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/***/ ((module) => {

"use strict";
module.exports = window["lodash"];

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["compose"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["hooks"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/***/ ((module, exports) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames () {
		var classes = '';

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (arg) {
				classes = appendClass(classes, parseValue(arg));
			}
		}

		return classes;
	}

	function parseValue (arg) {
		if (typeof arg === 'string' || typeof arg === 'number') {
			return arg;
		}

		if (typeof arg !== 'object') {
			return '';
		}

		if (Array.isArray(arg)) {
			return classNames.apply(null, arg);
		}

		if (arg.toString !== Object.prototype.toString && !arg.toString.toString().includes('[native code]')) {
			return arg.toString();
		}

		var classes = '';

		for (var key in arg) {
			if (hasOwn.call(arg, key) && arg[key]) {
				classes = appendClass(classes, key);
			}
		}

		return classes;
	}

	function appendClass (value, newClass) {
		if (!newClass) {
			return value;
		}
	
		if (value) {
			return value + ' ' + newClass;
		}
	
		return value + newClass;
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "./node_modules/axios/lib/adapters/adapters.js":
/*!*****************************************************!*\
  !*** ./node_modules/axios/lib/adapters/adapters.js ***!
  \*****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _http_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./http.js */ "./node_modules/axios/lib/helpers/null.js");
/* harmony import */ var _xhr_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./xhr.js */ "./node_modules/axios/lib/adapters/xhr.js");
/* harmony import */ var _fetch_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./fetch.js */ "./node_modules/axios/lib/adapters/fetch.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");






const knownAdapters = {
  http: _http_js__WEBPACK_IMPORTED_MODULE_0__["default"],
  xhr: _xhr_js__WEBPACK_IMPORTED_MODULE_1__["default"],
  fetch: _fetch_js__WEBPACK_IMPORTED_MODULE_2__["default"]
}

_utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].forEach(knownAdapters, (fn, value) => {
  if (fn) {
    try {
      Object.defineProperty(fn, 'name', {value});
    } catch (e) {
      // eslint-disable-next-line no-empty
    }
    Object.defineProperty(fn, 'adapterName', {value});
  }
});

const renderReason = (reason) => `- ${reason}`;

const isResolvedHandle = (adapter) => _utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].isFunction(adapter) || adapter === null || adapter === false;

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  getAdapter: (adapters) => {
    adapters = _utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].isArray(adapters) ? adapters : [adapters];

    const {length} = adapters;
    let nameOrAdapter;
    let adapter;

    const rejectedReasons = {};

    for (let i = 0; i < length; i++) {
      nameOrAdapter = adapters[i];
      let id;

      adapter = nameOrAdapter;

      if (!isResolvedHandle(nameOrAdapter)) {
        adapter = knownAdapters[(id = String(nameOrAdapter)).toLowerCase()];

        if (adapter === undefined) {
          throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_4__["default"](`Unknown adapter '${id}'`);
        }
      }

      if (adapter) {
        break;
      }

      rejectedReasons[id || '#' + i] = adapter;
    }

    if (!adapter) {

      const reasons = Object.entries(rejectedReasons)
        .map(([id, state]) => `adapter ${id} ` +
          (state === false ? 'is not supported by the environment' : 'is not available in the build')
        );

      let s = length ?
        (reasons.length > 1 ? 'since :\n' + reasons.map(renderReason).join('\n') : ' ' + renderReason(reasons[0])) :
        'as no adapter specified';

      throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_4__["default"](
        `There is no suitable adapter to dispatch the request ` + s,
        'ERR_NOT_SUPPORT'
      );
    }

    return adapter;
  },
  adapters: knownAdapters
});


/***/ }),

/***/ "./node_modules/axios/lib/adapters/fetch.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/adapters/fetch.js ***!
  \**************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _helpers_composeSignals_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../helpers/composeSignals.js */ "./node_modules/axios/lib/helpers/composeSignals.js");
/* harmony import */ var _helpers_trackStream_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../helpers/trackStream.js */ "./node_modules/axios/lib/helpers/trackStream.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../helpers/progressEventReducer.js */ "./node_modules/axios/lib/helpers/progressEventReducer.js");
/* harmony import */ var _helpers_resolveConfig_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../helpers/resolveConfig.js */ "./node_modules/axios/lib/helpers/resolveConfig.js");
/* harmony import */ var _core_settle_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../core/settle.js */ "./node_modules/axios/lib/core/settle.js");










const isFetchSupported = typeof fetch === 'function' && typeof Request === 'function' && typeof Response === 'function';
const isReadableStreamSupported = isFetchSupported && typeof ReadableStream === 'function';

// used only inside the fetch adapter
const encodeText = isFetchSupported && (typeof TextEncoder === 'function' ?
    ((encoder) => (str) => encoder.encode(str))(new TextEncoder()) :
    async (str) => new Uint8Array(await new Response(str).arrayBuffer())
);

const test = (fn, ...args) => {
  try {
    return !!fn(...args);
  } catch (e) {
    return false
  }
}

const supportsRequestStream = isReadableStreamSupported && test(() => {
  let duplexAccessed = false;

  const hasContentType = new Request(_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].origin, {
    body: new ReadableStream(),
    method: 'POST',
    get duplex() {
      duplexAccessed = true;
      return 'half';
    },
  }).headers.has('Content-Type');

  return duplexAccessed && !hasContentType;
});

const DEFAULT_CHUNK_SIZE = 64 * 1024;

const supportsResponseStream = isReadableStreamSupported &&
  test(() => _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isReadableStream(new Response('').body));


const resolvers = {
  stream: supportsResponseStream && ((res) => res.body)
};

isFetchSupported && (((res) => {
  ['text', 'arrayBuffer', 'blob', 'formData', 'stream'].forEach(type => {
    !resolvers[type] && (resolvers[type] = _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isFunction(res[type]) ? (res) => res[type]() :
      (_, config) => {
        throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"](`Response type '${type}' is not supported`, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"].ERR_NOT_SUPPORT, config);
      })
  });
})(new Response));

const getBodyLength = async (body) => {
  if (body == null) {
    return 0;
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isBlob(body)) {
    return body.size;
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isSpecCompliantForm(body)) {
    const _request = new Request(_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].origin, {
      method: 'POST',
      body,
    });
    return (await _request.arrayBuffer()).byteLength;
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isArrayBufferView(body) || _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isArrayBuffer(body)) {
    return body.byteLength;
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isURLSearchParams(body)) {
    body = body + '';
  }

  if(_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isString(body)) {
    return (await encodeText(body)).byteLength;
  }
}

const resolveBodyLength = async (headers, body) => {
  const length = _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].toFiniteNumber(headers.getContentLength());

  return length == null ? getBodyLength(body) : length;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (isFetchSupported && (async (config) => {
  let {
    url,
    method,
    data,
    signal,
    cancelToken,
    timeout,
    onDownloadProgress,
    onUploadProgress,
    responseType,
    headers,
    withCredentials = 'same-origin',
    fetchOptions
  } = (0,_helpers_resolveConfig_js__WEBPACK_IMPORTED_MODULE_3__["default"])(config);

  responseType = responseType ? (responseType + '').toLowerCase() : 'text';

  let composedSignal = (0,_helpers_composeSignals_js__WEBPACK_IMPORTED_MODULE_4__["default"])([signal, cancelToken && cancelToken.toAbortSignal()], timeout);

  let request;

  const unsubscribe = composedSignal && composedSignal.unsubscribe && (() => {
      composedSignal.unsubscribe();
  });

  let requestContentLength;

  try {
    if (
      onUploadProgress && supportsRequestStream && method !== 'get' && method !== 'head' &&
      (requestContentLength = await resolveBodyLength(headers, data)) !== 0
    ) {
      let _request = new Request(url, {
        method: 'POST',
        body: data,
        duplex: "half"
      });

      let contentTypeHeader;

      if (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isFormData(data) && (contentTypeHeader = _request.headers.get('content-type'))) {
        headers.setContentType(contentTypeHeader)
      }

      if (_request.body) {
        const [onProgress, flush] = (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.progressEventDecorator)(
          requestContentLength,
          (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.progressEventReducer)((0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.asyncDecorator)(onUploadProgress))
        );

        data = (0,_helpers_trackStream_js__WEBPACK_IMPORTED_MODULE_6__.trackStream)(_request.body, DEFAULT_CHUNK_SIZE, onProgress, flush);
      }
    }

    if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isString(withCredentials)) {
      withCredentials = withCredentials ? 'include' : 'omit';
    }

    // Cloudflare Workers throws when credentials are defined
    // see https://github.com/cloudflare/workerd/issues/902
    const isCredentialsSupported = "credentials" in Request.prototype;
    request = new Request(url, {
      ...fetchOptions,
      signal: composedSignal,
      method: method.toUpperCase(),
      headers: headers.normalize().toJSON(),
      body: data,
      duplex: "half",
      credentials: isCredentialsSupported ? withCredentials : undefined
    });

    let response = await fetch(request);

    const isStreamResponse = supportsResponseStream && (responseType === 'stream' || responseType === 'response');

    if (supportsResponseStream && (onDownloadProgress || (isStreamResponse && unsubscribe))) {
      const options = {};

      ['status', 'statusText', 'headers'].forEach(prop => {
        options[prop] = response[prop];
      });

      const responseContentLength = _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].toFiniteNumber(response.headers.get('content-length'));

      const [onProgress, flush] = onDownloadProgress && (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.progressEventDecorator)(
        responseContentLength,
        (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.progressEventReducer)((0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_5__.asyncDecorator)(onDownloadProgress), true)
      ) || [];

      response = new Response(
        (0,_helpers_trackStream_js__WEBPACK_IMPORTED_MODULE_6__.trackStream)(response.body, DEFAULT_CHUNK_SIZE, onProgress, () => {
          flush && flush();
          unsubscribe && unsubscribe();
        }),
        options
      );
    }

    responseType = responseType || 'text';

    let responseData = await resolvers[_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].findKey(resolvers, responseType) || 'text'](response, config);

    !isStreamResponse && unsubscribe && unsubscribe();

    return await new Promise((resolve, reject) => {
      (0,_core_settle_js__WEBPACK_IMPORTED_MODULE_7__["default"])(resolve, reject, {
        data: responseData,
        headers: _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_8__["default"].from(response.headers),
        status: response.status,
        statusText: response.statusText,
        config,
        request
      })
    })
  } catch (err) {
    unsubscribe && unsubscribe();

    if (err && err.name === 'TypeError' && /fetch/i.test(err.message)) {
      throw Object.assign(
        new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"]('Network Error', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"].ERR_NETWORK, config, request),
        {
          cause: err.cause || err
        }
      )
    }

    throw _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"].from(err, err && err.code, config, request);
  }
}));




/***/ }),

/***/ "./node_modules/axios/lib/adapters/xhr.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/adapters/xhr.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _core_settle_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../core/settle.js */ "./node_modules/axios/lib/core/settle.js");
/* harmony import */ var _defaults_transitional_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../defaults/transitional.js */ "./node_modules/axios/lib/defaults/transitional.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../cancel/CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");
/* harmony import */ var _helpers_parseProtocol_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../helpers/parseProtocol.js */ "./node_modules/axios/lib/helpers/parseProtocol.js");
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../helpers/progressEventReducer.js */ "./node_modules/axios/lib/helpers/progressEventReducer.js");
/* harmony import */ var _helpers_resolveConfig_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/resolveConfig.js */ "./node_modules/axios/lib/helpers/resolveConfig.js");











const isXHRAdapterSupported = typeof XMLHttpRequest !== 'undefined';

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (isXHRAdapterSupported && function (config) {
  return new Promise(function dispatchXhrRequest(resolve, reject) {
    const _config = (0,_helpers_resolveConfig_js__WEBPACK_IMPORTED_MODULE_0__["default"])(config);
    let requestData = _config.data;
    const requestHeaders = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(_config.headers).normalize();
    let {responseType, onUploadProgress, onDownloadProgress} = _config;
    let onCanceled;
    let uploadThrottled, downloadThrottled;
    let flushUpload, flushDownload;

    function done() {
      flushUpload && flushUpload(); // flush events
      flushDownload && flushDownload(); // flush events

      _config.cancelToken && _config.cancelToken.unsubscribe(onCanceled);

      _config.signal && _config.signal.removeEventListener('abort', onCanceled);
    }

    let request = new XMLHttpRequest();

    request.open(_config.method.toUpperCase(), _config.url, true);

    // Set the request timeout in MS
    request.timeout = _config.timeout;

    function onloadend() {
      if (!request) {
        return;
      }
      // Prepare the response
      const responseHeaders = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(
        'getAllResponseHeaders' in request && request.getAllResponseHeaders()
      );
      const responseData = !responseType || responseType === 'text' || responseType === 'json' ?
        request.responseText : request.response;
      const response = {
        data: responseData,
        status: request.status,
        statusText: request.statusText,
        headers: responseHeaders,
        config,
        request
      };

      (0,_core_settle_js__WEBPACK_IMPORTED_MODULE_2__["default"])(function _resolve(value) {
        resolve(value);
        done();
      }, function _reject(err) {
        reject(err);
        done();
      }, response);

      // Clean up request
      request = null;
    }

    if ('onloadend' in request) {
      // Use onloadend if available
      request.onloadend = onloadend;
    } else {
      // Listen for ready state to emulate onloadend
      request.onreadystatechange = function handleLoad() {
        if (!request || request.readyState !== 4) {
          return;
        }

        // The request errored out and we didn't get a response, this will be
        // handled by onerror instead
        // With one exception: request that using file: protocol, most browsers
        // will return status as 0 even though it's a successful request
        if (request.status === 0 && !(request.responseURL && request.responseURL.indexOf('file:') === 0)) {
          return;
        }
        // readystate handler is calling before onerror or ontimeout handlers,
        // so we should call onloadend on the next 'tick'
        setTimeout(onloadend);
      };
    }

    // Handle browser request cancellation (as opposed to a manual cancellation)
    request.onabort = function handleAbort() {
      if (!request) {
        return;
      }

      reject(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"]('Request aborted', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ECONNABORTED, config, request));

      // Clean up request
      request = null;
    };

    // Handle low level network errors
    request.onerror = function handleError() {
      // Real errors are hidden from us by the browser
      // onerror should only fire if it's a network error
      reject(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"]('Network Error', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ERR_NETWORK, config, request));

      // Clean up request
      request = null;
    };

    // Handle timeout
    request.ontimeout = function handleTimeout() {
      let timeoutErrorMessage = _config.timeout ? 'timeout of ' + _config.timeout + 'ms exceeded' : 'timeout exceeded';
      const transitional = _config.transitional || _defaults_transitional_js__WEBPACK_IMPORTED_MODULE_4__["default"];
      if (_config.timeoutErrorMessage) {
        timeoutErrorMessage = _config.timeoutErrorMessage;
      }
      reject(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"](
        timeoutErrorMessage,
        transitional.clarifyTimeoutError ? _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ETIMEDOUT : _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ECONNABORTED,
        config,
        request));

      // Clean up request
      request = null;
    };

    // Remove Content-Type if data is undefined
    requestData === undefined && requestHeaders.setContentType(null);

    // Add headers to the request
    if ('setRequestHeader' in request) {
      _utils_js__WEBPACK_IMPORTED_MODULE_5__["default"].forEach(requestHeaders.toJSON(), function setRequestHeader(val, key) {
        request.setRequestHeader(key, val);
      });
    }

    // Add withCredentials to request if needed
    if (!_utils_js__WEBPACK_IMPORTED_MODULE_5__["default"].isUndefined(_config.withCredentials)) {
      request.withCredentials = !!_config.withCredentials;
    }

    // Add responseType to request if needed
    if (responseType && responseType !== 'json') {
      request.responseType = _config.responseType;
    }

    // Handle progress if needed
    if (onDownloadProgress) {
      ([downloadThrottled, flushDownload] = (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_6__.progressEventReducer)(onDownloadProgress, true));
      request.addEventListener('progress', downloadThrottled);
    }

    // Not all browsers support upload events
    if (onUploadProgress && request.upload) {
      ([uploadThrottled, flushUpload] = (0,_helpers_progressEventReducer_js__WEBPACK_IMPORTED_MODULE_6__.progressEventReducer)(onUploadProgress));

      request.upload.addEventListener('progress', uploadThrottled);

      request.upload.addEventListener('loadend', flushUpload);
    }

    if (_config.cancelToken || _config.signal) {
      // Handle cancellation
      // eslint-disable-next-line func-names
      onCanceled = cancel => {
        if (!request) {
          return;
        }
        reject(!cancel || cancel.type ? new _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_7__["default"](null, config, request) : cancel);
        request.abort();
        request = null;
      };

      _config.cancelToken && _config.cancelToken.subscribe(onCanceled);
      if (_config.signal) {
        _config.signal.aborted ? onCanceled() : _config.signal.addEventListener('abort', onCanceled);
      }
    }

    const protocol = (0,_helpers_parseProtocol_js__WEBPACK_IMPORTED_MODULE_8__["default"])(_config.url);

    if (protocol && _platform_index_js__WEBPACK_IMPORTED_MODULE_9__["default"].protocols.indexOf(protocol) === -1) {
      reject(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"]('Unsupported protocol ' + protocol + ':', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_3__["default"].ERR_BAD_REQUEST, config));
      return;
    }


    // Send the request
    request.send(requestData || null);
  });
});


/***/ }),

/***/ "./node_modules/axios/lib/axios.js":
/*!*****************************************!*\
  !*** ./node_modules/axios/lib/axios.js ***!
  \*****************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _helpers_bind_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./helpers/bind.js */ "./node_modules/axios/lib/helpers/bind.js");
/* harmony import */ var _core_Axios_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./core/Axios.js */ "./node_modules/axios/lib/core/Axios.js");
/* harmony import */ var _core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./core/mergeConfig.js */ "./node_modules/axios/lib/core/mergeConfig.js");
/* harmony import */ var _defaults_index_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./defaults/index.js */ "./node_modules/axios/lib/defaults/index.js");
/* harmony import */ var _helpers_formDataToJSON_js__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./helpers/formDataToJSON.js */ "./node_modules/axios/lib/helpers/formDataToJSON.js");
/* harmony import */ var _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./cancel/CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");
/* harmony import */ var _cancel_CancelToken_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./cancel/CancelToken.js */ "./node_modules/axios/lib/cancel/CancelToken.js");
/* harmony import */ var _cancel_isCancel_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./cancel/isCancel.js */ "./node_modules/axios/lib/cancel/isCancel.js");
/* harmony import */ var _env_data_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./env/data.js */ "./node_modules/axios/lib/env/data.js");
/* harmony import */ var _helpers_toFormData_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./helpers/toFormData.js */ "./node_modules/axios/lib/helpers/toFormData.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _helpers_spread_js__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./helpers/spread.js */ "./node_modules/axios/lib/helpers/spread.js");
/* harmony import */ var _helpers_isAxiosError_js__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./helpers/isAxiosError.js */ "./node_modules/axios/lib/helpers/isAxiosError.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _adapters_adapters_js__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./adapters/adapters.js */ "./node_modules/axios/lib/adapters/adapters.js");
/* harmony import */ var _helpers_HttpStatusCode_js__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./helpers/HttpStatusCode.js */ "./node_modules/axios/lib/helpers/HttpStatusCode.js");




















/**
 * Create an instance of Axios
 *
 * @param {Object} defaultConfig The default config for the instance
 *
 * @returns {Axios} A new instance of Axios
 */
function createInstance(defaultConfig) {
  const context = new _core_Axios_js__WEBPACK_IMPORTED_MODULE_0__["default"](defaultConfig);
  const instance = (0,_helpers_bind_js__WEBPACK_IMPORTED_MODULE_1__["default"])(_core_Axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].prototype.request, context);

  // Copy axios.prototype to instance
  _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].extend(instance, _core_Axios_js__WEBPACK_IMPORTED_MODULE_0__["default"].prototype, context, {allOwnKeys: true});

  // Copy context to instance
  _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].extend(instance, context, null, {allOwnKeys: true});

  // Factory for creating new instances
  instance.create = function create(instanceConfig) {
    return createInstance((0,_core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_3__["default"])(defaultConfig, instanceConfig));
  };

  return instance;
}

// Create the default instance to be exported
const axios = createInstance(_defaults_index_js__WEBPACK_IMPORTED_MODULE_4__["default"]);

// Expose Axios class to allow class inheritance
axios.Axios = _core_Axios_js__WEBPACK_IMPORTED_MODULE_0__["default"];

// Expose Cancel & CancelToken
axios.CanceledError = _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_5__["default"];
axios.CancelToken = _cancel_CancelToken_js__WEBPACK_IMPORTED_MODULE_6__["default"];
axios.isCancel = _cancel_isCancel_js__WEBPACK_IMPORTED_MODULE_7__["default"];
axios.VERSION = _env_data_js__WEBPACK_IMPORTED_MODULE_8__.VERSION;
axios.toFormData = _helpers_toFormData_js__WEBPACK_IMPORTED_MODULE_9__["default"];

// Expose AxiosError class
axios.AxiosError = _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_10__["default"];

// alias for CanceledError for backward compatibility
axios.Cancel = axios.CanceledError;

// Expose all/spread
axios.all = function all(promises) {
  return Promise.all(promises);
};

axios.spread = _helpers_spread_js__WEBPACK_IMPORTED_MODULE_11__["default"];

// Expose isAxiosError
axios.isAxiosError = _helpers_isAxiosError_js__WEBPACK_IMPORTED_MODULE_12__["default"];

// Expose mergeConfig
axios.mergeConfig = _core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_3__["default"];

axios.AxiosHeaders = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_13__["default"];

axios.formToJSON = thing => (0,_helpers_formDataToJSON_js__WEBPACK_IMPORTED_MODULE_14__["default"])(_utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].isHTMLForm(thing) ? new FormData(thing) : thing);

axios.getAdapter = _adapters_adapters_js__WEBPACK_IMPORTED_MODULE_15__["default"].getAdapter;

axios.HttpStatusCode = _helpers_HttpStatusCode_js__WEBPACK_IMPORTED_MODULE_16__["default"];

axios.default = axios;

// this module should only have a default export
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (axios);


/***/ }),

/***/ "./node_modules/axios/lib/cancel/CancelToken.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/cancel/CancelToken.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _CanceledError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");




/**
 * A `CancelToken` is an object that can be used to request cancellation of an operation.
 *
 * @param {Function} executor The executor function.
 *
 * @returns {CancelToken}
 */
class CancelToken {
  constructor(executor) {
    if (typeof executor !== 'function') {
      throw new TypeError('executor must be a function.');
    }

    let resolvePromise;

    this.promise = new Promise(function promiseExecutor(resolve) {
      resolvePromise = resolve;
    });

    const token = this;

    // eslint-disable-next-line func-names
    this.promise.then(cancel => {
      if (!token._listeners) return;

      let i = token._listeners.length;

      while (i-- > 0) {
        token._listeners[i](cancel);
      }
      token._listeners = null;
    });

    // eslint-disable-next-line func-names
    this.promise.then = onfulfilled => {
      let _resolve;
      // eslint-disable-next-line func-names
      const promise = new Promise(resolve => {
        token.subscribe(resolve);
        _resolve = resolve;
      }).then(onfulfilled);

      promise.cancel = function reject() {
        token.unsubscribe(_resolve);
      };

      return promise;
    };

    executor(function cancel(message, config, request) {
      if (token.reason) {
        // Cancellation has already been requested
        return;
      }

      token.reason = new _CanceledError_js__WEBPACK_IMPORTED_MODULE_0__["default"](message, config, request);
      resolvePromise(token.reason);
    });
  }

  /**
   * Throws a `CanceledError` if cancellation has been requested.
   */
  throwIfRequested() {
    if (this.reason) {
      throw this.reason;
    }
  }

  /**
   * Subscribe to the cancel signal
   */

  subscribe(listener) {
    if (this.reason) {
      listener(this.reason);
      return;
    }

    if (this._listeners) {
      this._listeners.push(listener);
    } else {
      this._listeners = [listener];
    }
  }

  /**
   * Unsubscribe from the cancel signal
   */

  unsubscribe(listener) {
    if (!this._listeners) {
      return;
    }
    const index = this._listeners.indexOf(listener);
    if (index !== -1) {
      this._listeners.splice(index, 1);
    }
  }

  toAbortSignal() {
    const controller = new AbortController();

    const abort = (err) => {
      controller.abort(err);
    };

    this.subscribe(abort);

    controller.signal.unsubscribe = () => this.unsubscribe(abort);

    return controller.signal;
  }

  /**
   * Returns an object that contains a new `CancelToken` and a function that, when called,
   * cancels the `CancelToken`.
   */
  static source() {
    let cancel;
    const token = new CancelToken(function executor(c) {
      cancel = c;
    });
    return {
      token,
      cancel
    };
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CancelToken);


/***/ }),

/***/ "./node_modules/axios/lib/cancel/CanceledError.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/cancel/CanceledError.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");





/**
 * A `CanceledError` is an object that is thrown when an operation is canceled.
 *
 * @param {string=} message The message.
 * @param {Object=} config The config.
 * @param {Object=} request The request.
 *
 * @returns {CanceledError} The created error.
 */
function CanceledError(message, config, request) {
  // eslint-disable-next-line no-eq-null,eqeqeq
  _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].call(this, message == null ? 'canceled' : message, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].ERR_CANCELED, config, request);
  this.name = 'CanceledError';
}

_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].inherits(CanceledError, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"], {
  __CANCEL__: true
});

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CanceledError);


/***/ }),

/***/ "./node_modules/axios/lib/cancel/isCancel.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/cancel/isCancel.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ isCancel)
/* harmony export */ });


function isCancel(value) {
  return !!(value && value.__CANCEL__);
}


/***/ }),

/***/ "./node_modules/axios/lib/core/Axios.js":
/*!**********************************************!*\
  !*** ./node_modules/axios/lib/core/Axios.js ***!
  \**********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _helpers_buildURL_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../helpers/buildURL.js */ "./node_modules/axios/lib/helpers/buildURL.js");
/* harmony import */ var _InterceptorManager_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./InterceptorManager.js */ "./node_modules/axios/lib/core/InterceptorManager.js");
/* harmony import */ var _dispatchRequest_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./dispatchRequest.js */ "./node_modules/axios/lib/core/dispatchRequest.js");
/* harmony import */ var _mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./mergeConfig.js */ "./node_modules/axios/lib/core/mergeConfig.js");
/* harmony import */ var _buildFullPath_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./buildFullPath.js */ "./node_modules/axios/lib/core/buildFullPath.js");
/* harmony import */ var _helpers_validator_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/validator.js */ "./node_modules/axios/lib/helpers/validator.js");
/* harmony import */ var _AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");











const validators = _helpers_validator_js__WEBPACK_IMPORTED_MODULE_0__["default"].validators;

/**
 * Create a new instance of Axios
 *
 * @param {Object} instanceConfig The default config for the instance
 *
 * @return {Axios} A new instance of Axios
 */
class Axios {
  constructor(instanceConfig) {
    this.defaults = instanceConfig;
    this.interceptors = {
      request: new _InterceptorManager_js__WEBPACK_IMPORTED_MODULE_1__["default"](),
      response: new _InterceptorManager_js__WEBPACK_IMPORTED_MODULE_1__["default"]()
    };
  }

  /**
   * Dispatch a request
   *
   * @param {String|Object} configOrUrl The config specific for this request (merged with this.defaults)
   * @param {?Object} config
   *
   * @returns {Promise} The Promise to be fulfilled
   */
  async request(configOrUrl, config) {
    try {
      return await this._request(configOrUrl, config);
    } catch (err) {
      if (err instanceof Error) {
        let dummy;

        Error.captureStackTrace ? Error.captureStackTrace(dummy = {}) : (dummy = new Error());

        // slice off the Error: ... line
        const stack = dummy.stack ? dummy.stack.replace(/^.+\n/, '') : '';
        try {
          if (!err.stack) {
            err.stack = stack;
            // match without the 2 top stack lines
          } else if (stack && !String(err.stack).endsWith(stack.replace(/^.+\n.+\n/, ''))) {
            err.stack += '\n' + stack
          }
        } catch (e) {
          // ignore the case where "stack" is an un-writable property
        }
      }

      throw err;
    }
  }

  _request(configOrUrl, config) {
    /*eslint no-param-reassign:0*/
    // Allow for axios('example/url'[, config]) a la fetch API
    if (typeof configOrUrl === 'string') {
      config = config || {};
      config.url = configOrUrl;
    } else {
      config = configOrUrl || {};
    }

    config = (0,_mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__["default"])(this.defaults, config);

    const {transitional, paramsSerializer, headers} = config;

    if (transitional !== undefined) {
      _helpers_validator_js__WEBPACK_IMPORTED_MODULE_0__["default"].assertOptions(transitional, {
        silentJSONParsing: validators.transitional(validators.boolean),
        forcedJSONParsing: validators.transitional(validators.boolean),
        clarifyTimeoutError: validators.transitional(validators.boolean)
      }, false);
    }

    if (paramsSerializer != null) {
      if (_utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].isFunction(paramsSerializer)) {
        config.paramsSerializer = {
          serialize: paramsSerializer
        }
      } else {
        _helpers_validator_js__WEBPACK_IMPORTED_MODULE_0__["default"].assertOptions(paramsSerializer, {
          encode: validators.function,
          serialize: validators.function
        }, true);
      }
    }

    // Set config.method
    config.method = (config.method || this.defaults.method || 'get').toLowerCase();

    // Flatten headers
    let contextHeaders = headers && _utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].merge(
      headers.common,
      headers[config.method]
    );

    headers && _utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].forEach(
      ['delete', 'get', 'head', 'post', 'put', 'patch', 'common'],
      (method) => {
        delete headers[method];
      }
    );

    config.headers = _AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_4__["default"].concat(contextHeaders, headers);

    // filter out skipped interceptors
    const requestInterceptorChain = [];
    let synchronousRequestInterceptors = true;
    this.interceptors.request.forEach(function unshiftRequestInterceptors(interceptor) {
      if (typeof interceptor.runWhen === 'function' && interceptor.runWhen(config) === false) {
        return;
      }

      synchronousRequestInterceptors = synchronousRequestInterceptors && interceptor.synchronous;

      requestInterceptorChain.unshift(interceptor.fulfilled, interceptor.rejected);
    });

    const responseInterceptorChain = [];
    this.interceptors.response.forEach(function pushResponseInterceptors(interceptor) {
      responseInterceptorChain.push(interceptor.fulfilled, interceptor.rejected);
    });

    let promise;
    let i = 0;
    let len;

    if (!synchronousRequestInterceptors) {
      const chain = [_dispatchRequest_js__WEBPACK_IMPORTED_MODULE_5__["default"].bind(this), undefined];
      chain.unshift.apply(chain, requestInterceptorChain);
      chain.push.apply(chain, responseInterceptorChain);
      len = chain.length;

      promise = Promise.resolve(config);

      while (i < len) {
        promise = promise.then(chain[i++], chain[i++]);
      }

      return promise;
    }

    len = requestInterceptorChain.length;

    let newConfig = config;

    i = 0;

    while (i < len) {
      const onFulfilled = requestInterceptorChain[i++];
      const onRejected = requestInterceptorChain[i++];
      try {
        newConfig = onFulfilled(newConfig);
      } catch (error) {
        onRejected.call(this, error);
        break;
      }
    }

    try {
      promise = _dispatchRequest_js__WEBPACK_IMPORTED_MODULE_5__["default"].call(this, newConfig);
    } catch (error) {
      return Promise.reject(error);
    }

    i = 0;
    len = responseInterceptorChain.length;

    while (i < len) {
      promise = promise.then(responseInterceptorChain[i++], responseInterceptorChain[i++]);
    }

    return promise;
  }

  getUri(config) {
    config = (0,_mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__["default"])(this.defaults, config);
    const fullPath = (0,_buildFullPath_js__WEBPACK_IMPORTED_MODULE_6__["default"])(config.baseURL, config.url);
    return (0,_helpers_buildURL_js__WEBPACK_IMPORTED_MODULE_7__["default"])(fullPath, config.params, config.paramsSerializer);
  }
}

// Provide aliases for supported request methods
_utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].forEach(['delete', 'get', 'head', 'options'], function forEachMethodNoData(method) {
  /*eslint func-names:0*/
  Axios.prototype[method] = function(url, config) {
    return this.request((0,_mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__["default"])(config || {}, {
      method,
      url,
      data: (config || {}).data
    }));
  };
});

_utils_js__WEBPACK_IMPORTED_MODULE_3__["default"].forEach(['post', 'put', 'patch'], function forEachMethodWithData(method) {
  /*eslint func-names:0*/

  function generateHTTPMethod(isForm) {
    return function httpMethod(url, data, config) {
      return this.request((0,_mergeConfig_js__WEBPACK_IMPORTED_MODULE_2__["default"])(config || {}, {
        method,
        headers: isForm ? {
          'Content-Type': 'multipart/form-data'
        } : {},
        url,
        data
      }));
    };
  }

  Axios.prototype[method] = generateHTTPMethod();

  Axios.prototype[method + 'Form'] = generateHTTPMethod(true);
});

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Axios);


/***/ }),

/***/ "./node_modules/axios/lib/core/AxiosError.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/core/AxiosError.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");




/**
 * Create an Error with the specified message, config, error code, request and response.
 *
 * @param {string} message The error message.
 * @param {string} [code] The error code (for example, 'ECONNABORTED').
 * @param {Object} [config] The config.
 * @param {Object} [request] The request.
 * @param {Object} [response] The response.
 *
 * @returns {Error} The created error.
 */
function AxiosError(message, code, config, request, response) {
  Error.call(this);

  if (Error.captureStackTrace) {
    Error.captureStackTrace(this, this.constructor);
  } else {
    this.stack = (new Error()).stack;
  }

  this.message = message;
  this.name = 'AxiosError';
  code && (this.code = code);
  config && (this.config = config);
  request && (this.request = request);
  if (response) {
    this.response = response;
    this.status = response.status ? response.status : null;
  }
}

_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].inherits(AxiosError, Error, {
  toJSON: function toJSON() {
    return {
      // Standard
      message: this.message,
      name: this.name,
      // Microsoft
      description: this.description,
      number: this.number,
      // Mozilla
      fileName: this.fileName,
      lineNumber: this.lineNumber,
      columnNumber: this.columnNumber,
      stack: this.stack,
      // Axios
      config: _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toJSONObject(this.config),
      code: this.code,
      status: this.status
    };
  }
});

const prototype = AxiosError.prototype;
const descriptors = {};

[
  'ERR_BAD_OPTION_VALUE',
  'ERR_BAD_OPTION',
  'ECONNABORTED',
  'ETIMEDOUT',
  'ERR_NETWORK',
  'ERR_FR_TOO_MANY_REDIRECTS',
  'ERR_DEPRECATED',
  'ERR_BAD_RESPONSE',
  'ERR_BAD_REQUEST',
  'ERR_CANCELED',
  'ERR_NOT_SUPPORT',
  'ERR_INVALID_URL'
// eslint-disable-next-line func-names
].forEach(code => {
  descriptors[code] = {value: code};
});

Object.defineProperties(AxiosError, descriptors);
Object.defineProperty(prototype, 'isAxiosError', {value: true});

// eslint-disable-next-line func-names
AxiosError.from = (error, code, config, request, response, customProps) => {
  const axiosError = Object.create(prototype);

  _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toFlatObject(error, axiosError, function filter(obj) {
    return obj !== Error.prototype;
  }, prop => {
    return prop !== 'isAxiosError';
  });

  AxiosError.call(axiosError, error.message, code, config, request, response);

  axiosError.cause = error;

  axiosError.name = error.name;

  customProps && Object.assign(axiosError, customProps);

  return axiosError;
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AxiosError);


/***/ }),

/***/ "./node_modules/axios/lib/core/AxiosHeaders.js":
/*!*****************************************************!*\
  !*** ./node_modules/axios/lib/core/AxiosHeaders.js ***!
  \*****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _helpers_parseHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers/parseHeaders.js */ "./node_modules/axios/lib/helpers/parseHeaders.js");





const $internals = Symbol('internals');

function normalizeHeader(header) {
  return header && String(header).trim().toLowerCase();
}

function normalizeValue(value) {
  if (value === false || value == null) {
    return value;
  }

  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(value) ? value.map(normalizeValue) : String(value);
}

function parseTokens(str) {
  const tokens = Object.create(null);
  const tokensRE = /([^\s,;=]+)\s*(?:=\s*([^,;]+))?/g;
  let match;

  while ((match = tokensRE.exec(str))) {
    tokens[match[1]] = match[2];
  }

  return tokens;
}

const isValidHeaderName = (str) => /^[-_a-zA-Z0-9^`|~,!#$%&'*+.]+$/.test(str.trim());

function matchHeaderValue(context, value, header, filter, isHeaderNameFilter) {
  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFunction(filter)) {
    return filter.call(this, value, header);
  }

  if (isHeaderNameFilter) {
    value = header;
  }

  if (!_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(value)) return;

  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(filter)) {
    return value.indexOf(filter) !== -1;
  }

  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegExp(filter)) {
    return filter.test(value);
  }
}

function formatHeader(header) {
  return header.trim()
    .toLowerCase().replace(/([a-z\d])(\w*)/g, (w, char, str) => {
      return char.toUpperCase() + str;
    });
}

function buildAccessors(obj, header) {
  const accessorName = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toCamelCase(' ' + header);

  ['get', 'set', 'has'].forEach(methodName => {
    Object.defineProperty(obj, methodName + accessorName, {
      value: function(arg1, arg2, arg3) {
        return this[methodName].call(this, header, arg1, arg2, arg3);
      },
      configurable: true
    });
  });
}

class AxiosHeaders {
  constructor(headers) {
    headers && this.set(headers);
  }

  set(header, valueOrRewrite, rewrite) {
    const self = this;

    function setHeader(_value, _header, _rewrite) {
      const lHeader = normalizeHeader(_header);

      if (!lHeader) {
        throw new Error('header name must be a non-empty string');
      }

      const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(self, lHeader);

      if(!key || self[key] === undefined || _rewrite === true || (_rewrite === undefined && self[key] !== false)) {
        self[key || _header] = normalizeValue(_value);
      }
    }

    const setHeaders = (headers, _rewrite) =>
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(headers, (_value, _header) => setHeader(_value, _header, _rewrite));

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isPlainObject(header) || header instanceof this.constructor) {
      setHeaders(header, valueOrRewrite)
    } else if(_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(header) && (header = header.trim()) && !isValidHeaderName(header)) {
      setHeaders((0,_helpers_parseHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"])(header), valueOrRewrite);
    } else if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isHeaders(header)) {
      for (const [key, value] of header.entries()) {
        setHeader(value, key, rewrite);
      }
    } else {
      header != null && setHeader(valueOrRewrite, header, rewrite);
    }

    return this;
  }

  get(header, parser) {
    header = normalizeHeader(header);

    if (header) {
      const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(this, header);

      if (key) {
        const value = this[key];

        if (!parser) {
          return value;
        }

        if (parser === true) {
          return parseTokens(value);
        }

        if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFunction(parser)) {
          return parser.call(this, value, key);
        }

        if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isRegExp(parser)) {
          return parser.exec(value);
        }

        throw new TypeError('parser must be boolean|regexp|function');
      }
    }
  }

  has(header, matcher) {
    header = normalizeHeader(header);

    if (header) {
      const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(this, header);

      return !!(key && this[key] !== undefined && (!matcher || matchHeaderValue(this, this[key], key, matcher)));
    }

    return false;
  }

  delete(header, matcher) {
    const self = this;
    let deleted = false;

    function deleteHeader(_header) {
      _header = normalizeHeader(_header);

      if (_header) {
        const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(self, _header);

        if (key && (!matcher || matchHeaderValue(self, self[key], key, matcher))) {
          delete self[key];

          deleted = true;
        }
      }
    }

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(header)) {
      header.forEach(deleteHeader);
    } else {
      deleteHeader(header);
    }

    return deleted;
  }

  clear(matcher) {
    const keys = Object.keys(this);
    let i = keys.length;
    let deleted = false;

    while (i--) {
      const key = keys[i];
      if(!matcher || matchHeaderValue(this, this[key], key, matcher, true)) {
        delete this[key];
        deleted = true;
      }
    }

    return deleted;
  }

  normalize(format) {
    const self = this;
    const headers = {};

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(this, (value, header) => {
      const key = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].findKey(headers, header);

      if (key) {
        self[key] = normalizeValue(value);
        delete self[header];
        return;
      }

      const normalized = format ? formatHeader(header) : String(header).trim();

      if (normalized !== header) {
        delete self[header];
      }

      self[normalized] = normalizeValue(value);

      headers[normalized] = true;
    });

    return this;
  }

  concat(...targets) {
    return this.constructor.concat(this, ...targets);
  }

  toJSON(asStrings) {
    const obj = Object.create(null);

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(this, (value, header) => {
      value != null && value !== false && (obj[header] = asStrings && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(value) ? value.join(', ') : value);
    });

    return obj;
  }

  [Symbol.iterator]() {
    return Object.entries(this.toJSON())[Symbol.iterator]();
  }

  toString() {
    return Object.entries(this.toJSON()).map(([header, value]) => header + ': ' + value).join('\n');
  }

  get [Symbol.toStringTag]() {
    return 'AxiosHeaders';
  }

  static from(thing) {
    return thing instanceof this ? thing : new this(thing);
  }

  static concat(first, ...targets) {
    const computed = new this(first);

    targets.forEach((target) => computed.set(target));

    return computed;
  }

  static accessor(header) {
    const internals = this[$internals] = (this[$internals] = {
      accessors: {}
    });

    const accessors = internals.accessors;
    const prototype = this.prototype;

    function defineAccessor(_header) {
      const lHeader = normalizeHeader(_header);

      if (!accessors[lHeader]) {
        buildAccessors(prototype, _header);
        accessors[lHeader] = true;
      }
    }

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(header) ? header.forEach(defineAccessor) : defineAccessor(header);

    return this;
  }
}

AxiosHeaders.accessor(['Content-Type', 'Content-Length', 'Accept', 'Accept-Encoding', 'User-Agent', 'Authorization']);

// reserved names hotfix
_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].reduceDescriptors(AxiosHeaders.prototype, ({value}, key) => {
  let mapped = key[0].toUpperCase() + key.slice(1); // map `set` => `Set`
  return {
    get: () => value,
    set(headerValue) {
      this[mapped] = headerValue;
    }
  }
});

_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].freezeMethods(AxiosHeaders);

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AxiosHeaders);


/***/ }),

/***/ "./node_modules/axios/lib/core/InterceptorManager.js":
/*!***********************************************************!*\
  !*** ./node_modules/axios/lib/core/InterceptorManager.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");




class InterceptorManager {
  constructor() {
    this.handlers = [];
  }

  /**
   * Add a new interceptor to the stack
   *
   * @param {Function} fulfilled The function to handle `then` for a `Promise`
   * @param {Function} rejected The function to handle `reject` for a `Promise`
   *
   * @return {Number} An ID used to remove interceptor later
   */
  use(fulfilled, rejected, options) {
    this.handlers.push({
      fulfilled,
      rejected,
      synchronous: options ? options.synchronous : false,
      runWhen: options ? options.runWhen : null
    });
    return this.handlers.length - 1;
  }

  /**
   * Remove an interceptor from the stack
   *
   * @param {Number} id The ID that was returned by `use`
   *
   * @returns {Boolean} `true` if the interceptor was removed, `false` otherwise
   */
  eject(id) {
    if (this.handlers[id]) {
      this.handlers[id] = null;
    }
  }

  /**
   * Clear all interceptors from the stack
   *
   * @returns {void}
   */
  clear() {
    if (this.handlers) {
      this.handlers = [];
    }
  }

  /**
   * Iterate over all the registered interceptors
   *
   * This method is particularly useful for skipping over any
   * interceptors that may have become `null` calling `eject`.
   *
   * @param {Function} fn The function to call for each interceptor
   *
   * @returns {void}
   */
  forEach(fn) {
    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(this.handlers, function forEachHandler(h) {
      if (h !== null) {
        fn(h);
      }
    });
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (InterceptorManager);


/***/ }),

/***/ "./node_modules/axios/lib/core/buildFullPath.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/core/buildFullPath.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ buildFullPath)
/* harmony export */ });
/* harmony import */ var _helpers_isAbsoluteURL_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../helpers/isAbsoluteURL.js */ "./node_modules/axios/lib/helpers/isAbsoluteURL.js");
/* harmony import */ var _helpers_combineURLs_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers/combineURLs.js */ "./node_modules/axios/lib/helpers/combineURLs.js");





/**
 * Creates a new URL by combining the baseURL with the requestedURL,
 * only when the requestedURL is not already an absolute URL.
 * If the requestURL is absolute, this function returns the requestedURL untouched.
 *
 * @param {string} baseURL The base URL
 * @param {string} requestedURL Absolute or relative URL to combine
 *
 * @returns {string} The combined full path
 */
function buildFullPath(baseURL, requestedURL) {
  if (baseURL && !(0,_helpers_isAbsoluteURL_js__WEBPACK_IMPORTED_MODULE_0__["default"])(requestedURL)) {
    return (0,_helpers_combineURLs_js__WEBPACK_IMPORTED_MODULE_1__["default"])(baseURL, requestedURL);
  }
  return requestedURL;
}


/***/ }),

/***/ "./node_modules/axios/lib/core/dispatchRequest.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/core/dispatchRequest.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ dispatchRequest)
/* harmony export */ });
/* harmony import */ var _transformData_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./transformData.js */ "./node_modules/axios/lib/core/transformData.js");
/* harmony import */ var _cancel_isCancel_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../cancel/isCancel.js */ "./node_modules/axios/lib/cancel/isCancel.js");
/* harmony import */ var _defaults_index_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../defaults/index.js */ "./node_modules/axios/lib/defaults/index.js");
/* harmony import */ var _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../cancel/CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _adapters_adapters_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../adapters/adapters.js */ "./node_modules/axios/lib/adapters/adapters.js");









/**
 * Throws a `CanceledError` if cancellation has been requested.
 *
 * @param {Object} config The config that is to be used for the request
 *
 * @returns {void}
 */
function throwIfCancellationRequested(config) {
  if (config.cancelToken) {
    config.cancelToken.throwIfRequested();
  }

  if (config.signal && config.signal.aborted) {
    throw new _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_0__["default"](null, config);
  }
}

/**
 * Dispatch a request to the server using the configured adapter.
 *
 * @param {object} config The config that is to be used for the request
 *
 * @returns {Promise} The Promise to be fulfilled
 */
function dispatchRequest(config) {
  throwIfCancellationRequested(config);

  config.headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(config.headers);

  // Transform request data
  config.data = _transformData_js__WEBPACK_IMPORTED_MODULE_2__["default"].call(
    config,
    config.transformRequest
  );

  if (['post', 'put', 'patch'].indexOf(config.method) !== -1) {
    config.headers.setContentType('application/x-www-form-urlencoded', false);
  }

  const adapter = _adapters_adapters_js__WEBPACK_IMPORTED_MODULE_3__["default"].getAdapter(config.adapter || _defaults_index_js__WEBPACK_IMPORTED_MODULE_4__["default"].adapter);

  return adapter(config).then(function onAdapterResolution(response) {
    throwIfCancellationRequested(config);

    // Transform response data
    response.data = _transformData_js__WEBPACK_IMPORTED_MODULE_2__["default"].call(
      config,
      config.transformResponse,
      response
    );

    response.headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(response.headers);

    return response;
  }, function onAdapterRejection(reason) {
    if (!(0,_cancel_isCancel_js__WEBPACK_IMPORTED_MODULE_5__["default"])(reason)) {
      throwIfCancellationRequested(config);

      // Transform response data
      if (reason && reason.response) {
        reason.response.data = _transformData_js__WEBPACK_IMPORTED_MODULE_2__["default"].call(
          config,
          config.transformResponse,
          reason.response
        );
        reason.response.headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(reason.response.headers);
      }
    }

    return Promise.reject(reason);
  });
}


/***/ }),

/***/ "./node_modules/axios/lib/core/mergeConfig.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/core/mergeConfig.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ mergeConfig)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");





const headersToObject = (thing) => thing instanceof _AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_0__["default"] ? { ...thing } : thing;

/**
 * Config-specific merge-function which creates a new config-object
 * by merging two configuration objects together.
 *
 * @param {Object} config1
 * @param {Object} config2
 *
 * @returns {Object} New object resulting from merging config2 to config1
 */
function mergeConfig(config1, config2) {
  // eslint-disable-next-line no-param-reassign
  config2 = config2 || {};
  const config = {};

  function getMergedValue(target, source, caseless) {
    if (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isPlainObject(target) && _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isPlainObject(source)) {
      return _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].merge.call({caseless}, target, source);
    } else if (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isPlainObject(source)) {
      return _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].merge({}, source);
    } else if (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isArray(source)) {
      return source.slice();
    }
    return source;
  }

  // eslint-disable-next-line consistent-return
  function mergeDeepProperties(a, b, caseless) {
    if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(b)) {
      return getMergedValue(a, b, caseless);
    } else if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(a)) {
      return getMergedValue(undefined, a, caseless);
    }
  }

  // eslint-disable-next-line consistent-return
  function valueFromConfig2(a, b) {
    if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(b)) {
      return getMergedValue(undefined, b);
    }
  }

  // eslint-disable-next-line consistent-return
  function defaultToConfig2(a, b) {
    if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(b)) {
      return getMergedValue(undefined, b);
    } else if (!_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(a)) {
      return getMergedValue(undefined, a);
    }
  }

  // eslint-disable-next-line consistent-return
  function mergeDirectKeys(a, b, prop) {
    if (prop in config2) {
      return getMergedValue(a, b);
    } else if (prop in config1) {
      return getMergedValue(undefined, a);
    }
  }

  const mergeMap = {
    url: valueFromConfig2,
    method: valueFromConfig2,
    data: valueFromConfig2,
    baseURL: defaultToConfig2,
    transformRequest: defaultToConfig2,
    transformResponse: defaultToConfig2,
    paramsSerializer: defaultToConfig2,
    timeout: defaultToConfig2,
    timeoutMessage: defaultToConfig2,
    withCredentials: defaultToConfig2,
    withXSRFToken: defaultToConfig2,
    adapter: defaultToConfig2,
    responseType: defaultToConfig2,
    xsrfCookieName: defaultToConfig2,
    xsrfHeaderName: defaultToConfig2,
    onUploadProgress: defaultToConfig2,
    onDownloadProgress: defaultToConfig2,
    decompress: defaultToConfig2,
    maxContentLength: defaultToConfig2,
    maxBodyLength: defaultToConfig2,
    beforeRedirect: defaultToConfig2,
    transport: defaultToConfig2,
    httpAgent: defaultToConfig2,
    httpsAgent: defaultToConfig2,
    cancelToken: defaultToConfig2,
    socketPath: defaultToConfig2,
    responseEncoding: defaultToConfig2,
    validateStatus: mergeDirectKeys,
    headers: (a, b) => mergeDeepProperties(headersToObject(a), headersToObject(b), true)
  };

  _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].forEach(Object.keys(Object.assign({}, config1, config2)), function computeConfigValue(prop) {
    const merge = mergeMap[prop] || mergeDeepProperties;
    const configValue = merge(config1[prop], config2[prop], prop);
    (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isUndefined(configValue) && merge !== mergeDirectKeys) || (config[prop] = configValue);
  });

  return config;
}


/***/ }),

/***/ "./node_modules/axios/lib/core/settle.js":
/*!***********************************************!*\
  !*** ./node_modules/axios/lib/core/settle.js ***!
  \***********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ settle)
/* harmony export */ });
/* harmony import */ var _AxiosError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");




/**
 * Resolve or reject a Promise based on response status.
 *
 * @param {Function} resolve A function that resolves the promise.
 * @param {Function} reject A function that rejects the promise.
 * @param {object} response The response.
 *
 * @returns {object} The response.
 */
function settle(resolve, reject, response) {
  const validateStatus = response.config.validateStatus;
  if (!response.status || !validateStatus || validateStatus(response.status)) {
    resolve(response);
  } else {
    reject(new _AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"](
      'Request failed with status code ' + response.status,
      [_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].ERR_BAD_REQUEST, _AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].ERR_BAD_RESPONSE][Math.floor(response.status / 100) - 4],
      response.config,
      response.request,
      response
    ));
  }
}


/***/ }),

/***/ "./node_modules/axios/lib/core/transformData.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/core/transformData.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ transformData)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _defaults_index_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../defaults/index.js */ "./node_modules/axios/lib/defaults/index.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");






/**
 * Transform the data for a request or a response
 *
 * @param {Array|Function} fns A single function or Array of functions
 * @param {?Object} response The response object
 *
 * @returns {*} The resulting transformed data
 */
function transformData(fns, response) {
  const config = this || _defaults_index_js__WEBPACK_IMPORTED_MODULE_0__["default"];
  const context = response || config;
  const headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(context.headers);
  let data = context.data;

  _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].forEach(fns, function transform(fn) {
    data = fn.call(config, data, headers.normalize(), response ? response.status : undefined);
  });

  headers.normalize();

  return data;
}


/***/ }),

/***/ "./node_modules/axios/lib/defaults/index.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/defaults/index.js ***!
  \**************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _transitional_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./transitional.js */ "./node_modules/axios/lib/defaults/transitional.js");
/* harmony import */ var _helpers_toFormData_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../helpers/toFormData.js */ "./node_modules/axios/lib/helpers/toFormData.js");
/* harmony import */ var _helpers_toURLEncodedForm_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../helpers/toURLEncodedForm.js */ "./node_modules/axios/lib/helpers/toURLEncodedForm.js");
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");
/* harmony import */ var _helpers_formDataToJSON_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../helpers/formDataToJSON.js */ "./node_modules/axios/lib/helpers/formDataToJSON.js");










/**
 * It takes a string, tries to parse it, and if it fails, it returns the stringified version
 * of the input
 *
 * @param {any} rawValue - The value to be stringified.
 * @param {Function} parser - A function that parses a string into a JavaScript object.
 * @param {Function} encoder - A function that takes a value and returns a string.
 *
 * @returns {string} A stringified version of the rawValue.
 */
function stringifySafely(rawValue, parser, encoder) {
  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(rawValue)) {
    try {
      (parser || JSON.parse)(rawValue);
      return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].trim(rawValue);
    } catch (e) {
      if (e.name !== 'SyntaxError') {
        throw e;
      }
    }
  }

  return (encoder || JSON.stringify)(rawValue);
}

const defaults = {

  transitional: _transitional_js__WEBPACK_IMPORTED_MODULE_1__["default"],

  adapter: ['xhr', 'http', 'fetch'],

  transformRequest: [function transformRequest(data, headers) {
    const contentType = headers.getContentType() || '';
    const hasJSONContentType = contentType.indexOf('application/json') > -1;
    const isObjectPayload = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(data);

    if (isObjectPayload && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isHTMLForm(data)) {
      data = new FormData(data);
    }

    const isFormData = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFormData(data);

    if (isFormData) {
      return hasJSONContentType ? JSON.stringify((0,_helpers_formDataToJSON_js__WEBPACK_IMPORTED_MODULE_2__["default"])(data)) : data;
    }

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArrayBuffer(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isBuffer(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isStream(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFile(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isBlob(data) ||
      _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isReadableStream(data)
    ) {
      return data;
    }
    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArrayBufferView(data)) {
      return data.buffer;
    }
    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isURLSearchParams(data)) {
      headers.setContentType('application/x-www-form-urlencoded;charset=utf-8', false);
      return data.toString();
    }

    let isFileList;

    if (isObjectPayload) {
      if (contentType.indexOf('application/x-www-form-urlencoded') > -1) {
        return (0,_helpers_toURLEncodedForm_js__WEBPACK_IMPORTED_MODULE_3__["default"])(data, this.formSerializer).toString();
      }

      if ((isFileList = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFileList(data)) || contentType.indexOf('multipart/form-data') > -1) {
        const _FormData = this.env && this.env.FormData;

        return (0,_helpers_toFormData_js__WEBPACK_IMPORTED_MODULE_4__["default"])(
          isFileList ? {'files[]': data} : data,
          _FormData && new _FormData(),
          this.formSerializer
        );
      }
    }

    if (isObjectPayload || hasJSONContentType ) {
      headers.setContentType('application/json', false);
      return stringifySafely(data);
    }

    return data;
  }],

  transformResponse: [function transformResponse(data) {
    const transitional = this.transitional || defaults.transitional;
    const forcedJSONParsing = transitional && transitional.forcedJSONParsing;
    const JSONRequested = this.responseType === 'json';

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isResponse(data) || _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isReadableStream(data)) {
      return data;
    }

    if (data && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(data) && ((forcedJSONParsing && !this.responseType) || JSONRequested)) {
      const silentJSONParsing = transitional && transitional.silentJSONParsing;
      const strictJSONParsing = !silentJSONParsing && JSONRequested;

      try {
        return JSON.parse(data);
      } catch (e) {
        if (strictJSONParsing) {
          if (e.name === 'SyntaxError') {
            throw _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_5__["default"].from(e, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_5__["default"].ERR_BAD_RESPONSE, this, null, this.response);
          }
          throw e;
        }
      }
    }

    return data;
  }],

  /**
   * A timeout in milliseconds to abort a request. If set to 0 (default) a
   * timeout is not created.
   */
  timeout: 0,

  xsrfCookieName: 'XSRF-TOKEN',
  xsrfHeaderName: 'X-XSRF-TOKEN',

  maxContentLength: -1,
  maxBodyLength: -1,

  env: {
    FormData: _platform_index_js__WEBPACK_IMPORTED_MODULE_6__["default"].classes.FormData,
    Blob: _platform_index_js__WEBPACK_IMPORTED_MODULE_6__["default"].classes.Blob
  },

  validateStatus: function validateStatus(status) {
    return status >= 200 && status < 300;
  },

  headers: {
    common: {
      'Accept': 'application/json, text/plain, */*',
      'Content-Type': undefined
    }
  }
};

_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(['delete', 'get', 'head', 'post', 'put', 'patch'], (method) => {
  defaults.headers[method] = {};
});

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (defaults);


/***/ }),

/***/ "./node_modules/axios/lib/defaults/transitional.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/defaults/transitional.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  silentJSONParsing: true,
  forcedJSONParsing: true,
  clarifyTimeoutError: false
});


/***/ }),

/***/ "./node_modules/axios/lib/env/data.js":
/*!********************************************!*\
  !*** ./node_modules/axios/lib/env/data.js ***!
  \********************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   VERSION: () => (/* binding */ VERSION)
/* harmony export */ });
const VERSION = "1.7.7";

/***/ }),

/***/ "./node_modules/axios/lib/helpers/AxiosURLSearchParams.js":
/*!****************************************************************!*\
  !*** ./node_modules/axios/lib/helpers/AxiosURLSearchParams.js ***!
  \****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _toFormData_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toFormData.js */ "./node_modules/axios/lib/helpers/toFormData.js");




/**
 * It encodes a string by replacing all characters that are not in the unreserved set with
 * their percent-encoded equivalents
 *
 * @param {string} str - The string to encode.
 *
 * @returns {string} The encoded string.
 */
function encode(str) {
  const charMap = {
    '!': '%21',
    "'": '%27',
    '(': '%28',
    ')': '%29',
    '~': '%7E',
    '%20': '+',
    '%00': '\x00'
  };
  return encodeURIComponent(str).replace(/[!'()~]|%20|%00/g, function replacer(match) {
    return charMap[match];
  });
}

/**
 * It takes a params object and converts it to a FormData object
 *
 * @param {Object<string, any>} params - The parameters to be converted to a FormData object.
 * @param {Object<string, any>} options - The options object passed to the Axios constructor.
 *
 * @returns {void}
 */
function AxiosURLSearchParams(params, options) {
  this._pairs = [];

  params && (0,_toFormData_js__WEBPACK_IMPORTED_MODULE_0__["default"])(params, this, options);
}

const prototype = AxiosURLSearchParams.prototype;

prototype.append = function append(name, value) {
  this._pairs.push([name, value]);
};

prototype.toString = function toString(encoder) {
  const _encode = encoder ? function(value) {
    return encoder.call(this, value, encode);
  } : encode;

  return this._pairs.map(function each(pair) {
    return _encode(pair[0]) + '=' + _encode(pair[1]);
  }, '').join('&');
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (AxiosURLSearchParams);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/HttpStatusCode.js":
/*!**********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/HttpStatusCode.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const HttpStatusCode = {
  Continue: 100,
  SwitchingProtocols: 101,
  Processing: 102,
  EarlyHints: 103,
  Ok: 200,
  Created: 201,
  Accepted: 202,
  NonAuthoritativeInformation: 203,
  NoContent: 204,
  ResetContent: 205,
  PartialContent: 206,
  MultiStatus: 207,
  AlreadyReported: 208,
  ImUsed: 226,
  MultipleChoices: 300,
  MovedPermanently: 301,
  Found: 302,
  SeeOther: 303,
  NotModified: 304,
  UseProxy: 305,
  Unused: 306,
  TemporaryRedirect: 307,
  PermanentRedirect: 308,
  BadRequest: 400,
  Unauthorized: 401,
  PaymentRequired: 402,
  Forbidden: 403,
  NotFound: 404,
  MethodNotAllowed: 405,
  NotAcceptable: 406,
  ProxyAuthenticationRequired: 407,
  RequestTimeout: 408,
  Conflict: 409,
  Gone: 410,
  LengthRequired: 411,
  PreconditionFailed: 412,
  PayloadTooLarge: 413,
  UriTooLong: 414,
  UnsupportedMediaType: 415,
  RangeNotSatisfiable: 416,
  ExpectationFailed: 417,
  ImATeapot: 418,
  MisdirectedRequest: 421,
  UnprocessableEntity: 422,
  Locked: 423,
  FailedDependency: 424,
  TooEarly: 425,
  UpgradeRequired: 426,
  PreconditionRequired: 428,
  TooManyRequests: 429,
  RequestHeaderFieldsTooLarge: 431,
  UnavailableForLegalReasons: 451,
  InternalServerError: 500,
  NotImplemented: 501,
  BadGateway: 502,
  ServiceUnavailable: 503,
  GatewayTimeout: 504,
  HttpVersionNotSupported: 505,
  VariantAlsoNegotiates: 506,
  InsufficientStorage: 507,
  LoopDetected: 508,
  NotExtended: 510,
  NetworkAuthenticationRequired: 511,
};

Object.entries(HttpStatusCode).forEach(([key, value]) => {
  HttpStatusCode[value] = key;
});

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (HttpStatusCode);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/bind.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/helpers/bind.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ bind)
/* harmony export */ });


function bind(fn, thisArg) {
  return function wrap() {
    return fn.apply(thisArg, arguments);
  };
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/buildURL.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/helpers/buildURL.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ buildURL)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _helpers_AxiosURLSearchParams_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../helpers/AxiosURLSearchParams.js */ "./node_modules/axios/lib/helpers/AxiosURLSearchParams.js");





/**
 * It replaces all instances of the characters `:`, `$`, `,`, `+`, `[`, and `]` with their
 * URI encoded counterparts
 *
 * @param {string} val The value to be encoded.
 *
 * @returns {string} The encoded value.
 */
function encode(val) {
  return encodeURIComponent(val).
    replace(/%3A/gi, ':').
    replace(/%24/g, '$').
    replace(/%2C/gi, ',').
    replace(/%20/g, '+').
    replace(/%5B/gi, '[').
    replace(/%5D/gi, ']');
}

/**
 * Build a URL by appending params to the end
 *
 * @param {string} url The base of the url (e.g., http://www.google.com)
 * @param {object} [params] The params to be appended
 * @param {?object} options
 *
 * @returns {string} The formatted url
 */
function buildURL(url, params, options) {
  /*eslint no-param-reassign:0*/
  if (!params) {
    return url;
  }
  
  const _encode = options && options.encode || encode;

  const serializeFn = options && options.serialize;

  let serializedParams;

  if (serializeFn) {
    serializedParams = serializeFn(params, options);
  } else {
    serializedParams = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isURLSearchParams(params) ?
      params.toString() :
      new _helpers_AxiosURLSearchParams_js__WEBPACK_IMPORTED_MODULE_1__["default"](params, options).toString(_encode);
  }

  if (serializedParams) {
    const hashmarkIndex = url.indexOf("#");

    if (hashmarkIndex !== -1) {
      url = url.slice(0, hashmarkIndex);
    }
    url += (url.indexOf('?') === -1 ? '?' : '&') + serializedParams;
  }

  return url;
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/combineURLs.js":
/*!*******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/combineURLs.js ***!
  \*******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ combineURLs)
/* harmony export */ });


/**
 * Creates a new URL by combining the specified URLs
 *
 * @param {string} baseURL The base URL
 * @param {string} relativeURL The relative URL
 *
 * @returns {string} The combined URL
 */
function combineURLs(baseURL, relativeURL) {
  return relativeURL
    ? baseURL.replace(/\/?\/$/, '') + '/' + relativeURL.replace(/^\/+/, '')
    : baseURL;
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/composeSignals.js":
/*!**********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/composeSignals.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../cancel/CanceledError.js */ "./node_modules/axios/lib/cancel/CanceledError.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");




const composeSignals = (signals, timeout) => {
  const {length} = (signals = signals ? signals.filter(Boolean) : []);

  if (timeout || length) {
    let controller = new AbortController();

    let aborted;

    const onabort = function (reason) {
      if (!aborted) {
        aborted = true;
        unsubscribe();
        const err = reason instanceof Error ? reason : this.reason;
        controller.abort(err instanceof _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"] ? err : new _cancel_CanceledError_js__WEBPACK_IMPORTED_MODULE_1__["default"](err instanceof Error ? err.message : err));
      }
    }

    let timer = timeout && setTimeout(() => {
      timer = null;
      onabort(new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"](`timeout ${timeout} of ms exceeded`, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_0__["default"].ETIMEDOUT))
    }, timeout)

    const unsubscribe = () => {
      if (signals) {
        timer && clearTimeout(timer);
        timer = null;
        signals.forEach(signal => {
          signal.unsubscribe ? signal.unsubscribe(onabort) : signal.removeEventListener('abort', onabort);
        });
        signals = null;
      }
    }

    signals.forEach((signal) => signal.addEventListener('abort', onabort));

    const {signal} = controller;

    signal.unsubscribe = () => _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].asap(unsubscribe);

    return signal;
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (composeSignals);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/cookies.js":
/*!***************************************************!*\
  !*** ./node_modules/axios/lib/helpers/cookies.js ***!
  \***************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].hasStandardBrowserEnv ?

  // Standard browser envs support document.cookie
  {
    write(name, value, expires, path, domain, secure) {
      const cookie = [name + '=' + encodeURIComponent(value)];

      _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isNumber(expires) && cookie.push('expires=' + new Date(expires).toGMTString());

      _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isString(path) && cookie.push('path=' + path);

      _utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isString(domain) && cookie.push('domain=' + domain);

      secure === true && cookie.push('secure');

      document.cookie = cookie.join('; ');
    },

    read(name) {
      const match = document.cookie.match(new RegExp('(^|;\\s*)(' + name + ')=([^;]*)'));
      return (match ? decodeURIComponent(match[3]) : null);
    },

    remove(name) {
      this.write(name, '', Date.now() - 86400000);
    }
  }

  :

  // Non-standard browser env (web workers, react-native) lack needed support.
  {
    write() {},
    read() {
      return null;
    },
    remove() {}
  });



/***/ }),

/***/ "./node_modules/axios/lib/helpers/formDataToJSON.js":
/*!**********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/formDataToJSON.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");




/**
 * It takes a string like `foo[x][y][z]` and returns an array like `['foo', 'x', 'y', 'z']
 *
 * @param {string} name - The name of the property to get.
 *
 * @returns An array of strings.
 */
function parsePropPath(name) {
  // foo[x][y][z]
  // foo.x.y.z
  // foo-x-y-z
  // foo x y z
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].matchAll(/\w+|\[(\w*)]/g, name).map(match => {
    return match[0] === '[]' ? '' : match[1] || match[0];
  });
}

/**
 * Convert an array to an object.
 *
 * @param {Array<any>} arr - The array to convert to an object.
 *
 * @returns An object with the same keys and values as the array.
 */
function arrayToObject(arr) {
  const obj = {};
  const keys = Object.keys(arr);
  let i;
  const len = keys.length;
  let key;
  for (i = 0; i < len; i++) {
    key = keys[i];
    obj[key] = arr[key];
  }
  return obj;
}

/**
 * It takes a FormData object and returns a JavaScript object
 *
 * @param {string} formData The FormData object to convert to JSON.
 *
 * @returns {Object<string, any> | null} The converted object.
 */
function formDataToJSON(formData) {
  function buildPath(path, value, target, index) {
    let name = path[index++];

    if (name === '__proto__') return true;

    const isNumericKey = Number.isFinite(+name);
    const isLast = index >= path.length;
    name = !name && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(target) ? target.length : name;

    if (isLast) {
      if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].hasOwnProp(target, name)) {
        target[name] = [target[name], value];
      } else {
        target[name] = value;
      }

      return !isNumericKey;
    }

    if (!target[name] || !_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(target[name])) {
      target[name] = [];
    }

    const result = buildPath(path, value, target[name], index);

    if (result && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(target[name])) {
      target[name] = arrayToObject(target[name]);
    }

    return !isNumericKey;
  }

  if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFormData(formData) && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFunction(formData.entries)) {
    const obj = {};

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEachEntry(formData, (name, value) => {
      buildPath(parsePropPath(name), value, obj, 0);
    });

    return obj;
  }

  return null;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (formDataToJSON);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isAbsoluteURL.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isAbsoluteURL.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ isAbsoluteURL)
/* harmony export */ });


/**
 * Determines whether the specified URL is absolute
 *
 * @param {string} url The URL to test
 *
 * @returns {boolean} True if the specified URL is absolute, otherwise false
 */
function isAbsoluteURL(url) {
  // A URL is considered absolute if it begins with "<scheme>://" or "//" (protocol-relative URL).
  // RFC 3986 defines scheme name as a sequence of characters beginning with a letter and followed
  // by any combination of letters, digits, plus, period, or hyphen.
  return /^([a-z][a-z\d+\-.]*:)?\/\//i.test(url);
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isAxiosError.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isAxiosError.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ isAxiosError)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");




/**
 * Determines whether the payload is an error thrown by Axios
 *
 * @param {*} payload The value to test
 *
 * @returns {boolean} True if the payload is an error thrown by Axios, otherwise false
 */
function isAxiosError(payload) {
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(payload) && (payload.isAxiosError === true);
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/isURLSameOrigin.js":
/*!***********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/isURLSameOrigin.js ***!
  \***********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");





/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].hasStandardBrowserEnv ?

// Standard browser envs have full support of the APIs needed to test
// whether the request URL is of the same origin as current location.
  (function standardBrowserEnv() {
    const msie = _platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].navigator && /(msie|trident)/i.test(_platform_index_js__WEBPACK_IMPORTED_MODULE_0__["default"].navigator.userAgent);
    const urlParsingNode = document.createElement('a');
    let originURL;

    /**
    * Parse a URL to discover its components
    *
    * @param {String} url The URL to be parsed
    * @returns {Object}
    */
    function resolveURL(url) {
      let href = url;

      if (msie) {
        // IE needs attribute set twice to normalize properties
        urlParsingNode.setAttribute('href', href);
        href = urlParsingNode.href;
      }

      urlParsingNode.setAttribute('href', href);

      // urlParsingNode provides the UrlUtils interface - http://url.spec.whatwg.org/#urlutils
      return {
        href: urlParsingNode.href,
        protocol: urlParsingNode.protocol ? urlParsingNode.protocol.replace(/:$/, '') : '',
        host: urlParsingNode.host,
        search: urlParsingNode.search ? urlParsingNode.search.replace(/^\?/, '') : '',
        hash: urlParsingNode.hash ? urlParsingNode.hash.replace(/^#/, '') : '',
        hostname: urlParsingNode.hostname,
        port: urlParsingNode.port,
        pathname: (urlParsingNode.pathname.charAt(0) === '/') ?
          urlParsingNode.pathname :
          '/' + urlParsingNode.pathname
      };
    }

    originURL = resolveURL(window.location.href);

    /**
    * Determine if a URL shares the same origin as the current location
    *
    * @param {String} requestURL The URL to test
    * @returns {boolean} True if URL shares the same origin, otherwise false
    */
    return function isURLSameOrigin(requestURL) {
      const parsed = (_utils_js__WEBPACK_IMPORTED_MODULE_1__["default"].isString(requestURL)) ? resolveURL(requestURL) : requestURL;
      return (parsed.protocol === originURL.protocol &&
          parsed.host === originURL.host);
    };
  })() :

  // Non standard browser envs (web workers, react-native) lack needed support.
  (function nonStandardBrowserEnv() {
    return function isURLSameOrigin() {
      return true;
    };
  })());


/***/ }),

/***/ "./node_modules/axios/lib/helpers/null.js":
/*!************************************************!*\
  !*** ./node_modules/axios/lib/helpers/null.js ***!
  \************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// eslint-disable-next-line strict
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (null);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/parseHeaders.js":
/*!********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/parseHeaders.js ***!
  \********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./../utils.js */ "./node_modules/axios/lib/utils.js");




// RawAxiosHeaders whose duplicates are ignored by node
// c.f. https://nodejs.org/api/http.html#http_message_headers
const ignoreDuplicateOf = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toObjectSet([
  'age', 'authorization', 'content-length', 'content-type', 'etag',
  'expires', 'from', 'host', 'if-modified-since', 'if-unmodified-since',
  'last-modified', 'location', 'max-forwards', 'proxy-authorization',
  'referer', 'retry-after', 'user-agent'
]);

/**
 * Parse headers into an object
 *
 * ```
 * Date: Wed, 27 Aug 2014 08:58:49 GMT
 * Content-Type: application/json
 * Connection: keep-alive
 * Transfer-Encoding: chunked
 * ```
 *
 * @param {String} rawHeaders Headers needing to be parsed
 *
 * @returns {Object} Headers parsed into an object
 */
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (rawHeaders => {
  const parsed = {};
  let key;
  let val;
  let i;

  rawHeaders && rawHeaders.split('\n').forEach(function parser(line) {
    i = line.indexOf(':');
    key = line.substring(0, i).trim().toLowerCase();
    val = line.substring(i + 1).trim();

    if (!key || (parsed[key] && ignoreDuplicateOf[key])) {
      return;
    }

    if (key === 'set-cookie') {
      if (parsed[key]) {
        parsed[key].push(val);
      } else {
        parsed[key] = [val];
      }
    } else {
      parsed[key] = parsed[key] ? parsed[key] + ', ' + val : val;
    }
  });

  return parsed;
});


/***/ }),

/***/ "./node_modules/axios/lib/helpers/parseProtocol.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/parseProtocol.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ parseProtocol)
/* harmony export */ });


function parseProtocol(url) {
  const match = /^([-+\w]{1,25})(:?\/\/|:)/.exec(url);
  return match && match[1] || '';
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/progressEventReducer.js":
/*!****************************************************************!*\
  !*** ./node_modules/axios/lib/helpers/progressEventReducer.js ***!
  \****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   asyncDecorator: () => (/* binding */ asyncDecorator),
/* harmony export */   progressEventDecorator: () => (/* binding */ progressEventDecorator),
/* harmony export */   progressEventReducer: () => (/* binding */ progressEventReducer)
/* harmony export */ });
/* harmony import */ var _speedometer_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./speedometer.js */ "./node_modules/axios/lib/helpers/speedometer.js");
/* harmony import */ var _throttle_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./throttle.js */ "./node_modules/axios/lib/helpers/throttle.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");




const progressEventReducer = (listener, isDownloadStream, freq = 3) => {
  let bytesNotified = 0;
  const _speedometer = (0,_speedometer_js__WEBPACK_IMPORTED_MODULE_0__["default"])(50, 250);

  return (0,_throttle_js__WEBPACK_IMPORTED_MODULE_1__["default"])(e => {
    const loaded = e.loaded;
    const total = e.lengthComputable ? e.total : undefined;
    const progressBytes = loaded - bytesNotified;
    const rate = _speedometer(progressBytes);
    const inRange = loaded <= total;

    bytesNotified = loaded;

    const data = {
      loaded,
      total,
      progress: total ? (loaded / total) : undefined,
      bytes: progressBytes,
      rate: rate ? rate : undefined,
      estimated: rate && total && inRange ? (total - loaded) / rate : undefined,
      event: e,
      lengthComputable: total != null,
      [isDownloadStream ? 'download' : 'upload']: true
    };

    listener(data);
  }, freq);
}

const progressEventDecorator = (total, throttled) => {
  const lengthComputable = total != null;

  return [(loaded) => throttled[0]({
    lengthComputable,
    total,
    loaded
  }), throttled[1]];
}

const asyncDecorator = (fn) => (...args) => _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].asap(() => fn(...args));


/***/ }),

/***/ "./node_modules/axios/lib/helpers/resolveConfig.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/helpers/resolveConfig.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _isURLSameOrigin_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./isURLSameOrigin.js */ "./node_modules/axios/lib/helpers/isURLSameOrigin.js");
/* harmony import */ var _cookies_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./cookies.js */ "./node_modules/axios/lib/helpers/cookies.js");
/* harmony import */ var _core_buildFullPath_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../core/buildFullPath.js */ "./node_modules/axios/lib/core/buildFullPath.js");
/* harmony import */ var _core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../core/mergeConfig.js */ "./node_modules/axios/lib/core/mergeConfig.js");
/* harmony import */ var _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosHeaders.js */ "./node_modules/axios/lib/core/AxiosHeaders.js");
/* harmony import */ var _buildURL_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./buildURL.js */ "./node_modules/axios/lib/helpers/buildURL.js");









/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((config) => {
  const newConfig = (0,_core_mergeConfig_js__WEBPACK_IMPORTED_MODULE_0__["default"])({}, config);

  let {data, withXSRFToken, xsrfHeaderName, xsrfCookieName, headers, auth} = newConfig;

  newConfig.headers = headers = _core_AxiosHeaders_js__WEBPACK_IMPORTED_MODULE_1__["default"].from(headers);

  newConfig.url = (0,_buildURL_js__WEBPACK_IMPORTED_MODULE_2__["default"])((0,_core_buildFullPath_js__WEBPACK_IMPORTED_MODULE_3__["default"])(newConfig.baseURL, newConfig.url), config.params, config.paramsSerializer);

  // HTTP basic authentication
  if (auth) {
    headers.set('Authorization', 'Basic ' +
      btoa((auth.username || '') + ':' + (auth.password ? unescape(encodeURIComponent(auth.password)) : ''))
    );
  }

  let contentType;

  if (_utils_js__WEBPACK_IMPORTED_MODULE_4__["default"].isFormData(data)) {
    if (_platform_index_js__WEBPACK_IMPORTED_MODULE_5__["default"].hasStandardBrowserEnv || _platform_index_js__WEBPACK_IMPORTED_MODULE_5__["default"].hasStandardBrowserWebWorkerEnv) {
      headers.setContentType(undefined); // Let the browser set it
    } else if ((contentType = headers.getContentType()) !== false) {
      // fix semicolon duplication issue for ReactNative FormData implementation
      const [type, ...tokens] = contentType ? contentType.split(';').map(token => token.trim()).filter(Boolean) : [];
      headers.setContentType([type || 'multipart/form-data', ...tokens].join('; '));
    }
  }

  // Add xsrf header
  // This is only done if running in a standard browser environment.
  // Specifically not if we're in a web worker, or react-native.

  if (_platform_index_js__WEBPACK_IMPORTED_MODULE_5__["default"].hasStandardBrowserEnv) {
    withXSRFToken && _utils_js__WEBPACK_IMPORTED_MODULE_4__["default"].isFunction(withXSRFToken) && (withXSRFToken = withXSRFToken(newConfig));

    if (withXSRFToken || (withXSRFToken !== false && (0,_isURLSameOrigin_js__WEBPACK_IMPORTED_MODULE_6__["default"])(newConfig.url))) {
      // Add xsrf header
      const xsrfValue = xsrfHeaderName && xsrfCookieName && _cookies_js__WEBPACK_IMPORTED_MODULE_7__["default"].read(xsrfCookieName);

      if (xsrfValue) {
        headers.set(xsrfHeaderName, xsrfValue);
      }
    }
  }

  return newConfig;
});



/***/ }),

/***/ "./node_modules/axios/lib/helpers/speedometer.js":
/*!*******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/speedometer.js ***!
  \*******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });


/**
 * Calculate data maxRate
 * @param {Number} [samplesCount= 10]
 * @param {Number} [min= 1000]
 * @returns {Function}
 */
function speedometer(samplesCount, min) {
  samplesCount = samplesCount || 10;
  const bytes = new Array(samplesCount);
  const timestamps = new Array(samplesCount);
  let head = 0;
  let tail = 0;
  let firstSampleTS;

  min = min !== undefined ? min : 1000;

  return function push(chunkLength) {
    const now = Date.now();

    const startedAt = timestamps[tail];

    if (!firstSampleTS) {
      firstSampleTS = now;
    }

    bytes[head] = chunkLength;
    timestamps[head] = now;

    let i = tail;
    let bytesCount = 0;

    while (i !== head) {
      bytesCount += bytes[i++];
      i = i % samplesCount;
    }

    head = (head + 1) % samplesCount;

    if (head === tail) {
      tail = (tail + 1) % samplesCount;
    }

    if (now - firstSampleTS < min) {
      return;
    }

    const passed = startedAt && now - startedAt;

    return passed ? Math.round(bytesCount * 1000 / passed) : undefined;
  };
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (speedometer);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/spread.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/helpers/spread.js ***!
  \**************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ spread)
/* harmony export */ });


/**
 * Syntactic sugar for invoking a function and expanding an array for arguments.
 *
 * Common use case would be to use `Function.prototype.apply`.
 *
 *  ```js
 *  function f(x, y, z) {}
 *  var args = [1, 2, 3];
 *  f.apply(null, args);
 *  ```
 *
 * With `spread` this example can be re-written.
 *
 *  ```js
 *  spread(function(x, y, z) {})([1, 2, 3]);
 *  ```
 *
 * @param {Function} callback
 *
 * @returns {Function}
 */
function spread(callback) {
  return function wrap(arr) {
    return callback.apply(null, arr);
  };
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/throttle.js":
/*!****************************************************!*\
  !*** ./node_modules/axios/lib/helpers/throttle.js ***!
  \****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Throttle decorator
 * @param {Function} fn
 * @param {Number} freq
 * @return {Function}
 */
function throttle(fn, freq) {
  let timestamp = 0;
  let threshold = 1000 / freq;
  let lastArgs;
  let timer;

  const invoke = (args, now = Date.now()) => {
    timestamp = now;
    lastArgs = null;
    if (timer) {
      clearTimeout(timer);
      timer = null;
    }
    fn.apply(null, args);
  }

  const throttled = (...args) => {
    const now = Date.now();
    const passed = now - timestamp;
    if ( passed >= threshold) {
      invoke(args, now);
    } else {
      lastArgs = args;
      if (!timer) {
        timer = setTimeout(() => {
          timer = null;
          invoke(lastArgs)
        }, threshold - passed);
      }
    }
  }

  const flush = () => lastArgs && invoke(lastArgs);

  return [throttled, flush];
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (throttle);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/toFormData.js":
/*!******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/toFormData.js ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");
/* harmony import */ var _platform_node_classes_FormData_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../platform/node/classes/FormData.js */ "./node_modules/axios/lib/helpers/null.js");




// temporary hotfix to avoid circular references until AxiosURLSearchParams is refactored


/**
 * Determines if the given thing is a array or js object.
 *
 * @param {string} thing - The object or array to be visited.
 *
 * @returns {boolean}
 */
function isVisitable(thing) {
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isPlainObject(thing) || _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(thing);
}

/**
 * It removes the brackets from the end of a string
 *
 * @param {string} key - The key of the parameter.
 *
 * @returns {string} the key without the brackets.
 */
function removeBrackets(key) {
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].endsWith(key, '[]') ? key.slice(0, -2) : key;
}

/**
 * It takes a path, a key, and a boolean, and returns a string
 *
 * @param {string} path - The path to the current key.
 * @param {string} key - The key of the current object being iterated over.
 * @param {string} dots - If true, the key will be rendered with dots instead of brackets.
 *
 * @returns {string} The path to the current key.
 */
function renderKey(path, key, dots) {
  if (!path) return key;
  return path.concat(key).map(function each(token, i) {
    // eslint-disable-next-line no-param-reassign
    token = removeBrackets(token);
    return !dots && i ? '[' + token + ']' : token;
  }).join(dots ? '.' : '');
}

/**
 * If the array is an array and none of its elements are visitable, then it's a flat array.
 *
 * @param {Array<any>} arr - The array to check
 *
 * @returns {boolean}
 */
function isFlatArray(arr) {
  return _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(arr) && !arr.some(isVisitable);
}

const predicates = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toFlatObject(_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"], {}, null, function filter(prop) {
  return /^is[A-Z]/.test(prop);
});

/**
 * Convert a data object to FormData
 *
 * @param {Object} obj
 * @param {?Object} [formData]
 * @param {?Object} [options]
 * @param {Function} [options.visitor]
 * @param {Boolean} [options.metaTokens = true]
 * @param {Boolean} [options.dots = false]
 * @param {?Boolean} [options.indexes = false]
 *
 * @returns {Object}
 **/

/**
 * It converts an object into a FormData object
 *
 * @param {Object<any, any>} obj - The object to convert to form data.
 * @param {string} formData - The FormData object to append to.
 * @param {Object<string, any>} options
 *
 * @returns
 */
function toFormData(obj, formData, options) {
  if (!_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(obj)) {
    throw new TypeError('target must be an object');
  }

  // eslint-disable-next-line no-param-reassign
  formData = formData || new (_platform_node_classes_FormData_js__WEBPACK_IMPORTED_MODULE_1__["default"] || FormData)();

  // eslint-disable-next-line no-param-reassign
  options = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toFlatObject(options, {
    metaTokens: true,
    dots: false,
    indexes: false
  }, false, function defined(option, source) {
    // eslint-disable-next-line no-eq-null,eqeqeq
    return !_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isUndefined(source[option]);
  });

  const metaTokens = options.metaTokens;
  // eslint-disable-next-line no-use-before-define
  const visitor = options.visitor || defaultVisitor;
  const dots = options.dots;
  const indexes = options.indexes;
  const _Blob = options.Blob || typeof Blob !== 'undefined' && Blob;
  const useBlob = _Blob && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isSpecCompliantForm(formData);

  if (!_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFunction(visitor)) {
    throw new TypeError('visitor must be a function');
  }

  function convertValue(value) {
    if (value === null) return '';

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isDate(value)) {
      return value.toISOString();
    }

    if (!useBlob && _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isBlob(value)) {
      throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_2__["default"]('Blob is not supported. Use a Buffer instead.');
    }

    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArrayBuffer(value) || _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isTypedArray(value)) {
      return useBlob && typeof Blob === 'function' ? new Blob([value]) : Buffer.from(value);
    }

    return value;
  }

  /**
   * Default visitor.
   *
   * @param {*} value
   * @param {String|Number} key
   * @param {Array<String|Number>} path
   * @this {FormData}
   *
   * @returns {boolean} return true to visit the each prop of the value recursively
   */
  function defaultVisitor(value, key, path) {
    let arr = value;

    if (value && !path && typeof value === 'object') {
      if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].endsWith(key, '{}')) {
        // eslint-disable-next-line no-param-reassign
        key = metaTokens ? key : key.slice(0, -2);
        // eslint-disable-next-line no-param-reassign
        value = JSON.stringify(value);
      } else if (
        (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isArray(value) && isFlatArray(value)) ||
        ((_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isFileList(value) || _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].endsWith(key, '[]')) && (arr = _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].toArray(value))
        )) {
        // eslint-disable-next-line no-param-reassign
        key = removeBrackets(key);

        arr.forEach(function each(el, index) {
          !(_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isUndefined(el) || el === null) && formData.append(
            // eslint-disable-next-line no-nested-ternary
            indexes === true ? renderKey([key], index, dots) : (indexes === null ? key : key + '[]'),
            convertValue(el)
          );
        });
        return false;
      }
    }

    if (isVisitable(value)) {
      return true;
    }

    formData.append(renderKey(path, key, dots), convertValue(value));

    return false;
  }

  const stack = [];

  const exposedHelpers = Object.assign(predicates, {
    defaultVisitor,
    convertValue,
    isVisitable
  });

  function build(value, path) {
    if (_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isUndefined(value)) return;

    if (stack.indexOf(value) !== -1) {
      throw Error('Circular reference detected in ' + path.join('.'));
    }

    stack.push(value);

    _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].forEach(value, function each(el, key) {
      const result = !(_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isUndefined(el) || el === null) && visitor.call(
        formData, el, _utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isString(key) ? key.trim() : key, path, exposedHelpers
      );

      if (result === true) {
        build(el, path ? path.concat(key) : [key]);
      }
    });

    stack.pop();
  }

  if (!_utils_js__WEBPACK_IMPORTED_MODULE_0__["default"].isObject(obj)) {
    throw new TypeError('data must be an object');
  }

  build(obj);

  return formData;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (toFormData);


/***/ }),

/***/ "./node_modules/axios/lib/helpers/toURLEncodedForm.js":
/*!************************************************************!*\
  !*** ./node_modules/axios/lib/helpers/toURLEncodedForm.js ***!
  \************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ toURLEncodedForm)
/* harmony export */ });
/* harmony import */ var _utils_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils.js */ "./node_modules/axios/lib/utils.js");
/* harmony import */ var _toFormData_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toFormData.js */ "./node_modules/axios/lib/helpers/toFormData.js");
/* harmony import */ var _platform_index_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../platform/index.js */ "./node_modules/axios/lib/platform/index.js");






function toURLEncodedForm(data, options) {
  return (0,_toFormData_js__WEBPACK_IMPORTED_MODULE_0__["default"])(data, new _platform_index_js__WEBPACK_IMPORTED_MODULE_1__["default"].classes.URLSearchParams(), Object.assign({
    visitor: function(value, key, path, helpers) {
      if (_platform_index_js__WEBPACK_IMPORTED_MODULE_1__["default"].isNode && _utils_js__WEBPACK_IMPORTED_MODULE_2__["default"].isBuffer(value)) {
        this.append(key, value.toString('base64'));
        return false;
      }

      return helpers.defaultVisitor.apply(this, arguments);
    }
  }, options));
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/trackStream.js":
/*!*******************************************************!*\
  !*** ./node_modules/axios/lib/helpers/trackStream.js ***!
  \*******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   readBytes: () => (/* binding */ readBytes),
/* harmony export */   streamChunk: () => (/* binding */ streamChunk),
/* harmony export */   trackStream: () => (/* binding */ trackStream)
/* harmony export */ });

const streamChunk = function* (chunk, chunkSize) {
  let len = chunk.byteLength;

  if (!chunkSize || len < chunkSize) {
    yield chunk;
    return;
  }

  let pos = 0;
  let end;

  while (pos < len) {
    end = pos + chunkSize;
    yield chunk.slice(pos, end);
    pos = end;
  }
}

const readBytes = async function* (iterable, chunkSize) {
  for await (const chunk of readStream(iterable)) {
    yield* streamChunk(chunk, chunkSize);
  }
}

const readStream = async function* (stream) {
  if (stream[Symbol.asyncIterator]) {
    yield* stream;
    return;
  }

  const reader = stream.getReader();
  try {
    for (;;) {
      const {done, value} = await reader.read();
      if (done) {
        break;
      }
      yield value;
    }
  } finally {
    await reader.cancel();
  }
}

const trackStream = (stream, chunkSize, onProgress, onFinish) => {
  const iterator = readBytes(stream, chunkSize);

  let bytes = 0;
  let done;
  let _onFinish = (e) => {
    if (!done) {
      done = true;
      onFinish && onFinish(e);
    }
  }

  return new ReadableStream({
    async pull(controller) {
      try {
        const {done, value} = await iterator.next();

        if (done) {
         _onFinish();
          controller.close();
          return;
        }

        let len = value.byteLength;
        if (onProgress) {
          let loadedBytes = bytes += len;
          onProgress(loadedBytes);
        }
        controller.enqueue(new Uint8Array(value));
      } catch (err) {
        _onFinish(err);
        throw err;
      }
    },
    cancel(reason) {
      _onFinish(reason);
      return iterator.return();
    }
  }, {
    highWaterMark: 2
  })
}


/***/ }),

/***/ "./node_modules/axios/lib/helpers/validator.js":
/*!*****************************************************!*\
  !*** ./node_modules/axios/lib/helpers/validator.js ***!
  \*****************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _env_data_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../env/data.js */ "./node_modules/axios/lib/env/data.js");
/* harmony import */ var _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../core/AxiosError.js */ "./node_modules/axios/lib/core/AxiosError.js");





const validators = {};

// eslint-disable-next-line func-names
['object', 'boolean', 'number', 'function', 'string', 'symbol'].forEach((type, i) => {
  validators[type] = function validator(thing) {
    return typeof thing === type || 'a' + (i < 1 ? 'n ' : ' ') + type;
  };
});

const deprecatedWarnings = {};

/**
 * Transitional option validator
 *
 * @param {function|boolean?} validator - set to false if the transitional option has been removed
 * @param {string?} version - deprecated version / removed since version
 * @param {string?} message - some message with additional info
 *
 * @returns {function}
 */
validators.transitional = function transitional(validator, version, message) {
  function formatMessage(opt, desc) {
    return '[Axios v' + _env_data_js__WEBPACK_IMPORTED_MODULE_0__.VERSION + '] Transitional option \'' + opt + '\'' + desc + (message ? '. ' + message : '');
  }

  // eslint-disable-next-line func-names
  return (value, opt, opts) => {
    if (validator === false) {
      throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"](
        formatMessage(opt, ' has been removed' + (version ? ' in ' + version : '')),
        _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"].ERR_DEPRECATED
      );
    }

    if (version && !deprecatedWarnings[opt]) {
      deprecatedWarnings[opt] = true;
      // eslint-disable-next-line no-console
      console.warn(
        formatMessage(
          opt,
          ' has been deprecated since v' + version + ' and will be removed in the near future'
        )
      );
    }

    return validator ? validator(value, opt, opts) : true;
  };
};

/**
 * Assert object's properties type
 *
 * @param {object} options
 * @param {object} schema
 * @param {boolean?} allowUnknown
 *
 * @returns {object}
 */

function assertOptions(options, schema, allowUnknown) {
  if (typeof options !== 'object') {
    throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"]('options must be an object', _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"].ERR_BAD_OPTION_VALUE);
  }
  const keys = Object.keys(options);
  let i = keys.length;
  while (i-- > 0) {
    const opt = keys[i];
    const validator = schema[opt];
    if (validator) {
      const value = options[opt];
      const result = value === undefined || validator(value, opt, options);
      if (result !== true) {
        throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"]('option ' + opt + ' must be ' + result, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"].ERR_BAD_OPTION_VALUE);
      }
      continue;
    }
    if (allowUnknown !== true) {
      throw new _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"]('Unknown option ' + opt, _core_AxiosError_js__WEBPACK_IMPORTED_MODULE_1__["default"].ERR_BAD_OPTION);
    }
  }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  assertOptions,
  validators
});


/***/ }),

/***/ "./node_modules/axios/lib/platform/browser/classes/Blob.js":
/*!*****************************************************************!*\
  !*** ./node_modules/axios/lib/platform/browser/classes/Blob.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (typeof Blob !== 'undefined' ? Blob : null);


/***/ }),

/***/ "./node_modules/axios/lib/platform/browser/classes/FormData.js":
/*!*********************************************************************!*\
  !*** ./node_modules/axios/lib/platform/browser/classes/FormData.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (typeof FormData !== 'undefined' ? FormData : null);


/***/ }),

/***/ "./node_modules/axios/lib/platform/browser/classes/URLSearchParams.js":
/*!****************************************************************************!*\
  !*** ./node_modules/axios/lib/platform/browser/classes/URLSearchParams.js ***!
  \****************************************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _helpers_AxiosURLSearchParams_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../helpers/AxiosURLSearchParams.js */ "./node_modules/axios/lib/helpers/AxiosURLSearchParams.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (typeof URLSearchParams !== 'undefined' ? URLSearchParams : _helpers_AxiosURLSearchParams_js__WEBPACK_IMPORTED_MODULE_0__["default"]);


/***/ }),

/***/ "./node_modules/axios/lib/platform/browser/index.js":
/*!**********************************************************!*\
  !*** ./node_modules/axios/lib/platform/browser/index.js ***!
  \**********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _classes_URLSearchParams_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./classes/URLSearchParams.js */ "./node_modules/axios/lib/platform/browser/classes/URLSearchParams.js");
/* harmony import */ var _classes_FormData_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./classes/FormData.js */ "./node_modules/axios/lib/platform/browser/classes/FormData.js");
/* harmony import */ var _classes_Blob_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./classes/Blob.js */ "./node_modules/axios/lib/platform/browser/classes/Blob.js");




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  isBrowser: true,
  classes: {
    URLSearchParams: _classes_URLSearchParams_js__WEBPACK_IMPORTED_MODULE_0__["default"],
    FormData: _classes_FormData_js__WEBPACK_IMPORTED_MODULE_1__["default"],
    Blob: _classes_Blob_js__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  protocols: ['http', 'https', 'file', 'blob', 'url', 'data']
});


/***/ }),

/***/ "./node_modules/axios/lib/platform/common/utils.js":
/*!*********************************************************!*\
  !*** ./node_modules/axios/lib/platform/common/utils.js ***!
  \*********************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   hasBrowserEnv: () => (/* binding */ hasBrowserEnv),
/* harmony export */   hasStandardBrowserEnv: () => (/* binding */ hasStandardBrowserEnv),
/* harmony export */   hasStandardBrowserWebWorkerEnv: () => (/* binding */ hasStandardBrowserWebWorkerEnv),
/* harmony export */   navigator: () => (/* binding */ _navigator),
/* harmony export */   origin: () => (/* binding */ origin)
/* harmony export */ });
const hasBrowserEnv = typeof window !== 'undefined' && typeof document !== 'undefined';

const _navigator = typeof navigator === 'object' && navigator || undefined;

/**
 * Determine if we're running in a standard browser environment
 *
 * This allows axios to run in a web worker, and react-native.
 * Both environments support XMLHttpRequest, but not fully standard globals.
 *
 * web workers:
 *  typeof window -> undefined
 *  typeof document -> undefined
 *
 * react-native:
 *  navigator.product -> 'ReactNative'
 * nativescript
 *  navigator.product -> 'NativeScript' or 'NS'
 *
 * @returns {boolean}
 */
const hasStandardBrowserEnv = hasBrowserEnv &&
  (!_navigator || ['ReactNative', 'NativeScript', 'NS'].indexOf(_navigator.product) < 0);

/**
 * Determine if we're running in a standard browser webWorker environment
 *
 * Although the `isStandardBrowserEnv` method indicates that
 * `allows axios to run in a web worker`, the WebWorker will still be
 * filtered out due to its judgment standard
 * `typeof window !== 'undefined' && typeof document !== 'undefined'`.
 * This leads to a problem when axios post `FormData` in webWorker
 */
const hasStandardBrowserWebWorkerEnv = (() => {
  return (
    typeof WorkerGlobalScope !== 'undefined' &&
    // eslint-disable-next-line no-undef
    self instanceof WorkerGlobalScope &&
    typeof self.importScripts === 'function'
  );
})();

const origin = hasBrowserEnv && window.location.href || 'http://localhost';




/***/ }),

/***/ "./node_modules/axios/lib/platform/index.js":
/*!**************************************************!*\
  !*** ./node_modules/axios/lib/platform/index.js ***!
  \**************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_index_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node/index.js */ "./node_modules/axios/lib/platform/browser/index.js");
/* harmony import */ var _common_utils_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./common/utils.js */ "./node_modules/axios/lib/platform/common/utils.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  ..._common_utils_js__WEBPACK_IMPORTED_MODULE_0__,
  ..._node_index_js__WEBPACK_IMPORTED_MODULE_1__["default"]
});


/***/ }),

/***/ "./node_modules/axios/lib/utils.js":
/*!*****************************************!*\
  !*** ./node_modules/axios/lib/utils.js ***!
  \*****************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _helpers_bind_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./helpers/bind.js */ "./node_modules/axios/lib/helpers/bind.js");




// utils is a library of generic helper functions non-specific to axios

const {toString} = Object.prototype;
const {getPrototypeOf} = Object;

const kindOf = (cache => thing => {
    const str = toString.call(thing);
    return cache[str] || (cache[str] = str.slice(8, -1).toLowerCase());
})(Object.create(null));

const kindOfTest = (type) => {
  type = type.toLowerCase();
  return (thing) => kindOf(thing) === type
}

const typeOfTest = type => thing => typeof thing === type;

/**
 * Determine if a value is an Array
 *
 * @param {Object} val The value to test
 *
 * @returns {boolean} True if value is an Array, otherwise false
 */
const {isArray} = Array;

/**
 * Determine if a value is undefined
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if the value is undefined, otherwise false
 */
const isUndefined = typeOfTest('undefined');

/**
 * Determine if a value is a Buffer
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Buffer, otherwise false
 */
function isBuffer(val) {
  return val !== null && !isUndefined(val) && val.constructor !== null && !isUndefined(val.constructor)
    && isFunction(val.constructor.isBuffer) && val.constructor.isBuffer(val);
}

/**
 * Determine if a value is an ArrayBuffer
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is an ArrayBuffer, otherwise false
 */
const isArrayBuffer = kindOfTest('ArrayBuffer');


/**
 * Determine if a value is a view on an ArrayBuffer
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a view on an ArrayBuffer, otherwise false
 */
function isArrayBufferView(val) {
  let result;
  if ((typeof ArrayBuffer !== 'undefined') && (ArrayBuffer.isView)) {
    result = ArrayBuffer.isView(val);
  } else {
    result = (val) && (val.buffer) && (isArrayBuffer(val.buffer));
  }
  return result;
}

/**
 * Determine if a value is a String
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a String, otherwise false
 */
const isString = typeOfTest('string');

/**
 * Determine if a value is a Function
 *
 * @param {*} val The value to test
 * @returns {boolean} True if value is a Function, otherwise false
 */
const isFunction = typeOfTest('function');

/**
 * Determine if a value is a Number
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Number, otherwise false
 */
const isNumber = typeOfTest('number');

/**
 * Determine if a value is an Object
 *
 * @param {*} thing The value to test
 *
 * @returns {boolean} True if value is an Object, otherwise false
 */
const isObject = (thing) => thing !== null && typeof thing === 'object';

/**
 * Determine if a value is a Boolean
 *
 * @param {*} thing The value to test
 * @returns {boolean} True if value is a Boolean, otherwise false
 */
const isBoolean = thing => thing === true || thing === false;

/**
 * Determine if a value is a plain Object
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a plain Object, otherwise false
 */
const isPlainObject = (val) => {
  if (kindOf(val) !== 'object') {
    return false;
  }

  const prototype = getPrototypeOf(val);
  return (prototype === null || prototype === Object.prototype || Object.getPrototypeOf(prototype) === null) && !(Symbol.toStringTag in val) && !(Symbol.iterator in val);
}

/**
 * Determine if a value is a Date
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Date, otherwise false
 */
const isDate = kindOfTest('Date');

/**
 * Determine if a value is a File
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a File, otherwise false
 */
const isFile = kindOfTest('File');

/**
 * Determine if a value is a Blob
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Blob, otherwise false
 */
const isBlob = kindOfTest('Blob');

/**
 * Determine if a value is a FileList
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a File, otherwise false
 */
const isFileList = kindOfTest('FileList');

/**
 * Determine if a value is a Stream
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a Stream, otherwise false
 */
const isStream = (val) => isObject(val) && isFunction(val.pipe);

/**
 * Determine if a value is a FormData
 *
 * @param {*} thing The value to test
 *
 * @returns {boolean} True if value is an FormData, otherwise false
 */
const isFormData = (thing) => {
  let kind;
  return thing && (
    (typeof FormData === 'function' && thing instanceof FormData) || (
      isFunction(thing.append) && (
        (kind = kindOf(thing)) === 'formdata' ||
        // detect form-data instance
        (kind === 'object' && isFunction(thing.toString) && thing.toString() === '[object FormData]')
      )
    )
  )
}

/**
 * Determine if a value is a URLSearchParams object
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a URLSearchParams object, otherwise false
 */
const isURLSearchParams = kindOfTest('URLSearchParams');

const [isReadableStream, isRequest, isResponse, isHeaders] = ['ReadableStream', 'Request', 'Response', 'Headers'].map(kindOfTest);

/**
 * Trim excess whitespace off the beginning and end of a string
 *
 * @param {String} str The String to trim
 *
 * @returns {String} The String freed of excess whitespace
 */
const trim = (str) => str.trim ?
  str.trim() : str.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');

/**
 * Iterate over an Array or an Object invoking a function for each item.
 *
 * If `obj` is an Array callback will be called passing
 * the value, index, and complete array for each item.
 *
 * If 'obj' is an Object callback will be called passing
 * the value, key, and complete object for each property.
 *
 * @param {Object|Array} obj The object to iterate
 * @param {Function} fn The callback to invoke for each item
 *
 * @param {Boolean} [allOwnKeys = false]
 * @returns {any}
 */
function forEach(obj, fn, {allOwnKeys = false} = {}) {
  // Don't bother if no value provided
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  let i;
  let l;

  // Force an array if not already something iterable
  if (typeof obj !== 'object') {
    /*eslint no-param-reassign:0*/
    obj = [obj];
  }

  if (isArray(obj)) {
    // Iterate over array values
    for (i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // Iterate over object keys
    const keys = allOwnKeys ? Object.getOwnPropertyNames(obj) : Object.keys(obj);
    const len = keys.length;
    let key;

    for (i = 0; i < len; i++) {
      key = keys[i];
      fn.call(null, obj[key], key, obj);
    }
  }
}

function findKey(obj, key) {
  key = key.toLowerCase();
  const keys = Object.keys(obj);
  let i = keys.length;
  let _key;
  while (i-- > 0) {
    _key = keys[i];
    if (key === _key.toLowerCase()) {
      return _key;
    }
  }
  return null;
}

const _global = (() => {
  /*eslint no-undef:0*/
  if (typeof globalThis !== "undefined") return globalThis;
  return typeof self !== "undefined" ? self : (typeof window !== 'undefined' ? window : global)
})();

const isContextDefined = (context) => !isUndefined(context) && context !== _global;

/**
 * Accepts varargs expecting each argument to be an object, then
 * immutably merges the properties of each object and returns result.
 *
 * When multiple objects contain the same key the later object in
 * the arguments list will take precedence.
 *
 * Example:
 *
 * ```js
 * var result = merge({foo: 123}, {foo: 456});
 * console.log(result.foo); // outputs 456
 * ```
 *
 * @param {Object} obj1 Object to merge
 *
 * @returns {Object} Result of all merge properties
 */
function merge(/* obj1, obj2, obj3, ... */) {
  const {caseless} = isContextDefined(this) && this || {};
  const result = {};
  const assignValue = (val, key) => {
    const targetKey = caseless && findKey(result, key) || key;
    if (isPlainObject(result[targetKey]) && isPlainObject(val)) {
      result[targetKey] = merge(result[targetKey], val);
    } else if (isPlainObject(val)) {
      result[targetKey] = merge({}, val);
    } else if (isArray(val)) {
      result[targetKey] = val.slice();
    } else {
      result[targetKey] = val;
    }
  }

  for (let i = 0, l = arguments.length; i < l; i++) {
    arguments[i] && forEach(arguments[i], assignValue);
  }
  return result;
}

/**
 * Extends object a by mutably adding to it the properties of object b.
 *
 * @param {Object} a The object to be extended
 * @param {Object} b The object to copy properties from
 * @param {Object} thisArg The object to bind function to
 *
 * @param {Boolean} [allOwnKeys]
 * @returns {Object} The resulting value of object a
 */
const extend = (a, b, thisArg, {allOwnKeys}= {}) => {
  forEach(b, (val, key) => {
    if (thisArg && isFunction(val)) {
      a[key] = (0,_helpers_bind_js__WEBPACK_IMPORTED_MODULE_0__["default"])(val, thisArg);
    } else {
      a[key] = val;
    }
  }, {allOwnKeys});
  return a;
}

/**
 * Remove byte order marker. This catches EF BB BF (the UTF-8 BOM)
 *
 * @param {string} content with BOM
 *
 * @returns {string} content value without BOM
 */
const stripBOM = (content) => {
  if (content.charCodeAt(0) === 0xFEFF) {
    content = content.slice(1);
  }
  return content;
}

/**
 * Inherit the prototype methods from one constructor into another
 * @param {function} constructor
 * @param {function} superConstructor
 * @param {object} [props]
 * @param {object} [descriptors]
 *
 * @returns {void}
 */
const inherits = (constructor, superConstructor, props, descriptors) => {
  constructor.prototype = Object.create(superConstructor.prototype, descriptors);
  constructor.prototype.constructor = constructor;
  Object.defineProperty(constructor, 'super', {
    value: superConstructor.prototype
  });
  props && Object.assign(constructor.prototype, props);
}

/**
 * Resolve object with deep prototype chain to a flat object
 * @param {Object} sourceObj source object
 * @param {Object} [destObj]
 * @param {Function|Boolean} [filter]
 * @param {Function} [propFilter]
 *
 * @returns {Object}
 */
const toFlatObject = (sourceObj, destObj, filter, propFilter) => {
  let props;
  let i;
  let prop;
  const merged = {};

  destObj = destObj || {};
  // eslint-disable-next-line no-eq-null,eqeqeq
  if (sourceObj == null) return destObj;

  do {
    props = Object.getOwnPropertyNames(sourceObj);
    i = props.length;
    while (i-- > 0) {
      prop = props[i];
      if ((!propFilter || propFilter(prop, sourceObj, destObj)) && !merged[prop]) {
        destObj[prop] = sourceObj[prop];
        merged[prop] = true;
      }
    }
    sourceObj = filter !== false && getPrototypeOf(sourceObj);
  } while (sourceObj && (!filter || filter(sourceObj, destObj)) && sourceObj !== Object.prototype);

  return destObj;
}

/**
 * Determines whether a string ends with the characters of a specified string
 *
 * @param {String} str
 * @param {String} searchString
 * @param {Number} [position= 0]
 *
 * @returns {boolean}
 */
const endsWith = (str, searchString, position) => {
  str = String(str);
  if (position === undefined || position > str.length) {
    position = str.length;
  }
  position -= searchString.length;
  const lastIndex = str.indexOf(searchString, position);
  return lastIndex !== -1 && lastIndex === position;
}


/**
 * Returns new array from array like object or null if failed
 *
 * @param {*} [thing]
 *
 * @returns {?Array}
 */
const toArray = (thing) => {
  if (!thing) return null;
  if (isArray(thing)) return thing;
  let i = thing.length;
  if (!isNumber(i)) return null;
  const arr = new Array(i);
  while (i-- > 0) {
    arr[i] = thing[i];
  }
  return arr;
}

/**
 * Checking if the Uint8Array exists and if it does, it returns a function that checks if the
 * thing passed in is an instance of Uint8Array
 *
 * @param {TypedArray}
 *
 * @returns {Array}
 */
// eslint-disable-next-line func-names
const isTypedArray = (TypedArray => {
  // eslint-disable-next-line func-names
  return thing => {
    return TypedArray && thing instanceof TypedArray;
  };
})(typeof Uint8Array !== 'undefined' && getPrototypeOf(Uint8Array));

/**
 * For each entry in the object, call the function with the key and value.
 *
 * @param {Object<any, any>} obj - The object to iterate over.
 * @param {Function} fn - The function to call for each entry.
 *
 * @returns {void}
 */
const forEachEntry = (obj, fn) => {
  const generator = obj && obj[Symbol.iterator];

  const iterator = generator.call(obj);

  let result;

  while ((result = iterator.next()) && !result.done) {
    const pair = result.value;
    fn.call(obj, pair[0], pair[1]);
  }
}

/**
 * It takes a regular expression and a string, and returns an array of all the matches
 *
 * @param {string} regExp - The regular expression to match against.
 * @param {string} str - The string to search.
 *
 * @returns {Array<boolean>}
 */
const matchAll = (regExp, str) => {
  let matches;
  const arr = [];

  while ((matches = regExp.exec(str)) !== null) {
    arr.push(matches);
  }

  return arr;
}

/* Checking if the kindOfTest function returns true when passed an HTMLFormElement. */
const isHTMLForm = kindOfTest('HTMLFormElement');

const toCamelCase = str => {
  return str.toLowerCase().replace(/[-_\s]([a-z\d])(\w*)/g,
    function replacer(m, p1, p2) {
      return p1.toUpperCase() + p2;
    }
  );
};

/* Creating a function that will check if an object has a property. */
const hasOwnProperty = (({hasOwnProperty}) => (obj, prop) => hasOwnProperty.call(obj, prop))(Object.prototype);

/**
 * Determine if a value is a RegExp object
 *
 * @param {*} val The value to test
 *
 * @returns {boolean} True if value is a RegExp object, otherwise false
 */
const isRegExp = kindOfTest('RegExp');

const reduceDescriptors = (obj, reducer) => {
  const descriptors = Object.getOwnPropertyDescriptors(obj);
  const reducedDescriptors = {};

  forEach(descriptors, (descriptor, name) => {
    let ret;
    if ((ret = reducer(descriptor, name, obj)) !== false) {
      reducedDescriptors[name] = ret || descriptor;
    }
  });

  Object.defineProperties(obj, reducedDescriptors);
}

/**
 * Makes all methods read-only
 * @param {Object} obj
 */

const freezeMethods = (obj) => {
  reduceDescriptors(obj, (descriptor, name) => {
    // skip restricted props in strict mode
    if (isFunction(obj) && ['arguments', 'caller', 'callee'].indexOf(name) !== -1) {
      return false;
    }

    const value = obj[name];

    if (!isFunction(value)) return;

    descriptor.enumerable = false;

    if ('writable' in descriptor) {
      descriptor.writable = false;
      return;
    }

    if (!descriptor.set) {
      descriptor.set = () => {
        throw Error('Can not rewrite read-only method \'' + name + '\'');
      };
    }
  });
}

const toObjectSet = (arrayOrString, delimiter) => {
  const obj = {};

  const define = (arr) => {
    arr.forEach(value => {
      obj[value] = true;
    });
  }

  isArray(arrayOrString) ? define(arrayOrString) : define(String(arrayOrString).split(delimiter));

  return obj;
}

const noop = () => {}

const toFiniteNumber = (value, defaultValue) => {
  return value != null && Number.isFinite(value = +value) ? value : defaultValue;
}

const ALPHA = 'abcdefghijklmnopqrstuvwxyz'

const DIGIT = '0123456789';

const ALPHABET = {
  DIGIT,
  ALPHA,
  ALPHA_DIGIT: ALPHA + ALPHA.toUpperCase() + DIGIT
}

const generateString = (size = 16, alphabet = ALPHABET.ALPHA_DIGIT) => {
  let str = '';
  const {length} = alphabet;
  while (size--) {
    str += alphabet[Math.random() * length|0]
  }

  return str;
}

/**
 * If the thing is a FormData object, return true, otherwise return false.
 *
 * @param {unknown} thing - The thing to check.
 *
 * @returns {boolean}
 */
function isSpecCompliantForm(thing) {
  return !!(thing && isFunction(thing.append) && thing[Symbol.toStringTag] === 'FormData' && thing[Symbol.iterator]);
}

const toJSONObject = (obj) => {
  const stack = new Array(10);

  const visit = (source, i) => {

    if (isObject(source)) {
      if (stack.indexOf(source) >= 0) {
        return;
      }

      if(!('toJSON' in source)) {
        stack[i] = source;
        const target = isArray(source) ? [] : {};

        forEach(source, (value, key) => {
          const reducedValue = visit(value, i + 1);
          !isUndefined(reducedValue) && (target[key] = reducedValue);
        });

        stack[i] = undefined;

        return target;
      }
    }

    return source;
  }

  return visit(obj, 0);
}

const isAsyncFn = kindOfTest('AsyncFunction');

const isThenable = (thing) =>
  thing && (isObject(thing) || isFunction(thing)) && isFunction(thing.then) && isFunction(thing.catch);

// original code
// https://github.com/DigitalBrainJS/AxiosPromise/blob/16deab13710ec09779922131f3fa5954320f83ab/lib/utils.js#L11-L34

const _setImmediate = ((setImmediateSupported, postMessageSupported) => {
  if (setImmediateSupported) {
    return setImmediate;
  }

  return postMessageSupported ? ((token, callbacks) => {
    _global.addEventListener("message", ({source, data}) => {
      if (source === _global && data === token) {
        callbacks.length && callbacks.shift()();
      }
    }, false);

    return (cb) => {
      callbacks.push(cb);
      _global.postMessage(token, "*");
    }
  })(`axios@${Math.random()}`, []) : (cb) => setTimeout(cb);
})(
  typeof setImmediate === 'function',
  isFunction(_global.postMessage)
);

const asap = typeof queueMicrotask !== 'undefined' ?
  queueMicrotask.bind(_global) : ( typeof process !== 'undefined' && process.nextTick || _setImmediate);

// *********************

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  isArray,
  isArrayBuffer,
  isBuffer,
  isFormData,
  isArrayBufferView,
  isString,
  isNumber,
  isBoolean,
  isObject,
  isPlainObject,
  isReadableStream,
  isRequest,
  isResponse,
  isHeaders,
  isUndefined,
  isDate,
  isFile,
  isBlob,
  isRegExp,
  isFunction,
  isStream,
  isURLSearchParams,
  isTypedArray,
  isFileList,
  forEach,
  merge,
  extend,
  trim,
  stripBOM,
  inherits,
  toFlatObject,
  kindOf,
  kindOfTest,
  endsWith,
  toArray,
  forEachEntry,
  matchAll,
  isHTMLForm,
  hasOwnProperty,
  hasOwnProp: hasOwnProperty, // an alias to avoid ESLint no-prototype-builtins detection
  reduceDescriptors,
  freezeMethods,
  toObjectSet,
  toCamelCase,
  noop,
  toFiniteNumber,
  findKey,
  global: _global,
  isContextDefined,
  ALPHABET,
  generateString,
  isSpecCompliantForm,
  toJSONObject,
  isAsyncFn,
  isThenable,
  setImmediate: _setImmediate,
  asap
});


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _assets_scss_GUTENBERG_APP_THEME_editor_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./assets/scss/GUTENBERG_APP_THEME/editor.scss */ "./src/assets/scss/monsterinsights/editor.scss");
/* harmony import */ var _assets_scss_GUTENBERG_APP_THEME_frontend_GUTENBERG_APP_VERSION_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./assets/scss/GUTENBERG_APP_THEME/frontend-GUTENBERG_APP_VERSION.scss */ "./src/assets/scss/monsterinsights/frontend-Lite.scss");
/* harmony import */ var _plugins___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./plugins/ */ "./src/plugins/index.js");
/* harmony import */ var _blocks_index_GUTENBERG_APP_VERSION__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./blocks/index-GUTENBERG_APP_VERSION */ "./src/blocks/index-Lite.js");
/* harmony import */ var _hooks___WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./hooks/ */ "./src/hooks/index.js");
/**
 * Import styles & files
 */
const {
  setLocaleData
} = wp.i18n;
if ('undefined' !== typeof window.monsterinsights_gutenberg_tool_vars.translations) {
  setLocaleData(window.monsterinsights_gutenberg_tool_vars.translations, "google-analytics-for-wordpress");
}
 // eslint-disable-line import/no-unresolved
 // eslint-disable-line import/no-unresolved

// import plugins

 // eslint-disable-line import/no-unresolved


})();

/******/ })()
;
//# sourceMappingURL=index.js.map