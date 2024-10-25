const fs = require('fs');
const https = require('https');
const WebSocket = require('ws');

// Carica il certificato SSL e la chiave
const server = https.createServer({
  cert: fs.readFileSync('ssl/certificate.crt'),  // Percorso al file del certificato
  key: fs.readFileSync('ssl/private.key')    // Percorso alla chiave privata
});

// Crea il WebSocket Server e lega HTTPS al WebSocket
const wss = new WebSocket.Server({ server });

// Gestione delle connessioni
wss.on('connection', (ws) => {
  console.log('Client connesso');

  // Quando un messaggio viene ricevuto
  ws.on('message', (message) => {
    console.log('Ricevuto:', message.toString());

    // Rilancia il messaggio a tutti i client connessi, eccetto il mittente
    wss.clients.forEach((client) => {
      if (client !== ws && client.readyState === WebSocket.OPEN) {
        client.send(message.toString());
      }
    });
  });

  // Gestione della disconnessione
  ws.on('close', () => {
    console.log('Client disconnesso');
  });
});

// Avvia il server HTTPS sulla porta 443
server.listen(6969, () => {
  console.log('Server WebSocket sicuro in ascolto su wss://localhost:6969');
});
