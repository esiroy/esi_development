const express = require('express');
const app = express();
const https = require('https');
//const io = require('socket.io')(server);

/*
const server = app.listen(30001, "https://chatserver.mytutor-jpn.info/", function() {
    console.log('server running on port 30001');
});
*/

app.get('/', (req, res) => {
    //res.sendFile(__dirname + '/index.html');
    res.send('<h1>Chat server</h1>');
});

https.createServer({
    key: fs.readFileSync('server.key'),
    cert: fs.readFileSync('server.cert')
  }, app)
  .listen(30001, function () {
    console.log('Example app listening on port 30001! Go to https://localhost:30001/')
  })
  

var users = [];

io.on('connection', function(socket) 
{
    //console.log("user connected, with id " + socket.id)

    socket.on("SEND_USER_MESSAGE", function (data){

        console.log("send user ", data.recipient.username);

        //io.sockets.connected[data.id].emit("PRIVATE_MESSAGE", data);

        for (var i in users) {

            if (data.recipient.username == users[i].username) 
            {              
                io.sockets.connected[users[i].id].emit("PRIVATE_MESSAGE", data);
                break;
            }            
        }
        
        //this.handleUserPrivateMsg(data);
    });

    /*Register connected user*/
    socket.on('REGISTER',function(user)
    {
        console.log("user connected, with id " + socket.id + " " + user.username)

        //remove if ever there is same userid
        for (var i in users) {
            if (users[i].userid === user.userid) {
                delete users[i];
                break;
            }
        }

        users = users.filter(function( element ) {
          return element !== undefined;
        });        
        
        users.push({
                        'id': socket.id, 
                        'userid': user.userid,
                        'username': user.username
                    });

                    
        update_user_list();
    });


    //THIS WILL EMIT THE USER LIST TO CLIENT SIDE
    function update_user_list() 
    {
       
        io.emit('update_user_list', users);
    }

    //Removing the socket on disconnect
    socket.on('disconnect', function () 
    {
        for (var i in users) {
            if (users[i].id === socket.id) {
                delete users[i];
                break;
            }
        }

        users = users.filter(function( element ) {
          return element !== undefined;
        });

        update_user_list();
    });        



    //default (public)
    socket.on('SEND_MESSAGE', function(data) {
        io.emit('MESSAGE', data)
    });



    
    
});