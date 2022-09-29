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

eval("var socket = io('https://rtcserver.esuccess-inc.com:40002', {});\nvar peer = new Peer();\nvar myVideoStream;\nvar myId;\nvar videoGrid = document.getElementById('videoGrid');\nvar myvideo = document.createElement('video');\nmyvideo.setAttribute(\"id\", \"myVideo\");\nmyvideo.muted = false;\nvar peerConnections = {};\nsocket.on(\"connect\", function () {\n  console.log(socket.id); // \"G5p5...\"\n\n  createUserMedia();\n});\npeer.on('open', function (id) {\n  myId = id;\n  socket.emit(\"newUser\", id, roomID);\n});\npeer.on('error', function (err) {\n  alert(err.type);\n});\nsocket.on('userJoined', function (id) {\n  console.log(\"new user joined\");\n  var call = peer.call(id, myVideoStream);\n  var vid = document.createElement('video');\n  call.on('error', function (err) {\n    alert(err);\n  });\n  call.on('stream', function (userStream) {\n    addVideo(vid, userStream);\n  });\n  call.on('close', function () {\n    vid.remove();\n    console.log(\"user disconect\");\n  });\n  peerConnections[id] = call;\n});\nsocket.on('userDisconnect', function (id) {\n  if (peerConnections[id]) {\n    peerConnections[id].close();\n  }\n});\n\nfunction createUserMedia() {\n  navigator.mediaDevices.getUserMedia({\n    video: true,\n    audio: true\n  }).then(function (stream) {\n    console.log(\"getting user Media...\");\n    myVideoStream = stream;\n    addVideo(myvideo, stream);\n    peer.on('call', function (call) {\n      call.answer(stream);\n      var vid = document.createElement('video');\n      vid.setAttribute(\"id\", \"callerVideo\");\n      call.on('stream', function (userStream) {\n        addVideo(vid, userStream);\n      });\n      call.on('error', function (err) {\n        alert(err);\n      });\n      call.on(\"close\", function () {\n        console.log(vid);\n        vid.remove();\n      });\n      peerConnections[call.peer] = call;\n    });\n  })[\"catch\"](function (err) {\n    alert(err.message);\n  });\n}\n\nfunction addVideo(video, stream) {\n  video.srcObject = stream;\n  video.addEventListener('loadedmetadata', function () {\n    video.play();\n  });\n  videoGrid.append(video);\n}\n\nfunction muteCam() {\n  myVideoStream.getVideoTracks().forEach(function (track) {\n    return track.enabled = !track.enabled;\n  });\n}\n\nfunction muteMic() {\n  myVideoStream.getAudioTracks().forEach(function (track) {\n    return track.enabled = !track.enabled;\n  });\n}\n\nfunction shareScreen() {\n  navigator.mediaDevices.getDisplayMedia({\n    video: true,\n    audio: true\n  }).then(function (stream) {\n    myVideoStream = stream;\n    addVideo(myvideo, stream);\n\n    myVideoStream.getVideoTracks()[0].onended = function () {\n      console.log(\"ended a video share\"); //reconnect userMedia\n\n      createUserMedia();\n    };\n  });\n} //DOM Execution\n\n\ndocument.getElementById(\"toggleCamera\").addEventListener(\"click\", function () {\n  muteCam();\n});\ndocument.getElementById(\"toggleAudio\").addEventListener(\"click\", function () {\n  muteMic();\n});\ndocument.getElementById(\"shareScreen\").addEventListener(\"click\", function () {\n  shareScreen();\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvd2ViUlRDLmpzP2MzMGQiXSwibmFtZXMiOlsic29ja2V0IiwiaW8iLCJwZWVyIiwiUGVlciIsIm15VmlkZW9TdHJlYW0iLCJteUlkIiwidmlkZW9HcmlkIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsIm15dmlkZW8iLCJjcmVhdGVFbGVtZW50Iiwic2V0QXR0cmlidXRlIiwibXV0ZWQiLCJwZWVyQ29ubmVjdGlvbnMiLCJvbiIsImNvbnNvbGUiLCJsb2ciLCJpZCIsImNyZWF0ZVVzZXJNZWRpYSIsImVtaXQiLCJyb29tSUQiLCJlcnIiLCJhbGVydCIsInR5cGUiLCJjYWxsIiwidmlkIiwidXNlclN0cmVhbSIsImFkZFZpZGVvIiwicmVtb3ZlIiwiY2xvc2UiLCJuYXZpZ2F0b3IiLCJtZWRpYURldmljZXMiLCJnZXRVc2VyTWVkaWEiLCJ2aWRlbyIsImF1ZGlvIiwidGhlbiIsInN0cmVhbSIsImFuc3dlciIsIm1lc3NhZ2UiLCJzcmNPYmplY3QiLCJhZGRFdmVudExpc3RlbmVyIiwicGxheSIsImFwcGVuZCIsIm11dGVDYW0iLCJnZXRWaWRlb1RyYWNrcyIsImZvckVhY2giLCJ0cmFjayIsImVuYWJsZWQiLCJtdXRlTWljIiwiZ2V0QXVkaW9UcmFja3MiLCJzaGFyZVNjcmVlbiIsImdldERpc3BsYXlNZWRpYSIsIm9uZW5kZWQiXSwibWFwcGluZ3MiOiJBQUFBLElBQU1BLE1BQU0sR0FBR0MsRUFBRSxDQUFDLDBDQUFELEVBQTZDLEVBQTdDLENBQWpCO0FBQ0EsSUFBTUMsSUFBSSxHQUFHLElBQUlDLElBQUosRUFBYjtBQUNBLElBQUlDLGFBQUo7QUFDQSxJQUFJQyxJQUFKO0FBQ0EsSUFBSUMsU0FBUyxHQUFHQyxRQUFRLENBQUNDLGNBQVQsQ0FBd0IsV0FBeEIsQ0FBaEI7QUFFQSxJQUFJQyxPQUFPLEdBQUdGLFFBQVEsQ0FBQ0csYUFBVCxDQUF1QixPQUF2QixDQUFkO0FBQ0FELE9BQU8sQ0FBQ0Us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RjRCLENBQXZDLEVBR0dDLElBSEgsQ0FHUSxVQUFDQyxNQUFELEVBQVk7SUFDaEJoQyxhQUFhLEdBQUdnQyxNQUFoQjtJQUNBVCxRQUFRLENBQUNsQixPQUFELEVBQVUyQixNQUFWLENBQVI7O0lBRUFoQyxhQUFhLENBQUN3QyxjQUFkLEdBQStCLENBQS9CLEVBQWtDUSxPQUFsQyxHQUE0QyxZQUFXO01BQ25EckMsT0FBTyxDQUFDQyxHQUFSLENBQVkscUJBQVosRUFEbUQsQ0FHbkQ7O01BQ0FFLGVBQWU7SUFDbEIsQ0FMRDtFQU9ILENBZEQ7QUFlSCxDLENBS0Q7OztBQUNBWCxRQUFRLENBQUNDLGNBQVQsQ0FBd0IsY0FBeEIsRUFBd0NnQyxnQkFBeEMsQ0FBeUQsT0FBekQsRUFBa0UsWUFBVztFQUN6RUcsT0FBTztBQUNWLENBRkQ7QUFJQXBDLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixhQUF4QixFQUF1Q2dDLGdCQUF2QyxDQUF3RCxPQUF4RCxFQUFpRSxZQUFXO0VBQ3hFUSxPQUFPO0FBQ1YsQ0FGRDtBQUlBekMsUUFBUSxDQUFDQyxjQUFULENBQXdCLGFBQXhCLEVBQXVDZ0MsZ0JBQXZDLENBQXdELE9BQXhELEVBQWlFLFlBQVc7RUFDeEVVLFdBQVc7QUFDZCxDQUZEIiwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL3dlYlJUQy5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbImNvbnN0IHNvY2tldCA9IGlvKCdodHRwczovL3J0Y3NlcnZlci5lc3VjY2Vzcy1pbmMuY29tOjQwMDAyJywge30pO1xyXG5jb25zdCBwZWVyID0gbmV3IFBlZXIoKTtcclxubGV0IG15VmlkZW9TdHJlYW07XHJcbmxldCBteUlkO1xyXG52YXIgdmlkZW9HcmlkID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3ZpZGVvR3JpZCcpXHJcblxyXG52YXIgbXl2aWRlbyA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ3ZpZGVvJyk7XHJcbm15dmlkZW8uc2V0QXR0cmlidXRlKFwiaWRcIiwgXCJteVZpZGVvXCIpXHJcblxyXG5teXZpZGVvLm11dGVkID0gZmFsc2U7XHJcblxyXG5cclxuY29uc3QgcGVlckNvbm5lY3Rpb25zID0ge31cclxuXHJcblxyXG5zb2NrZXQub24oXCJjb25uZWN0XCIsICgpID0+IHtcclxuICAgIGNvbnNvbGUubG9nKHNvY2tldC5pZCk7IC8vIFwiRzVwNS4uLlwiXHJcblxyXG4gICAgY3JlYXRlVXNlck1lZGlhKCk7XHJcbn0pO1xyXG5cclxuXHJcblxyXG5wZWVyLm9uKCdvcGVuJywgKGlkKSA9PiB7XHJcbiAgICBteUlkID0gaWQ7XHJcbiAgICBzb2NrZXQuZW1pdChcIm5ld1VzZXJcIiwgaWQsIHJvb21JRCk7XHJcbn0pO1xyXG5cclxucGVlci5vbignZXJyb3InLCAoZXJyKSA9PiB7XHJcbiAgICBhbGVydChlcnIudHlwZSk7XHJcbn0pO1xyXG5cclxuXHJcblxyXG5cclxuc29ja2V0Lm9uKCd1c2VySm9pbmVkJywgaWQgPT4ge1xyXG4gICAgY29uc29sZS5sb2coXCJuZXcgdXNlciBqb2luZWRcIilcclxuICAgIGNvbnN0IGNhbGwgPSBwZWVyLmNhbGwoaWQsIG15VmlkZW9TdHJlYW0pO1xyXG4gICAgY29uc3QgdmlkID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgndmlkZW8nKTtcclxuICAgIGNhbGwub24oJ2Vycm9yJywgKGVycikgPT4ge1xyXG4gICAgICAgIGFsZXJ0KGVycik7XHJcbiAgICB9KVxyXG4gICAgY2FsbC5vbignc3RyZWFtJywgdXNlclN0cmVhbSA9PiB7XHJcbiAgICAgICAgYWRkVmlkZW8odmlkLCB1c2VyU3RyZWFtKTtcclxuICAgIH0pXHJcbiAgICBjYWxsLm9uKCdjbG9zZScsICgpID0+IHtcclxuICAgICAgICB2aWQucmVtb3ZlKCk7XHJcbiAgICAgICAgY29uc29sZS5sb2coXCJ1c2VyIGRpc2NvbmVjdFwiKVxyXG4gICAgfSlcclxuICAgIHBlZXJDb25uZWN0aW9uc1tpZF0gPSBjYWxsO1xyXG59KTtcclxuXHJcbnNvY2tldC5vbigndXNlckRpc2Nvbm5lY3QnLCBpZCA9PiB7XHJcbiAgICBpZiAocGVlckNvbm5lY3Rpb25zW2lkXSkge1xyXG4gICAgICAgIHBlZXJDb25uZWN0aW9uc1tpZF0uY2xvc2UoKTtcclxuICAgIH1cclxufSk7XHJcblxyXG5cclxuZnVuY3Rpb24gY3JlYXRlVXNlck1lZGlhKCkge1xyXG5cclxuICAgIG5hdmlnYXRvci5tZWRpYURldmljZXMuZ2V0VXNlck1lZGlhKHtcclxuICAgICAgICB2aWRlbzogdHJ1ZSxcclxuICAgICAgICBhdWRpbzogdHJ1ZVxyXG4gICAgfSkudGhlbigoc3RyZWFtKSA9PiB7XHJcblxyXG4gICAgICAgIGNvbnNvbGUubG9nKFwiZ2V0dGluZyB1c2VyIE1lZGlhLi4uXCIpO1xyXG5cclxuICAgICAgICBteVZpZGVvU3RyZWFtID0gc3RyZWFtO1xyXG4gICAgICAgIGFkZFZpZGVvKG15dmlkZW8sIHN0cmVhbSk7XHJcblxyXG4gICAgICAgIHBlZXIub24oJ2NhbGwnLCBjYWxsID0+IHtcclxuICAgICAgICAgICAgY2FsbC5hbnN3ZXIoc3RyZWFtKTtcclxuICAgICAgICAgICAgY29uc3QgdmlkID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgndmlkZW8nKTtcclxuICAgICAgICAgICAgdmlkLnNldEF0dHJpYnV0ZShcImlkXCIsIFwiY2FsbGVyVmlkZW9cIilcclxuXHJcbiAgICAgICAgICAgIGNhbGwub24oJ3N0cmVhbScsIHVzZXJTdHJlYW0gPT4ge1xyXG4gICAgICAgICAgICAgICAgYWRkVmlkZW8odmlkLCB1c2VyU3RyZWFtKTtcclxuICAgICAgICAgICAgfSlcclxuICAgICAgICAgICAgY2FsbC5vbignZXJyb3InLCAoZXJyKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBhbGVydChlcnIpXHJcbiAgICAgICAgICAgIH0pXHJcbiAgICAgICAgICAgIGNhbGwub24oXCJjbG9zZVwiLCAoKSA9PiB7XHJcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyh2aWQpO1xyXG4gICAgICAgICAgICAgICAgdmlkLnJlbW92ZSgpO1xyXG4gICAgICAgICAgICB9KVxyXG4gICAgICAgICAgICBwZWVyQ29ubmVjdGlvbnNbY2FsbC5wZWVyXSA9IGNhbGw7XHJcbiAgICAgICAgfSlcclxuICAgIH0pLmNhdGNoKGVyciA9PiB7XHJcbiAgICAgICAgYWxlcnQoZXJyLm1lc3NhZ2UpXHJcbiAgICB9KTtcclxufVxyXG5cclxuXHJcbmZ1bmN0aW9uIGFkZFZpZGVvKHZpZGVvLCBzdHJlYW0pIHtcclxuICAgIHZpZGVvLnNyY09iamVjdCA9IHN0cmVhbTtcclxuICAgIHZpZGVvLmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWRlZG1ldGFkYXRhJywgKCkgPT4ge1xyXG4gICAgICAgIHZpZGVvLnBsYXkoKVxyXG4gICAgfSlcclxuICAgIHZpZGVvR3JpZC5hcHBlbmQodmlkZW8pO1xyXG59XHJcblxyXG5cclxuZnVuY3Rpb24gbXV0ZUNhbSgpIHtcclxuICAgIG15VmlkZW9TdHJlYW0uZ2V0VmlkZW9UcmFja3MoKS5mb3JFYWNoKHRyYWNrID0+IHRyYWNrLmVuYWJsZWQgPSAhdHJhY2suZW5hYmxlZCk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIG11dGVNaWMoKSB7XHJcbiAgICBteVZpZGVvU3RyZWFtLmdldEF1ZGlvVHJhY2tzKCkuZm9yRWFjaCh0cmFjayA9PiB0cmFjay5lbmFibGVkID0gIXRyYWNrLmVuYWJsZWQpO1xyXG59XHJcblxyXG5cclxuZnVuY3Rpb24gc2hhcmVTY3JlZW4oKSB7XHJcbiAgICBuYXZpZ2F0b3IubWVkaWFEZXZpY2VzLmdldERpc3BsYXlNZWRpYSh7XHJcbiAgICAgICAgdmlkZW86IHRydWUsXHJcbiAgICAgICAgYXVkaW86IHRydWVcclxuICAgIH0pLnRoZW4oKHN0cmVhbSkgPT4ge1xyXG4gICAgICAgIG15VmlkZW9TdHJlYW0gPSBzdHJlYW07XHJcbiAgICAgICAgYWRkVmlkZW8obXl2aWRlbywgc3RyZWFtKTtcclxuXHJcbiAgICAgICAgbXlWaWRlb1N0cmVhbS5nZXRWaWRlb1RyYWNrcygpWzBdLm9uZW5kZWQgPSBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgY29uc29sZS5sb2coXCJlbmRlZCBhIHZpZGVvIHNoYXJlXCIpXHJcblxyXG4gICAgICAgICAgICAvL3JlY29ubmVjdCB1c2VyTWVkaWFcclxuICAgICAgICAgICAgY3JlYXRlVXNlck1lZGlhKClcclxuICAgICAgICB9O1xyXG5cclxuICAgIH0pO1xyXG59XHJcblxyXG5cclxuXHJcblxyXG4vL0RPTSBFeGVjdXRpb25cclxuZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJ0b2dnbGVDYW1lcmFcIikuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGZ1bmN0aW9uKCkge1xyXG4gICAgbXV0ZUNhbSgpO1xyXG59KTtcclxuXHJcbmRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwidG9nZ2xlQXVkaW9cIikuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGZ1bmN0aW9uKCkge1xyXG4gICAgbXV0ZU1pYygpO1xyXG59KTtcclxuXHJcbmRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwic2hhcmVTY3JlZW5cIikuYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGZ1bmN0aW9uKCkge1xyXG4gICAgc2hhcmVTY3JlZW4oKTtcclxufSk7Il0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/webRTC.js\n");

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