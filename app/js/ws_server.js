const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

// Mantieni una lista di client connessi
let clients = [];

wss.on('connection', function connection(ws) {
  clients.push(ws);
  console.log('Nuovo client connesso.');

  ws.on('message', function incoming(message) {
    console.log('Messaggio ricevuto:', message);
    
    // Invia il messaggio a tutti i client connessi (tranne a chi ha inviato)
    clients.forEach(client => {
      if (client !== ws && client.readyState === WebSocket.OPEN) {
        client.send(message);
      }
    });
  });

  ws.on('close', () => {
    // Rimuovi il client dalla lista quando si disconnette
    clients = clients.filter(client => client !== ws);
    console.log('Client disconnesso.');
  });
});

console.log('Server WebSocket in esecuzione sulla porta 8080.');
