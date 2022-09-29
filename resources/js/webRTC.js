const socket = io('https://rtcserver.esuccess-inc.com:40002', {});
const peer = new Peer();
let myVideoStream;
let myId;
var videoGrid = document.getElementById('videoGrid')

var myvideo = document.createElement('video');
myvideo.setAttribute("id", "myVideo")
myvideo.muted = false;
const peerConnections = {}

socket.on("connect", () => {
    console.log(socket.id); // "G5p5..."
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
    console.log("user shared :" + myId)

    const call = peer.call(myId, stream);

    call.on("stream", (remoteStream) => {
        // Show stream in some <video> element.
    });

});


function shareScreen() {
    navigator.mediaDevices.getDisplayMedia({
        video: true,
        audio: true
    }).then((stream) => {
        myVideoStream = stream;

        myVideoStream.getVideoTracks()[0].onended = function() {
            //createUserMedia();
        };

        Object.keys(peerConnections).forEach(function(peerID) {
            console.log(peerID);

            const newcall = peer.call(peerID, myVideoStream);

            const vid = document.createElement('video');
            vid.setAttribute("id", "vid")

            newcall.on('error', (err) => {
                alert(err);
            })
            newcall.on('stream', userStream => {
                addVideo(vid, userStream);
            })
            newcall.on('close', () => {
                vid.remove();
                console.log("user disconect")
            });

        });

        replaceVideo(myvideo, stream);

        //socket.emit("userShare", roomID, myVideoStream);


    });
}



socket.on('userJoined', id => {
    console.log("new user joined", id);
    const call = peer.call(id, myVideoStream);
    const vid = document.createElement('video');
    vid.setAttribute("id", "vid")

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





function createUserMedia() {

    navigator.mediaDevices.getUserMedia({
        video: true,
        audio: true
    }).then((stream) => {

        console.log("getting user Media...");

        myVideoStream = stream;
        addVideo(myvideo, stream);
        peer.on('call', call => {
            call.answer(stream);
            const vid = document.createElement('video');
            vid.setAttribute("id", "callerVideo")

            call.on('stream', userStream => {
                addVideo(vid, userStream);
            })
            call.on('error', (err) => {
                alert(err)
            })
            call.on("close", () => {
                console.log(vid);
                vid.remove();
            })
            peerConnections[call.peer] = call;
        })
    }).catch(err => {
        alert(err.message)
    });
}


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
}

function muteCam() {
    myVideoStream.getVideoTracks().forEach(track => track.enabled = !track.enabled);
}

function muteMic() {
    myVideoStream.getAudioTracks().forEach(track => track.enabled = !track.enabled);
}








//DOM Execution
document.getElementById("toggleCamera").addEventListener("click", function() {
    muteCam();
});

document.getElementById("toggleAudio").addEventListener("click", function() {
    muteMic();
});

document.getElementById("shareScreen").addEventListener("click", function() {
    shareScreen();
});