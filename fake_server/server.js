const http = require('http');

const server = http.createServer();

server.on('request', function (req, res) {
    if (req.method === 'GET') {
        res.writeHead(405, {'Content-type': 'application/json'});
        res.end(JSON.stringify({status: "error", error: "Method Not Allowed"}));
    } else {
        req.on('data', function (data) {
            let object;
            try {
                object = JSON.parse(data);
            } catch (e) {}

            if (typeof object !== 'object' || Object.keys(object).length === 0) {
                res.writeHead(400, {'Content-type': 'application/json'});
                res.end(JSON.stringify({status: "error", error: "Bad Request"}));
            } else {
                let changedData = {};
                for (const [field, value] of Object.entries(object)) {
                    let probability = Math.random();
                    console.log(probability);
                    if (probability > 0.5) {
                        let newValue;

                        if (typeof value === 'string') {
                            newValue = value + String.fromCharCode(65 + Math.round(probability * 1000) % 27);
                        } else if (typeof value === 'number') {
                            newValue = value + probability;
                        }

                        changedData[field] = newValue;
                    }
                }

                if (Object.keys(changedData).length === 0) {
                    res.writeHead(204, {'Content-type': 'application/json'});
                    res.end();
                } else {
                    res.writeHead(200, {'Content-type': 'application/json'});
                    res.end(JSON.stringify({status: "ok", changes: changedData}));
                }
            }
        });
    }
});

server.listen(8080);