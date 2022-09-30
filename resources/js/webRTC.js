const socket = io('https://rtcserver.esuccess-inc.com:40002', {});
const peer = new Peer();
let myVideoStream;
let myId;
var videoGrid = document.getElementById('videoGrid')

var myvideo = document.createElement('video');
myvideo.setAttribute("id", "myVideo")

var mySharedVideo = document.createElement('video');
mySharedVideo.setAttribute("id", "sharedVideo");

let isSharedVideo = false;


myvideo.muted = false;
const peerConnections = {}

socket.on("connect", () => {
    console.log("socket created " + socket.id);
    createUserMedia();

});



peer.on('open', (id) => {

    console.log("my peer id" + id)
    myId = id;
    socket.emit("newUser", id, roomID);
});

peer.on('error', (err) => {
    alert(err.type);
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


function createUserMedia() {

    console.log("my media created!")

    navigator.mediaDevices.getUserMedia({
        video: true,
        audio: true
    }).then((stream) => {

        myVideoStream = stream;

        var vid = document.createElement('video');
        vid.setAttribute("id", "callerVideo");

        addVideo(myvideo, stream);

        peer.on('call', call => {

            console.log("called");
            call.answer(stream);

            call.on('stream', userStream => {

                console.log("called streamed")
                addVideo(vid, userStream);
            });


            call.on('error', (err) => {
                alert(err)
            })

            call.on("close", () => {
                console.log(vid);
                vid.remove();
            })
            peerConnections[call.peer] = call;
        });


        peer.on('connection', function(conn) {
            conn.on('data', function(isSharedScreen) {

                if (isSharedScreen == true) {
                    vid = document.createElement('video');
                    vid.setAttribute("id", "sharedVideo");
                }
            });
        });


    }).catch(err => {
        alert(err.message)
    });
}


function shareScreen() {
    navigator.mediaDevices.getDisplayMedia({
        video: true,
        audio: true
    }).then((stream) => {

        sharedScreen = stream;

        sharedScreen.getVideoTracks()[0].onended = function() {
            //createUserMedia();
        };

        Object.keys(peerConnections).forEach(function(peerID) {
            console.log(peerID);

            //connect and send
            var conn = peer.connect(peerID);

            conn.on('open', () => {
                let isSharedScreen = true;
                conn.send(isSharedScreen);

                const vid = document.createElement('video');
                vid.setAttribute("id", "vid")

                const newcall = peer.call(peerID, sharedScreen);

                newcall.on('error', (err) => {
                    alert(err);
                })
                newcall.on('stream', userStream => {
                    // addVideo(vid, userStream);
                })
                newcall.on('close', () => {
                    vid.remove();
                    console.log("user disconect")
                });

            });



        });

        replaceVideo(mySharedVideo, stream);


        //socket.emit("userShare", roomID, sharedScreen);


    });
}



socket.on('userJoined', id => {
    console.log("new user joined", id);
    const call = peer.call(id, myVideoStream);
    const vid = document.createElement('video');
    vid.setAttribute("id", "userVid")

    call.on('error', (err) => {
        alert(err);
    })
    call.on('stream', userStream => {
        addVideo(vid, userStream);
    })
    call.on('close', () => {
        vid.remove();
        console.log("user disconect")
    });

    peerConnections[id] = call;
});


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


function addVideo(video, stream) {
    video.srcObject = stream;
    video.addEventListener('loadedmetadata', () => {
        video.play()
    })
    videoGrid.append(video);
}


function stopCam() {
    myVideoStream.getVideoTracks().forEach(track => track.stop());

    myVideoStream.getTracks().forEach(function(track) {
        if (track.readyState == 'live') {
            track.stop();
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