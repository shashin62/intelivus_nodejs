var cheerio = require('cheerio');
var request = require('request');
var async = require('async');
var Fuse = require('fuse.js');
var json2csv = require('json2csv');
var fs = require('fs');

//Class TaxSiteTX Defination
function TaxSiteTX(pool, logger) {
    this.pool = pool;
    this.logger = logger;
    this.termArray = [];
    this.processStatus = 1; //0: Running 1: Completed
    this.statusCount = 0;
    this.searchTermIndex = 0;
    this.processStatus = 1; //0: Running 1: Completed
    this.statusCount = 0;
    this.proid = 0;
    this.csvFields = ['search_term', 'ap1', 'desg1', 'ap2', 'desg2', 'ap3', 'desg3', 'sso_link', 'company', 'caddress', 'match', 'fetched_date'];
    this.csvFieldNames = ['Search Term', "AP Name 1", "Designation", "AP Name 2", "Designation", "AP Name 3", "Designation", "SSO Link", "SOS Company Name", "SOS Address", "AP Name Match", "Fetched Date"];
}

var obj = TaxSiteTX.prototype;

obj.createSearchTermForTX = function (proid) {
    var that = this;
    that.proid = proid;
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
        that.searchTermIndex++;
        that.searchByNameTX(searchTerm, 0);

    } else {
        console.log('Process Completed');
        that.checkIsProcessCompleted();
    }
};

obj.checkIsProcessCompleted = function () {
    var that = this;
    var completeInterval = setInterval(function () {
        console.log('Waiting...' + that.processStatus);
        if (that.processStatus === 1) {
            that.statusCount++;

            if (that.statusCount > 5) {
                console.log('======================================Completed================================');
                clearInterval(completeInterval);
                that.createMatchingFile();
            }
        }
    }, 10000);

};

obj.createMatchingFile = function () {
    var that = this;
    that.pool.getConnection(function (err, connection) {

        if (err) {
            that.logger.error('connection error');
            that.logger.error(err);
            return;
        }

        // Use the connection 
        connection.query('SELECT * FROM data where proid=' + that.proid + ' and b_state="TX" ', function (err, result) {
            // And done with the connection. 
            connection.release();

            if (err) {
                that.logger.error('fetch error');
                that.logger.error(err);
            } else {

                that.searchTerms(result, function (data) {

                    //console.log(data);
                    var csv = json2csv({data: data, fields: that.csvFields, fieldNames: that.csvFieldNames});
                    var time = (new Date()).getTime();
                    var filename = 'TX_' + time + '.csv';
                    fs.writeFile('matching_files/' + filename, csv, function (err) {
                        if (err) {
                            that.logger.error('file creation error');
                            that.logger.error(err);
                            return;
                        }
                        console.log('file saved');

                        that.pool.getConnection(function (err, connection) {

                            if (err) {
                                that.logger.error('connection error');
                                that.logger.error(err);
                            } else {

                                // Use the connection 
                                connection.query('UPDATE project_data SET process_status = ?, data_filename = ? WHERE cid = ?', [1, filename, that.proid], function (error, results, fields) {

                                    // And done with the connection. 
                                    connection.release();
                                    if (error) {
                                        that.logger.error('update error');
                                        that.logger.error(err);
                                    } else {
                                        console.log('** Project updated **');
                                    }
                                });
                            }
                        });
                    });
                });
            }
        });
    });
};

obj.searchTerms = function (data, resultCallback) {
    var that = this;
    var searchdata = [];
    that.pool.getConnection(function (err, connection) {

        if (err) {
            that.logger.error('connection error');
            that.logger.error(err);
        }

        connection.query('select * FROM site_scrap_data WHERE search_proid=' + that.proid + ' and search_state="TX" ', function (err, result) {
            connection.release();
            if (err) {
                that.logger.error('exist error');
                that.logger.error(err);
            }

            var fuse = new Fuse(result, {keys: ["title"], include: ['score', 'matches'], threshold: 0.2});

            async.each(data, function (item, callback) {


                console.log(item['legal_name'] + ' ========================================================> ');

                var searchResults = fuse.search(item['legal_name']);
                var r = searchResults[0];
                //console.log(searchResults);
                //console.log(r);
                if (r) {
                    //searchResults.forEach(function (r) {
                    //console.log(r);
                    var resultItem = r;
                    var ap2 = '';
                    var desg2 = '';
                    var ap3 = '';
                    var desg3 = '';

                    if (resultItem.authorized_persons != null && resultItem.authorized_persons != 'null') {
                        var pd = JSON.parse(resultItem.authorized_persons);
                        if (pd[0]) {
                            var ap2 = pd[0]['name'];
                            var desg2 = pd[0]['title'];
                        }
                        if (pd[1]) {
                            var ap3 = pd[1]['name'];
                            var desg3 = pd[1]['title'];
                        }
                    } else if (resultItem.officer_detail != null && resultItem.officer_detail != 'null') {
                        var pd = JSON.parse(resultItem.officer_detail);
                        var pd = JSON.parse(resultItem.officer_detail);
                        console.log(pd);
                        if (pd[0]) {
                            var ap2 = pd[0]['agentName'];
                            var desg2 = pd[0]['agentTitle'];
                        }
                        if (pd[1]) {
                            var ap3 = pd[1]['agentName'];
                            var desg3 = pd[1]['agentTitle'];
                        }
                    }
                    var temp = {search_term: item['legal_name'], ap1: resultItem.registered_agent_name, desg1: "REGISTERED AGENT", ap2: ap2, desg2: desg2, ap3: ap3, desg3: desg3, sso_link: resultItem.sso_link, company: resultItem.title, caddress: resultItem.principal_address, match: 'YES', fetched_date: resultItem.created_at};

                    that.pool.getConnection(function (err, connection) {

                        if (err) {
                            that.logger.error('connection error');
                            that.logger.error(err);
                        } else {

                            // Use the connection 
                            connection.query('UPDATE data SET `apname1`= ?, `designation1`= ?, `apname2`= ?, `designation2`= ?, `apname3`= ?, `designation3`= ?, `soslink`= ?, `soscompany`= ?, `sosaddress`= ?, `apname_match` =? WHERE cid = ?', [resultItem.registered_agent_name,  "REGISTERED AGENT",  ap2,  desg2,  ap3,  desg3, resultItem.sso_link, resultItem.title, resultItem.principal_address,  'YES', item['cid']], function (error, results, fields) {

                                // And done with the connection. 
                                connection.release();
                                if (error) {
                                    that.logger.error('update error');
                                    that.logger.error(error);
                                } else {
                                    console.log('** updated **');
                                }
                            });
                        }
                    });
                    //console.log(temp);
                    searchdata.push(temp);
                } else {
                    console.log('not found');
                    var temp = {search_term: item['legal_name'], ap1: '', desg1: '', ap2: '', desg2: '', ap3: '', desg3: '', sso_link: '', company: '', caddress: '', match: 'NO', fetched_date: ''};
                    that.pool.getConnection(function (err, connection) {

                        if (err) {
                            that.logger.error('connection error');
                            that.logger.error(err);
                        } else {

                            // Use the connection 
                            connection.query('UPDATE data SET `apname1`= ?, `designation1`= ?, `apname2`= ?, `designation2`= ?, `apname3`= ?, `designation3`= ?, `soslink`= ?, `soscompany`= ?, `sosaddress`= ?, `apname_match` =? WHERE cid = ?', [resultItem.registered_agent_name,  "REGISTERED AGENT",  '',  '',  '',  '', '', '', '',  'NO', item['cid']], function (error, results, fields) {

                                // And done with the connection. 
                                connection.release();
                                if (error) {
                                    that.logger.error('update error');
                                    that.logger.error(error);
                                } else {
                                    console.log('** updated **');
                                }
                            });
                        }
                    });
                    searchdata.push(temp);
                }

                //console.log(searchResults);

                callback();
            }, function (err) {
                if (err) {
                    that.logger.error('Error Occured');
                    that.logger.error(err);
                } else {
                    console.log('Term Process Completed.');

                    resultCallback(searchdata);
                }
            });
        });
    });
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
            that.startSearchForTX();
            if (results.length > 0) {
                console.log('Data Found');
                that.insertPageDetailsForTX($);
            } else {
                console.log("No data found");
                //that.startSearchForTX();
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
            //that.startSearchForTX();
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
    that.processStatus = 0;
    that.pool.getConnection(function (err, connection) {

        if (err) {
            that.logger.error('connection error');
            that.logger.error(err);
            that.processStatus = 1;
            return;
        }

        //console.log(data['document_number']);
        if (result.taxpayerId) {
            var where = [result.taxpayerId, that.proid];
        } else {
            var where = [0, that.proid];
        }
        //console.log(where);
        connection.query('select count(*) as count FROM site_scrap_data WHERE  document_number = ? and search_state = "FL" and search_proid = ? ', where, function (err, r) {

            if (err) {
                that.logger.error('exist error');
                that.logger.error(err);
                that.processStatus = 1;
                return;
            }

            if (r[0]['count'] < 1) {
                // Use the connection 

                var data = {title: result.businessEntityName, document_number: result.taxpayerId, fei_ein_number: result.fileNumber, date_filed: result.sosRegDate, state: result.regionIncName, status: result.status, principal_address: result.businessEntityAdd, registered_agent_name: result.agentName, registered_agent_address: result.agentAddress, officer_detail: JSON.stringify(result.offiersList), annual_reports: result.reportYear, sso_link: '', search_term: searchTerm, site: 'TX', search_proid: that.proid, search_state: 'TX'};

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
                    that.processStatus = 1;
                });
            } else {
                connection.release();
                that.processStatus = 1;
            }

        });
    });
};


module.exports = TaxSiteTX;