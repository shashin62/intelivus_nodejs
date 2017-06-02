var cheerio = require('cheerio');
var request = require('request');
var async = require('async');
var mysql = require('mysql');
var winston = require('winston');
var express = require('express');
var app = express();
var config = require('./config');
var TaxSiteFL = require('./fl');
var TaxSiteTX = require('./tx');
var TaxSiteWA = require('./wa');

// Logger object
var logger = new winston.Logger({
    level: 'error',
    transports: [
        new (winston.transports.Console)(),
        new (winston.transports.File)({filename: config.logger.filename})
    ]
});

//MySQL Connection Pool Object
var pool = mysql.createPool({
    connectionLimit: config.db.connections,
    host: config.db.host,
    user: config.db.user,
    password: config.db.password,
    database: config.db.name
});

function configureScrapping(proid, state){
    
    if(state === 'TX'){
        var tx = new TaxSiteTX(pool, logger);
        tx.createSearchTermForTX(proid);
    } else if( state === 'FL'){
        var fl = new TaxSiteFL(pool, logger);
        fl.createSearchTermForFL(proid);
    } else if( state === 'WA'){
        var wa = new TaxSiteWA(pool, logger);
        wa.createSearchTermForWA(proid);
    }
    
}

app.get('/start-scrap', function (req, res) {
    
        var proid = req.query.proid;
        var state = req.query.state;
	console.log('Request '+proid+' '+state);
        configureScrapping(proid, state);
	res.end('cool');
});

var server = app.listen(8081, function () {

  var host = server.address().address
  var port = server.address().port

  console.log("App listening at http://%s:%s", host, port);

});
