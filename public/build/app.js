(self["webpackChunkhtml"] = self["webpackChunkhtml"] || []).push([["app"],{

/***/ "./assets/js/app.js":
/*!**************************!*\
  !*** ./assets/js/app.js ***!
  \**************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_activity_tracker_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/activity-tracker.js */ "./assets/js/components/activity-tracker.js");
/* harmony import */ var _components_activity_tracker_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_components_activity_tracker_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_report_chart_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/report-chart.js */ "./assets/js/components/report-chart.js");
/* harmony import */ var _components_report_chart_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_components_report_chart_js__WEBPACK_IMPORTED_MODULE_1__);



/***/ }),

/***/ "./assets/js/components/activity-tracker.js":
/*!**************************************************!*\
  !*** ./assets/js/components/activity-tracker.js ***!
  \**************************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  // Buy a cow button (Page A)
  var buyCowButton = document.getElementById('buyCowButton');
  if (buyCowButton) {
    buyCowButton.addEventListener('click', function () {
      // Hide the button section
      var buyCowSection = document.getElementById('buyCowSection');
      var thankYouSection = document.getElementById('thankYouSection');
      if (buyCowSection) buyCowSection.style.display = 'none';
      if (thankYouSection) thankYouSection.style.display = 'block';

      // Track the button click
      fetch('/api/activity/track', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          type: 'button_click',
          button: 'buy_cow',
          page: 'page_a'
        })
      });
    });
  }

  // Download button (Page B)
  var downloadButton = document.getElementById('downloadButton');
  if (downloadButton) {
    downloadButton.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent immediate navigation

      fetch('/api/activity/track', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          type: 'button_click',
          button: 'download',
          page: 'page_b'
        })
      }).then(function () {
        // After tracking, proceed to download
        window.location.href = '/downloads/sample.exe';
      });
    });
  }
});

/***/ }),

/***/ "./assets/js/components/report-chart.js":
/*!**********************************************!*\
  !*** ./assets/js/components/report-chart.js ***!
  \**********************************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  var chartCanvas = document.getElementById('activityChart');
  if (chartCanvas && window.activityChartData) {
    var ctx = chartCanvas.getContext('2d');
    var chart = new Chart(ctx, {
      type: 'line',
      data: window.activityChartData.data,
      options: window.activityChartData.options
    });
  }
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/js/app.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXBwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7Ozs7O0FBQTBDOzs7Ozs7Ozs7OztBQ0ExQ0EsUUFBUSxDQUFDQyxnQkFBZ0IsQ0FBQyxrQkFBa0IsRUFBRSxZQUFXO0VBQ3JEO0VBQ0EsSUFBSUMsWUFBWSxHQUFHRixRQUFRLENBQUNHLGNBQWMsQ0FBQyxjQUFjLENBQUM7RUFDMUQsSUFBSUQsWUFBWSxFQUFFO0lBQ2RBLFlBQVksQ0FBQ0QsZ0JBQWdCLENBQUMsT0FBTyxFQUFFLFlBQVc7TUFDOUM7TUFDQSxJQUFJRyxhQUFhLEdBQUdKLFFBQVEsQ0FBQ0csY0FBYyxDQUFDLGVBQWUsQ0FBQztNQUM1RCxJQUFJRSxlQUFlLEdBQUdMLFFBQVEsQ0FBQ0csY0FBYyxDQUFDLGlCQUFpQixDQUFDO01BQ2hFLElBQUlDLGFBQWEsRUFBRUEsYUFBYSxDQUFDRSxLQUFLLENBQUNDLE9BQU8sR0FBRyxNQUFNO01BQ3ZELElBQUlGLGVBQWUsRUFBRUEsZUFBZSxDQUFDQyxLQUFLLENBQUNDLE9BQU8sR0FBRyxPQUFPOztNQUU1RDtNQUNBQyxLQUFLLENBQUMscUJBQXFCLEVBQUU7UUFDekJDLE1BQU0sRUFBRSxNQUFNO1FBQ2RDLE9BQU8sRUFBRTtVQUNMLGNBQWMsRUFBRTtRQUNwQixDQUFDO1FBQ0RDLElBQUksRUFBRUMsSUFBSSxDQUFDQyxTQUFTLENBQUM7VUFDakJDLElBQUksRUFBRSxjQUFjO1VBQ3BCQyxNQUFNLEVBQUUsU0FBUztVQUNqQkMsSUFBSSxFQUFFO1FBQ1YsQ0FBQztNQUNMLENBQUMsQ0FBQztJQUNOLENBQUMsQ0FBQztFQUNOOztFQUVBO0VBQ0EsSUFBSUMsY0FBYyxHQUFHakIsUUFBUSxDQUFDRyxjQUFjLENBQUMsZ0JBQWdCLENBQUM7RUFDOUQsSUFBSWMsY0FBYyxFQUFFO0lBQ2hCQSxjQUFjLENBQUNoQixnQkFBZ0IsQ0FBQyxPQUFPLEVBQUUsVUFBU2lCLEtBQUssRUFBRTtNQUNyREEsS0FBSyxDQUFDQyxjQUFjLENBQUMsQ0FBQyxDQUFDLENBQUM7O01BRXhCWCxLQUFLLENBQUMscUJBQXFCLEVBQUU7UUFDekJDLE1BQU0sRUFBRSxNQUFNO1FBQ2RDLE9BQU8sRUFBRTtVQUNMLGNBQWMsRUFBRTtRQUNwQixDQUFDO1FBQ0RDLElBQUksRUFBRUMsSUFBSSxDQUFDQyxTQUFTLENBQUM7VUFDakJDLElBQUksRUFBRSxjQUFjO1VBQ3BCQyxNQUFNLEVBQUUsVUFBVTtVQUNsQkMsSUFBSSxFQUFFO1FBQ1YsQ0FBQztNQUNMLENBQUMsQ0FBQyxDQUFDSSxJQUFJLENBQUMsWUFBVztRQUNmO1FBQ0FDLE1BQU0sQ0FBQ0MsUUFBUSxDQUFDQyxJQUFJLEdBQUcsdUJBQXVCO01BQ2xELENBQUMsQ0FBQztJQUNOLENBQUMsQ0FBQztFQUNOO0FBQ0osQ0FBQyxDQUFDOzs7Ozs7Ozs7O0FDaERGdkIsUUFBUSxDQUFDQyxnQkFBZ0IsQ0FBQyxrQkFBa0IsRUFBRSxZQUFXO0VBQ3JELElBQUl1QixXQUFXLEdBQUd4QixRQUFRLENBQUNHLGNBQWMsQ0FBQyxlQUFlLENBQUM7RUFDMUQsSUFBSXFCLFdBQVcsSUFBSUgsTUFBTSxDQUFDSSxpQkFBaUIsRUFBRTtJQUN6QyxJQUFNQyxHQUFHLEdBQUdGLFdBQVcsQ0FBQ0csVUFBVSxDQUFDLElBQUksQ0FBQztJQUN4QyxJQUFNQyxLQUFLLEdBQUcsSUFBSUMsS0FBSyxDQUFDSCxHQUFHLEVBQUU7TUFDekJaLElBQUksRUFBRSxNQUFNO01BQ1pnQixJQUFJLEVBQUVULE1BQU0sQ0FBQ0ksaUJBQWlCLENBQUNLLElBQUk7TUFDbkNDLE9BQU8sRUFBRVYsTUFBTSxDQUFDSSxpQkFBaUIsQ0FBQ007SUFDdEMsQ0FBQyxDQUFDO0VBQ047QUFDSixDQUFDLENBQUMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9odG1sLy4vYXNzZXRzL2pzL2FwcC5qcyIsIndlYnBhY2s6Ly9odG1sLy4vYXNzZXRzL2pzL2NvbXBvbmVudHMvYWN0aXZpdHktdHJhY2tlci5qcyIsIndlYnBhY2s6Ly9odG1sLy4vYXNzZXRzL2pzL2NvbXBvbmVudHMvcmVwb3J0LWNoYXJ0LmpzIl0sInNvdXJjZXNDb250ZW50IjpbImltcG9ydCAnLi9jb21wb25lbnRzL2FjdGl2aXR5LXRyYWNrZXIuanMnO1xuaW1wb3J0ICcuL2NvbXBvbmVudHMvcmVwb3J0LWNoYXJ0LmpzJztcbiIsImRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCBmdW5jdGlvbigpIHtcbiAgICAvLyBCdXkgYSBjb3cgYnV0dG9uIChQYWdlIEEpXG4gICAgdmFyIGJ1eUNvd0J1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdidXlDb3dCdXR0b24nKTtcbiAgICBpZiAoYnV5Q293QnV0dG9uKSB7XG4gICAgICAgIGJ1eUNvd0J1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgLy8gSGlkZSB0aGUgYnV0dG9uIHNlY3Rpb25cbiAgICAgICAgICAgIHZhciBidXlDb3dTZWN0aW9uID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2J1eUNvd1NlY3Rpb24nKTtcbiAgICAgICAgICAgIHZhciB0aGFua1lvdVNlY3Rpb24gPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndGhhbmtZb3VTZWN0aW9uJyk7XG4gICAgICAgICAgICBpZiAoYnV5Q293U2VjdGlvbikgYnV5Q293U2VjdGlvbi5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgaWYgKHRoYW5rWW91U2VjdGlvbikgdGhhbmtZb3VTZWN0aW9uLnN0eWxlLmRpc3BsYXkgPSAnYmxvY2snO1xuXG4gICAgICAgICAgICAvLyBUcmFjayB0aGUgYnV0dG9uIGNsaWNrXG4gICAgICAgICAgICBmZXRjaCgnL2FwaS9hY3Rpdml0eS90cmFjaycsIHtcbiAgICAgICAgICAgICAgICBtZXRob2Q6ICdQT1NUJyxcbiAgICAgICAgICAgICAgICBoZWFkZXJzOiB7XG4gICAgICAgICAgICAgICAgICAgICdDb250ZW50LVR5cGUnOiAnYXBwbGljYXRpb24vanNvbicsXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBib2R5OiBKU09OLnN0cmluZ2lmeSh7XG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdidXR0b25fY2xpY2snLFxuICAgICAgICAgICAgICAgICAgICBidXR0b246ICdidXlfY293JyxcbiAgICAgICAgICAgICAgICAgICAgcGFnZTogJ3BhZ2VfYSdcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIC8vIERvd25sb2FkIGJ1dHRvbiAoUGFnZSBCKVxuICAgIHZhciBkb3dubG9hZEJ1dHRvbiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdkb3dubG9hZEJ1dHRvbicpO1xuICAgIGlmIChkb3dubG9hZEJ1dHRvbikge1xuICAgICAgICBkb3dubG9hZEJ1dHRvbi5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIGZ1bmN0aW9uKGV2ZW50KSB7XG4gICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpOyAvLyBQcmV2ZW50IGltbWVkaWF0ZSBuYXZpZ2F0aW9uXG5cbiAgICAgICAgICAgIGZldGNoKCcvYXBpL2FjdGl2aXR5L3RyYWNrJywge1xuICAgICAgICAgICAgICAgIG1ldGhvZDogJ1BPU1QnLFxuICAgICAgICAgICAgICAgIGhlYWRlcnM6IHtcbiAgICAgICAgICAgICAgICAgICAgJ0NvbnRlbnQtVHlwZSc6ICdhcHBsaWNhdGlvbi9qc29uJyxcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIGJvZHk6IEpTT04uc3RyaW5naWZ5KHtcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ2J1dHRvbl9jbGljaycsXG4gICAgICAgICAgICAgICAgICAgIGJ1dHRvbjogJ2Rvd25sb2FkJyxcbiAgICAgICAgICAgICAgICAgICAgcGFnZTogJ3BhZ2VfYidcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgfSkudGhlbihmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAvLyBBZnRlciB0cmFja2luZywgcHJvY2VlZCB0byBkb3dubG9hZFxuICAgICAgICAgICAgICAgIHdpbmRvdy5sb2NhdGlvbi5ocmVmID0gJy9kb3dubG9hZHMvc2FtcGxlLmV4ZSc7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfVxufSk7XG4iLCJkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgZnVuY3Rpb24oKSB7XG4gICAgdmFyIGNoYXJ0Q2FudmFzID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2FjdGl2aXR5Q2hhcnQnKTtcbiAgICBpZiAoY2hhcnRDYW52YXMgJiYgd2luZG93LmFjdGl2aXR5Q2hhcnREYXRhKSB7XG4gICAgICAgIGNvbnN0IGN0eCA9IGNoYXJ0Q2FudmFzLmdldENvbnRleHQoJzJkJyk7XG4gICAgICAgIGNvbnN0IGNoYXJ0ID0gbmV3IENoYXJ0KGN0eCwge1xuICAgICAgICAgICAgdHlwZTogJ2xpbmUnLFxuICAgICAgICAgICAgZGF0YTogd2luZG93LmFjdGl2aXR5Q2hhcnREYXRhLmRhdGEsXG4gICAgICAgICAgICBvcHRpb25zOiB3aW5kb3cuYWN0aXZpdHlDaGFydERhdGEub3B0aW9uc1xuICAgICAgICB9KTtcbiAgICB9XG59KTtcbiJdLCJuYW1lcyI6WyJkb2N1bWVudCIsImFkZEV2ZW50TGlzdGVuZXIiLCJidXlDb3dCdXR0b24iLCJnZXRFbGVtZW50QnlJZCIsImJ1eUNvd1NlY3Rpb24iLCJ0aGFua1lvdVNlY3Rpb24iLCJzdHlsZSIsImRpc3BsYXkiLCJmZXRjaCIsIm1ldGhvZCIsImhlYWRlcnMiLCJib2R5IiwiSlNPTiIsInN0cmluZ2lmeSIsInR5cGUiLCJidXR0b24iLCJwYWdlIiwiZG93bmxvYWRCdXR0b24iLCJldmVudCIsInByZXZlbnREZWZhdWx0IiwidGhlbiIsIndpbmRvdyIsImxvY2F0aW9uIiwiaHJlZiIsImNoYXJ0Q2FudmFzIiwiYWN0aXZpdHlDaGFydERhdGEiLCJjdHgiLCJnZXRDb250ZXh0IiwiY2hhcnQiLCJDaGFydCIsImRhdGEiLCJvcHRpb25zIl0sInNvdXJjZVJvb3QiOiIifQ==