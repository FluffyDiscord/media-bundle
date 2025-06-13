const Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');
const Dotenv = require('dotenv-webpack');
const path = require('path');
const fs = require('fs');
const uppyLocales = require('./scripts/webpackUppyLocales');
// const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

const currentPath = path.resolve(__dirname, '../');
const publicPath = `${currentPath}/public/`;
const environmentFile = path.resolve(__dirname, `.env.${process.env.NODE_ENV}`);


/* DontEnv */
Encore.addPlugin(
    new Dotenv({
        path: fs.existsSync(environmentFile) ? environmentFile : path.resolve(__dirname, '.env'),
        ignoreStub: true,
        systemvars: true,
    }),
);

/* Uppy Locales */
Encore.addPlugin(
    new webpack.DefinePlugin({
        'process.env.UPPY_LOCALES': `'${uppyLocales.join(',')}'`,
    }),
);

/*Encore.addPlugin(
  new BundleAnalyzerPlugin()
);*/

/* Config */
Encore.setOutputPath(publicPath)
    .configureWatchOptions((watchOptions) => {
        watchOptions.poll = 250; // check for changes every 250 milliseconds
    })
    .setManifestKeyPrefix('bundles/rankymedia/')
    .setPublicPath('/bundles/rankymedia')
    .addEntry('ranky_media', './src/load.tsx')
    .copyFiles([
        {
            from: './public',
        }
    ])
    .enableTypeScriptLoader(function callback(tsConfig) {
        // https://github.com/TypeStrong/ts-loader/blob/master/README.md#loader-options
        // tsConfig.transpileOnly = true;
        tsConfig.silent = false;
    })
    .enableForkedTypeScriptTypesChecking()
    .splitEntryChunks()
    .addCacheGroup('react', {
        node_modules: ['react', 'react-dom', 'scheduler', 'object-assign', 'loose-envify'],
        name: 'react.18.3.1'
    })
    //.addExternals({react: 'React', 'react-dom': 'ReactDOM'})
    .configureSplitChunks((splitChunks) => {
        // https://webpack.js.org/plugins/split-chunks-plugin/#splitchunksname
        splitChunks.name = 'ranky_media.vendors';
    })
    .configureFilenames({
        js: '[name].js?v=[fullhash:8]',
        css: '[name].css?v=[fullhash:8]',
    })
    .configureImageRule({filename: '[path][name][ext]'})
    .configureFontRule({filename: '[path][name][ext]'})
    // .enableVersioning()
    .enablePostCssLoader()
    .enableSassLoader()
    .cleanupOutputBeforeBuild()
    // .enableSingleRuntimeChunk()
    .disableSingleRuntimeChunk()
    .enableSourceMaps(!Encore.isProduction());

// enable all Uppy languages, still lazily loaded
const uppyLocalesDir = './node_modules/@uppy/locales/lib';
const outputLocalesDir = './_uppy-locales-pre-compiled';

if (fs.existsSync(outputLocalesDir)) {
  fs.rmSync(outputLocalesDir, {recursive: true, force: true});
}

fs.mkdirSync(outputLocalesDir, { recursive: true });

const files = fs.readdirSync(uppyLocalesDir).filter((f) => f.endsWith('.js'));

files.forEach((file) => {
  const content = fs.readFileSync(path.join(uppyLocalesDir, file), 'utf8');

  // strip `export default ...` to retain everything else
  const transformed = content.replace(/export\s+default\s+[a-zA-Z0-9_]+;\s*$/, '');

  fs.writeFileSync(path.join(outputLocalesDir, file), transformed);
  Encore.addEntry(`uppy/locales/${path.basename(file, '.js')}.min`, `${outputLocalesDir}/${file}`);
});

const media = Encore.getWebpackConfig();
media.name = 'media';
media.watchOptions = {
    poll: true,
    ignored: /node_modules/,
};
media.resolve = {
    extensions: ['.mjs', '.json', '.jsx', '.ts', '.tsx', '.js'],
    alias: {
        '@assets': path.resolve(__dirname, './public'),
        '@rankyMedia': path.resolve(__dirname, './src'),
    },
};

/* media.entry['react'] = {
  import: ['react','react-dom'],
  filename: 'react.18.3.1.js',
};
 */
Encore.reset();

module.exports = media;
