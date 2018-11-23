var cheerio = require('cheerio');
var request = require('request');
var async = require('async');
var Fuse = require('fuse.js');
var json2csv = require('json2csv');
var fs = require('fs');

//Class TaxSiteTX Defination
function TaxSiteFL(pool, logger) {
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

var obj = TaxSiteFL.prototype;

obj.createSearchTermForFL = function (proid) {
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

        $cond= '';
        //if(that.proid==1){
            $cond = ' and apname_match IS NULL';
        //}

        // Use the connection 
        connection.query('SELECT * FROM data where proid=' + proid + ' and b_state="FL" '+$cond, function (err, result) {
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
                that.searchByNameForFL(searchTerm, 0);
            }

        });
    });
};

obj.searchByNameForFL = function (searchTerm, retryCount) {
    var that = this;
    request.post({url: 'http://search.sunbiz.org/inquiry/corporationsearch/byname', form: {SearchTerm: searchTerm, InquiryType: 'EntityName', SearchNameOrder: ''}}, function (err, httpResponse, body) {

        if (err) {

            retryCount += 1;
            //console.log(error);
            console.log('Search Reconnect: ' + searchTerm + ' (Count: ' + retryCount + ')');

            if (retryCount < 3) {
                that.searchByNameForFL(searchTerm, retryCount);
            } else {
                that.logger.error('Failed to search for ' + searchTerm);
                that.logger.error(err);
				that.searchTermIndex++;
				searchTerm = that.termArray[that.searchTermIndex]['legal_name'];
				that.searchByNameForFL(searchTerm, 0);
            }

        } else {
            that.pagesScrap = 0;
            that.pageCount = 0;
            //console.log(body);
            $ = cheerio.load(body);

            var searchUrl = 'http://search.sunbiz.org' + $('a').attr('href');
            //console.log(searchUrl);
            that.fetchListDataForFL(searchUrl, 0, searchTerm);
        }
    });
};

obj.fetchListDataForFL = function (listPageLink, rcount, searchTerm) {
    var that = this;
    request.get(listPageLink, function (error, response, responseHtml) {

        //console.log('======================='+rcount);
        if (error) {
            rcount += 1;
            //console.log(error);
            console.log('List Reconnect: ' + searchTerm + ' (Count: ' + rcount + ')');

            if (rcount < 3) {
                that.fetchListDataForFL(listPageLink, rcount, searchTerm);
            } else {
                that.logger.error('Failed to fetch list page link: ' + listPageLink);
                that.logger.error(error);
                that.searchTermIndex++;
                if (that.searchTermIndex < that.termArray.length) {
                    searchTerm = that.termArray[that.searchTermIndex]['legal_name'];
                    console.log(searchTerm);
                    that.searchByNameForFL(searchTerm, 0);
                } else {
                    console.log('Scraping process completed.');
                }
            }
            return;
        }
        that.pagesScrap++;
        //console.log(responseHtml);
        $listPage = cheerio.load(responseHtml);

        //Create links to fetch details
        var pageLinks = [];
        $listPage('#search-results tbody tr').each(function (i, elem) {
            $tr = cheerio.load($(this).html());
            var link = $tr('td:first-child a').attr('href');
            //console.log(link);
            pageLinks.push('http://search.sunbiz.org' + link);

        });
        //console.log(pageLinks);
        async.each(pageLinks, function (link, callback) {
            that.insertPageDetailsForFL(link, 0, searchTerm);
            callback();
        }, function (err) {
            if (err) {
                that.logger.error('A link failed to process');
            } else {
                console.log('All Links are sent to fetch and insert data for ' + that.pageCount);
                that.pageCount += 1;
                //Nest Page Link
                var nextListPageLink = 'http://search.sunbiz.org' + $listPage('.navigationBarPaging span:nth-child(2) a').attr('href');
                //console.log('next: ' + nextListPageLink);
                if (that.pagesScrap < 1) {
                    that.fetchListDataForFL(nextListPageLink, 0, searchTerm);
                } else {
                    that.searchTermIndex++;
                    if (that.searchTermIndex < that.termArray.length) {
                        searchTerm = that.termArray[that.searchTermIndex]['legal_name'];
                        console.log(searchTerm);
                        that.searchByNameForFL(searchTerm, 0);
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

        // Use the connection 
        
        $cond= '';
        if(that.proid==1){
            $cond = ' and apname_match IS NULL';
        }

        //console.log('SELECT * FROM data where proid=' + that.proid + ' and b_state="FL"');
        connection.query('SELECT * FROM data where proid=' + that.proid + ' and b_state="FL" '+$cond, function (err, result) {
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
                    var filename = 'FL_' + time + '.csv';
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

        //console.log('select * FROM site_scrap_data WHERE search_proid=' + that.proid + ' and search_state="FL" ');
        connection.query('select * FROM site_scrap_data WHERE search_proid=' + that.proid + ' and search_state="FL" ', function (err, result) {
            connection.release();
            if (err) {
                that.logger.error('exist error');
                that.logger.error(err);
            }

            var fuse = new Fuse(result, {keys: ["title"], includeScore: true, threshold: 0.2});

            async.each(data, function (item, callback) {


                console.log(item['legal_name'] + ' ========================================================> ');

                var searchResults = fuse.search(item['legal_name']);
                var r = searchResults[0];
                //console.log(searchResults);
                //console.log(r);
                if (r) {

                    //searchResults.forEach(function (r) {
                    console.log(r);
                    var resultItem = r.item;
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
                            connection.query('UPDATE data SET `apname1`= ?, `designation1`= ?, `apname2`= ?, `designation2`= ?, `apname3`= ?, `designation3`= ?, `soslink`= ?, `soscompany`= ?, `sosaddress`= ?, `apname_match` =?, status=3, establish=?,confmetric=? WHERE cid = ?', [resultItem.registered_agent_name,  "REGISTERED AGENT",  ap2,  desg2,  ap3,  desg3, resultItem.sso_link, resultItem.title, resultItem.principal_address, 'YES', resultItem.date_filed, r.score, item['cid']], function (error, results, fields) {

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
                            connection.query('UPDATE data SET `apname1`= ?, `designation1`= ?, `apname2`= ?, `designation2`= ?, `apname3`= ?, `designation3`= ?, `soslink`= ?, `soscompany`= ?, `sosaddress`= ?, `apname_match` =?, status=5, establish=?,confmetric=?  WHERE cid = ?', ['',  "",  '',  '',  '',  '', '', '', '',  'NO', '', '0', item['cid']], function (error, results, fields) {

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

obj.insertPageDetailsForFL = function (pageURL, retry_count, searchTerm) {
    var that = this;
    that.processStatus = 0;
    console.log('Flag Set');
    request(pageURL, function (error, response, responseHtml) {

        if (error) {
            retry_count += 1;
            //console.log(error);
            console.log('Retrying to connect: (Count: ' + retry_count + ')');

            if (retry_count < 3) {
                that.insertPageDetailsForFL(pageURL, retry_count, searchTerm);
            } else {
                that.processStatus = 1;
                console.log('Flag reset');
                that.logger.error('Failed to fetch details page: ' + pageURL);
                that.logger.error(error);
            }
            return;
        }
        //console.log(responseHtml);
        $ = cheerio.load(responseHtml);

        var insertData = [];

        insertData['sso_link'] = pageURL;
        insertData['search_term'] = searchTerm;
        insertData['search_proid'] = that.proid;
        insertData['search_state'] = 'FL';
        insertData['title'] = $('.corporationName p').last().text();

        //Filing Information
        var filingInfo = $('.filingInformation > span').last().html();

        //console.log($('.detailSection~ .detailSection+ .detailSection span:nth-child(2)').text());

        //console.log(filingInfo);
        if (filingInfo) {
            //console.log('did');
            $fi = cheerio.load(filingInfo);
            var filingFields = [];
            $fi('label').each(function (i, elem) {
                var label = $fi(this).text().replace(/[\W_]/g, '_').toLowerCase();
                //console.log(i+' '+label);
                filingFields.push(label);
            });
            $fi('span').each(function (i, elem) {
                insertData[filingFields[i]] = $fi(this).text();
                //console.log(i+' '+insertData[filingFields[i]]);
            });
        }


        $('.detailSection').each(function (i, elem) {
            if (i > 1) {
                $info = cheerio.load('<div class="detailSection">' + $(this).html() + '</div>');
                //console.log($(this).html());
                var field = $info('span').eq(0).text().replace(/[\n\t\r]| +(?= )/g, "").trim();
                //console.log(field);
                if (field === 'Principal Address') { //principal address

                    //console.log($(this).html());
                    insertData['principal_address'] = $info('span').eq(1).text().replace(/[\n\t\r]| +(?= )/g, "").trim();
                    //console.log($info('span').eq(1).text());

                } else if (field === 'Mailing Address') { //mailing address
                    //console.log($(this).html());
                    insertData['mailing_address'] = $info('span').eq(1).text().replace(/[\n\t\r]| +(?= )/g, "").trim();
                    //console.log($info('span').eq(1).text());

                } else if (field === 'Registered Agent Name & Address') { //Registered Agent Name & Address

                    //console.log($(this).html());
                    insertData['registered_agent_name'] = $info('span').eq(1).text().replace(/[\n\t\r]| +(?= )/g, "").trim();
                    //console.log($info('span').eq(1).text());
                    insertData['registered_agent_address'] = $info('span').eq(2).text().replace(/[\n\t\r]| +(?= )/g, "").trim();
                    //console.log($info('span').eq(2).text());

                } else if (field === 'Officer/Director Detail') { //Officer/Director Detail

                    $dataHtml = $info('.detailSection').html().toString().replace(/\<br\>|[\n\t\r]/g, "").replace(/ +(?= )/g, '|').replace(/<([^>]+?)([^>]*?)>(.*?)<\/\1>/ig, "");

                    var officerArray = that.cleanArray($dataHtml.split('|'));
                    //console.log(officerArray);
                    var od = [];
                    for (var i = 0; i < officerArray.length; i++) {
                        var index = (i + 1) * 2;
                        var t = {name: officerArray[i], title: $info('span').eq(index).text().replace(/[\n\t\r]| +(?= )/g, "").replace('Title', '').trim(), address: $info('span').eq(index + 1).text().replace(/[\n\t\r]| +(?= )/g, "").trim()};

                        od.push(t);
                    }
                    insertData['officer_detail'] = JSON.stringify(od);

                } else if (field === 'Authorized Person(s) Detail') {
                    $dataHtml = $info('.detailSection').html().toString().replace(/\<br\>|[\n\t\r]/g, "").replace(/ +(?= )/g, '|').replace(/<([^>]+?)([^>]*?)>(.*?)<\/\1>/ig, "");

                    var officerArray = that.cleanArray($dataHtml.split('|'));
                    //console.log(officerArray);
                    var od = [];
                    for (var i = 0; i < officerArray.length; i++) {
                        var index = (i + 1) * 2;
                        var t = {name: officerArray[i], title: $info('span').eq(index).text().replace(/[\n\t\r]| +(?= )/g, "").replace('Title', '').trim(), address: $info('span').eq(index + 1).text().replace(/[\n\t\r]| +(?= )/g, "").trim()};

                        od.push(t);
                    }
                    insertData['authorized_persons'] = JSON.stringify(od);
                } else if (field === 'Owners') {
                    $dataHtml = $info('.detailSection').html().toString().replace(/\<br\>|[\n\t\r]/g, "").replace(/ +(?= )/g, '|').replace(/<([^>]+?)([^>]*?)>(.*?)<\/\1>/ig, "");

                    var officerArray = that.cleanArray($dataHtml.split('|'));
                    //console.log(officerArray);
                    var od = [];
                    for (var i = 0; i < officerArray.length; i++) {
                        var index = (i + 1) * 2;
                        var t = {name: officerArray[i], address: $info('span').eq(index).text().replace(/[\n\t\r]| +(?= )/g, "").trim()};

                        od.push(t);
                    }
                    insertData['owner_detail'] = JSON.stringify(od);
                } else if (field === 'Annual Reports') {

                    var ar = [];
                    $info('.detailSection table tr').each(function (i, elem) {

                        if (i > 0) {
                            var index = i * 2;

                            var t = {
                                report_year: $info('.detailSection table tr td').eq(index).text().trim(),
                                filed_date: $info('.detailSection table tr td').eq(index + 1).text().trim()
                            };

                            ar.push(t);
                        }
                    });
                    insertData['annual_reports'] = JSON.stringify(ar);
                }

            }
        });

        var data = {};

        //console.log(insertData);
        for (var k in insertData) {
            if (that.databaseFields.indexOf(k) !== -1) {
                data[k] = insertData[k];
            }
        }

        //console.log(data);
        that.pool.getConnection(function (err, connection) {

            if (err) {
                that.processStatus = 1;
                console.log('Flag reset');
                that.logger.error('connection error');
                that.logger.error(data);
                that.logger.error(err);
            }

            //console.log(data['document_number']);
            if (data['document_number']) {
                var where = [data['document_number'], that.proid];
            } else {
                var where = [0, that.proid];
            }
            //console.log(where);
            connection.query('select count(*) as count FROM site_scrap_data WHERE  document_number = ? and search_state = "FL" and search_proid = ? ', where, function (err, result) {

                if (err) {
                    that.processStatus = 1;
                    console.log('Flag reset');
                    that.logger.error('exist error');
                    that.logger.error(err);
                } else {
                    //console.log(result);
                    //console.log(data['document_number']+' '+result[0]['count']);

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

module.exports = TaxSiteFL;