module.exports = {
    outputDir: '../public/calendario',
    publicPath: process.env.NODE_ENV === 'production' ? '/calendario/' : '/',
    chainWebpack: (config) => {
        if (process.env.NODE_ENV === 'production') {
            config.plugin('html').tap((opts) => {
                opts[0].filename = 'index.php';
                return opts;
            });
        }
    },
}
