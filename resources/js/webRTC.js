const socket = io('https://rtcserver.esuccess-inc.com:40002', {});
const peer = new Peer();
let myVideoStream;
let myId;
var videoGrid = document.getElementById('videoGrid')

var myvideo = document.createElement('video');
myvideo.setAttribute("id", "myVideo")
myvideo.muted = true;

var mySharedVideo = document.createElement('video');
mySharedVideo.setAttribute("id", "sharedVideo");
let isSharedVideo = false;




const peerConnections = {}

socket.on("connect", () => {
    console.log("socket created " + socket.id);
    createUserMedia();
});




socket.on('userJoined', id => {

    console.log("new user joined", id);

    const call = peer.call(id, myVideoStream);
    const vid = document.createElement('video');
    vid.setAttribute("id", "userVid");
    vid.muted = false;

    call.on('error', (err) => {
        console.log(err);
    })
    call.on('stream', userStream => {
        addVideo(vid, userStream);
    })
    call.on('close', () => {
        vid.remove();
        console.log("user disconected")
    });

    peerConnections[id] = call;


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
        vid.muted = false;

        addVideo(myvideo, myVideoStream);

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


            call.answer(stream);

            call.on('stream', userStream => {
                addVideo(vid, userStream);
            });

            call.on('finish', function() {
                console.log("called finish")
            });

            call.on('error', (err) => {
                alert(err)
            });

            call.on("close", () => {
                console.log(vid);
                vid.remove();
            });

            peerConnections[call.peer] = call;
        });

    }).catch(err => {
        alert(err.message)
    });
}

peer.on('open', (id) => {

    console.log("my peer id" + id)
    myId = id;
    socket.emit("newUser", id, roomID);
});

peer.on('error', (err) => {
    console.log(err + " : " + err.type)
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


function addVideo(video, stream) {


    video.srcObject = stream;
    video.addEventListener('loadedmetadata', () => {
        video.play()
    })
    videoGrid.append(video);


}


function stopCam() {

    //myVideoStream.getVideoTracks().forEach(track => track.stop());

    myVideoStream.getVideoTracks().forEach(function(track) {
        if (track.readyState == 'live') {
            track.stop();
        } else {
            console.log("video broadcasting live");
        }
    });

    myVideoStream.getTracks().forEach(function(track) {
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