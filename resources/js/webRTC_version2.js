const socket = io('https://rtcserver.esuccess-inc.com:40002', {});

const peer = new Peer();

//Your Vide or Audio Stream
let myVideoStream;
let myAudioStream;
let myId;

//My Video Grid
let videoGrid = document.getElementById('videoGrid');

//My video is going to be muted for feedback suppression while playing
var myvideo = document.createElement('video');
myvideo.setAttribute("id", "myVideo")
myvideo.muted = true;


/* Caller  Video Grid*/
let callerVideoGrid = document.getElementById('callerVideoGrid');


// Caller Stream, Element (Video or Video)
let callerStream;
var callerElement;



console.log("user", user);

//My Shared video will be muted for feedback suppression while playing
var mySharedVideo = document.createElement('video');
mySharedVideo.setAttribute("id", "sharedVideo");
mySharedVideo.muted = true;


//When loading shared video is always hidden and false
let isSharedVideo = false;


const audioInputSelect = document.querySelector('select#audioSource');
const audioOutputSelect = document.querySelector('select#audioOutput');
const videoSelect = document.querySelector('select#videoSource');
const selectors = [audioInputSelect, audioOutputSelect, videoSelect];

audioOutputSelect.disabled = !('sinkId' in HTMLMediaElement.prototype);


navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleMediaError);

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
                //console.error(errorMessage);

                console.log(error.message + " : " + error.name);

                // Jump back to first output device in the list as it's the default.
                audioOutputSelect.selectedIndex = 0;
            });
    } else {
        console.warn('Browser does not support output device selection.');
    }
}

function changeAudioDestination() {
    const audioDestination = audioOutputSelect.value;
    console.log("my audio destination:", audioDestination);
    attachSinkId(myvideo, audioDestination);
}

function gotStream(stream) {

    console.log("got stream called...")

    //Register the Video
    myVideoStream = stream;

    addVideo(myvideo, myVideoStream);



    /*
    Object.keys(peerConnections).forEach(function(peerID) {
        if (myId !== peerID) {

            peer.call(peerID, myVideoStream);

            if (call) {
                call.on('error', (err) => {
                    console.log(err);
                })
                call.on('stream', userStream => {
                    // addVideo(vid, userStream);
                })
                call.on('close', () => {
                    vid.remove();
                    console.log("user disconected")
                });
            }
        }
    });*/


    return navigator.mediaDevices.enumerateDevices();
}




const peerConnections = {}

socket.on("connect", () => {
    audioInputSelect.onchange = createUserMedia;
    audioOutputSelect.onchange = changeAudioDestination;
    videoSelect.onchange = createUserMedia;
    createUserMedia();
});


function callUserPeer(id, stream, type) {

    console.log("call user peer function: ", type)
    const call = peer.call(id, stream);


    if (call) {


        call.on('stream', userStream => {

            callerStream = userStream;

            if (userStream.getAudioTracks().length && userStream.getVideoTracks().length) {

                console.log("stream is a video, creating video element");

                callerElement = document.createElement('video');
                callerElement.setAttribute("id", "callVideo");
                callerElement.muted = false;

                addVideo(callerElement, userStream);

            } else {

                console.log("stream is a audio, creating audio element");

                callerElement = document.createElement('audio');
                callerElement.setAttribute("id", "callerAudio");
                callerElement.setAttribute("controls", "controls");
                callerElement.muted = false;

                addAudio(callerElement, userStream);
            }
        });

        call.on('close', (id) => {

            console.log("closing...", id);

            callerElement.remove();
            console.log("user disconected")

        });


        call.on('error', (err) => {
            console.log(err);
        });

        //add to peer connection
        peerConnections[id] = call;



    } else {

        console.log("call was not successfull")
    }
}


socket.on('userJoined', (data) => {

    let id = data.id;
    let roomID = data.roomID;
    let user = data.user;

    console.log("new user joined", data);

    //@todo: When user joins the "videostream" or "audio stream" is not known???
    //@todo: make a script to determine which  stream he is on...
    if (myVideoStream) {

        if (myVideoStream.getAudioTracks().length && myVideoStream.getVideoTracks().length) {
            callUserPeer(id, myVideoStream, "user joined (video)");
        }

    } else if (myAudioStream) {

        callUserPeer(id, myAudioStream, "user joined (audio)");
    }
});


function handleMediaError(error) {
    console.log('navigator.MediaDevices.getUserMedia error: ', error.message, error.name);
    createUserAudio();
}

function createUserMedia() {
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

    navigator.mediaDevices.getUserMedia(constraints).
    then((stream) => {
        connectClientVideo(myVideoStream);
    }).
    then(gotDevices).
    catch((err) => {
        handleMediaError(err)
    });
}


function createUserAudio() {

    console.log("creating audio only");

    //media device only
    navigator.mediaDevices.getUserMedia({
        video: false,
        audio: true,
    }).then((stream) => {

        myAudioStream = stream;

        //Create my audio stream
        var audioElement = document.createElement('audio');
        audioElement.setAttribute("id", "myAudio");
        audioElement.setAttribute("controls", "controls");
        audioElement.muted = true;

        //Create user 
        addAudio(audioElement, myAudioStream);

        //connectClientVideo(myAudioStream);

    }).catch(err => {

        alert("Can't create audio");
    });
}



function connectClientVideo(stream) {

    peer.on('connection', function(conn) {

        conn.on('data', function(isSharedScreen) {

            if (isSharedScreen == true) {
                vid = document.createElement('video');
                vid.setAttribute("id", "sharedVideo");

            } else {
                stopSharing();
                return false;
            }
            console.log(isSharedScreen);
        });
    });

    peer.on('close', function(conn) {
        console.log("close")
    });

    peer.on('call', call => {

        if (stream == null) {
            call.answer();
        } else {
            call.answer(stream);
        }

        call.on('stream', userStream => {

            console.log("call for connecting video or audio")

            if (userStream.getAudioTracks().length && userStream.getVideoTracks().length) {
                callerElement = document.createElement('video');
                callerElement.setAttribute("id", "callerVideo");
                callerElement.muted = false;
                addVideo(callerElement, userStream);
            } else {
                callerElement = document.createElement('audio');
                callerElement.setAttribute("id", "myAudio");
                callerElement.setAttribute("controls", "controls");
                callerElement.muted = true;
                //Create user 
                addAudio(callerElement, myAudioStream);
            }
        });

        call.on('finish', function() {
            console.log("called finish")
        });

        call.on('error', (err) => {
            alert(err)
        });

        call.on("close", () => {
            callerElement.remove();
        });

        peerConnections[call.peer] = call;
    });

}



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

peer.on('error', (err) => {
    console.log(err + " : " + err.type)
});


call.on('finish', function() {
    alert("called finish")
});

call.on('error', (err) => {
    alert(err)
});

call.on("close", () => {
    alert("close")
});


socket.on('userShared', (roomID, stream) => {

    /*
    console.log("user shared :" + myId);
    const call = peer.call(myId, stream);

    call.on("stream", (remoteStream) => {
        // Show stream in some <video> element.
    });
    */

});



//user end stop sharing
function stopSharing() {
    document.getElementById("sharedVideo").remove();
}


function shareScreen() {

    navigator.mediaDevices.getDisplayMedia({
        video: true,
        audio: true
    }).then((stream) => {

        sharedScreen = stream;

        //The screen record is stopped by myself
        sharedScreen.getVideoTracks()[0].onended = function() {
            document.getElementById("sharedVideo").remove();

            //send this shared screen false to stop peer
            Object.keys(peerConnections).forEach(function(peerID) {
                var conn = peer.connect(peerID);
                conn.on('open', () => {
                    let isSharedScreen = false;
                    conn.send(isSharedScreen);
                });
            })
        };

        //Connect to peers
        Object.keys(peerConnections).forEach(function(peerID) {
            //connect and send
            var conn = peer.connect(peerID);

            conn.on('open', () => {
                let isSharedScreen = true;
                conn.send(isSharedScreen);
                const vid = document.createElement('video');
                vid.setAttribute("id", "vid");

                const newcall = peer.call(peerID, sharedScreen);

                newcall.on('error', (err) => {
                    console.log(err)
                });

                newcall.on('stream', userStream => {
                    // addVideo(vid, userStream);
                });

                newcall.on('close', () => {
                    vid.remove();
                    console.log("user disconect")
                });

            });

        });

        replaceVideo(mySharedVideo, sharedScreen);

        //socket.emit("userShare", roomID, sharedScreen);

    });
}






socket.on('userDisconnect', id => {
    if (peerConnections[id]) {
        peerConnections[id].close();
    }
});


function replaceVideo(video, stream) {
    video.srcObject = stream;
    video.addEventListener('loadedmetadata', () => {
        video.play()
    })
    videoGrid.append(video);

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


function stopCam() {
    myVideoStream.getVideoTracks().forEach((track) => {
        if (track.readyState == 'live') {
            track.stop();
        } else {
            console.log("video broadcasting live");
        }
    });

    myVideoStream.getTracks().forEach((track) => {
        if (track.readyState == 'live') {
            track.stop();
        } else {
            console.log("audio not broadcasting live");
        }
    });
}

// stop only camera
function stopVideoOnly(stream) {
    stream.getTracks().forEach(function(track) {
        if (track.readyState == 'live' && track.kind === 'video') {
            track.stop();
        }
    });
}

// stop only mic
function stopAudioOnly(stream) {
    stream.getTracks().forEach(function(track) {
        if (track.readyState == 'live' && track.kind === 'audio') {
            track.stop();
        }
    });
}

function muteCam() {
    myVideoStream.getVideoTracks().forEach((track) => {
        track.enabled = !track.enabled
        console.log(track)
    });
}

function muteMic() {
    myVideoStream.getAudioTracks().forEach(track => track.enabled = !track.enabled);
}

//DOM Execution
document.getElementById("stopCamera").addEventListener("click", function() {
    stopCam();
});

document.getElementById("toggleCamera").addEventListener("click", function() {
    muteCam();
});

document.getElementById("toggleAudio").addEventListener("click", function() {
    muteMic();
});

document.getElementById("shareScreen").addEventListener("click", function() {
    shareScreen();
});