const webpack = require("webpack");

module.exports = {
  webpack: {
    configure: {
      resolve: {
        fallback: {
          process: require.resolve("process/browser"),
          zlib: require.resolve("browserify-zlib"),
          stream: require.resolve("stream-browserify"),
          util: require.resolve("util"),
          buffer: require.resolve("buffer"),
          asset: require.resolve("assert"),
          querystring: require.resolve("querystring-es3"),
          fs: require.resolve('graceful-fs'),
          url: require.resolve('url/'),
          http: require.resolve('stream-http'),
          crypto: require.resolve('crypto-browserify'),
          constants: require.resolve('constants-browserify'),
        },
      },
      plugins: [
        new webpack.ProvidePlugin({
          Buffer: ["buffer", "Buffer"],
          process: "process/browser",
        }),
      ],
    },
  },
};