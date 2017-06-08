/**
 * React Starter Kit (https://www.reactstarterkit.com/)
 *
 * Copyright © 2014-present Kriasoft, LLC. All rights reserved.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE.txt file in the root directory of this source tree.
 */

const babel = require("babel-core");
const requireFromString = require("require-from-string");
import webpack from 'webpack';

const commandLineArgs = require('command-line-args')
const clArgs = commandLineArgs([
    {name: 'verbose', alias: 'v', type: Boolean},
    {name: 'release', alias: 'r', type: Boolean},
    {name: 'analyze', alias: 'a', type: Boolean},
    {name: 'watch', alias: 'w', type: Boolean},
    {name: 'config', alias: 'c', type: String},
    {name: 'script', alias: 's', type: String},
]);


/**
 * Creates application bundles from the source files.
 */
function bundle() {

    var babelPresets = {
        "presets": [
            [
                "env",
                {
                    "targets": {
                        "node": "current"
                    }
                }
            ],
            "stage-2",
            "react"
        ],
        "env": {
            "test": {
                "plugins": [
                    "rewire"
                ]
            }
        }
    };

    var fs = require('fs');
    var configContent = fs.readFileSync(clArgs.config, "utf8");
    var configTranspile = babel.transform(configContent, babelPresets);
    var config = requireFromString(configTranspile.code);
    var webpackConfig = config.default;

    return new Promise((resolve, reject) => {

        var compiler = webpack(webpackConfig);

        if (clArgs.watch) {

            compiler.watch({ // watch options:
                aggregateTimeout: 300, // wait so long for more changes
                poll: true // use polling instead of native watchers
                // pass a number to set the polling interval
            }, function (err, stats) {
                if (err) {
                    return reject(err);
                }

                console.info(stats.toString(webpackConfig[0].stats));
                return resolve();
            });

        } else {
            compiler.run(function (err, stats) {
                if (err) {
                    return reject(err);
                }

                console.info(stats.toString(webpackConfig[0].stats));
                return resolve();
            });
        }

    });
}

export default bundle;