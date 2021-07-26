const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);

app.get('/', (req, res) => {
  res.send('<h1>Hello world 1</h1>');
});

server.listen(30000, () => {
  console.log('listening on *:30000');
});