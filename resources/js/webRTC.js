/*
 *  Copyright (c) 2015 The WebRTC project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a BSD-style license
 *  that can be found in the LICENSE file in the root of the source
 *  tree.
 */

const socket = io('https://rtcserver.esuccess-inc.com:40002', {});

const peer = new Peer({
    initiator: false,
    trickle: false,
});

'use strict';
let myVideoStream;


const peerConnections = {}

//steam
const videoElement = document.querySelector('video');
const audioInputSelect = document.querySelector('select#audioSource');
const audioOutputSelect = document.querySelector('select#audioOutput');
const videoSelect = document.querySelector('select#videoSource');
const selectors = [audioInputSelect, audioOutputSelect, videoSelect];

audioOutputSelect.disabled = !('sinkId' in HTMLMediaElement.prototype);

function gotDevices(deviceInfos) {
    // Handles being called several times to update labels. Preserve values.
    const values = selectors.map(select => select.value);
    selectors.forEach(select => {
        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }
    });
    for (let i = 0; i !== deviceInfos.length; ++i) {
        const deviceInfo = deviceInfos[i];
        const option = document.createElement('option');
        option.value = deviceInfo.deviceId;
        if (deviceInfo.kind === 'audioinput') {
            option.text = deviceInfo.label || `microphone ${audioInputSelect.length + 1}`;
            audioInputSelect.appendChild(option);
        } else if (deviceInfo.kind === 'audiooutput') {
            option.text = deviceInfo.label || `speaker ${audioOutputSelect.length + 1}`;
            audioOutputSelect.appendChild(option);
        } else if (deviceInfo.kind === 'videoinput') {
            option.text = deviceInfo.label || `camera ${videoSelect.length + 1}`;
            videoSelect.appendChild(option);
        } else {
            console.log('Some other kind of source/device: ', deviceInfo);
        }
    }
    selectors.forEach((select, selectorIndex) => {
        if (Array.prototype.slice.call(select.childNodes).some(n => n.value === values[selectorIndex])) {
            select.value = values[selectorIndex];
        }
    });
}

navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleError);

// Attach audio output device to video element using device/sink ID.
function attachSinkId(element, sinkId) {
    if (typeof element.sinkId !== 'undefined') {
        element.setSinkId(sinkId)
            .then(() => {
                console.log(`Success, audio output device attached: ${sinkId}`);
            })
            .catch(error => {
                let errorMessage = error;
                if (error.name === 'SecurityError') {
                    errorMessage = `You need to use HTTPS for selecting audio output device: ${error}`;
                }
                console.error(errorMessage);
                // Jump back to first output device in the list as it's the default.
                audioOutputSelect.selectedIndex = 0;
            });
    } else {
        console.warn('Browser does not support output device selection.');
    }
}

function changeAudioDestination() {
    const audioDestination = audioOutputSelect.value;
    attachSinkId(videoElement, audioDestination);
}

function gotStream(stream) {
    window.stream = stream; // make stream available to console
    videoElement.srcObject = stream;

    //Register the video stream to my Stream
    myVideoStream = stream;

    // Refresh button list in case labels have become available
    return navigator.mediaDevices.enumerateDevices();
}

function handleError(error) {
    console.log('navigator.MediaDevices.getUserMedia error: ', error.message, error.name);
}

function start() {
    if (window.stream) {
        window.stream.getTracks().forEach(track => {
            track.stop();
        });
    }
    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;
    const constraints = {
        audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
        video: { deviceId: videoSource ? { exact: videoSource } : undefined }
    };
    navigator.mediaDevices.getUserMedia(constraints).then(gotStream).then(gotDevices).catch(handleError);

}

function addVideo(video, stream) {
    video.srcObject = stream;
    video.addEventListener('loadedmetadata', () => {
        video.play()
    })
    videoGrid.append(video);
}


audioInputSelect.onchange = start;
audioOutputSelect.onchange = changeAudioDestination;
videoSelect.onchange = start;


start();

peer.on('connection', function(conn) {

    conn.on('data', function(data) {
        console.log("peer connected", data)
    });

    conn.on('close', () => {
        alert("close")
    });

});

peer.on('open', (id) => {
    console.log("my peer id" + id)
    console.log("my user ", user)
    console.log("my room id ", roomID)

    myId = id;

    data = {
        'id': id,
        'user': user,
        'roomID': roomID
    }

    socket.emit("newUser", data);
});

peer.on('call', call => {

    let ctr = 0;

    call.answer(myVideoStream);


    call.on('stream', (userStream) => {
        if (ctr == 0) {
            callerElement = document.createElement('video');
            //console.log(call.peer); (add for deletion purposes)
            callerElement.setAttribute("id", call.peer);
            callerElement.setAttribute("class", "callerVideo");
            callerElement.muted = false;
            addVideo(callerElement, userStream);
        }
        ctr++
    });
});

peer.on('close', (id) => {
    alert("close!")
});




socket.on('userJoined', (data) => {

    let id = data.id;
    let roomID = data.roomID;
    let user = data.user;

    const callback = peer.call(id, myVideoStream);

    if (callback) {

        let ctr = 0;
        callback.on('stream', (userStream) => {
            if (ctr == 0) {
                callerElement = document.createElement('video');
                callerElement.setAttribute("id", data.id);
                callerElement.setAttribute("class", "callerBackVideo");
                callerElement.muted = false;
                addVideo(callerElement, userStream);
            }
            ctr++;
        });


        callback.on('close', () => {
            document.getElementById(id).remove();
        });

        callback.on('error', (err) => {
            console.log(err);
        });
    }


    peerConnections[id] = callback;

});


socket.on('userDisconnect', id => {
    if (peerConnections[id]) {
        peerConnections[id].close();
        document.getElementById(id).remove();
    }
});