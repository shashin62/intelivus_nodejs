var cheerio = require('cheerio');
var request = require('request');
var async = require('async');

//Class TaxSiteTX Defination
function TaxSiteTX(pool, logger) {
    this.pool = pool;
    this.logger = logger;
    this.termArray = [];
    this.searchTermIndex = 0;
}

var obj = TaxSiteTX.prototype;

obj.createSearchTermForTX = function (proid) {
    var that = this;
    that.pool.getConnection(function (err, connection) {

        if (err) {
            that.logger.error('connection error');
            that.logger.error(err);
            connection.release();
            return;
        }

        // Use the connection 
        connection.query('SELECT * FROM data where proid=' + proid + ' and b_state="TX" ', function (err, result) {
            // And done with the connection. 
            connection.release();

            if (err) {
                that.logger.error('fetch error');
                that.logger.error(err);
            } else {
                pagesScrap = 0;
                tempArray = result;
                for (var i = 0; i < tempArray.length; i++) {
                    //console.log(tempArray[i]['afp_legal_name']);
                    var legalName = tempArray[i]['legal_name'];
                    var strArray = legalName.split(' ');
                    strArray = that.getCombinations(strArray);
                    for (var j = 0; j < strArray.length; j++) {
                        that.termArray.push(strArray[j]);
                    }

                    var legalName = tempArray[i]['dba_name'];
                    var strArray = legalName.split(' ');
                    strArray = that.getCombinations(strArray);
                    for (var j = 0; j < strArray.length; j++) {
                        that.termArray.push(strArray[j]);
                    }

                }

                that.startSearchForTX();
            }

        });
    });
};

obj.getCombinations = function (chars) {
    var result = [];
    var f = function (prefix, chars) {
        for (var i = 0; i < chars.length; i++) {
            result.push(prefix + chars[i]);
            f(prefix + chars[i] + ' ', chars.slice(i + 1));
        }
    };
    f('', chars);
    return result;
};

obj.startSearchForTX = function () {
    var that = this;
    if (that.searchTermIndex < that.termArray.length) {
        searchTerm = that.termArray[that.searchTermIndex];
        console.log(' ========== ' + searchTerm + ' ========== ');
        that.searchByNameTX(searchTerm, 0);
        that.searchTermIndex++;
    } else {
        console.log('Process Completed');
        return;
    }
};

obj.searchByNameTX = function (searchTerm, retryCount) {
    console.log('search for ====> ' + searchTerm);
    var that = this;
    request.post({url: 'https://mycpa.cpa.state.tx.us/coa/' + '/coaSearch.do', form: {entityName: searchTerm}}, function (err, httpResponse, body) {

        if (err) {

            retryCount += 1;
            //console.log(error);
            console.log('Search Reconnect: ' + searchTerm + ' (Count: ' + retryCount + ')');

            if (retryCount < 5) {
                that.searchByNameTX(searchTerm, retryCount);
            } else {
                that.logger.error('Failed to search for ' + searchTerm);
                that.logger.error(err);
                that.startSearchForTX();
            }
            return;
        } else {
            pagesScrap = 0;
            pageCount = 0;
            //console.log(body);
            $ = cheerio.load(body);

            var results = $('.tablesaw');
            //console.log(results);
            if (results.length > 0) {
                console.log('Data Found');
                that.insertPageDetailsForTX($);
            } else {
                console.log("No data found");
                that.startSearchForTX();
            }
//            fetchListData(searchUrl, 0);
        }
    });
};



obj.insertPageDetailsForTX = function ($) {
    var that = this;
    var infiIds = [];
    $('.tablesaw tbody tr').each(function (i, elem) {
        $info = cheerio.load($(this).html());
        var id = $info('td .getDetails').attr('value');
        infiIds.push(id);
    });

    //console.log(infiIds);

    async.each(infiIds, function (id, callback) {
        that.fetchInfo(id, 0);
        callback();
    }, function (err) {
        if (err) {
            console.log('A link failed to process');
        } else {
            console.log('Search Term Insertion Completed');
            that.startSearchForTX();
        }
    });
};

obj.fetchInfo = function (id, rCount) {
    var that = this;
    request.get({url: 'https://mycpa.cpa.state.tx.us/coa/coaEntitySearch.do?argName=' + id}, function (err, httpResponse, body) {

        if (err) {

            if (rCount < 10) {
                rCount++;
                that.fetchInfo(id, rCount);
                return;
            }
            console.log('fetchInfo =>' + id);
            console.log(err);
            return;
        }
        var data = JSON.parse(body);
        //console.log(JSON.stringify(data.result));

        var result = data.result;

        if (result !== null) {
            //console.log(data);
            that.insertInfoForTX(result);
        } else {
            console.log(id + ' => Null');
        }
    });
};

obj.insertInfoForTX = function (result) {
    var that = this;
    that.pool.getConnection(function (err, connection) {

        if (err) {
            that.logger.error('connection error');
            that.logger.error(err);
            return;
        }

        //console.log(data['document_number']);
        if (result.taxpayerId) {
            var where = {document_number: result.taxpayerId};
        } else {
            var where = {document_number: 0};
        }
        //console.log(where);
        connection.query('select count(*) as count FROM site_scrap_data WHERE  ? ', where, function (err, r) {

            if (err) {
                that.logger.error('exist error');
                that.logger.error(err);
                return;
            }

            if (r[0]['count'] < 1) {
                // Use the connection 

                var data = {title: result.businessEntityName, document_number: result.taxpayerId, fei_ein_number: result.fileNumber, date_filed: result.sosRegDate, state: result.regionIncName, status: result.status, principal_address: result.businessEntityAdd, registered_agent_name: result.agentName, registered_agent_address: result.agentAddress, officer_detail: JSON.stringify(result.offiersList), annual_reports: result.reportYear, sso_link: '', search_term: searchTerm, site: 'TX'};

                connection.query('INSERT INTO site_scrap_data SET ?', data, function (err, r2) {
                    // And done with the connection. 
                    connection.release();

                    if (err) {
                        that.logger.error('insert row error');
                        that.logger.error(data);
                        that.logger.error(err);
                    } else {
                        console.log(r2.insertId);
                    }
                });
            } else {
                connection.release();
            }
        });
    });
};


module.exports = TaxSiteTX;