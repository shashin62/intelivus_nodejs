var cheerio = require('cheerio');
var request = require('request');
var async = require('async');
var Fuse = require('fuse.js');
var json2csv = require('json2csv');
var fs = require('fs');

//Class TaxSiteTX Defination
function TaxSiteWA(pool, logger) {
    this.pool = pool;
    this.proid = 0;
    this.logger = logger;
    this.termArray = [];
    this.searchTermIndex = 0;
    this.pagesScrap = 0;
    this.pageCount = 0;
    this.processStatus = 1; //0: Running 1: Completed
    this.statusCount = 0;
    this.databaseFields = ['title', 'document_number', 'fei_ein_number', 'date_filed', 'effective_date', 'expiration_date', 'state', 'status', 'last_event', 'event_date_filed', 'event_effective_date', 'principal_address', 'mailing_address', 'registered_agent_name', 'registered_agent_address', 'officer_detail', 'authorized_persons', 'owner_detail', 'annual_reports', 'sso_link', 'search_term', 'search_state', 'search_proid'];
    this.csvFields = ['search_term', 'ap1', 'desg1', 'ap2', 'desg2', 'ap3', 'desg3', 'sso_link', 'company', 'caddress', 'match', 'fetched_date'];
    this.csvFieldNames = ['Search Term', "AP Name 1", "Designation", "AP Name 2", "Designation", "AP Name 3", "Designation", "SSO Link", "SOS Company Name", "SOS Address", "AP Name Match", "Fetched Date"];
}

var obj = TaxSiteWA.prototype;

obj.createSearchTermForWA = function (proid) {
    var that = this;
    that.proid = proid;
    //that.createMatchingFile();
    //return;
    that.pool.getConnection(function (err, connection) {

        if (err) {
            that.logger.error('connection error');
            that.logger.error(err);
            return;
        }

        $cond = '';
        if (that.proid == 1) {
            $cond = ' and apname_match IS NULL';
        }

        // Use the connection 
        connection.query('SELECT * FROM data where proid=' + proid + ' and b_state="WA" '+$cond, function (err, result) {
            // And done with the connection. 
            connection.release();

            if (err) {
                that.logger.error('fetch error');
                that.logger.error(err);
            } else {
                that.pagesScrap = 0;
                that.termArray = result;
                searchTerm = that.termArray[that.searchTermIndex]['legal_name'];
                console.log(searchTerm);

                that.fetchListDataForWA(searchTerm, 0);
            }

        });
    });
};


obj.fetchListDataForWA = function (searchTerm, rcount) {
    var that = this;
    //starts_with
    request.get('https://www.sos.wa.gov/corps/search_results.aspx?search_type=simple&criteria=all&name_type=contains&name=' + searchTerm, function (error, response, responseHtml) {

        //console.log('======================='+rcount);
        if (error) {
            rcount += 1;
            //console.log(error);
            console.log('List Reconnect: ' + searchTerm + ' (Count: ' + rcount + ')');

            if (rcount < 3) {
                that.fetchListDataForWA(searchTerm, rcount);
            } else {
                that.logger.error('Failed to fetch list page link: ' + searchTerm);
                that.logger.error(error);
                that.searchTermIndex++;
                if (that.searchTermIndex < that.termArray.length) {
                    searchTerm = that.termArray[that.searchTermIndex]['legal_name'];
                    console.log(searchTerm);
                    that.fetchListDataForWA(searchTerm, 0);
                } else {
                    console.log('Scraping process completed.');
                }
            }
            return;
        }
        that.pagesScrap++;
        //console.log(responseHtml);
        $ = cheerio.load(responseHtml);
        $listPage = cheerio.load(responseHtml);

        //Create links to fetch details
        var ubiIds = [];
        $listPage('.table tr').each(function (i, elem) {
            $tr = cheerio.load($(this).html());
            var ubiId = $tr('td:first-child a').attr('data-target');

            if (ubiId) {
                //console.log(link);
                ubiIds.push(ubiId.replace('.ubi', ''));
            }

        });
        //console.log(ubiIds);
        async.each(ubiIds, function (ubiId, callback) {
            that.insertPageDetailsForWA(ubiId, 0, searchTerm);
            callback();
        }, function (err) {
            if (err) {
                that.logger.error('A link failed to process');
            } else {
                console.log('All Links are sent to fetch and insert data for ' + that.pageCount);
                that.pageCount += 1;
                //Nest Page Link
                //var nextListPageLink = 'http://search.sunbiz.org' + $listPage('.navigationBarPaging span:nth-child(2) a').attr('href');
                //console.log('next: ' + nextListPageLink);
                if (that.pagesScrap < 1) {
                    //that.fetchListDataForWA(nextListPageLink, 0, searchTerm);
                } else {
                    that.searchTermIndex++;
                    if (that.searchTermIndex < that.termArray.length) {
                        searchTerm = that.termArray[that.searchTermIndex]['legal_name'];
                        console.log(searchTerm);
                        that.fetchListDataForWA(searchTerm, 0);
                    } else {
                        console.log('All data send to insert ----------------------->');
                        that.checkIsProcessCompleted();
                    }
                }
            }
        });

    });
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
    }, 6000);

};

obj.createMatchingFile = function () {
    var that = this;
    that.pool.getConnection(function (err, connection) {

        if (err) {
            that.logger.error('connection error');
            that.logger.error(err);
            return;
        }

        $cond= '';
        if(that.proid==1){
            $cond = ' and apname_match IS NULL';
        }

        // Use the connection 

        //console.log('SELECT * FROM data where proid=' + that.proid + ' and b_state="WA"');
        connection.query('SELECT * FROM data where proid=' + that.proid + ' and b_state="WA" '+$cond, function (err, result) {
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
                    var filename = 'WA_' + time + '.csv';
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

        //console.log('select * FROM site_scrap_data WHERE search_proid=' + that.proid + ' and search_state="WA" ');
        connection.query('select * FROM site_scrap_data WHERE search_proid=' + that.proid + ' and search_state="WA" ', function (err, result) {
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

                    if (resultItem.authorized_persons != null) {
                        var pd = JSON.parse(resultItem.authorized_persons);
                        if (pd[0]) {
                            var ap2 = pd[0]['name'];
                            var desg2 = pd[0]['title'];
                        }
                        if (pd[1]) {
                            var ap3 = pd[1]['name'];
                            var desg3 = pd[1]['title'];
                        }
                    } else if (resultItem.officer_detail != null) {
                        var pd = JSON.parse(resultItem.officer_detail);
                        if (pd[0]) {
                            var ap2 = pd[0]['name'];
                            var desg2 = pd[0]['title'];
                        }
                        if (pd[1]) {
                            var ap3 = pd[1]['name'];
                            var desg3 = pd[1]['title'];
                        }
                    }
                    var temp = {search_term: item['legal_name'], ap1: resultItem.registered_agent_name, desg1: "REGISTERED AGENT", ap2: ap2, desg2: desg2, ap3: ap3, desg3: desg3, sso_link: resultItem.sso_link, company: resultItem.title, caddress: resultItem.principal_address, match: 'YES', fetched_date: resultItem.created_at};
                    //console.log(temp);
                    that.pool.getConnection(function (err, connection) {

                        if (err) {
                            that.logger.error('connection error');
                            that.logger.error(err);
                        } else {

                            // Use the connection 
                            connection.query('UPDATE data SET `apname1`= ?, `designation1`= ?, `apname2`= ?, `designation2`= ?, `apname3`= ?, `designation3`= ?, `soslink`= ?, `soscompany`= ?, `sosaddress`= ?, `apname_match` =? WHERE cid = ?', [resultItem.registered_agent_name, "REGISTERED AGENT", ap2, desg2, ap3, desg3, resultItem.sso_link, resultItem.title, resultItem.principal_address, 'YES', item['cid']], function (error, results, fields) {

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
                } else {
                    console.log('not found');
                    var temp = {search_term: item['legal_name'], ap1: '', desg1: '', ap2: '', desg2: '', ap3: '', desg3: '', sso_link: '', company: '', caddress: '', match: 'NO', fetched_date: ''};
                    that.pool.getConnection(function (err, connection) {

                        if (err) {
                            that.logger.error('connection error');
                            that.logger.error(err);
                        } else {

                            // Use the connection 
                            connection.query('UPDATE data SET `apname1`= ?, `designation1`= ?, `apname2`= ?, `designation2`= ?, `apname3`= ?, `designation3`= ?, `soslink`= ?, `soscompany`= ?, `sosaddress`= ?, `apname_match` =? WHERE cid = ?', ['', "", '', '', '', '', '', '', '', 'NO', item['cid']], function (error, results, fields) {

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

obj.insertPageDetailsForWA = function (ubiId, retry_count, searchTerm) {
    var that = this;
    that.processStatus = 0;
    console.log('Flag Set');
    var pageURL = 'https://www.sos.wa.gov/corps/search_detail.aspx?ubi=' + ubiId;
    request(pageURL, function (error, response, responseHtml) {

        if (error) {
            retry_count += 1;
            //console.log(error);
            console.log('Retrying to connect: (Count: ' + retry_count + ')');

            if (retry_count < 3) {
                that.insertPageDetailsForWA(ubiId, retry_count, searchTerm);
            } else {
                that.processStatus = 1;
                console.log('Flag reset');
                that.logger.error('Failed to fetch details page: ' + ubiId);
                that.logger.error(error);
            }
            return;
        }
        //console.log(responseHtml);
        $ = cheerio.load(responseHtml);

        var insertData = {};

        insertData['sso_link'] = pageURL;
        insertData['search_term'] = searchTerm;
        insertData['search_proid'] = that.proid;
        insertData['search_state'] = 'WA';
        insertData['title'] = $('.CorpName').text().trim();

        var sectionName = '', agentAdd = '', pAdd = '';
        $('.corp-detail tr').each(function (i, elem) {
            //console.log('<div class="detailSection">' + $(this).html() + '</div>');
            $info = cheerio.load('<div class="detailSection">' + $(this).html() + '</div>');
            var label = $info('.CorpLabel').text();


            switch (label) {
                case 'UBI Number':
                    insertData['document_number'] = $info('td').last().text();
                    break;
                case 'Active/Inactive':
                    insertData['status'] = $info('td').last().text();
                    break;
                case 'State Of Incorporation':
                    insertData['state'] = $info('td').last().text();
                    break;
                case 'WA Filing Date':
                    insertData['effective_date'] = $info('td').last().text();
                    break;
                case 'Expiration Date':
                    insertData['expiration_date'] = $info('td').last().text();
                    break;
                case 'Agent Name':
                    insertData['registered_agent_name'] = $info('td').last().text().replace(/[\n\t\r]| +(?= )/g, "");
                    break;
                case 'Address':
                case 'City':
                case 'State':
                case 'Zip':
                    var temp;
                    temp = $info('td').last().text();
                    if (temp) {
                        if (sectionName === 'agent') {
                            agentAdd += ' ' + temp.trim();
                        } else if (sectionName === 'address') {
                            pAdd += ' ' + temp.trim();
                        }
                    }
                    break;
            }


            if ($info('.CorpSection').text() == 'Registered Agent Information') {
                sectionName = 'agent';
            } else if ($info('.CorpSection').text() == 'Special Address Information') {
                sectionName = 'address';
            }

        });

        insertData['registered_agent_address'] = agentAdd.replace(/[\n\t\r]| +(?= )/g, "").trim();
        insertData['principal_address'] = pAdd.replace(/[\n\t\r]| +(?= )/g, "").trim();
        firstTR = 1;
        officerDetails = '';
        //[{"name":"SABAROFF, JONATHAN","title":"P","address":"5399 NW 60TH DR CORAL SPRINGS, FL 33067"}]
        $('.governing-persons tr').each(function (i, elem) {
            if (!firstTR) {
                $info = cheerio.load('<div class="detailSection">' + $(this).html() + '</div>');
                officerDetails += ' "title": "' + $info('td').eq(0).text().trim() + '",';
                officerDetails += ' "name": "' + $info('td').eq(1).text().replace(/[\n\t\r]| +(?= )/g, "").trim() + '",';
                officerDetails += ' "address": "' + $info('td').eq(2).text().replace(/[\n\t\r]| +(?= )/g, "").trim() + '",';
            }
            firstTR = 0;
        });

        if (officerDetails !== '') {
            officerDetails = '[{' + officerDetails.replace(/,\s*$/, "") + '}]';
        }

        insertData['officer_detail'] = officerDetails;

        //console.log(insertData);

        var data = insertData;
        //console.log(data);
        that.pool.getConnection(function (err, connection) {

            if (err) {
                that.processStatus = 1;
                console.log('Flag reset');
                that.logger.error('connection error');
                that.logger.error(data);
                that.logger.error(err);
            }

            //console.log(' ------------------------> ',data['document_number']);
            if (data['document_number']) {
                var where = [data['document_number'], that.proid];
            } else {
                var where = [0, that.proid];
            }
            //console.log(where);
            connection.query('select count(*) as count FROM site_scrap_data WHERE  document_number = ? and search_state = "WA" and search_proid = ? ', where, function (err, result) {

                if (err) {
                    that.processStatus = 1;
                    console.log('Flag reset');
                    that.logger.error('exist error');
                    that.logger.error(err);
                } else {
                    //console.log(result);
                    //console.log(data['document_number']+' <====> '+result[0]['count']);

                    if (result[0]['count'] < 1) {
                        // Use the connection 
                        connection.query('INSERT INTO site_scrap_data SET ?', data, function (err, result) {
                            // And done with the connection. 
                            connection.release();

                            if (err) {
                                that.processStatus = 1;
                                console.log('Flag reset');
                                that.logger.error('insert row error');
                                that.logger.error(data);
                                that.logger.error(err);
                            } else {
                                // console.log(result);
                                that.processStatus = 1;
                                console.log('Flag reset');
                            }

                        });
                    } else {
                        connection.release();
                        that.processStatus = 1;
                        console.log('Flag reset');
                        //console.log('record exist');
                    }
                }

            });

        });

    });

};

obj.cleanArray = function (actual) {
    var newArray = new Array();
    for (var i = 0; i < actual.length; i++) {
        if (actual[i].trim()) {
            newArray.push(actual[i].trim());
        }
    }
    return newArray;
};

module.exports = TaxSiteWA;