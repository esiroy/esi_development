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
    myId = id;
    socket.emit("newUser", id, roomID);
});

peer.on('error', (err) => {
    alert(err.type);
});




socket.on('userJoined', id => {
    console.log("new user joined")
    const call = peer.call(id, myVideoStream);
    const vid = document.createElement('video');
    call.on('error', (err) => {
        alert(err);
    })
    call.on('stream', userStream => {
        addVideo(vid, userStream);
    })
    call.on('close', () => {
        vid.remove();
        console.log("user disconect")
    })
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


function addVideo(video, stream) {
    video.srcObject = stream;
    video.addEventListener('loadedmetadata', () => {
        video.play()
    })
    videoGrid.append(video);
}


function muteCam() {
    myVideoStream.getVideoTracks().forEach(track => track.enabled = !track.enabled);
}

function muteMic() {
    myVideoStream.getAudioTracks().forEach(track => track.enabled = !track.enabled);
}


function shareScreen() {
    navigator.mediaDevices.getDisplayMedia({
        video: true,
        audio: true
    }).then((stream) => {
        myVideoStream = stream;
        addVideo(myvideo, stream);

        myVideoStream.getVideoTracks()[0].onended = function() {
            console.log("ended a video share")

            //reconnect userMedia
            createUserMedia()
        };

    });
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