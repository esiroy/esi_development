var serverPort = 50001;


const express = require('express');
const app = express();

const server = app.listen(serverPort, function() {
    console.log('server running on port ' + serverPort);
});

const io = require('socket.io')(server);



app.get('/', function(req, res) {

    res.send('<h1>Chat server</h1>');
});

var users = [];



io.on('connection', function(socket) {

    console.log("user connected, with id " + socket.id)




    /*****************************************/
    /*  CANVAS SERVER
    /*****************************************/

    socket.on("CALL_USER", (data) => {
        //io.to('' + data.channelid + '').emit("CALL_USER", data);
        io.sockets.emit("CALL_USER", data);
    });

    socket.on("ACCEPT_CALL", (data) => {
        io.sockets.emit("ACCEPT_CALL", data);
    });



    socket.on("START_SLIDER", (data) => {
        io.sockets.emit("START_SLIDER", data);
    });


    socket.on("DROP_CALL", (data) => {
        io.sockets.emit("DROP_CALL", data);
    });


    socket.on("SEND_DRAWING", (data) => {
        io.to('' + data.channelid + '').emit('UPDATE_DRAWING', data);
    });

    socket.on("GOTO_SLIDE", (data) => {
        io.to('' + data.channelid + '').emit("GOTO_SLIDE", data);
    });


    socket.on("SEND_SLIDE_PRIVATE_MESSAGE", (data) => {
        io.to('' + data.channelid + '').emit("SEND_SLIDE_PRIVATE_MESSAGE", data);
    });




    /*****************************************/
    /*  ADMIN CHAT SUPPORT MESSENGER 
    /*****************************************/

    socket.on("SEND_USER_MESSAGE", function(data) {
        io.sockets.emit("PRIVATE_MESSAGE", data);
    });

    socket.on("SEND_OWNER_MESSAGE", function(data) {
        io.emit('OWNER_MESSAGE', data);
        //this.handleUserPrivateMsg(data);
    });

    /*Register connected user*/
    socket.on('REGISTER', function(user) {

        console.log("user connected, with id " + socket.id + " " + user.username)

        //remove if ever there is same userid
        /*
        for (var i in users) {
            if (users[i].userid === user.userid) {
                delete users[i];
                break;
            }
        }*/

        socket.join(user.channelid);



        users = users.filter(function(element) {
            return element !== undefined;
        });

        users.push({
            'id': socket.id,
            'channelid': user.channelid,
            'userid': user.userid,
            'username': user.username,
            'user_image': user.user_image,
            'nickname': user.nickname,
            'status': user.status,
            'type': user.type,
        });


        update_user_list();
    });


    //THIS WILL EMIT THE USER LIST TO CLIENT SIDE
    function update_user_list() {

        io.emit('update_user_list', users);
    }

    //Removing the socket on disconnect
    socket.on('disconnect', function() {
        for (var i in users) {
            if (users[i].id === socket.id) {
                delete users[i];
                break;
            }
        }

        users = users.filter(function(element) {
            return element !== undefined;
        });

        update_user_list();
    });

    //default (public)
    socket.on('SEND_MESSAGE', function(data) {
        io.emit('MESSAGE', data)
    });

});