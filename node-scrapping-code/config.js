module.exports = {
  db: {
    host: 'localhost',
    name: 'intelivu_dawork',
    user: 'root',
    password: 'root123',
    connections: 100
  },
  logger: {
      filename: 'error-log.log'
  },
  state: {
      TX: {
          domain: 'https://mycpa.cpa.state.tx.us/coa/'
      },
      FL: {
          domain: 'http://search.sunbiz.org'
      }
  }
};