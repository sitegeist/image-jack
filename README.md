# Image Jack
Jack of all trades concerning image optimization. Also introduces the usage of next-gen-image-formats.

## What does it do?
Adds the possibility to optimize existing jpg, gif and png files during processing.
Furthermore, it can add a webP copy of every processed image.

## Installation
Use composer to add the extension:
```
composer require "sitegeist/image-jack"
```
* Completely clear all processed images via the maintenance module
* Flush Caches
* Change the server configuration (see below)

## Configuration
### Webserver
To deliver the webP images, the webserver needs to be configured properly.
Example for Apache (.htaccess):
```
RewriteCond %{HTTP_ACCEPT} image/webp
RewriteCond %{REQUEST_FILENAME} (.*)\.(png|gif|jpe?g)$
RewriteCond %{REQUEST_FILENAME}\.webp -f
RewriteRule ^ %{REQUEST_FILENAME}\.webp [L,T=image/webp]

<IfModule mod_headers.c>
    <FilesMatch "\.(png|gif|jpe?g)$">
        Header append Vary Accept
    </FilesMatch>
</IfModule>
```
As we are creating the webP images as copies (keeping the original image
untouched) this configuration delivers the webP image if the client
supports it. Otherwise, the original image is served.

### Scheduler / Cronjob
To start the image processing a command is available:
```
jack:process --limit=20
```
The limit can be changed according to your server capacity.


## Requirements
### webp
For the webp conversion an installed version of Imagemagick or GraphicsMagick with webp support is required.

You can check this with:
```
gm -version | grep -i webp
```
or:
```
convert -version | grep -i webp
```

When using gd, webP support is needed.

### jpg optimization
For optimizing jpg files the binary "jpegoptim" is required.
You can check this with:
```
which jpegoptim
```
(Should return a path)

If missing it can be installed for example with this command:
```
apt install jpegoptim
```


### png/gif optimization
For optimizing png and gif files the binary "optipng" is required.
You can check this with:
```
which optipng
```
(Should return a path)

If missing it can be installed for example with this command:
```
apt install optipng
```

## Extension settings
### General
#### useLiveProcessing
If enabled the images are processed live on request using a LocalImageProcessor.
This can lead to high load and very long loading times upon first request (depending on the number of images on the page).
It is recommended to stick with the default and use the available
command for processing.

### WebP
#### active
(De)activate the conversion.

#### converter
Which way should be used to convert the images:
Imagemagick/GraphicsMagick, GD or an external binary

#### options
Depending on the conversion type this field can be used for different purposes:
* IM/GM: Add options that are directly passed to the binary
* GD: Quality for the new images (1 - 100)
* External: The complete path to the binary to be used. You can use %s for source and target image.

### Jpeg
#### active
(De)activate the optimization.

#### path
The path to the jpegoptim binary. Please use the complete path as `is_executable` won't work correctly otherwise.

### Png
#### active
(De)activate the optimization.

#### path
The path to the optipng binary. Please use the complete path as `is_executable` won't work correctly otherwise.

### Logging
Just activate the levels that should be written to the log file.

## Troubleshooting
A log is written to var/log/typo3_image_jack_*.log

The extension settings offer the possibility to decide how verbose the logging should be.

## Special thanks
This extension was inspired by the [webp](https://github.com/plan2net/webp) extension from plan2net.

## Authors & Sponsors

* [Thorsten Schramm](https://github.com/t-schramm)
* [Benjamin Tammling](https://github.com/Atomschinken)
* [All contributors](https://github.com/sitegeist/image-jack/graphs/contributors)

*The development and the public-releases of this package is generously sponsored
by our employer https://sitegeist.de.*
