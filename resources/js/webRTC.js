const socket = io(APP_WEBRTC_SERVER_URL, {});
//const socket = io('{{ https://rtcserver.esuccess-inc.com:40002}}', {});

let myId = null;

const peer = new Peer({
    initiator: false,
    trickle: false,
});

'use strict';

let myVideoStream = null;
let myAudioStream = null;

//this will determine who calls
let userCallStream = null;
let recieverCallStream = null;
let userJoinedStream = null;

//Elements
let videoElement;
let audioElement;

//Share Screen
let sharedScreen = false;
let checkingInterval = null;

const peerConnections = {}

let mediaContainer = document.getElementById('myMediaContainer');
let videoGrid = document.getElementById('videoGrid');

//Source
const audioInputSelect = document.querySelector('select#audioSource');
const audioOutputSelect = document.querySelector('select#audioOutput');
const videoSelect = document.querySelector('select#videoSource');
const selectors = [audioInputSelect, audioOutputSelect, videoSelect];

audioOutputSelect.disabled = !('sinkId' in HTMLMediaElement.prototype);

navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleError);


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
            //console.log('Some other kind of source/device: ', deviceInfo);
        }
    }
    selectors.forEach((select, selectorIndex) => {
        if (Array.prototype.slice.call(select.childNodes).some(n => n.value === values[selectorIndex])) {
            select.value = values[selectorIndex];
        }
    });
}



// Attach audio output device to video element using device/sink ID.
function attachSinkId(element, sinkId) {

    //(mute element, feedback if false)
    element.muted = true;

    if (typeof element.sinkId !== 'undefined') {
        element.setSinkId(sinkId)
            .then(() => {
                //console.log(`Success, audio output device attached: ${sinkId}`);
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
    audio.muted = true;

    audio.addEventListener('loadedmetadata', () => {
        audio.play()
    })
    mediaContainer.append(audio);
}


function addMyVideo(video, stream) {
    video.srcObject = stream;
    video.muted = true;

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


function addVideoContent(containerID, video, stream) {
    video.srcObject = stream;
    video.addEventListener('loadedmetadata', () => {
        video.play()
    })

    let containerElement = document.getElementById(containerID);
    if (containerElement) {
        containerElement.append(video);
    }
}


function showByElementId(elementID) {
    let el = document.getElementById(elementID);
    if (el) {
        el.style.display = 'block';
    }
}

function hideByElementId(elementID) {
    let el = document.getElementById(elementID);
    if (el) {
        el.style.display = 'none';
    }
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
            /*******
                (NOTE: THIS SHOULD BE MUTED = TRUE) 
            *******/

            videoElement = document.createElement('video');
            videoElement.setAttribute("id", "myVideo");
            videoElement.muted = false;
            //videoElement.muted = true;
            addMyVideo(videoElement, stream);


            // detectDesktopShared(stream)
        } else {


            //Register the video stream to my Stream
            myVideoStream = null;
            window.stream = stream; // make stream available to console       

            //add to my audio stream
            myAudioStream = stream;

            //console.log("this is a audio only")

            /*******
                (NOTE: THIS SHOULD BE MUTED = TRUE) 
            *******/
            audioElement = document.createElement('audio');
            audioElement.setAttribute("id", "myAudio");
            audioElement.setAttribute("controls", "controls");
            //audioElement.muted = false;
            audioElement.muted = true;

            addMyAudio(audioElement, stream);

            // detectDesktopShared(stream)



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

        //console.log("connect video : start")

        createUserMedia(video, audio, constraints)

    } else if (video == false && audio == true) {

        const constraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: false
        };

        //console.log("connect audio : start")
        createUserMedia(video, audio, constraints)

    } else {

        //alert("no media detected, please connect and try again")
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
        window.stream = stream; // make stream available to console
        videoElement.srcObject = stream;

        //console.log("i have muted this")
        //videoElement.muted = true;

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



//user end stop sharing
function stopSharing() {
    console.log("stopSharing()")

    let showElement = document.getElementById("lessonSlide");

    if (showElement) {
        showElement.style.display = 'block';
    }

    checkAndRemoveSharedVideo();
}

function checkAndRemoveSharedVideo() {
    const removeElement = document.getElementById("sharedVideo");
    if (removeElement) {
        removeSharedVideo();
    } else {
        console.log("rechecking shared video after 1 second")
        // If not found, wait for a while and then check again
        checkingInterval = setTimeout(checkAndRemoveSharedVideo, 1000); // Wait for 1 second (adjust as needed)
    }
}


function removeSharedVideo() {

    const container = document.getElementById("lessonSharedContainer");

    if (container) {
        // Store the parent element to insert the new container in the same position.
        const parent = container.parentNode;
    
        // Create a new container element.
        const newContainer = document.createElement("div");
        newContainer.id = "lessonSharedContainer";
    
        // Add content or attributes to the new container if needed.
        newContainer.textContent = "";
    
        // Replace the old container with the new one.
        parent.insertBefore(newContainer, container);
    
        // Remove the old shared video container.
        container.remove();
    }

}

function detectDesktopShared(stream) {
    peer.on('connection', function(conn) {
        conn.on('data', function(data) {
            if (data.sharedScreen == true) {
                sharedScreen = true;
            } else if (data.sharedScreen == false) {

                console.log("detectDesktopShared stopped");

                stopSharing();
                sharedScreen = false;
                return false;
            } else {
                alert("the stream data")
            }
        });
    });


    peer.on('close', function(conn) {
        //console.log("close")
    });

    peer.on('call', call => {

        if (stream == null) {

            //console.log("answer the stream without any stream", call)
            call.answer();

        } else {

            //console.log("answer the stream", stream)

            call.answer(stream);

            if (stream.getAudioTracks().length == 1 && stream.getVideoTracks().length == 1) {
                removeElementByID(call.peer);
                callerElement = document.createElement('video');
                callerElement.setAttribute("id", call.peer);
                callerElement.setAttribute("class", "peerCallBackVideo");
                callerElement.muted = false;

                addVideo(callerElement, stream);
            } else {
                removeElementByID(call.peer);
                callerElement = document.createElement('audio');
                callerElement.setAttribute("id", call.peer);
                callerElement.setAttribute("class", "peerCallBackAudio"); //call peer
                callerElement.setAttribute("controls", "controls");
                callerElement.muted = false;
                addAudio(callerElement, stream);
            }
        }

        call.on('stream', userStream => {

            if (sharedScreen == true) {
                sharedVid = document.createElement('video');
                sharedVid.setAttribute("id", "sharedVideo");


                //the lesson shared container must be on the member lesson slider component
                addVideoContent('lessonSharedContainer', sharedVid, userStream);

                //hide lesson Slide
                hideByElementId("lessonSlide");
            } else {
                data = {
                    'id': myId,
                    'user': user,
                    'roomID': roomID,
                    'videoStream': mediaCallStream
                }
                socket.emit("changeMedia", data);
            }
        });

        call.on('finish', function() {
            //console.log("called finish")
        });

        call.on('error', (err) => {
            alert(err)
        });

        call.on("close", () => {
            sharedVid.remove();
        });


    });
};



function shareScreen() {

    navigator.mediaDevices.getDisplayMedia({
        video: true,
        audio: true
    }).then((userStream) => {

        sharedScreen = userStream;

        //@todo: (hide slide then show the user shared)
        const sharedVid = document.createElement('video');
        sharedVid.setAttribute("id", "sharedVideo");
        sharedVid.muted = false;

        //the lesson shared container must be on the member lesson slider component
        addVideoContent('lessonSharedContainer', sharedVid, userStream);

        //hide lesson Slide
        hideByElementId("lessonSlide");

        //Connect to peers
        Object.keys(peerConnections).forEach(function(peerID) {
            //connect and send
            var conn = peer.connect(peerID);
            conn.on('open', () => {
                /*********************               
                    (NEW) share screen data
                *************************/
                let data = {
                        'id': peerID,
                        'sharedScreen': true
                    }
                    //add to the connection, and send then call
                conn.send(data);
                let sharingScreen = peer.call(peerID, sharedScreen);
            });
        });


        //The screen record is stopped by myself
        sharedScreen.getVideoTracks()[0].onended = function() {
            showByElementId("lessonSlide")
            document.getElementById("sharedVideo").remove();
            //send this shared screen false to stop peer
            Object.keys(peerConnections).forEach(function(peerID) {
                var conn = peer.connect(peerID);
                conn.on('open', () => {
                    /*********************               
                        (STOP SHARE) share screen data
                    *************************/
                    let data = {
                        'id': peerID,
                        'sharedScreen': false
                    }
                    conn.send(data);
                });
            })
        };





        //socket.emit("userShare", roomID, sharedScreen);

    });
}

audioInputSelect.onchange = restart;
audioOutputSelect.onchange = changeAudioDestination;
videoSelect.onchange = restart;




peer.on('connection', function(conn) {

    conn.on('data', function(data) {

        console.log("share connection: peer connected", data)

        if (data.sharedScreen == true) {

            sharedScreen = true;

        } else if (data.sharedScreen == false) {

            console.log("connection shared stopped");
            stopSharing();
            sharedScreen = false;
            return false;
        } else {

            alert("the stream data")
        }

    });

    conn.on('close', (conn) => {
        console.log("shared video connection of peer has been closed", conn)

        const sharedVideo = document.getElementById('sharedVideo');

        // Check if the video element exists before attempting to remove it
        if (sharedVideo) {
          // Remove the video element from the DOM
          sharedVideo.remove();
        } else {
          console.log('Element with id "sharedVideo" not found.');
        }
                
    });

});

peer.on('open', (id) => {

    //console.log("my peer id" + id)
    //console.log("my user ", user)
    //console.log("my room id ", roomID)

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

    //console.log("PEER:: CALLING... for a video stream or audio stream ...");

    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;
    const constraints = {
        audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
        video: { deviceId: videoSource ? { exact: videoSource } : undefined }
    };

    navigator.mediaDevices.getUserMedia(constraints).then((stream) => {

        userCallStream = stream;

        if (stream.getAudioTracks().length == 1 && stream.getVideoTracks().length == 1) {
            //alert("stream from sender is a video 1")
        } else {
            //alert("stream from sender is a audio 2")
        }

        call.answer(stream);

        call.on('stream', (userStream) => {

            recieverCallStream = userStream;


            peerConnections[call.peer] = call;

            //console.log("recieve video from initiator ", call);

            if (ctr == 0) {

                if (sharedScreen == true) {
                    sharedVid = document.createElement('video');
                    sharedVid.setAttribute("id", "sharedVideo");


                    //the lesson shared container must be on the member lesson slider component
                    addVideoContent('lessonSharedContainer', sharedVid, userStream);

                    //hide lesson Slide
                    hideByElementId("lessonSlide");

                    return false;
                }

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
            //console.log("user disconected")
        });

    }).catch((error) => {

        //console.log("recieve audio from initiator", call);



        const audioSource = audioInputSelect.value;
        const constraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: false,
        };

        navigator.mediaDevices.getUserMedia(constraints).then((stream) => {

            userCallStream = stream;
            if (stream.getAudioTracks().length == 1 && stream.getVideoTracks().length == 1) {
                //alert("stream from sender is a video 3")
            } else {
                //alert("stream from sender is a audio 4")
            }

            call.answer(stream);

            call.on('stream', (userStream) => {

                recieverCallStream = userStream;
                peerConnections[call.peer] = call;

                if (ctr == 0) {


                    if (sharedScreen == true) {
                        sharedVid = document.createElement('video');
                        sharedVid.setAttribute("id", "sharedVideo");

                        //the lesson shared container must be on the member lesson slider component
                        addVideoContent('lessonSharedContainer', sharedVid, userStream);

                        //hide lesson Slide
                        hideByElementId("lessonSlide");

                        return false;
                    }

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
                //console.log("user disconected")
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


    peerConnections[data.id] = data;

    console.log("user joined ::: calling initiator with just audio and video", data.id);

    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;
    const constraints = {
        audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
        video: { deviceId: videoSource ? { exact: videoSource } : undefined }
    };

    navigator.mediaDevices.getUserMedia(constraints).then((mediaStream) => {

        //console.log("user joined ::: calling initiator with just audio and video", data.id);

        userJoinedStream = mediaStream;

        callback = peer.call(data.id, mediaStream);

        if (callback) {

            peerConnections[callback.peer] = callback;

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
                //console.log(err);
            });


        }

        checkVideoDisabled();
        checkAudioMuted();

    }).catch((error) => {

        //I have only audio, send to the audio to my peer
        //console.log("user joined:: I have only audio, send to the audio to my peer", data.id);


        const audioConstraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: false,
        };

        navigator.mediaDevices.getUserMedia(audioConstraints).then((mediaCallStream) => {
            userJoinedStream = mediaCallStream;


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

                peerConnections[callback.peer] = callback;
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
                    //console.log(err);
                });

                peerConnections[data.id] = callback;
            }
        }).catch((error) => {

            //alert("Please connect audioinput device and try again");
            // //console.log(error)
        });

        checkVideoDisabled();
        checkAudioMuted();

    });

});


socket.on('mediaChanged', (data) => {
    const audioSource = audioInputSelect.value;
    const videoSource = videoSelect.value;
    const constraints = {
        audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
        video: { deviceId: videoSource ? { exact: videoSource } : undefined }
    };

    navigator.mediaDevices.getUserMedia(constraints).then((userStream) => {

        //console.log("mediaChanged: initiator (recieved)")

        callback = peer.call(data.id, userStream);

        if (callback) {

            let ctr = 0;

            callback.on('stream', (userStream) => {

                if (ctr == 0) {

                    //console.log("recipient callback : video stream");

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
                //console.log(err);
            });

            peerConnections[data.id] = callback;
        }


    }).catch((error) => {

        const audioConstraints = {
            audio: { deviceId: audioSource ? { exact: audioSource } : undefined },
            video: false,
        };

        navigator.mediaDevices.getUserMedia(audioConstraints).then((userStream) => {

            userCallStream = userStream;

            //console.log("initiator SENT AND AUDIO")

            callback = peer.call(data.id, userStream);

            if (callback) {

                let ctr = 0;

                callback.on('stream', (userStream) => {

                    recieverCallStream = userStream;

                    //console.log("this is for the audio, stream of the initiator");

                    if (ctr == 0) {

                        //console.log(userStream.getAudioTracks().length)
                        //console.log(userStream.getVideoTracks().length)

                        if (userStream.getAudioTracks().length == 1 && userStream.getVideoTracks().length == 1) {

                            //console.log("user sent a video")

                            removeElementByID(callback.peer);
                            callerElement = document.createElement('video');
                            callerElement.setAttribute("id", callback.peer);
                            callerElement.setAttribute("class", "callerBackVideo");
                            callerElement.muted = false;
                            addVideo(callerElement, userStream);
                        } else {

                            //console.log("user sent a AUDIO")

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
                    //console.log(err);
                });

                peerConnections[data.id] = callback;
            }


        }).catch((error) => {

            //alert("audio only");
            //console.log(error)
        });

    });
});


socket.on('userDisconnect', id => {
    //console.log("userDisconnected", id);
    removeElementByID(id);
    try {
        peerConnections[id].close();
    } catch (err) {
        //console.log("error disconnect : ", err)
    }
});


function checkAudioMuted() {
    const muteIcon = document.querySelector('.fa-volume-mute');

    // Check if the 'display' property is set to 'none'
    if (muteIcon.style.display !== 'none') {       
        // The element with class 'fa-volume-mute' is not hidden
        console.log('fa-volume-mute is visible, we will mute mic');
        muteMic();
    }
}

function muteMic() {
  userJoinedStream.getAudioTracks().forEach((track) => {
    track.enabled = !track.enabled;
  });
}


function toggleMic() {
    if (userJoinedStream != null) {
        userJoinedStream.getAudioTracks().forEach((track) => {
            track.enabled = !track.enabled;
            //console.log(track);

            if (track.enabled == true) {
                $('#toggleAudio .fa-volume-up').show();
                $('#toggleAudio .fa-volume-mute').hide();
            } else {
                $('#toggleAudio .fa-volume-up').hide();
                $('#toggleAudio .fa-volume-mute').show();
            }
        });

    }

    if (userCallStream != null) {
        userCallStream.getAudioTracks().forEach((track) => {
            track.enabled = !track.enabled;
            //console.log(track);

            if (track.enabled == true) {
                $('#toggleAudio .fa-volume-up').show();
                $('#toggleAudio .fa-volume-mute').hide();
            } else {
                $('#toggleAudio .fa-volume-up').hide();
                $('#toggleAudio .fa-volume-mute').show();
            }
        });
    }
}



function checkVideoDisabled() {
    const videoIcon = document.querySelector('.fa-video-slash');    
    if (videoIcon.style.display !== 'none') {               
        console.log('video slash is visible, we will disable video');
        disableVideo();
    }
}

function disableVideo() {
    userJoinedStream.getVideoTracks().forEach((track) => {
      track.enabled = !track.enabled;
    });
  }

  
function toggleCamera() {
    if (userJoinedStream != null) {
        userJoinedStream.getVideoTracks().forEach((track) => {
            track.enabled = !track.enabled;
            //console.log(track);

            if (track.enabled == true) {
                $('#toggleCamera .fa-video').show();
                $('#toggleCamera .fa-video-slash').hide();
            } else {
                $('#toggleCamera .fa-video').hide();
                $('#toggleCamera .fa-video-slash').show();
            }
        });
    }


    if (userCallStream != null) {
        userCallStream.getVideoTracks().forEach((track) => {
            track.enabled = !track.enabled;
            //console.log(track);

            if (track.enabled == true) {
                $('#toggleCamera .fa-video').show();
                $('#toggleCamera .fa-video-slash').hide();
            } else {
                $('#toggleCamera .fa-video').hide();
                $('#toggleCamera .fa-video-slash').show();
            }

        });

    }
}

let shareScreenBtn = document.getElementById("btnShareScreen")

if (shareScreenBtn) {
    document.getElementById("btnShareScreen").addEventListener("click", function() {
        shareScreen();
    });
}


let toggleCameraBtn = document.getElementById("toggleCamera");

if (toggleCameraBtn) {
    document.getElementById("toggleCamera").addEventListener("click", function() {
        toggleCamera()
    });
}

let toggleAudioBtn = document.getElementById("toggleAudio")

if (toggleAudioBtn) {
    document.getElementById("toggleAudio").addEventListener("click", function() {
        toggleMic();
    });
}

/************VOLUME CONTROL*************** */
function setVolume(volume) {
    myVideoStream.getAudioTracks().forEach(track => {
        track.applyConstraints({ volume: volume });
    });
}



const volumeControl = document.getElementById('volume-control');
if (volumeControl) {
    volumeControl.addEventListener('input', () => {
        setVolume(volumeControl.value);
    });
}

function destroyVideoSteam(mediaStream) {
    mediaStream.getVideoTracks().forEach((track) => {
        track.stop();
    });
}

function destroyAudioStream(audioStream) {
    audioStream.getAudioTracks().forEach((track) => {
        track.stop();
    });
}

/************ END SESSION *************** */
function destroyUserMediaContainer() {
    $('.right-fixed').remove();
}

async function destroyUserMedia() {
    Object.keys(peerConnections).forEach(function(peerID) {
        peerConnections[peerID].close();
    });

    if (myVideoStream != null) {
        destroyVideoSteam(myVideoStream);
        destroyAudioStream(myVideoStream);
    }

    if (myAudioStream != null) {
        destroyVideoSteam(myAudioStream);
        destroyAudioStream(myAudioStream);
    }

    if (userJoinedStream != null) {
        destroyVideoSteam(userJoinedStream);
        destroyAudioStream(userJoinedStream);
    }

    if (userCallStream != null) {
        destroyVideoSteam(userCallStream);
        destroyAudioStream(userCallStream);
    }

    if (recieverCallStream != null) {
        destroyVideoSteam(recieverCallStream);
        destroyAudioStream(recieverCallStream);
    }

    destroyUserMediaContainer();
}

const destroySessionMediaBtn = document.getElementById('destroy-session-media');

if (destroySessionMediaBtn) {
    destroySessionMediaBtn.addEventListener('click', () => {
        destroyUserMedia();
    });
}