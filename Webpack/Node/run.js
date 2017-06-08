/**
 * React Starter Kit (https://www.reactstarterkit.com/)
 *
 * Copyright © 2014-present Kriasoft, LLC. All rights reserved.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE.txt file in the root directory of this source tree.
 */


const commandLineArgs = require('command-line-args')
const clArgs = commandLineArgs([
  {name: 'verbose', alias: 'v', type: Boolean},
  {name: 'release', alias: 'r', type: Boolean},
  {name: 'analyze', alias: 'a', type: Boolean},
  {name: 'watch', alias: 'w', type: Boolean},
  {name: 'config', alias: 'c', type: String},
  {name: 'script', alias: 's', type: String},
]);





export function format(time) {
  return time.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, '$1');
}

function run(fn, options) {
  const task = typeof fn.default === 'undefined' ? fn : fn.default;
  const start = new Date();
  console.info(
    `[${format(start)}] Starting '${task.name}${options ? ` (${options})` : ''}'...`,
  );
  return task(options).then((resolution) => {
    const end = new Date();
    const time = end.getTime() - start.getTime();
    console.info(
      `[${format(end)}] Finished '${task.name}${options ? ` (${options})` : ''}' after ${time} ms`,
    );
    return resolution;
  });
}

if (require.main === module && clArgs.script) {
  // eslint-disable-next-line no-underscore-dangle
  // delete require.cache[__filename];

  // eslint-disable-next-line global-require, import/no-dynamic-require
  const module = require(`./${clArgs.script}.js`).default;
  // const webpackConfig = require(`${process.argv[4]}`);

  run(module).catch((err) => {
    console.error(err.stack);
    process.exit(1);
  });
}

export default run;
