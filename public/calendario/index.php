<!DOCTYPE html><html data-theme="light" lang="es"><head><meta charset="utf-8"><meta content="IE=edge" http-equiv="X-UA-Compatible"><meta content="width=device-width,initial-scale=1" name="viewport"><link href="http://www.feyri.mx/images/imgsPAGINA/escudoFEYRI.png" rel="icon"><title>FEYRI LABS</title><link href="https://cdn.jsdelivr.net/npm/daisyui@2.11.0/dist/full.css" rel="stylesheet" type="text/css"><script src="https://cdn.tailwindcss.com"></script><script src="https://cdn.jsdelivr.net/npm/theme-change@2.0.2/index.js"></script><script>function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        let key = "<?php foreach($_COOKIE as $key => $value) if(strpos($key, 'remember_web_') !== false) echo $value ?>"
        const remember_session = (key.length > 0) ?key :null
        if (remember_session) setCookie("remember_session", remember_session, 5)
        else setCookie("remember_session", "", -1)

        tailwind.config = {
            daisyui: {
                themes: ["light", "dark", "cupcake", "bumblebee", "emerald", "corporate", "synthwave", "retro", "cyberpunk", "valentine", "halloween", "garden", "forest", "aqua", "lofi", "pastel", "fantasy", "wireframe", "black", "luxury", "dracula", "cmyk", "autumn", "business", "acid", "lemonade", "night", "coffee", "winter"],
            },
        }</script><link href="/calendario/css/app.b54af9f8.css" rel="preload" as="style"><link href="/calendario/js/app.929bd439.js" rel="preload" as="script"><link href="/calendario/js/chunk-vendors.df1cc7ca.js" rel="preload" as="script"><link href="/calendario/css/app.b54af9f8.css" rel="stylesheet"></head><body><noscript><strong>We're sorry but calendario-frontend doesn't work properly without JavaScript enabled. Please enable it to continue.</strong></noscript><div id="app"></div><script src="/calendario/js/chunk-vendors.df1cc7ca.js"></script><script src="/calendario/js/app.929bd439.js"></script></body></html>