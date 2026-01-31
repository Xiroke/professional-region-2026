import { WebSocketServer } from 'ws';
import { randomBytes } from 'crypto';

const wss = new WebSocketServer({ port: 8080 });
const channels = new Map();

wss.on('connection', (ws) => {
    let currentChannel = null;

    ws.on('message', (rawData) => {
        const { action, payload } = JSON.parse(rawData);

        switch (action) {
            case 'create':
                const hash = randomBytes(4).toString('hex');
                channels.set(hash, new Set([ws]));
                currentChannel = hash;
                ws.send(JSON.stringify({ action: 'created', hash }));
                break;

            case 'join':
                if (channels.has(payload.hash)) {
                    channels.get(payload.hash).add(ws);
                    currentChannel = payload.hash;
                    channels.get(currentChannel).forEach(client => {
                        if (client !== ws && client.readyState === 1) {
                            client.send(JSON.stringify({ action: 'joined', hash: payload.hash }));
                        }
                    });
                } else {
                    ws.send(JSON.stringify({ error: 'Channel not found' }));
                }
                break;

            case 'message':
                if (currentChannel && channels.has(currentChannel)) {
                    channels.get(currentChannel).forEach(client => {
                        if (client !== ws && client.readyState === 1) {
                            client.send(JSON.stringify({ action: 'message', data: payload }));
                        }
                    });
                }
                break;
        }
    });

    ws.on('close', () => {
        if (currentChannel && channels.has(currentChannel)) {
            channels.get(currentChannel).delete(ws);
            if (channels.get(currentChannel).size === 0) channels.delete(currentChannel);
        }
    });
});