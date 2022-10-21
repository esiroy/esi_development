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
let myAudioStream;


const peerConnections = {}


let mediaContainer = document.getElementById('myMediaContainer');


let videoGrid = document.getElementById('videoGrid');

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


    data = {
        'id': myId,
        'user': user,
        'roomID': roomID
    }

    socket.emit("newUser", data);

    //start a video with audio connection width data of user
    let video = false;
    let audio = true;

    console.log(data);

    start(video, audio, data);
}


function addMyAudio(audio, stream) {
    audio.srcObject = stream;
    audio.addEventListener('loadedmetadata', () => {
        audio.play()
    })
    mediaContainer.append(audio);
}


function addMyVideo(video, stream) {
    video.srcObject = stream;
    video.addEventListener('loadedmetadata', () => {
        video.play()
    })
    mediaContainer.append(video);
}


function addAudio(audio, stream) {
    audio.srcObject = stream;
    audio.addEventListener('loadedmetadata', () => {
        audio.play()
    })
    videoGrid.append(audio);
}

function addVideo(video, stream) {
    video.srcObject = stream;
    video.addEventListener('loadedmetadata', () => {
        video.play()
    })
    videoGrid.append(video);
}

function removeElementByID(id) {
    let element = document.getElementById(id);
    if (element) {
        element.remove();
    }
}

function connectMedia(video, audio, constraints) {

    navigator.mediaDevices.getUserMedia(constraints).then((stream) => {

        removeElementByID("myVideo");
        removeElementByID("myAudio");

        if (audio == true && video == true) {

            //Register the video stream to my Stream
            myVideoStream = stream;
            window.stream = stream; // make stream available to console       

            myAudioStream = null;

            console.log("this is a video");

            videoElement = document.createElement('video');
            videoElement.setAttribute("id", "myVideo");
            videoElement.muted = true;
            addMyVideo(videoElement, stream);

            console.log("calling change media, so we can get contact video");

            socket.emit("changeMedia", data);

        } else {


            //Register the video stream to my Stream
            myVideoStream = null;

            window.stream = stream; // make stream available to console       
            myAudioStream = stream;

            console.log("this is a audio only")

            audio = document.createElement('audio');
            audio.setAttribute("class", "myAudio");
            audio.setAttribute("controls", "controls");
            audio.muted = true;

            addMyAudio(audio, stream);

            socket.emit("changeMedia", data);

        }



        return navigator.mediaDevices.enumerateDevices();


    }).then(gotDevices).catch((err) => {

        if (video == true && audio == true) {
            handleError(err);
        } else {

            alert("no media detected, please connect and try again")
        }
    });


}

function start(video, audio, data) {

    if (window.stream) {

        window.stream.getTracks().forEach(track => {
            track.stop();
        });
    }
    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;

    if (video == true && audio == true) {

        const constraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: { deviceId: videoSource ? { exact: videoSource } : undefined }
        };

        connectMedia(video, audio, constraints)

    } else if (video == false && audio == true) {

        const constraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: false
        };

        connectMedia(video, audio, constraints)

    } else {

        alert("no media detected, please connect and try again")
    }

}

function restart() {

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
    navigator.mediaDevices.getUserMedia(constraints).then((stream) => {

        console.log("restart")


        window.stream = stream; // make stream available to console
        videoElement.srcObject = stream;

        //Register the video stream to my Stream
        myVideoStream = stream;

        data = {
            'id': myId,
            'user': user,
            'roomID': roomID,
            'videoStream': myVideoStream
        }

        socket.emit("changeMedia", data);

        removeElementByID(myId);


    }).catch(handleError);
}

audioInputSelect.onchange = restart;
audioOutputSelect.onchange = changeAudioDestination;
videoSelect.onchange = restart;




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

    //start a video with audio connection width data of user
    let video = true;
    let audio = true;

    start(video, audio, data)

});



peer.on('call', call => {

    let ctr = 0;

    console.log("call");

    call.answer(myVideoStream);

    call.on('stream', (userStream) => {

        if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

            console.log("callback is a video")

            removeElementByID(data.id);

            callerElement = document.createElement('video');
            callerElement.setAttribute("id", data.id);
            callerElement.setAttribute("class", "callerBackVideo");
            callerElement.muted = false;

            addVideo(callerElement, userStream);


        } else {

            console.log("callback AUDIO")

            removeElementByID(data.id);

            callerElement = document.createElement('audio');
            callerElement.setAttribute("id", data.id);
            callerElement.setAttribute("class", "callbackAudio");
            callerElement.setAttribute("controls", "controls");
            callerElement.muted = false;

            addAudio(callerElement, userStream);
        }
        // }

        ctr++


    });

    call.on('close', () => {
        removeElementByID(call.peer);
        console.log("user disconected")
    });

});

peer.on('close', (id) => {
    document.getElementById(id).remove();
});


socket.on('userJoined', (data) => {

    /*

    let id = data.id;
    let roomID = data.roomID;
    let user = data.user;

    const callback = peer.call(id, myVideoStream);

    if (callback) {

        let ctr = 0;
        callback.on('stream', (userStream) => {

            if (ctr == 1) {

                removeElementByID(data.id);

                callerElement = document.createElement('video');
                callerElement.setAttribute("id", data.id);
                callerElement.setAttribute("class", "callerBackVideo");
                callerElement.muted = false;
                addVideo(callerElement, userStream);
            }
            ctr++;
        });

        callback.on('close', () => {
            console.log("closing! callback video...")
            removeElementByID(data.id);
        });

        callback.on('error', (err) => {
            console.log(err);
        });
    }
    */

});


socket.on('mediaChanged', (data) => {


    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;
    const constraints = {
        audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
        video: { deviceId: videoSource ? { exact: videoSource } : undefined }
    };

    navigator.mediaDevices.getUserMedia(constraints).then((userStream) => {

        console.log(userStream.getAudioTracks().length)
        console.log(userStream.getVideoTracks().length);

        callback = peer.call(data.id, userStream);

        if (callback) {

            let ctr = 0;

            callback.on('stream', (userStream) => {

                if (ctr == 0) {

                    console.log(userStream.getAudioTracks().length)
                    console.log(userStream.getVideoTracks().length)

                    if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

                        console.log("user sent a video")

                        removeElementByID(allback.peer);

                        callerElement = document.createElement('video');
                        callerElement.setAttribute("id", callback.peer);
                        callerElement.setAttribute("class", "callerBackVideo");
                        callerElement.muted = false;

                        addVideo(callerElement, userStream);


                    } else {

                        console.log("user sent a AUDIO")

                        removeElementByID(data.id);


                        callerElement = document.createElement('audio');
                        callerElement.setAttribute("id", data.id);
                        callerElement.setAttribute("class", "callbackAudio");
                        callerElement.setAttribute("controls", "controls");
                        callerElement.muted = false;

                        addAudio(callerElement, userStream);
                    }

                }


                ctr++;
            });

            callback.on('close', () => {
                removeElementByID(data.id);
            });

            callback.on('error', (err) => {
                console.log(err);
            });

            peerConnections[data.id] = callback;
        }


    }).catch((error) => {

        alert("")
        console.log(error)
    });



    /*
    let id = data.id;
    let roomID = data.roomID;
    let user = data.user;

    let callback = null;



    if (myVideoStream !== null) {
        callback = peer.call(id, myVideoStream);
    } else if (myAudioStream !== null) {
        callback = peer.call(id, myAudioStream);
    }

    console.log("my video", myVideoStream);
    console.log("my video", myAudioStream);



    if (callback) {

        let ctr = 0;

        callback.on('stream', (userStream) => {

            if (ctr == 0) {

                console.log(userStream.getAudioTracks().length)
                console.log(userStream.getVideoTracks().length)

                if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

                    console.log("user sent a video")

                    removeElementByID(data.id);


                    callerElement = document.createElement('video');
                    callerElement.setAttribute("id", data.id);
                    callerElement.setAttribute("class", "callerBackVideo");
                    callerElement.muted = false;

                    addVideo(callerElement, userStream);


                } else {

                    console.log("user sent a AUDIO")

                    removeElementByID(data.id);


                    callerElement = document.createElement('audio');
                    callerElement.setAttribute("id", data.id);
                    callerElement.setAttribute("class", "callbackAudio");
                    callerElement.setAttribute("controls", "controls");
                    callerElement.muted = false;

                    addAudio(callerElement, userStream);
                }

            }


            ctr++;
        });

        callback.on('close', () => {
            removeElementByID(data.id);
        });

        callback.on('error', (err) => {
            console.log(err);
        });

        peerConnections[data.id] = callback;
    }
    */
});


socket.on('userDisconnect', id => {

    console.log("userDisconnected", id);

    removeElementByID(id);

    if (peerConnections[id]) {
        peerConnections[id].close();
    }
});