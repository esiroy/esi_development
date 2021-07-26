const express = require("express");
const app = express();
const server = require("http").Server(app);
const io = require("socket.io")(server);

var users = [];

io.on("connection", socket => {

  const { id } = socket.client;
  console.log(`User Connected: ${id}`); 

  /*Register connected user*/
  socket.on('register',function(username)
  {
    socket.username = username;
    users.push({'username': socket.username, 'userid': socket.id});
    update_users();
    
    io.emit("registered", {'username': socket.username, 'userid': socket.id})
  });


  function update_users() 
  {
    io.emit('users_list', {users: users});
  }



  socket.on("chat message", ({ username, msg }) => {
    io.emit("chat message", { username , msg });   
  })

  //Removing the socket on disconnect
  socket.on('disconnect', function () {
    for (var ki in users) {
        if (users[ki].userid === socket.id) {
            delete users[ki];
            break;
        }
    }
    users = users.filter(function( element ) {
      return element !== undefined;
    });
    update_users();
  })


  socket.on('private-message', function (data) 
  {
      //io.sockets.connected[data.userid].emit("add-private-message", data);

      for (var i in users) {
          //console.log('socid: ' + users[i].userid);
          //console.log('uname: ' + users[i].username);         

          if (data.username == users[i].username) {

              var userid = users[i].userid;
              var username = users[i].username;
              var content = data.content;
              var senderuserid = data.senderuserid;
              var senderusername = data.senderusername;

              console.log("Sending: " + data.content + " to " + users[i].username + " | id " + users[i].userid);                 
            
              io.sockets.connected[users[i].userid].emit("add-private-message", { userid, username, content, senderuserid, senderusername});
              break;
          }

      }
     
      //io.sockets.connected[senderuserid].emit("own_message", data);
     
  });

  socket.on('own_message', function (data)  {

    console.log("own message fired!" + data)
  });




});

const PORT = process.env.PORT || 30000;
server.listen(PORT, () => console.log(`Listen on *: ${PORT}`));