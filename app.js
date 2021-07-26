const express = require('express');
const app = express();
const http = require('https');
const server = http.createServer(app);

app.get('/', (req, res) => {
  res.send('<h1>Hello world</h1>');
});

server.listen(30000, () => {
  console.log('listening on *:30000');
});