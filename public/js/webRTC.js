/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 7);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/webRTC.js":
/*!********************************!*\
  !*** ./resources/js/webRTC.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var socket = io('https://rtcserver.esuccess-inc.com:40002', {});\nvar peer = new Peer();\nvar myVideoStream;\nvar myId;\nvar videoGrid = document.getElementById('videoGrid');\nvar myvideo = document.createElement('video');\nmyvideo.muted = true;\nvar peerConnections = {};\nnavigator.mediaDevices.getUserMedia({\n  video: true,\n  audio: true\n}).then(function (stream) {\n  myVideoStream = stream;\n  addVideo(myvideo, stream);\n  peer.on('call', function (call) {\n    call.answer(stream);\n    var vid = document.createElement('video');\n    call.on('stream', function (userStream) {\n      addVideo(vid, userStream);\n    });\n    call.on('error', function (err) {\n      alert(err);\n    });\n    call.on(\"close\", function () {\n      console.log(vid);\n      vid.remove();\n    });\n    peerConnections[call.peer] = call;\n  });\n})[\"catch\"](function (err) {\n  alert(err.message);\n});\npeer.on('open', function (id) {\n  myId = id;\n  socket.emit(\"newUser\", id, roomID);\n});\npeer.on('error', function (err) {\n  alert(err.type);\n});\nsocket.on(\"connect\", function () {\n  console.log(socket.id); // \"G5p5...\"\n});\nsocket.on('userJoined', function (id) {\n  console.log(\"new user joined\");\n  var call = peer.call(id, myVideoStream);\n  var vid = document.createElement('video');\n  call.on('error', function (err) {\n    alert(err);\n  });\n  call.on('stream', function (userStream) {\n    addVideo(vid, userStream);\n  });\n  call.on('close', function () {\n    vid.remove();\n    console.log(\"user disconect\");\n  });\n  peerConnections[id] = call;\n});\nsocket.on('userDisconnect', function (id) {\n  if (peerConnections[id]) {\n    peerConnections[id].close();\n  }\n});\n\nfunction addVideo(video, stream) {\n  video.srcObject = stream;\n  video.addEventListener('loadedmetadata', function () {\n    video.play();\n  });\n  videoGrid.append(video);\n}\n\nfunction muteCam() {\n  myVideoStream.getVideoTracks().forEach(function (track) {\n    return track.enabled = !track.enabled;\n  });\n}\n\nfunction muteMic() {\n  myVideoStream.getAudioTracks().forEach(function (track) {\n    return track.enabled = !track.enabled;\n  });\n}\n\nvar testapp = function testapp() {\n  console.log(\"Executing action\");\n};\n\ndocument.getElementById(\"toggleCamera\").addEventListener(\"click\", function () {\n  muteCam();\n});\ndocument.getElementById(\"toggleAudio\").addEventListener(\"click\", function () {\n  muteMic();\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvd2ViUlRDLmpzP2MzMGQiXSwibmFtZXMiOlsic29ja2V0IiwiaW8iLCJwZWVyIiwiUGVlciIsIm15VmlkZW9TdHJlYW0iLCJteUlkIiwidmlkZW9HcmlkIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsIm15dmlkZW8iLCJjcmVhdGVFbGVtZW50IiwibXV0ZWQiLCJwZWVyQ29ubmVjdGlvbnMiLCJuYXZpZ2F0b3IiLCJtZWRpYURldmljZXMiLCJnZXRVc2VyTWVkaWEiLCJ2aWRlbyIsImF1ZGlvIiwidGhlbiIsInN0cmVhbSIsImFkZFZpZGVvIiwib24iLCJjYWxsIiwiYW5zd2VyIiwidmlkIiwidXNlclN0cmVhbSIsImVyciIsImFsZXJ0IiwiY29uc29sZSIsImxvZyIsInJlbW92ZSIsIm1lc3NhZ2UiLCJpZCIsImVtaXQiLCJyb29tSUQiLCJ0eXBlIiwiY2xvc2UiLCJzcmNPYmplY3QiLCJhZGRFdmVudExpc3RlbmVyIiwicGxheSIsImFwcGVuZCIsIm11dGVDYW0iLCJnZXRWaWRlb1RyYWNrcyIsImZvckVhY2giLCJ0cmFjayIsImVuYWJsZWQiLCJtdXRlTWljIiwiZ2V0QXVkaW9UcmFja3MiLCJ0ZXN0YXBwIl0sIm1hcHBpbmdzIjoiQUFBQSxJQUFNQSxNQUFNLEdBQUdDLEVBQUUsQ0FBQywwQ0FBRCxFQUE2QyxFQUE3QyxDQUFqQjtBQUNBLElBQU1DLElBQUksR0FBRyxJQUFJQyxJQUFKLEVBQWI7QUFDQSxJQUFJQyxhQUFKO0FBQ0EsSUFBSUMsSUFBSjtBQUNBLElBQUlDLFNBQVMsR0FBR0MsUUFBUSxDQUFDQyxjQUFULENBQXdCLFdBQXhCLENBQWhCO0FBQ0EsSUFBSUMsT0FBTyxHQUFHRixRQUFRLENBQUNHLGFBQVQsQ0FBdUIsT0FBdkIsQ0FBZDtBQUNBRCxPQUFPLENBQUNFLEtBQVIsR0FBZ0IsSUFBaEI7QUFHQSxJQUFNQyxlQUFlLEdBQUcsRUFBeEI7QUFFQUMsU0FBUyxDQUFDQyxZQUFW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ZW50IjpbImNvbnN0IHNvY2tldCA9IGlvKCdodHRwczovL3J0Y3NlcnZlci5lc3VjY2Vzcy1pbmMuY29tOjQwMDAyJywge30pO1xyXG5jb25zdCBwZWVyID0gbmV3IFBlZXIoKTtcclxubGV0IG15VmlkZW9TdHJlYW07XHJcbmxldCBteUlkO1xyXG52YXIgdmlkZW9HcmlkID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3ZpZGVvR3JpZCcpXHJcbnZhciBteXZpZGVvID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgndmlkZW8nKTtcclxubXl2aWRlby5tdXRlZCA9IHRydWU7XHJcblxyXG5cclxuY29uc3QgcGVlckNvbm5lY3Rpb25zID0ge31cclxuXHJcbm5hdmlnYXRvci5tZWRpYURldmljZXMuZ2V0VXNlck1lZGlhKHtcclxuICAgIHZpZGVvOiB0cnVlLFxyXG4gICAgYXVkaW86IHRydWVcclxufSkudGhlbigoc3RyZWFtKSA9PiB7XHJcbiAgICBteVZpZGVvU3RyZWFtID0gc3RyZWFtO1xyXG4gICAgYWRkVmlkZW8obXl2aWRlbywgc3RyZWFtKTtcclxuICAgIHBlZXIub24oJ2NhbGwnLCBjYWxsID0+IHtcclxuICAgICAgICBjYWxsLmFuc3dlcihzdHJlYW0pO1xyXG4gICAgICAgIGNvbnN0IHZpZCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3ZpZGVvJyk7XHJcbiAgICAgICAgY2FsbC5vbignc3RyZWFtJywgdXNlclN0cmVhbSA9PiB7XHJcbiAgICAgICAgICAgIGFkZFZpZGVvKHZpZCwgdXNlclN0cmVhbSk7XHJcbiAgICAgICAgfSlcclxuICAgICAgICBjYWxsLm9uKCdlcnJvcicsIChlcnIpID0+IHtcclxuICAgICAgICAgICAgYWxlcnQoZXJyKVxyXG4gICAgICAgIH0pXHJcbiAgICAgICAgY2FsbC5vbihcImNsb3NlXCIsICgpID0+IHtcclxuICAgICAgICAgICAgY29uc29sZS5sb2codmlkKTtcclxuICAgICAgICAgICAgdmlkLnJlbW92ZSgpO1xyXG4gICAgICAgIH0pXHJcbiAgICAgICAgcGVlckNvbm5lY3Rpb25zW2NhbGwucGVlcl0gPSBjYWxsO1xyXG4gICAgfSlcclxufSkuY2F0Y2goZXJyID0+IHtcclxuICAgIGFsZXJ0KGVyci5tZXNzYWdlKVxyXG59KTtcclxuXHJcbnBlZXIub24oJ29wZW4nLCAoaWQpID0+IHtcclxuICAgIG15SWQgPSBpZDtcclxuICAgIHNvY2tldC5lbWl0KFwibmV3VXNlclwiLCBpZCwgcm9vbUlEKTtcclxufSk7XHJcblxyXG5wZWVyLm9uKCdlcnJvcicsIChlcnIpID0+IHtcclxuICAgIGFsZXJ0KGVyci50eXBlKTtcclxufSk7XHJcblxyXG5zb2NrZXQub24oXCJjb25uZWN0XCIsICgpID0+IHtcclxuICAgIGNvbnNvbGUubG9nKHNvY2tldC5pZCk7IC8vIFwiRzVwNS4uLlwiXHJcbn0pO1xyXG5cclxuXHJcbnNvY2tldC5vbigndXNlckpvaW5lZCcsIGlkID0+IHtcclxuICAgIGNvbnNvbGUubG9nKFwibmV3IHVzZXIgam9pbmVkXCIpXHJcbiAgICBjb25zdCBjYWxsID0gcGVlci5jYWxsKGlkLCBteVZpZGVvU3RyZWFtKTtcclxuICAgIGNvbnN0IHZpZCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3ZpZGVvJyk7XHJcbiAgICBjYWxsLm9uKCdlcnJvcicsIChlcnIpID0+IHtcclxuICAgICAgICBhbGVydChlcnIpO1xyXG4gICAgfSlcclxuICAgIGNhbGwub24oJ3N0cmVhbScsIHVzZXJTdHJlYW0gPT4ge1xyXG4gICAgICAgIGFkZFZpZGVvKHZpZCwgdXNlclN0cmVhbSk7XHJcbiAgICB9KVxyXG4gICAgY2FsbC5vbignY2xvc2UnLCAoKSA9PiB7XHJcbiAgICAgICAgdmlkLnJlbW92ZSgpO1xyXG4gICAgICAgIGNvbnNvbGUubG9nKFwidXNlciBkaXNjb25lY3RcIilcclxuICAgIH0pXHJcbiAgICBwZWVyQ29ubmVjdGlvbnNbaWRdID0gY2FsbDtcclxufSk7XHJcblxyXG5zb2NrZXQub24oJ3VzZXJEaXNjb25uZWN0JywgaWQgPT4ge1xyXG4gICAgaWYgKHBlZXJDb25uZWN0aW9uc1tpZF0pIHtcclxuICAgICAgICBwZWVyQ29ubmVjdGlvbnNbaWRdLmNsb3NlKCk7XHJcbiAgICB9XHJcbn0pO1xyXG5cclxuXHJcblxyXG5mdW5jdGlvbiBhZGRWaWRlbyh2aWRlbywgc3RyZWFtKSB7XHJcbiAgICB2aWRlby5zcmNPYmplY3QgPSBzdHJlYW07XHJcbiAgICB2aWRlby5hZGRFdmVudExpc3RlbmVyKCdsb2FkZWRtZXRhZGF0YScsICgpID0+IHtcclxuICAgICAgICB2aWRlby5wbGF5KClcclxuICAgIH0pXHJcbiAgICB2aWRlb0dyaWQuYXBwZW5kKHZpZGVvKTtcclxufVxyXG5cclxuXHJcbmZ1bmN0aW9uIG11dGVDYW0oKSB7XHJcbiAgICBteVZpZGVvU3RyZWFtLmdldFZpZGVvVHJhY2tzKCkuZm9yRWFjaCh0cmFjayA9PiB0cmFjay5lbmFibGVkID0gIXRyYWNrLmVuYWJsZWQpO1xyXG59XHJcblxyXG5mdW5jdGlvbiBtdXRlTWljKCkge1xyXG4gICAgbXlWaWRlb1N0cmVhbS5nZXRBdWRpb1RyYWNrcygpLmZvckVhY2godHJhY2sgPT4gdHJhY2suZW5hYmxlZCA9ICF0cmFjay5lbmFibGVkKTtcclxufVxyXG5cclxuXHJcblxyXG5sZXQgdGVzdGFwcCA9ICgpID0+IHtcclxuICAgIGNvbnNvbGUubG9nKFwiRXhlY3V0aW5nIGFjdGlvblwiKTtcclxufTtcclxuXHJcblxyXG5kb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInRvZ2dsZUNhbWVyYVwiKS5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgZnVuY3Rpb24oKSB7XHJcbiAgICBtdXRlQ2FtKCk7XHJcbn0pO1xyXG5cclxuZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJ0b2dnbGVBdWRpb1wiKS5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgZnVuY3Rpb24oKSB7XHJcbiAgICBtdXRlTWljKCk7XHJcbn0pOyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/webRTC.js\n");

/***/ }),

/***/ 7:
/*!**************************************!*\
  !*** multi ./resources/js/webRTC.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/esi_development/resources/js/webRTC.js */"./resources/js/webRTC.js");


/***/ })

/******/ });