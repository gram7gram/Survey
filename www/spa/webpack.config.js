const PROD = 'production'
const DEV = 'development'

if (!process.env.NODE_ENV)
    throw new Error("NODE_ENV variable is undefined. Should be " + [PROD, DEV].join('|'));

console.log("Building " + process.env.NODE_ENV + " bundle");

module.exports = process.env.NODE_ENV == PROD ? require('./webpack.prod-config.js') : require('./webpack.dev-config.js');