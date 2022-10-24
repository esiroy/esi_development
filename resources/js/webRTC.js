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

let videoElement;
let audioElement;

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


    if (myVideoStream) {
        attachSinkId(videoElement, audioDestination);
    } else {
        attachSinkId(audioElement, audioDestination);
    }
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
    //start a video with audio connection width data of user
    let video = false;
    let audio = true;


    //start audio
    data = {
        'id': myId,
        'user': user,
        'roomID': roomID
    }
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

function createUserMedia(video, audio, constraints) {

    navigator.mediaDevices.getUserMedia(constraints).then((stream) => {

        removeElementByID("myVideo");
        removeElementByID("myAudio");

        if (audio == true && video == true) {
            myAudioStream = null;

            //Register the video stream to my Stream
            myVideoStream = stream;
            window.stream = stream; // make stream available to console       


            console.log("this is a video");

            /*******
                (NOTE: THIS SHOULD BE MUTED = TRUE) 
            *******/

            videoElement = document.createElement('video');
            videoElement.setAttribute("id", "myVideo");
            videoElement.muted = false;
            //videoElement.muted = true;
            addMyVideo(videoElement, stream);

        } else {


            //Register the video stream to my Stream
            myVideoStream = null;
            window.stream = stream; // make stream available to console       

            myAudioStream = stream;

            console.log("this is a audio only")

            /*******
                (NOTE: THIS SHOULD BE MUTED = TRUE) 
            *******/
            audioElement = document.createElement('audio');
            audioElement.setAttribute("id", "myAudio");
            audioElement.setAttribute("controls", "controls");
            audioElement.muted = false;
            //audioElement.muted = true;

            addMyAudio(audioElement, stream);
        }

        return navigator.mediaDevices.enumerateDevices();


    }).then(gotDevices).catch((err) => {

        if (video == true && audio == true) {

            handleError(err);

        } else {

            //alert("no media detected, please connect and try again")
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

        console.log("connect video : start")

        createUserMedia(video, audio, constraints)

    } else if (video == false && audio == true) {

        const constraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: false
        };

        console.log("connect audio : start")
        createUserMedia(video, audio, constraints)

    } else {

        //alert("no media detected, please connect and try again")
    }

}

function restart() {

    /*
       data = {
           'id': myId,
           'user': user,
           'roomID': roomID,
           'videoStream': myVideoStream
       }

   

    start(true, true, data);


    socket.emit("changeMedia", data);
     */




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
        //alert("close")
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

    //start the my own video camera

    start(video, audio, data)

});



peer.on('call', call => {

    let ctr = 0;

    console.log("PEER:: CALLING... for a video stream or audio stream ...");



    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;
    const constraints = {
        audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
        video: { deviceId: videoSource ? { exact: videoSource } : undefined }
    };

    navigator.mediaDevices.getUserMedia(constraints).then((stream) => {

        if (stream.getAudioTracks().length == 1 && stream.getVideoTracks().length == 1) {
            //alert("stream from sender is a video 1")
        } else {
            //alert("stream from sender is a audio 2")
        }


        call.answer(stream);

        call.on('stream', (userStream) => {

            console.log("recieve video from initiator");

            if (ctr == 0) {

                if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

                    //alert("reciever from sender is a video 5 :: (peer)" + call.peer)

                    removeElementByID(call.peer);
                    callerElement = document.createElement('video');
                    callerElement.setAttribute("id", call.peer);
                    callerElement.setAttribute("class", "peerCallBackVideo");
                    callerElement.muted = false;

                    addVideo(callerElement, userStream);
                } else {


                    //alert("reciever from sender is a audio 6 :: (peer)" + call.peer)

                    removeElementByID(call.peer);
                    callerElement = document.createElement('audio');
                    callerElement.setAttribute("id", call.peer);
                    callerElement.setAttribute("class", "peerCallBackAudio"); //call peer
                    callerElement.setAttribute("controls", "controls");
                    callerElement.muted = false;

                    addAudio(callerElement, userStream);

                }
            }
            ctr++
        });

        call.on('close', () => {
            removeElementByID(call.peer);
            console.log("user disconected")
        });

    }).catch((error) => {

        console.log("recieve audio from initiator");

        const audioSource = audioInputSelect.value;
        const constraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: false,
        };

        navigator.mediaDevices.getUserMedia(constraints).then((stream) => {

            if (stream.getAudioTracks().length == 1 && stream.getVideoTracks().length == 1) {

                //alert("stream from sender is a video 3")

            } else {

                //alert("stream from sender is a audio 4")

            }


            call.answer(stream);

            call.on('stream', (userStream) => {

                if (ctr == 0) {

                    if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

                        //alert("reciever from sender is a video 7  :: (peer) " + call.peer)

                        removeElementByID(call.peer);
                        callerElement = document.createElement('video');
                        callerElement.setAttribute("id", call.peer);
                        callerElement.setAttribute("class", "peerCallBackVideo");
                        callerElement.muted = false;

                        addVideo(callerElement, userStream);
                    } else {

                        //alert("reciever from sender is a audio 8 :: (peer)" + call.peer)

                        removeElementByID(call.peer);
                        callerElement = document.createElement('audio');
                        callerElement.setAttribute("id", call.peer);
                        callerElement.setAttribute("class", "peerCallBackAudio"); //call peer
                        callerElement.setAttribute("controls", "controls");
                        callerElement.muted = false;

                        addAudio(callerElement, userStream);

                    }


                }

                ctr++
            });

            call.on('close', () => {
                removeElementByID(call.peer);
                console.log("user disconected")
            });

        }).catch((error) => {
            //alert("I can't send any video r audio to your contact, please check media")
        });


    });




});

peer.on('close', (id) => {
    document.getElementById(id).remove();
});


socket.on('userJoined', (data) => {


    console.log("user joined ::: calling initiator with just audio and video", data.id);

    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;
    const constraints = {
        audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
        video: { deviceId: videoSource ? { exact: videoSource } : undefined }
    };

    navigator.mediaDevices.getUserMedia(constraints).then((mediaStream) => {

        console.log("user joined ::: calling initiator with just audio and video", data.id);

        callback = peer.call(data.id, mediaStream);

        if (callback) {

            let ctr = 0;

            callback.on('stream', (userStream) => {

                if (ctr == 0) {

                    if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

                        removeElementByID(data.id);

                        callerElement = document.createElement('video');
                        callerElement.setAttribute("id", data.id);
                        callerElement.setAttribute("class", "user_joined_peer_call_back");
                        callerElement.muted = false;

                        addVideo(callerElement, userStream);


                    } else {

                        removeElementByID(data.id);

                        callerElement = document.createElement('audio');
                        callerElement.setAttribute("id", data.id);
                        callerElement.setAttribute("class", "user_joined_peer_call_back");
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

        //I have only audio, send to the audio to my peer
        console.log("user joined:: I have only audio, send to the audio to my peer", data.id);



        const audioConstraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: false,
        };

        navigator.mediaDevices.getUserMedia(audioConstraints).then((mediaCallStream) => {

            if (myVideoStream) {


                callback = peer.call(data.id, mediaCallStream);

            } else {

                data = {
                    'id': myId,
                    'user': user,
                    'roomID': roomID,
                    'videoStream': mediaCallStream
                }

                socket.emit("changeMedia", data);

                //never mind callback, i will call change media
                callback = null;
            }


            if (callback) {

                let ctr = 0;

                callback.on('stream', (userStream) => {

                    if (ctr == 0) {

                        if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

                            //removeElementByID(data.id);

                            callerElement = document.createElement('video');
                            callerElement.setAttribute("id", data.id);
                            callerElement.setAttribute("class", "user_joined_peer_call_back");
                            callerElement.muted = false;

                            addVideo(callerElement, userStream);


                        } else {

                            removeElementByID(data.id);

                            callerElement = document.createElement('audio');
                            callerElement.setAttribute("id", data.id);
                            callerElement.setAttribute("class", "user_joined_peer_call_back");
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

            //alert("Please connect audioinput device and try again");
            console.log(error)
        });

    });

    /*
    let id = data.id;
    let roomID = data.roomID;
    let user = data.user;

    const callback = peer.call(id, myVideoStream);

    console.log("new user joined", data);


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


    //alert("media change");

    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;
    const constraints = {
        audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
        video: { deviceId: videoSource ? { exact: videoSource } : undefined }
    };

    navigator.mediaDevices.getUserMedia(constraints).then((userStream) => {

        console.log("mediaChanged: initiator (recieved)")

        callback = peer.call(data.id, userStream);

        if (callback) {

            let ctr = 0;

            callback.on('stream', (userStream) => {

                if (ctr == 0) {


                    console.log("repipient callback : video stream");

                    if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

                        removeElementByID(data.id);

                        callerElement = document.createElement('video');
                        callerElement.setAttribute("id", callback.peer);
                        callerElement.setAttribute("class", "repipient_video_changed");
                        callerElement.muted = false;

                        addVideo(callerElement, userStream);


                    } else {

                        removeElementByID(data.id);

                        callerElement = document.createElement('audio');
                        callerElement.setAttribute("id", data.id);
                        callerElement.setAttribute("class", "repipient_audio_changed");
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

        const audioConstraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: false,
        };

        navigator.mediaDevices.getUserMedia(audioConstraints).then((userStream) => {

            console.log("initiator SENT AND AUDIO")

            callback = peer.call(data.id, userStream);

            if (callback) {

                let ctr = 0;

                callback.on('stream', (userStream) => {


                    console.log("this is for the audio, stream of the initiator");

                    if (ctr == 0) {

                        console.log(userStream.getAudioTracks().length)
                        console.log(userStream.getVideoTracks().length)

                        if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

                            console.log("user sent a video")

                            removeElementByID(callback.peer);

                            callerElement = document.createElement('video');
                            callerElement.setAttribute("id", callback.peer);
                            callerElement.setAttribute("class", "callerBackVideo");
                            callerElement.muted = false;

                            addVideo(callerElement, userStream);


                        } else {

                            console.log("user sent a AUDIO")

                            removeElementByID(callback.peer);


                            callerElement = document.createElement('audio');
                            callerElement.setAttribute("id", callback.peer);
                            callerElement.setAttribute("class", "callbackAudio_media");
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

            //alert("audio only");



            console.log(error)
        });

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