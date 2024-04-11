@extends('layouts.chat-app')


@section('content')


{{ Auth::user()->id }}


<div id="messages"></div>

<input type="text" id="recipientID" placeholder="Recipient ID">
<input type="text" id="lessonID" placeholder="Lesson ID">

<input type="text" id="messageInput" placeholder="Type your message">


<button id="sendPrivateButton">Send</button>




@endsection

@section('scripts')
<script src='{{  env("APP_MESSENGER_URL") }}socket.io/socket.io.js'></script>

<script defer>    

    
    let id = "{{Auth::user()->id}}";
    let channelID =  "{{Auth::user()->id}}";
    let username = "{{Auth::user()->username }}";
   
    // Connect to the chat server
    //const socket = io('https://chatserver.mytutor-jpn.info:30001');  
    const socket = io('{{ env("APP_MESSENGER_URL") }}', {});        


    // Event listener for when the connection is established
    socket.on('connect', () => {
        console.log('Connected to chat server');
        let user = {
            channelid: id,
            userid: id,
            username: username,
            nickname: username,
            email: 'develeoper@local',
            user_image: null, 
            status: "active",
            type: "MEMBER",      
        }    
        socket.emit('REGISTER', user);  
    });


    socket.on('CHANNEL_JOINED', (channelID) => {
        console.log("joined in channel: " + channelID);
    });

    socket.on('PRIVATE_MESSAGE_SENT', (data) => {
        console.log('recieved',);
    });

    // Event listener for handling disconnection
    socket.on('disconnect', () => {
        console.log('Disconnected from chat server');
    });

    window.addEventListener('load', function () 
    {
        // Get DOM elements
        const message = document.getElementById('messageInput');
        const recipientInput = document.getElementById('recipientID');
        const sendPrivateBtn = document.getElementById('sendPrivateButton');

        // Join a Channel
        let data = {
            channelID: id
        }
        socket.emit('JOIN_CHANNEL', data);  

        //Send User a message
        sendPrivateBtn.addEventListener('click', () => {

            console.log("sending private");           

            const message = messageInput.value.trim();
            const recipientID = recipientInput.value.trim();

            if (message !== '') {
                
                console.log("sending.....");
                socket.emit('SEND_PRIVATE_MESSAGE', { recipientID: recipientID, sender: socket.id, message: message, channelID });

                //socket.emit('SEND_PRIVATE_MESSAGE', { recipient: recipientID, sender: socket.id, message: message, channelID });
                messageInput.value = '';
            }
        });        


    });
</script>
@endsection