(function () {
    var defaults = {
        file: 'Owned_Stamp.png',
        xpos: 0,
        ypos: 0,
        xrepeat: 0,
        opacity: 100,
        clickable: false,
        url: "",
        className: 'vjs-watermark',
        text: false,
        debug: false
    },
        extend = function () {
            var args, target, i, object, property;
            args = Array.prototype.slice.call(arguments);
            target = args.shift() || {};
            for (i in args) {
                object = args[i];
                for (property in object) {
                    if (object.hasOwnProperty(property)) {
                        if (typeof object[property] === 'object') {
                            target[property] = extend(target[property], object[property]);
                        } else {
                            target[property] = object[property];
                        }
                    }
                }
            }
            return target;
        };

    //! global varible containing reference to the DOM element
    var div;
    var div2;

    /**
     * register the thubmnails plugin
     */
    videojs.plugin('watermark', function (settings) {
        if (settings.debug) console.log('watermark: Register init');

        var options, player, video, img, link;
        options = extend(defaults, settings);

        /* Grab the necessary DOM elements */
        player = this.el();
        video = this.el().getElementsByTagName('video')[0];

        // create the watermark element
        if (!div) {
            div = document.createElement('div');
            div.className = options.className;
        }
        else {
            //! if div already exists, empty it
            div.innerHTML = '';
        }

        // if text is set, display text
        if (options.text)
            div.textContent = options.text;

        // if img is set, add img
        if (options.file) {
            img = document.createElement('img');
            div.appendChild(img);
            img.src = options.file;
        }

        if ((options.ypos === 0) && (options.xpos === 0)) // Top left
        {
            div.style.top = "0";
            div.style.left = "0";
        }
        else if ((options.ypos === 0) && (options.xpos === 100)) // Top right
        {
            div.style.top = "0";
            div.style.right = "0";
        }
        else if ((options.ypos === 100) && (options.xpos === 100)) // Bottom right
        {
            div.style.bottom = "0";
            div.style.right = "0";
        }
        else if ((options.ypos === 100) && (options.xpos === 0)) // Bottom left
        {
            div.style.bottom = "0";
            div.style.left = "0";
        }
        else if ((options.ypos === 50) && (options.xpos === 50)) // Center
        {
            if (options.debug) console.log('watermark: player:' + player.width + 'x' + player.height);
            if (options.debug) console.log('watermark: video:' + video.videoWidth + 'x' + video.videoHeight);
            if (options.debug) console.log('watermark: image:' + img.width + 'x' + img.height);
            div.style.top = (this.height() / 2) + "px";
            div.style.left = (this.width() / 2) + "px";
        }
        div.style.opacity = options.opacity;

        if (options.clickable && options.url !== "") {
            link = document.createElement("a");
            link.href = options.url;
            link.target = "_blank";
            link.appendChild(div);
            player.appendChild(link);
        } else {
            player.appendChild(div);
        }

        if (!div2 && options.hasOwnProperty("brand")) {
            div2 = document.createElement('div');
            div2.className = options.className;

            if (options.brand.hasOwnProperty("file")) {
                img2 = document.createElement('img');
                div2.appendChild(img2);
                img2.src = options.brand.file;
            }

            if ((options.brand.ypos === 0) && (options.brand.xpos === 0)) // Top left
            {
                div2.style.top = "0";
                div2.style.left = "0";
            }
            else if ((options.brand.ypos === 0) && (options.brand.xpos === 100)) // Top right
            {
                div2.style.top = "0";
                div2.style.right = "0";
            }
            else if ((options.brand.ypos === 100) && (options.brand.xpos === 100)) // Bottom right
            {
                div2.style.bottom = "0";
                div2.style.right = "0";
            }
            else if ((options.brand.ypos === 100) && (options.brand.xpos === 0)) // Bottom left
            {
                div2.style.bottom = "0";
                div2.style.left = "0";
            }
            else if ((options.brand.ypos === 50) && (options.brand.xpos === 50)) // Center
            {
                if (options.brand.debug) console.log('watermark: player:' + player.width + 'x' + player.height);
                if (options.brand.debug) console.log('watermark: video:' + video.videoWidth + 'x' + video.videoHeight);
                if (options.brand.debug) console.log('watermark: image:' + img2.width + 'x' + img2.height);
                div2.style.top = (this.height() / 2) + "px";
                div2.style.left = (this.width() / 2) + "px";
            }
            div2.style.opacity = options.brand.opacity;

            if (options.brand.clickable && options.brand.url !== "") {
                link2 = document.createElement("a");
                link2.href = options.brand.url;
                link2.target = "_blank";
                link2.appendChild(div2);
                player.appendChild(link2);
            } else {
                player.appendChild(div2);
            }
        }

        if (options.debug) console.log('watermark: Register end');
    });
})();